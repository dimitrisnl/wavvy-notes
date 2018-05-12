<?php

namespace App\Http\Controllers;

use Auth;
use Lang;
use App\Services\Jwt;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    use ThrottlesLogins;

    /**
     * Issue a JWT token when valid login credentials are
     * presented.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Determine if the user has too many failed login attempts.
        if ($this->hasTooManyLoginAttempts($request)) {
            // Fire an event when a lockout occurs.
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $validator = $this->validator($request->all());



        if($validator->fails()){
            return $this->responseWithUnprocessableEntity($validator->errors()->first());
        };

        // Grab credentials from the request.
        $credentials = $request->only('email', 'password');

        // Attempt to verify the credentials and create a token for the user.
        if ($token = Auth::guard('api')->attempt($credentials)) {
            // All good so return the json with token and user.
            return $this->sendLoginResponse($request, $token);
        }

        // Increments login attempts.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Return the token and current user authenticated.
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request, $token)
    {
        // Clear the login locks for the given user credentials.
        $this->clearLoginAttempts($request);

        $user = Auth::guard('api')->user();

        // get time to live of token form JWT service.
        $token_ttl = (new Jwt($token))->getTokenTTL();

        return $this->response(compact('token', 'token_ttl', 'user'));
    }

    /**
     * Return error message after determining invalid credentials.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $message = Lang::get('auth.failed');

        return $this->responseWithUnauthorized($message);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = Lang::get('auth.throttle', ['seconds' => $seconds]);

        return $this->responseWithTooManyRequests($message);
    }

    /**
     * Revoke the user's token.
     *
     * @return \Illuminate\Http\Response
     */
    public function revokeToken()
    {
        Auth::guard('api')->logout();

        return $this->responseWithNoContent();
    }

    /**
     * Refresh the user's token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $token = Auth::guard('api')->refresh();

        // get time to live of token form JWT service.
        $token_ttl = (new Jwt($token))->getTokenTTL();

        return $this->response(compact('token', 'token_ttl'));
    }

    public function username()
    {
        return 'email';
    }

    /* Get a validator for an incoming login request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'    => 'required|email|max:255|exists:users',
            'password' => 'required|min:6',
        ]);
    }
}
