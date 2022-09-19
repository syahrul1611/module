<?php

namespace Modules\Post\Entities\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'category_id',
        'title',
        'image',
        'content',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo('Modules\Category\Entities\V1\Category', 'category_id');
    }
}
