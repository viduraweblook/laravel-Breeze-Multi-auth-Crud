<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index()
    {
        if (auth()->user()) {
            $user = Auth::user();
            // print_r($user);
            $subject = 'Inform Logging Message To ' . $user->name;
            $body = ' Hello ' . $user->name . ' You Logged in ' . $user->email;
            $useremailname = $user->name;
            $filepath = public_path(path:'sample.pdf');
            Mail::to($user->email)->send(new TestMail($subject, $body, $useremailname, $filepath));
        } else {
            $body = 'No User';
            $subject = 'No User MAil & F#ck You';
            $useremailname = 'No User name';
            $filepath = public_path(path: 'sample.pdf');
            Mail::to('noMail@gmail.com')->send(new TestMail($subject, $body , $useremailname, $filepath))->cc('')->bcc('');
        }
        // return view('email.email');
    }
}
