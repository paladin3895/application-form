<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use Mail;
use Validator;
use Response;
use Exception;

class TokenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Create user endpoint
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json(['status' => 0, 'errors' => $validator->errors()->messages()], 409);
        }

        try {

            $token = Token::create($request->all());
            $email = $request->get('email');
            $this->sendToken($token->token, $email);
            return Response::json(['status' => 1]);

        } catch (Exeption $e) {
            return Response::json(['status' => 0], 500);
        }
    }

    /**
     * Check user email endpoint
     *
     * @return \Illuminate\Http\Response
     */
    public function check($token, $email)
    {
        $isValid = Token::where('token', $token)
            ->where('email', $email)
            ->where('expired_at', '>=', date('Y-m-d H:i:s', time()))
            ->count();
        return Response::json([
            'isValid' => $isValid
        ]);
    }

    public function sendToken($token, $email) {
        $content = "Your confirmation code is: {$token} (this token will expire in 1 hour)";
        Mail::raw($content, function ($message) use ($email) {
            $message->subject('Confirmation code for application');
            $message->from(config('app.admin_email'));
            $message->to($email);
        });
    }
}
