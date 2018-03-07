<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \App\Models\User;
use Validator;
use Response;
use Exception;

class UserController extends Controller
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
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'dob' => 'required|date',
            'phone' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json(['status' => 0, 'errors' => $validator->errors()->messages()], 409);
        }

        try {
            User::create($request->all());
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
    public function check(Request $request, $email)
    {
        $exists = User::where('email', $email)->count();
        return Response::json([
            'exists' => $exists
        ]);
    }
}
