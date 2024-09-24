<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }
    public function orders()
    {
        // $orders = Order::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->paginate(10);
        $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
        return view('user.orders', compact('orders'));
    }


    //     public function order_details($order_id){
    //         $order = Order::find($order_id);

    //     // $order = Order::where('user_id',Auth::user()->id)->where('id',$order_id)->first();
    //     if($order){
    //         // $orderItems = OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
    //         $orderItems = OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
    //         $transaction = Transaction::where('order_id',$order_id)->first();
    //         // $transaction = Transaction::where('order_id',$order_id)->first();
    //     return view('user.order-details',compact('order','orderItems','transaction'));

    //     }
    //     else{
    // return redirect()->route('login');
    //     }

    //     }
    public function order_details($order_id)
    {
        $order = Order::find($order_id);

        if ($order) {
            $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', $order_id)->first();

            // Optionally flash a message if needed
            session()->flash('message', 'Order details retrieved successfully.');

            return view('user.order-details', compact('order', 'orderItems', 'transaction'));
        } else {
            // Handle case where order is not found, maybe flash an error
            session()->flash('error', 'Order not found.');
            return redirect()->back();
        }
    }


    public function order_cancel(Request $request){
        $order  = Order::find($request->order_id);
        $order->status = "canceled";
        $order->canceled_date = Carbon::now();
        $order->save();
        return back()->with('status','Order has been Canceled successfully');
    }
    public function address(){
        $address = Address::all();
        return view('user.account-address',compact('address'));
    }
    public function store_address(Request $request){
        // $user_id = Auth::user()->id;

        // // Corrected the method name from 'wheres' to 'where'
        // $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        // if (!$address) {
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
             $address->isdefault = true;
            $address->save();

            return redirect()->route('user.address')->with('status','Address Add Successfully!!');


    }
    public function addressEdit($id){
     $address = Address::find($id);

        return view('user.account-edit-address',compact('address'));
    }
    public function updateaddress(Request $request ,$id){

        $address =  Address::find($id);
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->zip = $request->zip;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->landmark = $request->landmark;
        $address->locality = $request->locality;
        $address->address = $request->address;
        $address->country = 'India';
         $address->isdefault = true;
        $address->save();
        return redirect()->route('user.address')->with('status','Address Updated Successfully!!');
    }
    public function addressdelete($id){
        $address = Address::find($id);
         $address->delete();
         return redirect()->route('user.address')->with('status','Address Delete Successfully!!');
    }

    // public function addressEdit($id){
    //     $address = Address::find($id);
    //     return view('user.edit.address',compact('address'));
    // }
    public function details(){

        return view('user.account-details');
    }
    public function addAdrdess(){
        return view('user.account-add-address');
    }
}
