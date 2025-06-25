<?php

namespace App\Core\Interfaces;

interface FileUpload
{
    public function documentFullPathStore():string;
    public function requestKeysForFile():array;
}
