<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $supportedLocales = config('app.supported_locales', ['en']);

        if (!in_array($locale, $supportedLocales)) {
            abort(400, 'Invalid locale');
        }

        Session::put('locale', $locale);

        // If user is authenticated, update their locale preference
        if ($request->user()) {
            // Optionally update user's locale in database if you have that column
            // $request->user()->update(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
