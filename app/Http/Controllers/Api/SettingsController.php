<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'email_notifications_enabled' => ['required', 'boolean'],
        ]);

        $request->user()->update([
            'email_notifications_enabled' => $request->boolean('email_notifications_enabled'),
        ]);

        return $request->user();
    }
}
