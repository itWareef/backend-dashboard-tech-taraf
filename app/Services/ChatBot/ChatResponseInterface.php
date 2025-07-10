<?php

namespace App\Services\ChatBot;

interface ChatResponseInterface
{
    public function matches(string $message, ?string $lang): bool;
    public function reply(string $lang): array;
}
