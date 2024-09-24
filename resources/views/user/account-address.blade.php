@extends('layouts.app')
@section('content')


<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Addresses</h2>
      <div class="row">
        <div class="col-lg-3">
          <ul class="account-nav">
            <li><a href="{{route('user.index')}}" class="menu-link menu-link_us-s menu-">Dashboard</a></li>
            <li><a href="{{route('user.orders')}}" class="menu-link menu-link_us-s">Orders</a></li>
            <li><a href="{{route('user.address')}}" class="menu-link menu-link_us-s">Addresses</a></li>

            <li><a href="{{route('wishlist.index')}}" class="menu-link menu-link_us-s">Wishlist</a></li>
            <li><form action="{{route('logout')}}" method="POST" id="logout-form">
                @csrf
            <a href="{{route('logout')}}" class="menu-link menu-link_us-s"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        </form></li>
          </ul>
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__address">
            <div class="row">
         @if (Session::has('status'))
         <p class="alert alert-success">{{Session::get('status')}}</p>

         @endif
              <div class="col-6">
                <p class="notice">The following addresses will be used on the checkout page by default.</p>
              </div>
              <div class="col-6 text-right">
                <a href="{{route('user.add.address')}}" class="btn btn-sm btn-info">Add New</a>
              </div>
            </div>
@foreach ($address as $addresses )



            <div class="my-account__address-list row">
              <h5>Shipping Address</h5>

              <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__title">
                  <h5>{{$addresses->name}} <i class="fa fa-check-circle text-success"></i></h5>
                  <a href="{{route('user.address.edit',['id'=>$addresses->id])}}">Edit</a>
                  <a href="{{route('user.address.delete',['id'=>$addresses->id])}}" >Delete</a>
                </div>
                <div class="my-account__address-item__detail">
                  <p></p>
                  <p>{{$addresses->address}}</p>
                  <p>{{$addresses->landmark}} </p>
                  <p>{{$addresses->city}},{{$addresses->state}}</p>
                  <p>{{$addresses->zip}}</p>
                  <br>
                  <p>Mobile : {{$addresses->phone}}</p>
                </div>
              </div>
              <hr>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection
