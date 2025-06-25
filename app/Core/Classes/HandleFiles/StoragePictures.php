<?php

namespace App\Core\Classes\HandleFiles;


use AllowDynamicProperties;
use App\Core\Interfaces\OriginalName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

#[AllowDynamicProperties] class StoragePictures
{
    protected static string $disk='';

    public function __construct()
    {
        $this->disk = env('FILESYSTEM_DRIVER', 'public');
    }

    public static function storeFile(UploadedFile $file, Model $model): false|string
    {
        $disk =  static::$disk;
        $fullPath = $model->documentFullPathStore();
        $name = $file->hashName();
        if ($model instanceof OriginalName){
            $name = $file->getClientOriginalName();
            $name = str_replace(' ', '_', $name);
        }
        $file->storeAs($fullPath , $name,$disk);
        return $name;
    }

    private static function retrieveFile( string $fileName): string
    {
        return Storage::disk(static::$disk)->url($fileName);
    }

    public static function deleteFile(Model $model, string $fileName){
        Storage::disk(static::$disk)->delete(self::customPath($model,$fileName));
    }

    private static function customPath( Model $model, string$fileName): string
    {
        return rtrim($model->documentFullPathStore(),'/').'/'.$fileName;
    }
    public static function customUrl( Model $model, string$fileName): string{
        return self::retrieveFile(self::customPath($model,$fileName));
    }

}
