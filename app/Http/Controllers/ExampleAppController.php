<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\SpfApi\AuthApi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExampleAppController extends Controller
{
    /**
     * @var AuthApi
     */
    protected $authApi;

    public function __construct(AuthApi $auth)
    {
        $this->authApi = $auth;
    }

    public function index()
    {
        return view('example.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function loginShop(Request $request)
    {
        $storeName = $request->store_name;
        $urlInstall = $this->authApi->urlInstall($storeName);
        return redirect($urlInstall);
    }

    public function authRedirect(Request $request)
    {
        $data = $request->all();
        $shop = $data['shop'] ?? null;
        $code = $data['code'] ?? null;

        $verifyShop = $this->authApi->verifyRequest($data);
        if ($verifyShop) {
            $result = $this->authApi->getAccessToken($shop, $code);
            if ($result['status']) {
                $accessToken = $result['data']['access_token'];
                $reponseProduct = $this->authApi->getProducts($shop, $accessToken);

                DB::beginTransaction();
                try {

                    Shop::updateOrCreate(
                        ['shop_name' => $shop],
                        ['access_token' => $accessToken, 'shop_name' => $shop]
                    );

                    if ($reponseProduct['status']) {
                        foreach ($reponseProduct['data']['products'] as $product) {
                            $images = array_map(function ($element) {
                                return $element['src'];
                            }, $product['images']);

                            //Update variants with multiple price later
                            $variants = $product['variants'];
                            $price =  count($variants) ? floatval($variants[0]['price']) : 0;

                            Product::updateOrCreate(
                                ['product_id' => $product['id']]
                                , [
                                'product_id' => $product['id'],
                                'title' => $product['title'],
                                'price' => $price,
                                'body_html' => $product['body_html'],
                                'images' => json_encode($images, true),
                            ]);
                        }
                    }

                    DB::commit();
                    return view('example.final', [
                        'success' => true,
                        'shop' => $shop
                    ]);

                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                }
            }
        }
        return view('example.final', [
            'success' => false,
            'shop' => $shop
        ]);
    }

    public function products() {
        $products = Product::all();
        return view('example.products', compact('products'));
    }

    public function getFormMessage(Product $product) {
        return view('example.message-form', [
            'product' => $product
        ]);
    }

    public function postMessage(Request $request) {

        $data = $request->only(['product_id', 'content']);
        try {
            DB::transaction(function () use ($data) {
                Message::create([
                    'product_id' => $data['product_id'],
                    'content' => $data['content'],
                ]);
            });
            return redirect()->back()->with('success','Send message to product successfully!');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error','Send message to product failed!');
        }

    }

}
