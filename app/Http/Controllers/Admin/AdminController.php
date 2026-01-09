<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('created_at', 'desc')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:admins,username|max:255',
            'password' => 'required|string|min:6|confirmed',
            'is_super_admin' => 'boolean',
            'permissions' => 'array',
        ]);

        $admin = Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'is_super_admin' => $request->boolean('is_super_admin'),
            'permissions' => $request->permissions ?? [],
        ]);

        Log::info('New Admin Created', ['id' => $admin->id, 'username' => $admin->username]);
        
        return redirect()->route('admin.admins.index')
            ->with('success', 'تم إضافة الأدمن بنجاح');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        
        $request->validate([
            'username' => 'required|string|max:255|unique:admins,username,' . $id,
            'permissions' => 'array',
            'is_super_admin' => 'boolean',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'username' => $request->username,
            'is_super_admin' => $request->boolean('is_super_admin'),
            'permissions' => $request->permissions ?? [],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم تحديث الأدمن بنجاح');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        
        // ✅ مش يمسح نفسه
        if ($admin->id == session('admin')) {
            return back()->with('error', 'لا يمكن حذف نفسك');
        }

        $admin->delete();
        return redirect()->route('admin.admins.index')
            ->with('success', 'تم حذف الأدمن بنجاح');
    }
}
