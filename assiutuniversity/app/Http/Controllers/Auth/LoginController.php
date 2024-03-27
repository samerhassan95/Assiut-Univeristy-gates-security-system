<?php

// LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['frozen' => false]);
    }

    public function login(Request $request)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if ($this->guard()->attempt($credentials, $request->filled('remember'))) {
            // Check if the user is approved or is an admin
            if (auth()->user()->approved || auth()->user()->role === 'admin') {
                // Authentication passed, redirect to intended page or home
                return redirect('/dashboard');
            } else {
                // User is not approved and not an admin, log them out and redirect with a message
                auth()->logout();
                return back()->withErrors(['email' => 'Your account is pending approval. Please wait for the admin to approve.']);
            }
        } else {
            // Authentication failed, redirect back with errors
            return back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        return redirect('/assiutuniversity');
    }
}
