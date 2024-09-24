<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at','DESC')->get()->take(10);
        // $daashboardDatas = DB::select("Select sum(total) As TotalAmount,
        //                                 sum(if(status='ordered',total,0)) As TotalOrderedAmount,
        //                                 sum(if(status='delivered',total,0)) As TotalOrderedAmount,
        //                                 sum(if(status='canceled',total,0)) As TotalOrderedAmount,
        //                                 Count(*) As Total,
        //                                    sum(if(status='ordered',1,0)) As TotalAmount,
        //                                 sum(if(status='delivered',1,0)) As TotalAmount,
        //                                 sum(if(status='canceled',1,0)) As TotalAmount,
        //                                 Form Orders
        // ");


        return view('admin.index',compact('orders'));
    }
    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }
    public function add_brands()
    {
        return view('admin.brand_add');
    }
    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg |max:2048'
        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateBrandThumbailsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brands has been added successfully');
    }


    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }
    public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:brands,slug,' . $request->slug,
            'image' => 'mimes:png,jpg,jpeg |max:2048'
        ]);
        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/brands') . '/' . $brand->image)) {
                File::delete(public_path('uploads/brands') . '/' . $brand->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateBrandThumbailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brands has been added successfully');
    }
    public function GenerateBrandThumbailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img  = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }


    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        File::delete(public_path('uploads/brands') . '/' . $brand->image);
        $brand->delete();
        return redirect()->route('admin.brands')->with('status', 'Brand has been delete successfully');
    }


    // Category
    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }


    public function add_category()
    {
        return view('admin.categories_add');
    }
    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg |max:2048'
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateCategoryThumbailsImage($image, $file_name);
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Categories has been added successfully');
    }


    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:categories,slug,' . $request->slug,
            'image' => 'mimes:png,jpg,jpeg |max:2048'
        ]);
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/categories') . '/' . $category->image)) {
                File::delete(public_path('uploads/categories') . '/' . $category->image);
            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateCategoryThumbailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Categories has been added successfully');
    }

    public function GenerateCategoryThumbailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img  = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }



    public function category_edit($id)
    {
        $category = Category::find($id);
        return view('admin.categories-edit', compact('category'));
    }
    public function category_delete($id)
    {
        $category = Category::find($id);
        File::delete(public_path('uploads/categories') . '/' . $category->image);
        $category->delete();
        return redirect()->route('admin.categories')->with('status', 'Cateries has been deleted successfully');
    }


    // products

    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }





    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }


    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quatity' => 'required',
            'image' => 'required | mimes:png,jpg,jpeg | max:2048',

            'category_id' => 'required',
            'brand_id' => 'required',
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quatity = $request->quatity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbailsImage($image, $imageName);
            $product->image =  $imageName;
        }
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;
        if ($request->hasFile('images')) {
            $allowedfileExtention = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {

                $gextention =  $file->getClientOriginalExtension();
                $gcheck = in_array($gextention, $allowedfileExtention);
                if ($gcheck) {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextention;
                    $this->GenerateProductThumbailsImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',',  $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has benn added successfully');;
    }
    public function GenerateProductThumbailsImage($image, $imageName)
    {
        $destinationPathTHumbnail = public_path('uploads/products\thumbnails');
        $destinationPath = public_path('uploads/products');
        $img  = Image::read($image->path());
        $img->cover(540, 689, "top");
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);

        $img->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathTHumbnail . '/' . $imageName);
    }


    public function product_edit($id)
    {
        $product = Product::find($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required ',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quatity' => 'required',
            'image' => ' mimes:png,jpg,jpeg | max:2048',

            'category_id' => 'required',
            'brand_id' => 'required',
        ]);

        $product = Product::find($request->id);
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quatity = $request->quatity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
                File::delete(public_path('uploads/products') . '/' . $product->image);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbailsImage($image, $imageName);
            $product->image =  $imageName;
        }
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;
        if ($request->hasFile('images')) {
            foreach (explode(',', $product->images) as  $ofile) {
                if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                    File::delete(public_path('uploads/products') . '/' . $ofile);
                }
                if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                    File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
                }
            }
            $allowedfileExtention = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {

                $gextention =  $file->getClientOriginalExtension();
                $gcheck = in_array($gextention, $allowedfileExtention);
                if ($gcheck) {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextention;
                    $this->GenerateProductThumbailsImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',',  $gallery_arr);
            $product->images = $gallery_images;
        }
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been updted successfully!!');
    }
    public function product_delete($id)
    {
        $product = Product::find($id);
        File::delete(public_path('uploads/products') . '/' . $product->image);
        File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        foreach (explode(',', $product->images) as  $ofile) {
            if (File::exists(public_path('uploads/products') . '/' . $ofile)) {
                File::delete(public_path('uploads/products') . '/' . $ofile);
            }
            if (File::exists(public_path('uploads/products/thumbnails') . '/' . $ofile)) {
                File::delete(public_path('uploads/products/thumbnails') . '/' . $ofile);
            }
        }
        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully');
    }


    // Coupon controller

    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_data', 'DESC')->paginate(12);
        return view('admin.coupons', compact('coupons'));
    }
    public function coupon_add()
    {
        return view('admin.coupon-add');
    }
    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required |numeric',
            'cart_value' => 'required |numeric',
            'expiry_data' => 'required |date',
        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_data = $request->expiry_data;

        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupons has been added successfully!!');
    }

    public function coupon_edit($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit', compact('coupon'));
    }
    public function coupon_update(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required |numeric',
            'cart_value' => 'required |numeric',
            'expiry_data' => 'required |date',
        ]);
        $coupon =  Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_data = $request->expiry_data;

        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupons has been updated successfully!!');
    }

    public function coupon_delete($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been delete successfully!!');
    }
    public function orders()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.orders', compact('orders'));
    }
    public function order_details($order_id)
    {
        $order = Order::find($order_id);
        $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id', $order_id)->first();
        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }


    //  public function update_order_status(Request $request){
    //         $order = Order::find($request->order_id);
    //         $order->status = $request->order_status;
    //         if($request->order_status == 'delivered')
    //         {
    //             $order->delivered_date = Carbon::now();
    //         }
    //         elseif($request->order_status == 'canceled'){
    //             $order->canceled_date = Carbon::now();
    //         }
    //         $order->save();

    //         if($request->order_status == 'delivered'){
    //             $transaction = Transaction::where('order_id',$request->order_id)->first();
    //             $transaction->status = 'approved';
    //             $transaction->save();
    //         }
    //  }
    public function update_order_status(Request $request)
    {
        // Find the order by ID
        $order = Order::find($request->order_id);

        // Check if the order exists
        if ($order) {
            // Update the order status
            $order->status = $request->order_status;

            // Set delivered or canceled date based on the order status
            if ($request->order_status == 'delivered') {
                $order->delivered_date = Carbon::now();
            } elseif ($request->order_status == 'canceled') {
                $order->canceled_date = Carbon::now();
            }

            // Save the updated order
            $order->save();

            // Update the transaction status if the order is delivered
            if ($request->order_status == 'delivered') {
                $transaction = Transaction::where('order_id', $request->order_id)->first();

                // Check if the transaction exists
                if ($transaction) {
                    $transaction->status = 'approved';
                    $transaction->save();
                } else {
                    // Handle the case where the transaction is not found
                    session()->flash('error', 'Transaction not found for this order.');
                }
            }
        } else {
            // Handle the case where the order is not found
            session()->flash('error', 'Order not found.');
        }

        // Redirect or return response as needed
        return redirect()->back()->with('status', 'Status Updated');
    }

    public function slides(){
        $slides = Slide::orderBy('id','DESC')->paginate(12);
        return view('admin.slides',compact('slides'));
    }

    public function slide_add(){
        return view('admin.slide-add');
    }

    public function slide_store(Request $request){

        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required', // Change this to subtitle
            'link' => 'required',
            'status' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        $slide =new Slide();
       $slide->tagline = $request->tagline;
       $slide->title = $request->title;
       $slide->subtitle = $request->subtitle;
       $slide->link = $request->link;
       $slide->status = $request->status;

       $image = $request->file('image');
       $file_extention = $request->file('image')->extension();
       $file_name = Carbon::now()->timestamp . '.' . $file_extention;
       $this->GenerateSlideThumbailsImage($image, $file_name);
       $slide->image = $file_name;
       $slide->save();
       return redirect()->route('admin.slides')->with('status','slide add successfully');




    }
    public function GenerateSlideThumbailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/slides');
        $img  = Image::read($image->path());
        $img->cover(400, 690, "top");
        $img->resize(400, 690, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function slide_edit($id){

        $slide = Slide::find($id);
        return view('admin.slide-edit',compact('slide'));
    }

    public function slide_update(Request $request){
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required', // Change this to subtitle
            'link' => 'required',
            'status' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        $slide = Slide::find($request->id);
       $slide->tagline = $request->tagline;
       $slide->title = $request->title;
       $slide->subtitle = $request->subtitle;
       $slide->link = $request->link;
       $slide->status = $request->status;
        if($request->hasFile('image'))
        {
            if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
                File::delete(public_path('uploads/slides').'/'.$slide->image);

            }
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateSlideThumbailsImage($image, $file_name);
            $slide->image = $file_name;

        }
       $slide->save();
       return redirect()->route('admin.slides')->with('status','slide Updated successfully');
    }

    public function slide_delete($id){
        $slide = Slide::find($id);
        if(File::exists(public_path('uploads/slides').'/'.$slide->image)){
            File::delete(public_path('uploads/slides').'/'.$slide->image);

        }
        $slide->delete();
        return redirect()->route('admin.slides')->with('status','slide Delete successfully');

    }

    public function contact(){
        $contacts = Contact::orderBy('created_at','DESC')->paginate(10);
        return view('admin.contacts',compact('contacts'));
    }
    public function contact_delete($id) {
        $contact = Contact::find($id);
        $contact->delete();
        return redirect()->route('admin.contacts')->with('status', 'Contact has been deleted!');
    }
}
