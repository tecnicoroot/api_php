<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isRegisterValid(Request $request)
    {
        return  $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:5'
            ]
        );
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|App\Traits\Iluminate\Http\JsonResponse|void
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        if ($this->isRegisterValid($request)) {
            try {
                $user = new User();
                $user->password = $request->password;
                $user->email = $request->email;
                $user->name = $request->name;
                $user->client_id = $this->generateApiKey();
                $user->client_secret = $this->generateApiKey();
                $user->save();
                return $this->successResponse($user);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isLoginValid(Request $request)
    {

        return $this->validate($request, [
            'email' => 'required|string',
            'password' =>  'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isLoginEmailValid(Request $request)
    {

        return $this->validate($request, [
            'email' => 'required|string',
            'password' =>  'required|string'
        ]);
    }

    public function isLoginCredentiallValid(Request $request)
    {

        return $this->validate($request, [
            'client_id' => 'required|string',
            'client_secret' =>  'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if (isset($request->grant_type)) {
            if ($request->grant_type == 'credential') {
                $token = $this->loginWithCredential($request);
            } else {
                $token = $this->loginWithEmail($request);
            }
        } else {
            $token = $this->loginWithEmail($request);
        }

        if ($token) {
            return $this->respondWithToken($token);
        } else {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
        }
    }

    public function loginWithEmail(Request $request)
    {

        if ($this->isLoginEmailValid($request)) {
            $credentials = $request->only(['email', 'password']);
            $token = auth()->setTTL(env('JWT_TTL', '60'))->attempt($credentials);
            return $token;
        }
    }

    public function loginWithCredential(Request $request)
    {
        if ($this->isLoginCredentiallValid($request)) {
            $credentials = $request->only(['client_id', 'client_secret']);
            $user =  User::where('client_id', $request->client_id)->where('client_secret', $request->client_secret)->first();
            if($user){
                $token = auth()->setTTL(env('JWT_TTL', '60'))->login($user);
                return $token;
            }else{
                return null;
            }

        }
    }

    public function me(){
        $user = auth()->user();

        return $this->successResponse($user);
    }

    public function generateApiKey()
    {
        $data = random_bytes(16);
        if (false === $data) {
            return false;
        }
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}



  