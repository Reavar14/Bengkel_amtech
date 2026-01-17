<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        Mail::send('emails.contact', [
            'name'    => $request->name,
            'email'   => $request->email,
            'content' => $request->message,
        ], function ($mail) use ($request) {
            $mail->to('bengkelsementara@gmail.com') // EMAIL BENGKEL
                 ->subject('Pesan dari Website Bengkel')
                 ->replyTo($request->email, $request->name);
        });

        return back()->with('success', 'Pesan berhasil dikirim.');
    }
}