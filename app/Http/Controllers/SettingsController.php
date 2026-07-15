<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(Request $request): View
    {
        return view('settings.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'email_notifications_enabled' => ['sometimes', 'boolean'],
        ]);

        $request->user()->update([
            'email_notifications_enabled' => $request->boolean('email_notifications_enabled'),
        ]);

        return Redirect::route('settings.edit')->with('status', 'settings-updated');
    }
}
