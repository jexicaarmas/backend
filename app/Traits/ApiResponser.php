<?php

namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponser
{
  private function successResponse($data, $code)
  {
    return response()->json([
      'response' => $data,
      'code'     => $code,
    ], $code);
  }

  protected function errorResponse($exception, $code)
  {
    if( $exception instanceof ValidationException ) {
      return response()->json([
          'error' => $exception->validator->errors()->getMessages(), 
          'code'  => Response::HTTP_BAD_REQUEST
        ], Response::HTTP_BAD_REQUEST);
    }

    if( $exception instanceof ModelNotFoundException ) {
      $model = strtolower(class_basename($exception->getModel()));
      return response()->json([
          'error' => ['model' => [ __('exception.model', ['attribute' => $model]) ]], 
          'code'  => Response::HTTP_BAD_REQUEST
        ], Response::HTTP_BAD_REQUEST);
    }

    if( $exception instanceof AuthenticationException ) {
      return response()->json([
        'error' => ['authentication' => [__('exception.authentication')]], 
        'code'  => Response::HTTP_UNAUTHORIZED
      ], Response::HTTP_UNAUTHORIZED);
    }

    if( $exception instanceof AuthorizationException ) {
      return response()->json([
        'error' => ['authorization' => [__('exception.authorization')]], 
        'code'  => Response::HTTP_FORBIDDEN
      ], Response::HTTP_FORBIDDEN);
    }

    if ( $exception instanceof QueryException ) {
      return response()->json([
        'error'       => ['query' => [__('exception.query')]], 
        'description' => $exception->getMessage(), 
        'code'        => Response::HTTP_CONFLICT
      ], Response::HTTP_CONFLICT);
    }

    if($code === Response::HTTP_INTERNAL_SERVER_ERROR){
      return response()->json([
        'error'       => ['internal_error' => [__('exception.interal_error')]], 
        'description' => $exception->getMessage(), 
        'line'        => $exception->getLine(), 
        'code'        => $code
      ], $code);
    }

    if($code === Response::HTTP_NO_CONTENT){
      return response()->json([
        'response'    => [],
        'description' => ['not_found_data' => [__('exception.not_found_data')]],
        'code'        => Response::HTTP_NO_CONTENT,
      ], 200);
    }
    
    return response()->json([
        'error'       => ['internal_error' => [__('exception.interal_error')]], 
        'description' => $exception, 
        'code'        => $code
      ], $code);
  }

  protected function showAll(Collection $collection, $code = Response::HTTP_OK)
  {
    if ($collection->count() === 0) {
      return  $this->successResponse([], $code);
    }

    $collection = $this->sortBy($collection);
    $collection = $this->paginate($collection);

    return $this->successResponse($collection, $code);
  }

  protected function showOne(Model $instance, $code = Response::HTTP_OK)
  {
    return $this->successResponse($instance, $code);
  }

  protected function showMessage(string $message, $code = Response::HTTP_OK)
  {
    return $this->successResponse($message, $code);
  }

  private function sortBy(Collection $collection)
  {
    if ( request()->has('sort_by') ) {
      if ( request()->has('desc') ) {
        $collection = $collection->sortByDesc( request()->sort_by )->values();
      } else {
        $collection = $collection->sortBy( request()->sort_by )->values();
      }
    }

    return $collection;
  }

  private function paginate(Collection $collection)
  {

    if ( request()->has('page') ) {
      $page    = LengthAwarePaginator::resolveCurrentPage();
      $perPage = ( request()->has('number') ) ? request()->number : 15;
      $results = $collection->slice( ($page - 1) * $perPage, $perPage)->values();

      $collection = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
          'path' => LengthAwarePaginator::resolveCurrentPage()
        ]);

      $collection->appends(request()->all());
    }
    
    return $collection;
  }

  protected function cacheResponse($data)
  {
    $uri = request()->url();
    $queryParams = request()->query();

    ksort($queryParams);
    $queryString = http_build_query($queryParams);

    return Cache::remember(sha1("{$uri}?{$queryString}"), 15/60, function() use($data){
      return $data;
    });
  }

}