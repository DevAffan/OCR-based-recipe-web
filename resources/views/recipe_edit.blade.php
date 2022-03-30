@extends('layouts.header')

@section('content')
<div class="container">
    <div class="row" style="margin: 10px">
        <a href="{{route('recipe.index')}}"><button class="btn btn-warning">Back</button></a>
    </div>
    <h1>Edit Recipe</h1>
    <div class="row">
    <div class="col-md-7">
        <form action="{{Route('recipe.update' , $recipe->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="col-md-10">
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" id="name" value="{{$recipe->name}}">
            @error('name')
            <span class="alert" style="color: red"><strong>{{$message}}</strong></span>
            @enderror
        </div>

        <div class="col-md-10">
            <label for="name">Image Text</label>
            <textarea class="form-control" name="image_text" id="image_text" cols="50" rows="10">{{$recipe->image_text}}</textarea>
        </div>
        <div class="col-md-10 mt-2">
        <input class="form-control" type="file" name="image" id="image">
        </div>
        <div class="col-md-10" style="margin:10px 0px">
            <input class="btn btn-success" type="submit" value="Update">
        </div>
        </form>

</div>
<div class="col-md-5" style="margin-bottom: 50px">
    <div class="mr-5">
        <label for="name">Image</label>
        <div class=""><img height="430px" width="400px" src="{{$recipe->image}}" alt=""></div>
    </div>
</div>
</div>
</div>
@endsection
