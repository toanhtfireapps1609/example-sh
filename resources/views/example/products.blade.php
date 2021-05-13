@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="title m-b-md">
            <img src="{{asset('/logo-sh.svg')}}" class="w-25">
            <span> List Products </span>
        </div>
        <div class="row">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">product id</th>
                    <th scope="col">title</th>
                    <th scope="col">price</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                <tr>
                    <th scope="row">{{$product['id']}}</th>
                    <td>{{$product['product_id']}}</td>
                    <td>{{$product['title']}}</td>
                    <td>{{$product['price']}}</td>
                    <td>
                        <a class="btn btn-primary btn-sm"
                           href="{{route('example-app.message.form', ['product' => $product['id']])}}">
                            Send Message
                        </a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
