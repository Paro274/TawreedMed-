<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MissionVisionValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MissionVisionValuesController extends Controller
{
    public function index()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $mvv = MissionVisionValues::first();
        return view('admin.mvv.index', compact('mvv'));
    }

    public function update(Request $request)
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'mission_title' => 'nullable|string|max:255',
            'mission_description' => 'nullable|string',
            'vision_title' => 'nullable|string|max:255',
            'vision_description' => 'nullable|string',
            'values_title' => 'nullable|string|max:255',
            'values_description' => 'nullable|string',
        ]);

        $mvv = MissionVisionValues::first();
        $mvv->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث رسالتنا ورؤيتنا وقيمنا بنجاح.');
    }
}
