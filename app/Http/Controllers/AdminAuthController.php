<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin-login');
    }

    public function postlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Hardcoded admin credentials
        $adminEmail = 'admin@soleco.com';
        $adminPassword = 'admin123';

        if (
            strtolower(trim($credentials['email'])) === strtolower($adminEmail) &&
            trim($credentials['password']) === $adminPassword
        ) {
            $request->session()->put('admin_logged_in', true);
            return redirect()->route('admin.dashboardtest'); // works now
        }

        return back()->with('error', 'Invalid credentials');
    }
}
