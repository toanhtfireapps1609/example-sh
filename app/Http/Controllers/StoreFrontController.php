<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\SpfApi\AuthApi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreFrontController extends Controller
{
    public function getMessages($product_id)
    {
        $message = Message::where('product_id', $product_id)->latest()->limit(3)->get();
        return response([
            'status' => 200,
            'data' => $message
        ], 200);
    }

}
