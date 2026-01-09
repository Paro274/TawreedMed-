<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JourneyController extends Controller
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
        $journeys = Journey::orderBy('year', 'desc')->get();
        return view('admin.journey.index', compact('journeys'));
    }

    public function create()
    {
        $this->checkAuth();
        return view('admin.journey.create');
    }

    public function store(Request $request)
    {
        $this->checkAuth();

        $request->validate([
            'year' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $data = $request->all();
        $data['order'] = Journey::max('order') + 1;

        Journey::create($data);

        return redirect()->route('admin.journey.index')->with('success', 'تم إضافة المرحلة بنجاح.');
    }

    public function edit(Journey $journey)
    {
        $this->checkAuth();
        return view('admin.journey.edit', compact('journey'));
    }

    public function update(Request $request, Journey $journey)
    {
        $this->checkAuth();

        $request->validate([
            'year' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $journey->update($request->all());

        return redirect()->route('admin.journey.index')->with('success', 'تم تحديث المرحلة بنجاح.');
    }

    public function destroy(Journey $journey)
    {
        $this->checkAuth();
        $journey->delete();
        return redirect()->route('admin.journey.index')->with('success', 'تم حذف المرحلة بنجاح.');
    }
}
