<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File; // ✅ استخدام File بدل Storage

class TeamMemberController extends Controller
{
    private function checkAuth()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }
    }

    public function index()
    {
        $this->checkAuth();
        $members = TeamMember::all();
        return view('admin.team.index', compact('members'));
    }

    public function create()
    {
        $this->checkAuth();
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $this->checkAuth();

        $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image'); // استثناء الصورة مؤقتاً

        if ($request->hasFile('image')) {
            // ✅ إنشاء المسار لو مش موجود
            $destinationPath = public_path('uploads/team');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file = $request->file('image');
            $fileName = 'team_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            
            // ✅ حفظ المسار النسبي للصورة
            $data['image'] = 'uploads/team/' . $fileName;
        }

        TeamMember::create($data);

        return redirect()->route('admin.team.index')->with('success', 'تم إضافة عضو الفريق بنجاح.');
    }

    public function edit(TeamMember $member)
    {
        $this->checkAuth();
        return view('admin.team.edit', compact('member'));
    }

    public function update(Request $request, TeamMember $member)
    {
        $this->checkAuth();

        $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // ✅ حذف الصورة القديمة من public
            if ($member->image && File::exists(public_path($member->image))) {
                File::delete(public_path($member->image));
            }

            $destinationPath = public_path('uploads/team');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file = $request->file('image');
            $fileName = 'team_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            
            $data['image'] = 'uploads/team/' . $fileName;
        }

        $member->update($data);

        return redirect()->route('admin.team.index')->with('success', 'تم تحديث عضو الفريق بنجاح.');
    }

    public function destroy(TeamMember $member)
    {
        $this->checkAuth();

        // ✅ حذف الصورة من public
        if ($member->image && File::exists(public_path($member->image))) {
            File::delete(public_path($member->image));
        }

        $member->delete();

        return redirect()->route('admin.team.index')->with('success', 'تم حذف عضو الفريق بنجاح.');
    }
}
