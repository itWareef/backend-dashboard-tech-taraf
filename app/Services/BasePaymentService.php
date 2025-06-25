<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class BasePaymentService
{
    protected  $base_url;
    protected $header;

    protected function buildRequest($method, $url, $data=null , $type="json")
    {
        try {
            $response = Http::withHeaders($this->header)->send($method, $this->base_url.$url, [
                $type =>$data
            ]);
            if (array_key_exists('errors', $response->json())) {
                return Response::error($response->json());
            }
            return Response::success($response->json(),["Payment Successful"],);
        }catch (\Exception $e){
            return Response::error($e->getMessage());
        }
    }
}
