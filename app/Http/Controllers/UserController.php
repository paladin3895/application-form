<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Validator;
use Illuminate\Validation\Rule;
use MathieuViossat\Util\ArrayToTextTable;

use Mail;
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
            'gender' => [
                function($attribute, $value, $fail) {
                    if (!is_null($value) && !in_array($value, ['M', 'F', 'N'])) {
                        return $fail($attribute.' is invalid.');
                    }
                },
            ],
            'token' => [
                'required',
                Rule::exists('tokens')->where(function ($query) use ($request) {
                    $query->where('token', $request->get('token'))
                        ->where('email', $request->get('email'))
                        ->where('expired_at', '>=', date('Y-m-d H:i:s', time()));
                }),
            ],
            'cv' => 'file|mimes:pdf,doc,docx,odt',
        ]);

        if ($validator->fails()) {
            return Response::json(['status' => 0, 'errors' => $validator->errors()->messages()], 409);
        }

        try {
            $user = new User($request->all());
            if ($request->file('cv')) {
                $file = $request->file('cv');
                $filePath = $file->storeAs('resumes',
                    "cv_{$user->firstname}_{$user->lastname}_{$request->get('token')}.{$file->getClientOriginalExtension()}");
                $user->cv = $filePath;
            }
            $user->save();
            $this->sendMailToAdmin($user, $user->cv ? storage_path('/app/'.$user->cv) : null);
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

    public function sendMailToAdmin($user, $filePath = null) {
        $userData = (new ArrayToTextTable(
            [array_only($user->toArray(), ['firstname', 'lastname', 'email', 'phone'])]
        ))->getTable();

        $content = "There is a successful application:" . PHP_EOL . "{$userData}";
        Mail::raw($content, function ($message) use ($user, $filePath) {
            $message->subject("New successful application from {$user->firstname} {$user->lastname}");
            $message->from(config('app.admin_email'));
            $message->to(config('app.admin_email'));
            if ($filePath) {
                $message->attach($filePath);
            }
        });
    }
}
