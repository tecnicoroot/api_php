<?php
/**
 * Para que o PHP trabelhe com type hints
 */
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;


/**
 * Class Controller
 * @package App\Http\Controller
 */
abstract class AbstractController extends BaseController implements ControllerInterface
{
    /**
     * @var ServiceInterface
     */
    protected ServiceInterface $service;

    /**
     * @var array
     */
    protected array $searchFields = [];

    /**
     * @var array
     */
    protected array $validationCreate = [];

    /**
     * @var array
     */
    protected array $validationUpdate = [];

    /**
     * @var array
     */
    protected array $validationUpdatePassword = [];

    /**
     * AbstractController constructor
     * @param  ServiceInterface $service
     */
    public function __construct(ServiceInterface $service, array $validationCreate, array $validationUpdate, array $validationUpdatePassword)
    {
       $this->service = $service;
       $this->validationCreate = $validationCreate;
       $this->validationUpdate = $validationUpdate;
       $this->validationUpdatePassword = $validationUpdatePassword;

    }
    
     /**
      * @param  Request $request
      * @return JsonResponse
      */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, $this->validationCreate);
        try{
            $result = $this->service->create($request->all());
            $response = $this->successResponse($result, Response::HTTP_CREATED);
        }catch(Exception $e){
            $response = $this->errorResponse($e);
        }

        return response()->json($response, $response['status_code']);
    }

      /**
       * findAll
       *
       * @param  Request $request
       * @return JsonResponse
       */
    public function findAll(Request $request): JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $orderBy = $request->get('order_by', []);
            $result = $this->service->findAll($limit,$orderBy);
            $searchString = $request->get('q','');

            if(!empty($searchString)){
                $result = $this->service->searchBy(
                    $searchString,
                    $this->searchFFields,
                    $limit,
                    $orderBy
                );
            }
            $response = $this->successResponse($result, Response::HTTP_PARTIAL_CONTENT);
        } catch (Exception $e) {
            $response = $this->errorResponse($e);
        }

        return response()->json($response, $response['status_code']);
    }

      /**
       * findOneBy
       *
       * @param  Request $request
       * @param  int $id
       * @return JsonResponse
       */
    public function findOneBy(Request $request, int $id): JsonResponse
    {
        
        try {
            $result = $this->service->findOneBy($id);
            $response = $this->successResponse($result);
        } catch (Exception $e) {
            $response = $this->errorResponse($e);
        }
        return response()->json($response, $response['status_code']);
    }

      /**
       * update
       *
       * @param  Request $request
       * @param  string $param
       * @return JsonResponse
       */
    public function update(Request $request, int $id): JsonResponse
    {
        //var_dump($request);
        $this->validate($request, $this->validationUpdate);
        
        try {
            $result['registo_alterado'] = $this->service->update($request->all(), $id);
            $response = $this->successResponse($result);
        } catch (Exception $e) {
            $response = $this->errorResponse($e);
        }
        return response()->json($response, $response['status_code']);
    }

      /**
       * delete
       *
       * @param  Request $request
       * @param  int $id
       * @return JsonResponse
       */
    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $result['registo_deletado'] = $this->service->delete($id);
            $response = $this->successResponse($result);
        } catch (Exception $e) {
            $response = $this->errorResponse($e);
        }
        return response()->json($response, $response['status_code']);
    }

    /**
     * @param  array $data
     * @param  int $statusCode
     * @return array
     */
    protected function successResponse(array $data, int $statusCode = Response::HTTP_OK): array
    {
        return [
            'status_code' => $statusCode,
            'data' => $data
        ];
    }

    /**
     * @param  Exception $e
     * @param  int $statusCode
     * @return array
     */
    protected function errorResponse(Exception $e, int $statusCode = Response::HTTP_BAD_REQUEST): array
    {
        return [
            'status_code' => $statusCode,
            'error' => true,
            'error_description' => $e->getMessage()
        ];
    }



}
