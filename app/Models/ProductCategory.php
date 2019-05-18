<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uuid;

class ProductCategory extends Model
{
    protected $table = 'product_categories';
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'nome', 'status', 'categoria_pai_id'
    ];

    
    protected $hidden = [
        'id', "created_at", "updated_at", "deleted_at"
    ];

    //run create
    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    public static function find_uuid($uuid) {
        return ProductCategory::where('uuid', $uuid)->first();
    }

    public function produto() {
        return $this->hasMany(Produto::class, 'categoria_id');
    }
}