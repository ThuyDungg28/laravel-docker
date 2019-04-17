<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request, AuthService $passportService)
    {
        \Log::info(1);
        $data = $request->only(['email', 'password']);
        $response = $passportService->passwordGrantToken($data);

        return new AuthResource($response, 'login');
    }

    public function logout(Request $request)
    {
        $loggedIn = $request->user();
        if ($loggedIn) {
            $loggedIn->token()->revoke();
        }

        return new AuthResource([], 'logout');
    }
}
