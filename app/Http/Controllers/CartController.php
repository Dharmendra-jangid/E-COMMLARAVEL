<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }
    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;

        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }
    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;

        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }
    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }
    public function apply_coupon_code(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:255',
        ]);
        $coupon_code = $request->coupon_code;

        if ($coupon_code) {
            $coupon = Coupon::where('code', $coupon_code)
    ->where('expiry_data', '>=', Carbon::today())
    ->where('cart_value', '<=', Cart::instance('cart')->subtotal())
    ->first();

            if (!$coupon) {
                return redirect()->back()->with('error', 'Invalid Coupon code');
            }
            //     'code' => $coupon->code,
            //     'type' => $coupon->type,
            //     'value' => $coupon->value,
            //     'cart_value' => $coupon->cart_value,
            // ]);
            Session::put('coupon', [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'cart_value' => $coupon->cart_value,
            ]);


            $this->calculateDiscount(); // Ensure this method is defined properly
            return redirect()->back()->with('success', 'Coupon has been applied');
        }
        return redirect()->back()->with('error', 'Invalid Coupon code');
    }
    public function calculateDiscount()
    {
        $discount = 0;
        if (Session::has('coupon')) {
            if (Session::get('coupon')['type'] == 'fixed') {
                $discount =  Session::get('coupon')['value'];
            } else {
                $discount =(Cart::instance('cart')->subtotal() * Session::get('coupon')['value'])/100;

            }
            $subtotlAfterDiscount =  Cart::instance('cart')->subtotal() - $discount;
            $taxAfterDiscount =  ($subtotlAfterDiscount * config('cart.tax'))/100;
            $totalAfterDiscount = $subtotlAfterDiscount + $taxAfterDiscount;

            Session::put('discount',[
                    'discount'=>number_format(floatval($discount),2,'.',''),
                    'subtotal' =>number_format(floatval($subtotlAfterDiscount),2,'.',''),
                    'tax' => number_format(floatval($taxAfterDiscount),2,'.',''),
                    'total' => number_format(floatval($totalAfterDiscount),2,'.','')
            ]);
        }
    }


                // public function remove_coupon_code(){

            public function checkout(){
                    if(!Auth::check()){
                        return redirect()->route('login');
                    }
                    $address = Address::where('user_id',Auth::user()->id)->where('isdefault',1)->first();
                    return view('checkout',compact('address'));
            }

                // public function place_an_order(Request $request){
                //     $user_id = Auth::user()->id;
                //     $address = Address::where('user_id',$user_id)->wheres('isdefault',true)->first();
                //     if(!$address){
                //     $request->validate([
                //         'name'=>'required |max:100',
                //         'phone'=>'required|numeric|digits:10',
                //         'zip'=>'required|numeric|digits:6',
                //         'state'=>'required',
                //         'city'=>'required',
                //         'landmark'=>'required',
                //         'locality'=>'required',
                //         'address'=>'required',
                //     ]);
                //     $address= new Address();
                //    $address->name = $request->name;
                //    $address->phone = $request->phone;
                //    $address->zip = $request->zip;
                //    $address->state = $request->state;
                //    $address->city = $request->city;
                //    $address->landmark = $request->landmark;
                //    $address->locality = $request->locality;
                //    $address->address = $request->address;
                //    $address->country = 'India';
                //    $address->user_id = $user_id;
                //    $address->isdefault = true;
                //    $address->save();
                //     }
                //     $this->setAmountforCheckout();


                //     $order = new Order();

                //                $order->user_id = $user_id;
                //                $order->subtotal= Session::get('checkout')['subtotal'];
                //                $order->discount= Session::get('checkout')['discount'];
                //                $order->tax=  Session::get('checkout')['tax'];
                //                $order->total= Session::get('checkout')['total'];
                //                $order->name = $address->name;
                //                $order->phone =$address->phone;
                //                $order->locality = $address->locality;
                //                $order->address = $address->address;
                //                $order->city = $address->city;
                //                $order->state = $address->state;
                //                $order->country = $address->country;
                //                $order->landmark =$address->landmark;
                //                $order->zip = $address->zip;
                //                $order->save();

                //                foreach (Cart::instance('cart')->content() as $item) {
                //                     $orderItem = new OrderItem();
                //                     $orderItem->product_id = $item->id;
                //                     $orderItem->order_id = $order->id;
                //                     $orderItem->price = $item->price;
                //                     $orderItem->quantity = $item->qty;
                //                     $orderItem->save();
                //                }
                //                if($request->mode == "card"){
                //                             //
                //                }

                //                elseif($request->mode == "paypal"){
                //                 //
                //             }
                //                 elseif($request->mode == "cod"){
                //                     $transaction =new Transaction();
                //                     $transaction->user_id = $user_id;
                //                     $transaction->order_id = $order->id;
                //                     $transaction->mode = $request->mode;
                //                     $transaction->status = "pending";
                //                     $transaction->save();
                //                 }


                //                Cart::instance('cart')->destroy();
                //                Session::forget('checkout');
                //                Session::forget('coupon');
                //                Session::forget('discounts');
                //                return redirect()->route('cart.order.confirmation',compact('order'));

                // }


                public function place_an_order(Request $request) {
                    $user_id = Auth::user()->id;

                    // Corrected the method name from 'wheres' to 'where'
                    $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

                    if (!$address) {
                        $request->validate([
                            'name' => 'required|max:100',
                            'phone' => 'required|numeric|digits:10',
                            'zip' => 'required|numeric|digits:6',
                            'state' => 'required',
                            'city' => 'required',
                            'landmark' => 'required',
                            'locality' => 'required',
                            'address' => 'required',
                        ]);

                        $address = new Address();
                        $address->name = $request->name;
                        $address->phone = $request->phone;
                        $address->zip = $request->zip;
                        $address->state = $request->state;
                        $address->city = $request->city;
                        $address->landmark = $request->landmark;
                        $address->locality = $request->locality;
                        $address->address = $request->address;
                        $address->country = 'India';
                        $address->user_id = $user_id;
                        $address->isdefault = true;
                        $address->save();
                    }

                    $this->setAmountforCheckout();

                    $order = new Order();
                    $order->user_id = $user_id;
                    $order->subtotal = Session::get('checkout')['subtotal'] ?? 0;
                    $order->discount = Session::get('checkout')['discount'] ?? 0;
                    $order->tax = Session::get('checkout')['tax'] ?? 0;
                    $order->total = Session::get('checkout')['total'] ?? 0; // Ensure total is also set
                     // Ensure total is also set

                    $order->name = $address->name;
                    $order->phone = $address->phone;
                    $order->locality = $address->locality;
                    $order->address = $address->address;
                    $order->city = $address->city;
                    $order->state = $address->state;
                    $order->country = $address->country;
                    $order->landmark = $address->landmark;
                    $order->zip = $address->zip;
                    $order->save();

                    foreach (Cart::instance('cart')->content() as $item) {
                        $orderItem = new OrderItem();
                        $orderItem->product_id = $item->id;
                        $orderItem->order_id = $order->id;
                        $orderItem->price = $item->price;
                        $orderItem->quantity = $item->qty;
                        $orderItem->save();
                    }

                    if ($request->mode == "card") {
                        // Handle card payment
                    } elseif ($request->mode == "paypal") {
                        // Handle PayPal payment
                    } elseif ($request->mode == "cod") {
                        $transaction = new Transaction();
                        $transaction->user_id = $user_id;
                        $transaction->order_id = $order->id;
                        $transaction->mode = $request->mode;
                        $transaction->status = "pending";
                        $transaction->save();
                    }

                    Cart::instance('cart')->destroy();
                    Session::forget('checkout');
                    Session::forget('coupon');
                    Session::forget('discounts');
                    Session::put('order_id',$order->id);
                    return redirect()->route('cart.order.confirmation');
                }


                protected function setAmountforCheckout() {
                    $cartItems = Cart::instance('cart')->content();

                    $subtotal = $cartItems->sum(function($item) {
                        return $item->price * $item->qty; // Example calculation
                    });

                    $discount = 0; // Set your discount logic here
                    $tax = $subtotal * 0.2; // Example tax calculation (20% VAT)
                    $total = $subtotal - $discount + $tax;

                    Session::put('checkout', [
                        'subtotal' => $subtotal,
                        'discount' => $discount,
                        'tax' => $tax,
                        'total' => $total,
                    ]);
                }


                    // public function order_confirmation(){
                    //     if(Session::has('order_id'))
                    //     {
                    //         $order = Order::find(Session::get('order_id'));
                    //         $items = Cart::instance('cart')->content();
                    //         return view('order-confirmation' ,compact('order','items'));
                    //     }

                    //     return redirect()->route('cart.index');

                    // }
                    public function order_confirmation() {
                        if (Session::has('order_id')) {
                            $order = Order::find(Session::get('order_id'));

                            // Check if the order exists
                            if ($order) {
                                $items = Cart::instance('cart')->content();
                                return view('order-confirmation', compact('order', 'items'));
                            }

                            return redirect()->route('cart.index'); // If the order does not exist
                        }

                        return redirect()->route('cart.index');
                    }


}
