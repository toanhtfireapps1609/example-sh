@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="title m-b-md">
            <img src="{{asset('/logo-sh.svg')}}" class="w-25">
            <span> {{$product['title'] ?? '-'}} </span>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <form action="{{route('example-app.message')}}" method="POST" role="form">
            @csrf
            <div class="row">
                <span class="font-weight-bold"> Set message to product</span>
            </div>

            <div class="row">
                <div class="input-group mb-3 w-25">
                    <input name="product_id" value="{{$product['product_id']}}" hidden>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                     <textarea type="text" class="form-control" rows="5" name="content" placeholder="Type message here"
                               aria-label="Type message here">
                    </textarea>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-primary w-25" name="submit">Post</button>
            </div>
        </form>
    </div>
@endsection
