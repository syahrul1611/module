<?php

namespace Modules\Category\Entities\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'desc',
        'created_by',
        'updated_by'
    ];
    
    public function posts()
    {
        return $this->hasMany('Modules\Post\Entities\V1\Post', 'id');
    }
}
