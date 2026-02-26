<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    // use ResetsPasswords;

    /**
     * Where to redirect users after password reset.
     */
    protected $redirectTo = '/dashboard';

    /**
     * Handle password reset.
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request()->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // $user = User::where('email', $request->email)->first();

        // if (!$user) {
        //     return back()->withErrors(['email' => 'User not found.']);
        // }

        //  Optional: If you want to block admin reset via staff flow
        // if ($user->role === 'admin') {
        //     return back()->withErrors(['email' => 'Admin password reset is not allowed here.']);
        // }

        // Update password only (role remains same)
        // $user->update([
        //     'password' => Hash::make($request->password)
        // ]);

        // return redirect($this->redirectTo)
        //     ->with('success', 'Password reset successful!');

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect($this->redirectTo)->with('success', 'Password reset successful!')
            : back()->withErrors(['email' => __($status)]);
    }
}
