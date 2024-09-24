<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $slides = Slide::where('status',1)->get()->take(3);
        $categories = Category::orderBy('name')->get();
        $sproduct = Product::whereNotNull('sale_price')->where('sale_price','<>','')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured',1)->get()->take(8);
        return view('index',compact('slides','categories','sproduct','fproducts'));
    }
    public function contact(){
        return view('contact');
    }
    public function contact_store(Request $request){
        $request->validate([
      'name'=>'required |max:100',
      'email'=>'required |email',
      'phone'=>'required |numeric |digits:10',
      'commet'=>'required'
        ]);
        $contact = new Contact();

            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->commet = $request->commet;
            $contact->save();
            return redirect()->back()->with('success','Your Message has been sent successfully');
    }

// public function search(Request $request){
//     $query = $request->input('query');
//     $results = Product::where('name','Like',"%{$query}%")->get()->take(8);
//     return response()->json($results);
// }
public function search(Request $request) {
    $query = $request->input('query');
    $results = Product::where('name', 'LIKE', "%{$query}%")->take(8)->get(['id', 'name', 'image', 'slug']);
    return response()->json($results);
}

}