@extends('layouts.app')
@section('content')
<style>
    .text-danger{
        color:#e72010;
    }
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="contact-us container">
      <div class="mw-930">
        <h2 class="page-title">CONTACT US</h2>
      </div>
    </section>

    <hr class="mt-2 text-secondary " />
    <div class="mb-4 pb-4"></div>

    <section class="contact-us container">
      <div class="mw-930">
        <div class="contact-us__form">
            @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show">{{Session::get('success')}} </div>
            @endif
          <form name="contact-us-form" class="needs-validation" novalidate="" action="{{route('home.contact.store')}}" method="POST">
            @csrf
            <h3 class="mb-5">Get In Touch</h3>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Name *" >
              <label for="contact_us_name">Name *</label>
              @error('name') <span class=" text-danger">{{$message}}</span>@enderror
              <span class="text-danger"></span>
            </div>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="phone" value="{{old('phone')}}" placeholder="Phone *" >
              <label for="contact_us_name">Phone *</label>
              @error('phone') <span class=" text-danger">{{$message}}</span>@enderror

              <span class="text-danger"></span>
            </div>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="email" value="{{old('email')}}" placeholder="Email address *" >
              <label for="contact_us_name">Email address *</label>
              @error('email') <span class=" text-danger">{{$message}}</span>@enderror

              <span class="text-danger"></span>
            </div>
            <div class="my-4">
              <textarea class="form-control form-control_gray" name="commet" placeholder="Your Message" cols="30"
                rows="8" >{{old('commet')}}</textarea>
              @error('commet') <span class=" text-danger">{{$message}}</span>@enderror

              <span class="text-danger"></span>
            </div>
            <div class="my-4">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </main>
@endsection
