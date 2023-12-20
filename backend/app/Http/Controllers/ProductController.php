<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{   
    const LOCAL_FOLDER_PATH = 'public/images/';
    private $product;

    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function index() {
        $all_products = $this->product->all();

        return view('products.index')->with('all_products', $all_products);
    }

    public function store(Request $request) {
        $request->validate([
            'product_name'  => 'required|min:1|max:255',
            'price'         => 'required|min:1|max:999.99',
            'stock'         => 'required|min:1',
            'image'         => 'mimes:jpeg,jpg,png,gif|max:1048' 
        ]);

        $this->product->name  = $request->product_name;
        $this->product->price = $request->price;
        $this->product->stock = $request->stock;

        if($request->image) {
            $this->product->image = $this->saveImage($request);
        }

        $this->product->save();

        return redirect()->back();
    } 
    
    private function saveImage($request) {
        $img_name = time(). '.'. $request->image->extension();
        $request->image->storeAs(self::LOCAL_FOLDER_PATH, $img_name);

        return $img_name;
    }

    private function deleteImage($img_name) {
        $img_name = self::LOCAL_FOLDER_PATH. $img_name;
        if(Storage::disk('local')->exists($img_name)) {
            Storage::disk('local')->delete($img_name);
        }
    }

    public function edit($id) {
        $product = $this->product->findOrFail($id);
        return view('products.create')->with('product', $product);
    }

    public function update(Request $request, $id) {
        $product = $this->product->findOrFail($id);
        
        $request->validate([
            'product_name'  => 'required|min:1|max:255',
            'price'         => 'required|min:1|max:999.99',
            'stock'         => 'required|min:1',
            'image'         => 'mimes:jpeg,jpg,png,gif|max:1048' 
        ]);
        
        $product->name  = $request->product_name;
        $product->price = $request->price;
        $product->stock = $request->stock;

        if($request->image) {
            $this->deleteImage($product->image);
            $product->image = $this->saveImage($request);
        }

        $product->save();

        return redirect()->route('index');
    }

    public function destroy($id) {
        $this->product->destroy($id);

        return redirect()->back();
    }

    public function updateStock(Request $request, $id) {
        $product = $this->product->findOrFail($id);
        
        $request->validate([
            'qty' => 'required|min:1' 
        ]);

        // Check Payment
        $payment_check = $this->paymentCheck($request, $product->price);

        if($payment_check) {
            return redirect()->back()->withErrors(['payment' => 'Insufficient payment.']);
        }

        $new_stock = $product->stock - $request->qty;

        // Stock is gone -> delete the item
        if($new_stock <= 0) {
            $product->delete();
        } else {
            $product->stock = $new_stock;
            $product->save();
        }

        return redirect()->back();
    }

    private function paymentCheck($request, $price) {
        $payment = $request->total;
        $total   = $request->qty * $price;

        if($payment >= $total) {
            return false;
        }

        return true;
    }
}
