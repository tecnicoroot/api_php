<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Repositories\AbstractRepository;
use App\Models\User;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */

 class UserRepository extends AbstractRepository
 {
    public function __construct(User $model)
    {
        $this->model =  $model;
    }
    
    /**
     * Função resposnsável por alterar a senha do usuário
     * @param int $id
     * @param array $data
     * @return bool
     */

    public function updatePassword(int $id, array $data) : bool
    {
        return $this->model->find($id)->update($data);
    }

    
    public function create(array $data): array
    {
        $data['client_id'] = $this->generateApiKey();
        $data['client_secret']= $this->generateApiKey(); 
        return $this->model::create($data)->toArray();
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