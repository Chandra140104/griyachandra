<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember-me');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Redirect based on role if necessary, or just go to home
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/dashboard')->with('success', 'Welcome Admin!');
            }

            return redirect()->intended('/dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed', // requested password "12345" is 5 chars
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role is user
        ]);

        Auth::login($user);

        event(new \Illuminate\Auth\Events\Registered($user));

        return redirect('/dashboard')->with('success', 'Registration successful! Please check your email for a verification link.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showFindEmail()
    {
        return view('auth.find-email');
    }

    public function findEmail(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        $user = \App\Models\User::where('name', 'like', '%' . $request->name . '%')->first();

        if (!$user) {
            return back()->withErrors(['name' => 'Nama tersebut tidak ditemukan dalam sistem.']);
        }

        $email = $user->email;
        $atPos = strpos($email, '@');
        $name = substr($email, 0, $atPos);
        $domain = substr($email, $atPos);
        
        // Mask the name part: "username" -> "use***me" (less hidden)
        $len = strlen($name);
        if ($len <= 4) {
            // Very short names, just show first character
            $maskedName = substr($name, 0, 1) . str_repeat('*', $len - 1);
        } else {
            // Show first 3 and last 2 characters
            $maskedName = substr($name, 0, 3) . str_repeat('*', $len - 5) . substr($name, -2);
        }
        
        $maskedEmail = $maskedName . $domain;

        return back()->with('found_email', $maskedEmail)->with('user_name', $user->name);
    }
}
