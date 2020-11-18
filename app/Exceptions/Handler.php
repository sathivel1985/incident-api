<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException AS AccessDeniedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Auth\AuthenticationException as AuthenticationException;
use Illuminate\Validation\ValidationException as ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException as MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException as HttpException;
use Illuminate\Database\QueryException as QueryException; 
use Mockery\Exception\BadMethodCallException as BadMethodCallException;
use Symfony\Component\Debug\Exception\FatalThrowableError AS FatalThrowableError;
use Illuminate\Auth\Access\AuthorizationException AS AuthorizationException;

use Throwable;
 
use App\Traits\ApiResponser;

class Handler extends ExceptionHandler
{
     use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        
        

        if ($exception instanceof ValidationException) {

            return $this->convertValidationExceptionToResponse($exception, $request);

        }

        if ($exception instanceof AuthorizationException)
        {
              return $this->errorResponse("this is unauthorized access", 403);
        }

        if ($exception instanceof AuthenticationException) {

            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ModelNotFoundException) {
            
            $modelName = strtolower(class_basename($exception->getModel()));

            return $this->errorResponse("Does not exists any {$modelName} with the sepecified identifcator", 405);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            
             return $this->errorResponse("the specified method for the request is invalid", 405);
        }
 

        if ($exception instanceof NotFoundHttpException) {
            
             return $this->errorResponse("the specified URL cannot be found", 404);
        }

        if ($exception instanceof HttpException) {
            
             return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        if ($exception instanceof QueryException) {
            $errorcode = $exception->errorInfo[1];
            if($errorcode == 1451   )
            {
                return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource', 409);
            } else if($errorcode == 1364) {
                return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource <br/>'.$exception->getMessage(), 409);
            }
        }

        if ( $exception instanceof FatalThrowableError )
        {
            //return $this->errorResponse('Unexpected FatalThrowableError exception'.$exception->getMessage(), 500);
        }


        if (config('app.debug')) {
           return parent::render($request, $exception);
        }
        
        return $this->errorResponse('Unexpected Exception. Try later', 500);



    }

    protected function convertValidationExceptionToResponse(ValidationException $e , $request) 
    {
        $errors = $e->validator->errors()->getMessages();

         if (!$this->isFrontend($request)) {
           
            return $this->errorResponse($errors,422);

        } else {
            
            return redirect()->back()->withInput()->withErrors($errors);
        
        }

        //return $this->errorResponse($errors,422);
    }
    protected function unauthenticated($request, AuthenticationException $exception) 
    {
        if (!$this->isFrontend($request)) {
           
            return $this->errorResponse('Unauthenticated', 401);
        }

        return redirect()->guest('login');
    }
    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
     
}

