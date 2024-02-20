<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Categorie extends Model
{
    use HasFactory;
    protected $table="categories";
   
    public function cat_rel()
{
    return $this->hasMany(Product::class, 'cat_id', 'id'); //cat_id in products table , id in categories table
}
}
