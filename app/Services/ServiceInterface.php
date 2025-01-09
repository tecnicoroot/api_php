<?php
declare(strict_types = 1);
namespace App\Services;

/**
 * Interface RepositoryInterface
 * @package App\Services
 */

interface ServiceInterface
{
    /**
     * @param array $data
     * @return array
     */

     public function create(array $data): array;
    
     /**
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function findAll(int $limit = 10, array $orderBy = []): array;

    /**
     * @param int $id
     * @return array
     */
    public function findOneBy(int $id): array;

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(array $data, int $id): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param string $string
     * @param array $searchFields
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function searchBy(string $string, array $searchFields, int $limit = 10, array $orderBy = []): array;
}