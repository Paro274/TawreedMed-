<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        $contact = ContactInfo::first();
        $map = \App\Models\MapLocation::first();
        return view('frontend.contact.index', compact('contact', 'map'));
    }

    /**
     * Handle the contact form submission.
     */
    public function send(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'accountType' => 'required|in:customer,supplier',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'phone.required' => 'رقم الهاتف مطلوب',
            'subject.required' => 'عنوان الرسالة مطلوب',
            'accountType.required' => 'نوع الحساب مطلوب',
            'message.required' => 'الرسالة مطلوبة',
        ]);

        try {
            // Save the message to the database
            ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
                'account_type' => $validated['accountType'],
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Prepare email data
            $emailData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
                'accountType' => $validated['accountType'],
                'messageContent' => $validated['message'],
            ];

            // Send email to admin with multiple fallback methods
            try {
                // Try to send email using the configured mailer
                Mail::send('emails.contact', $emailData, function($message) use ($validated) {
                    $message->to('tawreed.med@gmail.com')
                            ->subject('رسالة جديدة من توريد ميد: ' . $validated['subject'])
                            ->replyTo($validated['email'], $validated['name']);
                });
                
                // Log successful email sending
                Log::info('تم إرسال البريد الإلكتروني بنجاح', [
                    'to' => 'tawreed.med@gmail.com',
                    'subject' => $validated['subject'],
                    'from' => $validated['email'],
                    'mailer' => config('mail.default')
                ]);
                
            } catch (\Exception $mailException) {
                // Log mail error but don't fail the whole process
                Log::warning('فشل إرسال البريد الإلكتروني ولكن تم حفظ الرسالة', [
                    'error' => $mailException->getMessage(),
                    'contact_data' => $validated,
                    'mailer' => config('mail.default')
                ]);
                
                // Try fallback to log mailer if not already using it
                if (config('mail.default') !== 'log') {
                    try {
                        config(['mail.default' => 'log']);
                        Mail::send('emails.contact', $emailData, function($message) use ($validated) {
                            $message->to('tawreed.med@gmail.com')
                                    ->subject('رسالة جديدة من توريد ميد: ' . $validated['subject'])
                                    ->replyTo($validated['email'], $validated['name']);
                        });
                        
                        Log::info('تم تسجيل البريد الإلكتروني في اللوج كنسخة احتياطية', [
                            'to' => 'tawreed.med@gmail.com',
                            'subject' => $validated['subject']
                        ]);
                        
                    } catch (\Exception $logException) {
                        Log::error('فشل تسجيل البريد الإلكتروني حتى في اللوج', [
                            'error' => $logException->getMessage()
                        ]);
                    }
                }
            }

            // Log the contact message for verification
            Log::info('تم استلام رسالة جديدة من نموذج الاتصال', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
                'account_type' => $validated['accountType'],
            ]);

            return redirect()->back()
                ->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك في أقرب وقت ممكن.');

        } catch (\Exception $e) {
            Log::error('فشل إرسال رسالة الاتصال: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إرسال رسالتك. يرجى المحاولة مرة أخرى لاحقاً.')
                ->withInput();
        }
    }
}
