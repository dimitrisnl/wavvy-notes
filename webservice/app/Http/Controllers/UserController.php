<?php

namespace App\Http\Controllers;

use App\User;
use App\Category;
use App\Note;
use Log;

use App\Transformers\UserTransformer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return $this->response(
            $this->transform->item(Auth::guard('api')->user(), new UserTransformer)
        );
    }

    public function register(Request $request)
    {   

        $validator = $this->validator($request->all());

        if($validator->fails())
            return $this->responseWithUnprocessableEntity($validator->errors()->first());

        $user = $this->create($request->all());

        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);

        return $this->response(['token' => $token, 'user' => $user]); 
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'username'     => $data['username'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $category = Category::create([
            'name'    => 'Personal',
            'user_id' => $user->id
        ]);

        Note::create([
            'name'        => 'My first note',
            'body'        => 'Hey there, welcome aboard!',
            'user_id'     => $user->id,
            'category_id' => $category->id,
        ]);

        return $user;
    }
}
