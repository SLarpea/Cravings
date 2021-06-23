<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Jobs\SendEmail;
use Validator;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users',
            'password' => 'required',
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            foreach($errors->all() as $message){
                return response()->json(['message' => $message])->header('code', 400);
            }
        }

        $email = ['email' => $request->input('email')];

        User::create(array_merge($validator->validated(), ['password' => bcrypt($request->password)]));

        SendEmail::dispatch($email);

        return response()->json(['message' => "User successfully registered"])->header('code', 201);
    }
}
