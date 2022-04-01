@extends('layouts.header')

@section('content')
@if(session('message'))
<div class="alert alert-success">{{session('message')}}</div>
@endif
<div class="container">
    <div class="row">
    <div class="card shadow mb-4">
        <div class="m-auto">
            <h1 class="m-0 font-weight-bold text-primary">"Recipes"</h1>
        </div>
        <div class="ml-3">
            <a href="{{route('recipe.create')}}"><button class="btn btn-success">Create</button></a>
        </div>
        <div class="card-body">
    <div class="table-responsive">
    <table class="table table-striped table-bordered data-table" id="myTable" style="text-align: center">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Image</th>
            <th>Text</th>
            <th>Length</th>
            <th>Length/entropy</th>
            <th>Entropy/Length</th>
            <th>Entropy</th>
            <th >Action</th>
        </tr>
        </thead>
        <tbody id="search_table">
{{--
        @foreach($recipes as $recipe)

            <tr>
                <td>{{$recipe->id}}</td>
                <td>{{$recipe->name}}</td>
                <td><img width="100px" src="{{$recipe->image}}" alt=""></td>
                <td ><a style="text-decoration: none" href="{{route('recipe.show' , $recipe->id)}}">{{ Str::limit($recipe->image_text, 20) }}</a></td>
                <td>{{$recipe->length}}</td>
                <td>{{$recipe->len_entro}}</td>
                <td>{{$recipe->entro_len}}</td>
                <td>{{$recipe->entropy}}</td>
                <td>
                    <a href="{{route('recipe.edit' , $recipe->id)}}"><Button class="btn btn-primary">Edit</Button></a>
                </td>
                <td>
                        <button type="button" class="btn btn-danger"
                                onclick="loadDeleteModal({{ $recipe->id }}, `{{ $recipe->name }}`)">Delete
                        </button>
                </td>
            </tr>
         @endforeach --}}

        </tbody>
    </table>
    </div>
        </div>
    </div>
{{--
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Recipe</th>
                <th>Legth</th>
                <th>entropy</th>
                <th>Updated At</th>
                <th>Deleted At</th>
            </tr>
            </thead>
            <tbody id="tab_body" class="tab_body">
            </tbody>
            </table> --}}

    </div>

        <div class="modal" id="deleteRecipe" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="deleteRecipe" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">This action is not reversible.</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <span id="modal-category_name"></span>?
                        {{-- @if (empty($recipes)) --}}
                        {{-- <input type="hidden" id="category" name="category_id" value="{{$recipe->id}}"> --}}
                        {{-- @endif --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-white" data-bs-dismiss="modal">Close</button>
                        <form id="myform" method="post">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <input class="btn btn-danger" type="submit" value="Delete">
                        </form>
                        {{-- <button type="button" class="btn btn-danger" id="modal-confirm_delete">Delete</button> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
<script>
    $(document).ready(function(){
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#search_table tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });

    function loadDeleteModal(id, name) {
        $('#modal-category_name').html(name);
        $('#modal-confirm_delete').on('click', confirmDelete(id));
        $('#deleteRecipe').modal('show');
        // $('#myform').attr('action' , url);

        // document.getElementById("myform").action = "";
    }

    function confirmDelete(id) {
        $.ajax({
            url: '{{ url('recipe') }}/' + id,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                '_method': 'delete',
            },
            success: function (data) {
                // Success logic goes here..!
                window.location.reload()
            $('#deleteRecipe').modal('hide');
            },
            error: function (error) {
                // Error logic goes here..!
            }
        });
    }

  $(function () {

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('recipe.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'image', name: 'image'},
            {data: 'image_text', name: 'image_text'},
            {data: 'length', name: 'length' , orderable: true},
            {data: 'len_entro', name: 'len_entro'},
            {data: 'entro_len', name: 'entro_len'},
            {data: 'entropy', name: 'entropy' , orderable: true},
            {data: 'action', name: 'action'},


        ]
    });

  });







</script>


<script type="text/javascript">
    /*Search*/
        $('#search').on('keyup',function(){
        $value=$(this).val();
        $.ajax({
        type : 'get',
        url : '{{URL::to('search')}}',
        data:{'search':$value},
        success:function(data){
        $('tbody').html(data);
        }
        });
        })
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });








        </script>





@endsection
