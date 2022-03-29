<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

// use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class BaseController extends Controller
{
    protected $statusCode;

    /**
     * @var ApiResponder
     */
    protected $responder;
    protected $transformer;

    public function __construct()
    {
        $this->request = app('request');
    }

    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {

    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }


    public function setStatusCode($statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function addMeta($metaKey, $metaValue): self
    {
        $this->responder->addMeta($metaKey, $metaValue);

        return $this;
    }

    public function withPaginator(LengthAwarePaginator $paginator, $transformer = null): Response
    {
        $statusCode = $this->statusCode ?: Response::HTTP_OK;
        return $this->responder->respondWithPaginator($paginator, $transformer ?: $this->transformer, $statusCode);
    }

    public function withItem($item, $transformer = null): Response
    {
        $statusCode = $this->statusCode ?: ResponseAlias::HTTP_OK;
        return $this->responder->respondWithItem($item, $transformer ?: $this->transformer, $statusCode);
    }

    public function respondWithEmpty(): Response
    {
        return $this->responder->respondWithEmpty();
    }

    public function withCollection($collection, $transformer = null): Response
    {
        $statusCode = $this->statusCode ?: Response::HTTP_OK;
        return $this->responder->respondWithCollection($collection, $transformer ?: $this->transformer, $statusCode);
    }

    public function withArray(array $data): JsonResponse
    {
        $statusCode = $this->statusCode ?: Response::HTTP_OK;
        return $this->responder->respondWithArray($data, $statusCode);
    }

    protected function getQueryBuilderParams($per_page = 10): array
    {
        return [
            'per_page'  => $this->request->get('per_page', $per_page),
        ];
    }
}
