<?php

use Illuminate\Support\Str;

Str::macro('snakeToTitle', function ($value) {
    return ucwords(str_replace('_', ' ', $value));
});

Str::macro('humanText', function($value) {
    $text = preg_replace("/[^a-zA-Z0-9]+/", " ", $value);
    return Str::title($text);
});

