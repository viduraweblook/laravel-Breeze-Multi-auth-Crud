<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if (auth()->user()) {
            $user = Auth::user();
            // print_r($user);
            $subject = 'Inform Logging Message To ' . $user->name;
            $body = ' Hello ' . $user->name . ' You Logged in ' . $user->email;
            $useremailname = $user->name;
            $filepath = public_path(path: 'favicon.ico');
            Mail::to($user->email)->send(new TestMail($subject, $body, $useremailname, $filepath));
        } else {
            $body = 'No User';
            $subject = 'No User MAil & F#ck You';
            $useremailname = 'No User name';
            $filepath = public_path(path: 'favicon.ico');
            Mail::to('noMail@gmail.com')->send(new TestMail($subject, $body, $useremailname, $filepath));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
