<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class UserController
 * @package App\Http\Controllers\V1\User
 */
class UserController extends AbstractController
{
    /**
     * @var array|string[]
     */
    protected array $searchFields = [
        
    ];
    
    /**
     * __construct
     * UserController constructor
     * @param  UserService $service
     */
    public function __construct(UserService $service)
    {
        
        $validationCreate = [
            'name' => 'required | max: 80',
            'email' => 'required|email',
            'password'	=> 'required|confirmed',
            'password_confirmation' => 'required',
    
        ];
        
        $validationUpdate = [
            'name' => 'required | max: 80',
            'email' => 'required|email',
        ];

        $validationUpdatePassword =[
            'password'	=> 'required|confirmed',
            'password_confirmation' => 'required',
        ];
        
        //$this->service = $service;
        parent::__construct($service, $validationCreate, $validationUpdate, $validationUpdatePassword);
    }

    public function updatePassword(int $id, Request $request): JsonResponse
    {
        $this->validate($request, $this->validationUpdatePassword);
        try {
            $result['senha_alterada'] = $this->service->updatePassword($id, $request->all());
            $response = $this->successResponse($result);
        } catch (Exception $e) {
            $response = $this->errorResponse($e);
        }
        return response()->json($response, $response['status_code']);
    }

    public function generateKey(int $id){
        try {
            $user = $this->service->findOneBy($id);
            $user["client_id"] = (new User($user))->setClientIdAttribute();
            $user["client_secret"] = (new User($user))->setClientSecretAttribute();
            
            $this->service->update($user ,  $id);
            
            $response = $this->successResponse( $user );
        } catch (Exception $e) {
            $response = $this->errorResponse($e);
        }
        return response()->json($response, $response['status_code']);
        
    }


}


