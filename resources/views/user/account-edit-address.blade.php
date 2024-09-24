@extends('layouts.app')
@section('content')

<form action="{{route('user.address.update',['id'=>$address->id])}}" method="POST">
    @csrf

    <input type="hidden" name="id" value="{{$address->id}}" id="">
    <div class="container">
        <h2> Edit Address</h2>
    <div class="row mt-5">
        <div class="col-md-6">

            <div class="form-floating my-3">
                <input type="text" class="form-control" name="name"
                    value="{{$address->name}}">
                <label for="name">Full Name *</label>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="phone"
                    value="{{$address->phone}}">
                <label for="phone">Phone Number *</label>
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="zip"
                    value="{{$address->zip}}">
                <label for="zip">Pincode *</label>
                @error('zip')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating mt-3 mb-3">
                <input type="text" class="form-control" name="state"
                    value="{{$address->state}}">
                <label for="state">State *</label>
                @error('state')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="city"
                    value="{{$address->city}}">
                <label for="city">Town / City *</label>
                @error('city')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="address"
                    value="{{$address->address}}">
                <label for="address">House no, Building Name *</label>
                @error('address')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="locality"
                    value="{{$address->locality}}">
                <label for="locality">Road Name, Area, Colony *</label>
                @error('locality')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="landmark"
                    value="{{$address->landmark}}">
                <label for="landmark">Landmark *</label>
                @error('landmark')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-floating my-3">
        <button class="btn btn-success">Submit</button>
            </div></div>
    </div>
</div>
</form>

@endsection
