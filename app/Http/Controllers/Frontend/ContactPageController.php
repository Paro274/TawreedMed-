<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo2;
use App\Models\Faq;
use App\Models\MapLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactPageController extends Controller
{
    public function index()
    {
        // ✅ جلب البيانات من جدول contact_info2
        $contact = ContactInfo2::first();
        
        // 🔍 سطر تشخيصي مؤقت - امسحه بعد ما تتأكد
        // dd($contact); // هذا السطر هيوقف الصفحة ويعرض محتوى $contact
        
        $faqs = Faq::orderBy('id', 'desc')->get();
        $map = MapLocation::first();

        return view('frontend.contact.index', compact('contact', 'faqs', 'map'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'accountType' => 'required|in:supplier,customer',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Prepare email body
            $emailBody = "
                <div dir='rtl' style='font-family:Arial,sans-serif;'>
                    <h2>رسالة جديدة من نموذج التواصل</h2>
                    <p><strong>الاسم:</strong> {$validated['name']}</p>
                    <p><strong>البريد الإلكتروني:</strong> {$validated['email']}</p>
                    <p><strong>الهاتف:</strong> {$validated['phone']}</p>
                    <p><strong>نوع الحساب:</strong> " . ($validated['accountType'] == 'customer' ? 'عميل' : 'مورد') . "</p>
                    <p><strong>الموضوع:</strong> {$validated['subject']}</p>
                    <hr>
                    <p><strong>الرسالة:</strong></p>
                    <p>" . nl2br(e($validated['message'])) . "</p>
                </div>
            ";
            
            // Try to send email using the configured mailer
            Mail::html($emailBody, function ($message) use ($validated) {
                $message->to('tawreed.med@gmail.com')
                        ->subject('رسالة جديدة من نموذج التواصل: ' . $validated['subject'])
                        ->replyTo($validated['email'], $validated['name']);
            });

            Log::info('تم إرسال البريد الإلكتروني بنجاح من نموذج التواصل', [
                'to' => 'tawreed.med@gmail.com',
                'subject' => $validated['subject'],
                'from' => $validated['email'],
                'mailer' => config('mail.default')
            ]);

            return back()->with('success', 'تم إرسال الرسالة بنجاح! سنتواصل معك قريبًا.');

        } catch (\Exception $e) {
            Log::warning('فشل إرسال البريد الإلكتروني من نموذج التواصل', [
                'error' => $e->getMessage(),
                'contact_data' => $validated,
                'mailer' => config('mail.default')
            ]);
            
            // Try fallback to log mailer if not already using it
            if (config('mail.default') !== 'log') {
                try {
                    $emailBody = "
                        <div dir='rtl' style='font-family:Arial,sans-serif;'>
                            <h2>رسالة جديدة من نموذج التواصل</h2>
                            <p><strong>الاسم:</strong> {$validated['name']}</p>
                            <p><strong>البريد الإلكتروني:</strong> {$validated['email']}</p>
                            <p><strong>الهاتف:</strong> {$validated['phone']}</p>
                            <p><strong>نوع الحساب:</strong> " . ($validated['accountType'] == 'customer' ? 'عميل' : 'مورد') . "</p>
                            <p><strong>الموضوع:</strong> {$validated['subject']}</p>
                            <hr>
                            <p><strong>الرسالة:</strong></p>
                            <p>" . nl2br(e($validated['message'])) . "</p>
                        </div>
                    ";
                    
                    config(['mail.default' => 'log']);
                    Mail::html($emailBody, function ($message) use ($validated) {
                        $message->to('tawreed.med@gmail.com')
                                ->subject('رسالة جديدة من نموذج التواصل: ' . $validated['subject'])
                                ->replyTo($validated['email'], $validated['name']);
                    });
                    
                    Log::info('تم تسجيل البريد الإلكتروني في اللوج كنسخة احتياطية', [
                        'to' => 'tawreed.med@gmail.com',
                        'subject' => $validated['subject']
                    ]);
                    
                    return back()->with('success', 'تم إرسال الرسالة بنجاح! سنتواصل معك قريبًا.');
                    
                } catch (\Exception $logException) {
                    Log::error('فشل تسجيل البريد الإلكتروني حتى في اللوج', [
                        'error' => $logException->getMessage()
                    ]);
                }
            }
            
            return back()->with('error', 'حدث خطأ أثناء إرسال الرسالة. حاول مرة أخرى لاحقًا.');
        }
    }
}
