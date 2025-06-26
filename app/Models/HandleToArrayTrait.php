<?php

namespace App\Models;

use App\Core\Classes\HandleFiles\StoragePictures;
use App\Core\Interfaces\FileUpload;

trait HandleToArrayTrait
{
    public function toArray()
    {
        $data = parent::toArray();
        if ($this instanceof FileUpload){
            foreach ($this->requestKeysForFile()as $file){
                if (isset($data[$file]) && $data[$file] != null){
                        $data[$file] = StoragePictures::customUrl($this , $data[$file]);
                }

            }
        }
        return $data;
    }
}
