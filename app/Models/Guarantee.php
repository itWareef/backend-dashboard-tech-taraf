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

    protected $appends = ['expiry_date'];

    protected $hidden = ['unit_purchase_date']; // لإخفاء الخاصية المؤقتة من JSON
    
    public function getExpiryDateAttribute()
    {
        if (!isset($this->unit_purchase_date)) {
            return null;
        }
        $duration = is_numeric($this->duration) ? (float)$this->duration : 0;

        try {
            return \Carbon\Carbon::parse($this->unit_purchase_date)
                ->addYears($duration)
                ->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }
    
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
