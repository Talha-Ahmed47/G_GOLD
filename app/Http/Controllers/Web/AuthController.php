<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Laravel\Socialite\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\Role;

class AuthController extends Controller
{

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => Role::USER->value,
        ]);

        $user->assignRole('user');

        // Automatically create wallet for user
        \App\Models\Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'gold_balance' => 0,
            'silver_balance' => 0,
            'platinum_balance' => 0,
            'palladium_balance' => 0,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'This account has been deactivated. Please contact support.',
                ])->onlyInput('email');
            }
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    public function redirectGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogle()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(str()->random(16))
        ]);

        Auth::login($user);

        return redirect('/');
    }
}
