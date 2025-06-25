<?php

use Illuminate\Support\Facades\Response;

Response::macro('success', function (array $data, array $messages = [], int $code = 200) {
    return $this->json([
        'status' => 'success',
        'messages' => $messages,
        'data' => $data
    ], $code);
});

Response::macro('error', function (string | array $messages = "", int $code = 500) {


    return $this->json([
        'status' => 'error',
        'errors' => is_array($messages)?$messages:[$messages]
    ], $code);
});
