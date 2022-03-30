@extends('layouts.header')
@section('content')

<div class="container">
    <div class="row" style="margin-top: 10px">
    <a href="{{route('recipe.index')}}"><button class="btn btn-warning">Back</button></a>
</div>
    <div class="row">
        <h1>Create Recipe</h1>
        <form action="{{Route('recipe.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="col-md-6">
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" id="name" placeholder="Recipe Name">
            @error('name')
            <span class="alert" style="color: red"><strong>{{$message}}</strong></span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="name">Image</label>
            <input class="form-control" type="file" name="image" id="image">
            @error('image')
            <span class="alert" style="color: red"><strong>{{$message}}</strong></span>
            @enderror
        </div>
        <div class="col-md-6" style="margin:10px 0px">
            <input class="btn btn-success" type="submit" value="Create">
        </div>
        </form>


</div>

</div>











@endsection
