@extends('layouts.app')
@section('content')

<form action="{{route('account.address.store')}}" method="POST">
    @csrf
    <div class="container">
        <h2>Address</h2>
    <div class="row mt-5">
        <div class="col-md-6">

            <div class="form-floating my-3">
                <input type="text" class="form-control" name="name"
                    value="{{ old('name') }}">
                <label for="name">Full Name *</label>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="phone"
                    value="{{ old('phone') }}">
                <label for="phone">Phone Number *</label>
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="zip"
                    value="{{ old('zip') }}">
                <label for="zip">Pincode *</label>
                @error('zip')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating mt-3 mb-3">
                <input type="text" class="form-control" name="state"
                    value="{{ old('state') }}">
                <label for="state">State *</label>
                @error('state')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="city"
                    value="{{ old('city') }}">
                <label for="city">Town / City *</label>
                @error('city')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="address"
                    value="{{ old('address') }}">
                <label for="address">House no, Building Name *</label>
                @error('address')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="locality"
                    value="{{ old('locality') }}">
                <label for="locality">Road Name, Area, Colony *</label>
                @error('locality')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-floating my-3">
                <input type="text" class="form-control" name="landmark"
                    value="{{ old('landmark') }}">
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