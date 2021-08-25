<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();

            $success['email'] = $email;
            $success['token'] = $user->createToken($request->email)->plainTextToken;
            $success['expired_at'] = Carbon::now()->addDays(7);

            return $this->sendResponse($success, "User Register");
        } else {
            return $this->sendError("Unauthorized", ["error" => 'Invalid Credentials']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        // if fails
        // return $this->sendError("Error Validation", $validator->errors());
        if ($validator->fails()) {
            return $this->sendError("Error Validation", $validator->errors());
        }

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt($request->password),
                'remember_token' => Str::random(10)
            ]
        );

        $success['email'] = $user->email;
        $success['token'] = $user->createToken($request->email)->plainTextToken;
        $success['expired_at'] = Carbon::now()->addDays(7);

        return $this->sendResponse($success, "User Register");
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
