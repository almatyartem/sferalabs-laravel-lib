<?php

namespace Tests\App\Modules\WorkingExample\Controllers;

use LaravelSferaLibrary\Exceptions\NotFoundException;
use LaravelSferaLibrary\Exceptions\ValidationException;
use LaravelSferaLibrary\Http\ApiResponse;
use LaravelSferaLibrary\Http\Controller;
use Tests\App\Modules\WorkingExample\Requests\TestRequest;
use Tests\App\Modules\WorkingExample\Services\PostsService;
use Illuminate\Http\JsonResponse;

class WorkingExamplePostsCrudController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/WorkingExample/v1/crud/{id}",
     *     summary="Get record by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of record",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response=200, ref="#components/responses/200"),
     *     @OA\Response(response=404, ref="#components/responses/404")
     * )
     * @param PostsService $service
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function read(PostsService $service, int $id) : JsonResponse
    {
        $entity = $service->read($id);

        if(!$entity){
            //Same that ApiResponse::notFoundResponse(404) BUT exception option will work same way everywhere
            throw new NotFoundException();
        }

        return ApiResponse::response($entity);
    }

    /**
     * @OA\Post(
     *     path="/api/WorkingExample/v1/crud",
     *     summary="Create new record",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"unique_string_example","string_example","int_example","enum_example"},
     *                 @OA\Property(
     *                     description="User id",
     *                     property="user_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Unique string example",
     *                     property="unique_string_example",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="String example",
     *                     property="string_example",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="Int example",
     *                     property="int_example",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Enum example",
     *                     property="enum_example",
     *                      type="array",
     *                      @OA\Items(
     *                          type="string",
     *                          enum = {"one", "two"},
     *                      )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, ref="#components/responses/200"),
     *     @OA\Response(response=400, ref="#components/responses/400"),
     *     @OA\Response(response=404, ref="#components/responses/404")
     * )
     * @param PostsService $service
     * @param TestRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(PostsService $service, TestRequest $request) : JsonResponse
    {
        $entity = $service->createByArray($this->getDataFromRequest($request)); //OR $service->createByEntity(OnlyForTestEntity::fromArray($data));

        return ApiResponse::response($entity);
    }

    /**
     * @OA\Post(
     *     path="/api/WorkingExample/v1/crud/{id}",
     *     summary="Update record by id",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"unique_string_example","string_example","int_example","enum_example"},
     *                 @OA\Property(
     *                     description="User id",
     *                     property="user_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Unique string example",
     *                     property="unique_string_example",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="String example",
     *                     property="string_example",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="Int example",
     *                     property="int_example",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="Enum example",
     *                     property="enum_example",
     *                     type="array",
     *                      @OA\Items(
     *                          type="string",
     *                          enum = {"one", "two"},
     *                      )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Id",
     *         in="path",
     *         description="ID of record",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(response=200, ref="#components/responses/200"),
     *     @OA\Response(response=400, ref="#components/responses/400"),
     *     @OA\Response(response=404, ref="#components/responses/404")
     * )
     * @param PostsService $service
     * @param TestRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function update(PostsService $service, TestRequest $request, int $id) : JsonResponse
    {
        $userEntity = $service->updateByArray($id, $this->getDataFromRequest($request));

        return ApiResponse::response($userEntity);
    }

    /**
     * @OA\Delete(
     *     path="/api/WorkingExample/v1/crud/{id}",
     *     summary="Delete record by id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of record",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response=200, ref="#components/responses/200")
     * )
     * @param PostsService $service
     * @param int $id
     * @return JsonResponse
     */
    public function delete(PostsService $service, int $id) : JsonResponse
    {
        return ApiResponse::response(null, $service->delete($id));
    }

    /**
     * @param TestRequest $request
     * @return array
     */
    protected function getDataFromRequest(TestRequest $request) : array
    {
        $data = [
            'user_id' => $request->getUserId(),
            'unique_string_example' => $request->getUniqueStringExample(),
            'string_example' => $request->getStringExample(),
            'int_example' => $request->getIntExample(),
            'enum_example' => $request->getEnumExample(),
        ];

        foreach($data as $k => $v){
            if(is_null($v)){
                unset($data[$k]);
            }
        }

        return $data;
    }
}
