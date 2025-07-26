<?php

namespace App\Models;

use App\Core\Interfaces\FileUpload;
use Illuminate\Database\Eloquent\Model;

class Guarantee extends Model implements FileUpload
{
    use HandleToArrayTrait;
    protected $fillable=[
        'name',
        'duration',
        'picture',
        'supplier_id',
    ];


    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function documentFullPathStore(): string
    {
        return 'guarantees/';
    }

    public function requestKeysForFile(): array
    {
        return [
            'picture'
        ];
    }
}
