<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'contact_email' => 'nullable|email',
            'facebook_link' => 'nullable|url',
            'twitter_link' => 'nullable|url',
            'instagram_link' => 'nullable|url',
            'theme_mode' => 'required|in:light,dark',
            'footer_description' => 'nullable|string',
        ]);

        if ($request->hasFile('site_logo')) {
            $validated['site_logo'] = $request->file('site_logo')->store('logos', 'public');
        }

        Setting::create($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Website settings saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'contact_email' => 'nullable|email',
            'facebook_link' => 'nullable|url',
            'twitter_link' => 'nullable|url',
            'instagram_link' => 'nullable|url',
            'theme_mode' => 'required|in:light,dark',
            'footer_description' => 'nullable|string',
        ]);

        if ($request->hasFile('site_logo')) {
            $validated['site_logo'] = $request->file('site_logo')->store('logos', 'public');
        }

        $setting->update($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Website settings updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
