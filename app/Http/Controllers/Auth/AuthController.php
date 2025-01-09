<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{

    use ApiResponser;
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

    public function logout()
    {
        auth()->logout();
        return $this->successResponse([
            'logout' => 'success'
        ], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return $this->successResponse([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL()
        ], 200);
    }

}