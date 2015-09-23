<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }

    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

//    public function authenticate(Request $request)
//    {
//        $credentials = $request->only('email', 'password');
//
//        try {
//            // verify credentials and create a token for the user
//            if (!$token = JWTAuth::attempt($credentials)) {
//                return response()->json(['error' => 'invalid credentials'], 401);
//            }
//        } catch (JWTException $e) {
//                return response()->json(['error' => 'could_not_create_token'], 500);
//        }
//
//        // if no errors are encountered, we can return a JWT
//        return response()->json(compact('token'));
//    }
}
