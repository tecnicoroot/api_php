<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RepositoryInterface;

class AbstractService implements ServiceInterface
{
    /**
     * @var RepositoryInterface
     */
    protected RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


     /**
     * @param array $data
     * @return array
     */

    public function create(array $data): array
    {
     
     return $this->repository->create($data);
    }


    /**
    * @param int $limit
    * @param array $orderBy
    * @return array
    */
   public function findAll(int $limit = 10, array $orderBy = []): array
   {
       return $this->repository->findAll($limit,$orderBy);
   }

   /**
    * @param int $id
    * @return array
    */
   public function findOneBy(int $id): array
   {
        return $this->repository->findOneBy($id);
   }

   /**
    * @param int $id
    * @param array $data
    * @return bool
    */
   public function update(array $data, int $id): bool
   {
        return $this->repository->update($data, $id);
   }

   /**
    * @param int $id
    * @return bool
    */
   public function delete(int $id): bool
   {
        return $this->repository->delete($id);
   }

   /**
    * @param string $string
    * @param array $searchFields
    * @param int $limit
    * @param array $orderBy
    * @return array
    */
   public function searchBy(string $string, array $searchFields, int $limit = 10, array $orderBy = []): array
   {
        return $this->repository->searchBy($string,$searchFields,$limit,$orderBy);
   }

}
