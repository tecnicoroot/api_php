<?php
namespace App\Services\User;

use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use App\Services\AbstractService;
use DB;


class UserService extends AbstractService
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
    * @param int $id
    * @param array $data
    * @return bool
    */
   public function updatePassword(int $id, array $data): bool
   {
        return $this->repository->updatePassword($id, $data);
   }
}
