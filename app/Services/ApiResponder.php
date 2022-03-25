<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiResponder
{
    /**
     * To include query string key.
     *
     * @var string
     */
    protected $includeKey = 'with';

    /**
     * To include separator.
     * @var string
     */
    protected $includeSeparator = ',';

    /**
     * @var Manager
     */
    protected static $fractal;

    /**
     * @var array
     */
    private $responseMeta = [];

    public static function setFractal($fractal)
    {
        static::$fractal = $fractal;
    }

    protected function parseIncludes()
    {
        $includes = request()->input($this->includeKey);

        if (! is_array($includes)) {
            $includes = array_map('trim', array_filter(explode($this->includeSeparator, $includes)));
        }

        static::$fractal->parseIncludes($includes);
    }

    /**
     * @param Model|null $model
     * @param TransformerAbstract $transformer
     * @param int $statusCode
     * @return Response
     */
    public function respondWithItem($model, TransformerAbstract $transformer, int $statusCode = Response::HTTP_OK)
    {
        $transformer = new Item($model, $transformer);
        return $this->respond($transformer, $statusCode);
    }

    public function respondWithEmpty(int $statusCode = Response::HTTP_NO_CONTENT)
    {
        return (new Response(null, $statusCode))
            ->header('Content-Type', 'application/json');
    }


    public function respondWithCollection(Collection $collection, TransformerAbstract $transformer, int $statusCode = Response::HTTP_OK): Response
    {
        $transformer = new \League\Fractal\Resource\Collection($collection, $transformer);
        return $this->respond($transformer, $statusCode);
    }

    public function addMeta($metaKey, $metaValue)
    {
        $this->responseMeta[$metaKey] = $metaValue;

        return $this;
    }

    public function respondWithPaginator(LengthAwarePaginator $paginator, TransformerAbstract $transformer, int $statusCode = Response::HTTP_OK)
    {
        $books = $paginator->getCollection();

        $resource = new \League\Fractal\Resource\Collection($books, $transformer, 'data');
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->respond($resource, $statusCode);
    }

    protected function respond(ResourceAbstract $resource, int $statusCode = ResponseAlias::HTTP_OK): Response
    {
        $this->parseIncludes();
        foreach ($this->responseMeta as $key => $value) {
            $resource->setMetaValue($key, $value);
        }
        $response = static::$fractal->createData($resource)->toArray();

        return (new Response($response, $statusCode))
            ->header('Content-Type', 'application/json');
    }

    public function respondWithArray(array $data, int $statusCode): JsonResponse
    {
        return new JsonResponse($data, $statusCode);
    }
}
