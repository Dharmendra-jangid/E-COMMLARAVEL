<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Break_;

class ShopController extends Controller
{
    // public function index(Request $request)

    // {
    //     $size = $request->query('size')  ? $request->query('size') : 12;
    //     $o_column = "";
    //     $o_order = "";
    //     $order = $request->query('order') ?  $request->query('order') : -1;
    //     $products = Product::orderBy('created_at', 'DESC')->orderBy($o_column,$o_order)->paginate($size);
    //     switch ($order) {
    //         case 1:
    //             $o_column = ' created_at';
    //             $o_order = ' desc';
    //             break;

    //         case 2:
    //             $o_column = ' created_at';
    //             $o_order = 'asc';
    //             break;

    //         case 3:
    //             $o_column = ' regular_price';
    //             $o_order = 'asc';
    //             break;
    //         case 4:
    //             $o_column = ' regular_price';
    //             $o_order = 'desc';
    //             break;
    //         default :
    //         $o_column = ' regular_price';
    //         $o_order = 'desc';
    //     }
    //     $products = Product::orderBy('created_at', 'DESC')->orderBy($o_column,$o_order)->paginate($size);
    //     return view('shop', compact('products', 'size', 'order'));
    // }



    public function index(Request $request)
    {
        $size = $request->query('size') ? $request->query('size') : 12;

        // Default column and order
        $o_column = 'created_at'; // Default column
        $o_order = 'desc';        // Default order

        // Get 'order' query parameter from request
        $order = $request->query('order') ? intval($request->query('order')) : -1;
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        // $min_price = $request->query('min') ? $request->query('min') : 1;
        // $max_price = $request->query('max') ? $request->query('max') :10000;

        $min_price = $request->query('min') ? $request->query('min') : 1;
$max_price = $request->query('max') ? $request->query('max') : 500000;

        // Determine column and order based on 'order' parameter
        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'desc';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'asc';
                break;
            case 3:
                $o_column = 'sale_price';
                $o_order = 'asc';
                break;
            case 4:
                $o_column = 'sale_price';
                $o_order = 'desc';
                break;
            default:
                $o_column = 'id';
                $o_order = 'desc';
        }

        // Validate the column and order to prevent SQL injection or errors
        $valid_columns = ['created_at', 'regular_price'];
        $valid_orders = ['asc', 'desc'];

        if (!in_array($o_column, $valid_columns)) {
            $o_column = 'regular_price'; // Fallback to default column
        }

        if (!in_array($o_order, $valid_orders)) {
            $o_order = 'desc'; // Fallback to default order
        }

        // Retrieve products with dynamic sorting and pagination
        // $products = Product::where(function($query) use($f_brands){
        //     $query->whereIn('brand_id',explode(',',$f_brands))->orWhereRaw("'".$f_brands."'=''");
        // })->orderBy($o_column, $o_order)->paginate($size);
        $categories = Category::orderBy('name','ASC')->get();

        $brands = Brand::orderBy('name','ASC')->get();

        $products = Product::where(function($query) use($f_brands) {
            $query->whereIn('brand_id', explode(',', $f_brands))
                  ->orWhereRaw("'".$f_brands."' = ''");
        })
        ->where(function($query) use($f_categories) {
            $query->whereIn('category_id', explode(',', $f_categories))
                  ->orWhereRaw("'".$f_categories."' = ''");
        })
        ->where(function($query) use($min_price,$max_price){
            $query->whereBetween('regular_price',[$min_price,$max_price])
            ->orWhereBetween('sale_price',[$min_price,$max_price]);
        })
        ->orderBy($o_column, $o_order)
          ->paginate($size);




        // Pass variables to the view
        return view('shop', compact('products', 'size', 'order','brands','f_brands','categories','f_categories','min_price','max_price'));
    }





    public function product_details($product_slug)

    {
        $product =  Product::where('slug', $product_slug)->first();
        $rproducts = Product::where('slug', '<>', $product_slug)->get()->take(8);
        return view('details', compact('product', 'rproducts'));
    }
}
