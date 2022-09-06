<?php

namespace App\Http\Controllers;

use App\Api\ApiMessages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct(private User $user)
    {
    }

    public function index()
    {
        $users = $this->user->paginate(10);

        return response()->json($users, 200);
    }

    public function create(Request $request)
    {

        try {

            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $array['error'] = $validator->messages();
                return $array;
            }

            $name = $request->input('name');
            $email = $request->input('email');
            $password = $request->input('password');

            $newUser = new User();
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->password = password_hash($password, PASSWORD_DEFAULT);
            $newUser->token = '';
            $newUser->save();

            return response()->json([
                'data' => [
                    'msg' => 'Usuário cadastrado com successo'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        if ($request->has('password') && $request->get('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        try {

            $user = $this->user->findOrFail($id);
            $user->update($data);


            return response()->json([
                'data' => [
                    'msg' => 'Usuário editado com successo',
                    'user' => $user
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function show($id)
    {
        try {

            $user = $this->user->with('profile')->findOrFail($id);

            return response()->json([
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }


    public function destroy($id)
    {
        try {

            $user = $this->user->findOrFail($id);
            $user->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Usuário excluído com successo'
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }


    public function login(Request $request)
    {
        try {
            $creds = $request->only('email', 'password');
    
            $token = Auth::attempt($creds);
    
            if ($token) {
                $array['token'] = $token;
            } else {
                $array['error'] = 'E-mail e/ou senha incorretos.';
            }
            return $array;

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }

    public function logout(Request $request)
    {
        $array = ['error' => ''];

        Auth::logout();

        // $user = $request->user();

        // $user->tokens()->delete();


        return $array;
    }

    public function me()
    {
        $array = ['error' => ''];

        $user = Auth::user();

        $array['email'] = $user->email;

        return $array;
    }
}
