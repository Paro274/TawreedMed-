<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MapLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MapLocationController extends Controller
{
    public function index()
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $location = MapLocation::first();
        return view('admin.map.index', compact('location'));
    }

    public function update(Request $request)
    {
        if (!Session::has('admin')) {
            return redirect('/admin/login');
        }

        $request->validate([
            'map_link' => 'required|url|max:500',
            'address' => 'nullable|string|max:500',
        ]);

        $location = MapLocation::first();
        $location->update($request->all());

        return redirect()->back()->with('success', 'تم تحديث الموقع بنجاح.');
    }
}
