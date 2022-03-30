@extends('layouts.header')

@section('content')
<div class="container">
    <div class="row" style="margin-top: 10px">
        <a href="{{route('recipe.index')}}"><button class="btn btn-warning">Back</button></a>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Image</td>
                        <td>Recipe</td>
                        <td>View</td>
                        <td>Edit</td>
                        <td>Delete</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resipes as $recipe)
                        <tr>
                            <td>{{$recipe->id}}</td>
                            <td>{{$recipe->name}}</td>
                            <td>{{$recipe->image}}</td>
                            <td>{{str::limit($recipe->image_text, 50)}}</td>
                            <td>
                                <button class="btn btn-success">View</button>
                            </td>
                            <td>
                                <button class="btn btn-primary">Edit</button>
                            </td>
                            <td>
                                <button class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
