<?php
namespace App\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CustomValidationException extends ValidationException
{
    public function render($request): JsonResponse
    {
        return new JsonResponse([
            "status" => "error",
            "errors" => $this->validator->errors()->all(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
