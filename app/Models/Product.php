<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categorie;

class Product extends Model
{
    use HasFactory;
   
    public function member_rel()
    {
        return $this->belongsTo(Categorie::class, 'cat_id', 'id'); //cat_id in products table , id in categories table
    }
}
