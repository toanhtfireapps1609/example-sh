@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="title m-b-md">
            <img src="{{asset('/logo-sh.svg')}}" class="w-25">
            <span>Install {{$success ? 'successfully' : 'failed'}} </span>
        </div>
        <div class="row">
            <span>Domain shop: {{ $shop}}</span>
            <span><a href="{{route('example-app.products.form')}}">View products </a></span>
        </div>
    </div>
@endsection
