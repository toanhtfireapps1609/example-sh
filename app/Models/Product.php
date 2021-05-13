<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['id', 'product_id', 'title', 'price', 'images', 'body_html'];

}
