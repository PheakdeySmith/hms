<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // You can load settings from database if you have a settings table
        // $settings = Setting::all()->pluck('value', 'key');

        // For now, we'll just return the view
        return view('settings.index');
    }

    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'hotel_name' => 'required|string|max:255',
            'hotel_address' => 'required|string',
            'hotel_phone' => 'required|string|max:20',
            'hotel_email' => 'required|email',
            // Add more validation rules as needed
        ]);

        // Update settings in database
        // foreach ($request->except('_token') as $key => $value) {
        //     Setting::updateOrCreate(
        //         ['key' => $key],
        //         ['value' => $value]
        //     );
        // }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
