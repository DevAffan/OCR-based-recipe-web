@extends('layouts.header')

@section('content')
@if(session('message'))
<div class="alert alert-danger">{{session('message')}}</div>
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
            <input class="form-control" id="search" name="search" type="text" placeholder="Search.."><br>
    <div class="table-responsive">
    <table class="table table-striped table-bordered" id="myTable" style="text-align: center">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Image</th>
            <th>Text</th>
            <th onclick="sortTable(0)">Length</th>
            <th onclick="sortTable(1)">Length/entropy</th>
            <th onclick="sortTable(2)">Entropy/Length</th>
            <th onclick="sortTable(3)">Entropy<span style="margin-left: 50px"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
            {{-- <th>Created At</th> --}}
            {{-- <th>Updated At</th> --}}
            <th >Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        {{-- <tfoot>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Image</th>
                <th>Text</th>
                <th>Entropy</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </tfoot> --}}
        <tbody id="search_table">

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
                {{-- <td>{{$recipe->created_at->diffForHumans()}}</td> --}}
                {{-- <td>{{$recipe->updated_at->diffForHumans()}}</td> --}}
                <td>
                    <a href="{{route('recipe.edit' , $recipe->id)}}"><Button class="btn btn-primary">Edit</Button></a>
                </td>
                <td>
                        <button type="button" class="btn btn-danger"
                                onclick="loadDeleteModal({{ $recipe->id }}, `{{ $recipe->name }}`)">Delete
                        </button>

                    {{-- <form method="post" action="{{route('recipe.destroy', $recipe->id)}}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form> --}}
                </td>
            </tr>
         @endforeach

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
        <div class="d-flex">
            <div class="mx-auto">
            {{$recipes->links('pagination::bootstrap-4')}}
            </div>
        </div>



        <div class="modal" id="deleteCategory" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="deleteCategory" aria-hidden="true">
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
                        @if (empty($recipes))
                        <input type="hidden" id="category" name="category_id" value="{{$recipe->id}}">
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-white" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="modal-confirm_delete">Delete</button>
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
        $('#modal-confirm_delete').attr('onclick', `confirmDelete(${id})`);
        $('#deleteCategory').modal('show');
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

            $('#deleteCategory').modal('hide');
            },
            error: function (error) {
                // Error logic goes here..!
            }
        });
    }

        function sortTable(n) {
          var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
          table = document.getElementById("myTable");
          switching = true;
          //Set the sorting direction to ascending:
          dir = "asc";
          /*Make a loop that will continue until
          no switching has been done:*/
          while (switching) {
            //start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /*Loop through all table rows (except the
            first, which contains table headers):*/
            for (i = 1; i < (rows.length - 1); i++) {
              //start by saying there should be no switching:
              shouldSwitch = false;
              /*Get the two elements you want to compare,
              one from current row and one from the next:*/
              x = rows[i].getElementsByTagName("TD")[n];
              y = rows[i + 1].getElementsByTagName("TD")[n];
              /*check if the two rows should switch place,
              based on the direction, asc or desc:*/
              if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                  //if so, mark as a switch and break the loop:
                  shouldSwitch= true;
                  break;
                }
              } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                  //if so, mark as a switch and break the loop:
                  shouldSwitch = true;
                  break;
                }
              }
            }
            if (shouldSwitch) {
              /*If a switch has been marked, make the switch
              and mark that a switch has been done:*/
              rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
              switching = true;
              //Each time a switch is done, increase this count by 1:
              switchcount ++;
            } else {
              /*If no switching has been done AND the direction is "asc",
              set the direction to "desc" and run the while loop again.*/
              if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
              }
            }
          }
        }
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
