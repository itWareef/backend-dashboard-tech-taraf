<?php

namespace App\Models\Requests;

use App\Core\Interfaces\FileUpload;
use App\Models\HandleToArrayTrait;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Model;

abstract class SuperVisorRequests extends Model implements FileUpload
{
    protected $fillable = [];
    use HandleToArrayTrait;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fillable = array_merge(
            $this->mostPopularAttributes(),
            $this->additionalAttributes()
        );
    }

    private function mostPopularAttributes(): array
    {
        return [
            'supervisor_id',
            'status',
            'picture'
        ];
    }

    abstract protected function additionalAttributes(): array;
    public const STATUES = ['pending', 'accepted', 'rejected'];
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class , 'supervisor_id');
    }
    public function documentFullPathStore():string{
        return 'requests/';
    }
    public function requestKeysForFile():array{
        return ['picture'];
    }
}
