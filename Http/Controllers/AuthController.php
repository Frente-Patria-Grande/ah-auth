<?php


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class AuthController
{
    public function redirect(Request $request)
    {
        $url = Socialite::driver('argentina-humana')
            ->with(['prompt' => 'login'])
            ->redirect();

        return Inertia::location($url->getTargetUrl());
    }

    public function callback(Request $request)
    {
        /** @var \Laravel\Socialite\Two\User $socialiteUser */
        $socialiteUser = Socialite::driver('argentina-humana')->user();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = Auth::query()->firstWhere('email', $socialiteUser->getEmail());

        if (! $user) {
            Session::put('auth.denied.email', $socialiteUser->getEmail());
            return redirect()->route('auth.denied');
        }

        $user->update([
            'ah_access_token' => $socialiteUser->token,
            'ah_refresh_token' => $socialiteUser->refreshToken,
            'ah_access_token_expires_at' => now()->addSeconds($socialiteUser->expiresIn),
        ]);

        Auth::login($user, true);

        return redirect()->intended(route('home'));
    }

    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('auth.login');
    }

    public function denied()
    {
        $authDeniedEmail = Session::get('auth.denied.email');

        if (! $authDeniedEmail) {
            return redirect()->route('auth.login');
        }

        return Inertia::render('Auth/Denied', [
            'email' => $authDeniedEmail,
        ]);
    }
}
