<?php
/**
 * Para que o PHP trabelhe com type hints
 */
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
/**
 * Interface ControllerInterface
 * @package App\Http\Controllers
 */

 interface ControllerInterface
 {

     /**
      * @param  Request $request
      * @return JsonResponse
      */
     public function create(Request $request): JsonResponse;

     /**
      * findAll
      *
      * @param  Request $request
      * @return JsonResponse
      */
     public function findAll(Request $request): JsonResponse;

     /**
      * findOneBy
      *
      * @param  Request $request
      * @param  int $id
      * @return JsonResponse
      */
     public function findOneBy(Request $request, int $id): JsonResponse;

     /**
      * update
      *
      * @param  Request $request
      * @param  int $id
      * @return JsonResponse
      */
     public function update(Request $request, int $id): JsonResponse;

     /**
      * delete
      *
      * @param  Request $request
      * @param  int $id
      * @return JsonResponse
      */
     public function delete(Request $request, int $id): JsonResponse;

 }
