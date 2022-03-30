@extends('layouts.header')

@section('content')
<div class="container">
    <h1 style="color: purple">{{$recipe->name}} Recipe</h1>
    <div class="row">
    <div class="col-md-7">
        <div class="col-md-10">
            <h3 style="color: green">Description</h3>
            {{$recipe->image_text}}
        </div>
</div>
<div class="col-md-5">
    <div class="mr-5">
        <h2 for="name">Image</h2>
        <div class=""><img height="450px" width="400px" src="{{$recipe->image}}" alt=""></div>
    </div>
</div>
</div>
</div>
@endsection
