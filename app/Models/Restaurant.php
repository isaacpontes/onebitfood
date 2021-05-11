<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'delivery_tax',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product_categories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
