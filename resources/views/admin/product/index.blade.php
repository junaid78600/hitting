<div class="page-content">
    <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18">{{$title}}</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{$title}}</a></li>
                                            <li class="breadcrumb-item active">Management</li>
                                        </ol>
                                    </div>
                                        
                                    </div>
                                </div>
                            </div>     
                            <!-- end page title -->
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="text-sm-right mt-2 mr-4">
                                            <button  type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 mr-2" data-toggle="modal" data-target=".addModal">Add</button>
                                        </div>
                                        <div class="card-body">
        
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Title</th>
                                                <th>Price</th>
                                                <th>Desciption</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th width="280px">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data as $item)
                                                    <tr>
                                                        <td>{{$item->category->name}}</td>
                                                        <td>{{$item->title}}</td>
                                                        <td>{{$item->price}}</td>
                                                        <td>{{$item->description}}</td>
                                                        <td>@if($item->image!='')<img src="{{$item->image}}" width="100" height="100">@endif</td>
                                                        <td><span class="btn btn-success">@if($item->status=='1') Active @else Disable @endif</span></td>
                                                        <td>
                                                            <form action="{{ route('admin.product.destroy',$item->id) }}" method="POST">
                                                            <a class="btn btn-warning" onclick="update({{$item->id}})" >Edit</a>

                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </td >
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>



<div class="modal fade addModal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">Add {{$title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Category</label>
                        <div class="col-md-10">
                            <select class="form-control" name="categoryId">
                                <option>Choose Category</option>
                                @foreach($category as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Title</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="title">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">price</label>
                        <div class="col-md-10">
                            <input class="form-control" type="number" name="price">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Description</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="description" ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">image</label>
                        <div class="col-md-10">
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>
                    <div style="float:right">
                        <button type="submit"  class="btn btn-primary waves-effect waves-light" >Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade updateModal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">Update {{$title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  method="post" action="{{ route('admin.product.update',0) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="id">

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Title</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="title" id="name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Price</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="price" id="price">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">Description</label>
                        <div class="col-md-10">
                        <textarea class="form-control" name="description" id="description" ></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">image</label>
                        <div class="col-md-10">
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>
                    <img id="image" with="200" height="200">
                    <div style="float:right">
                        <button type="submit"  class="btn btn-primary waves-effect waves-light" >Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
        function update(id){
            $.get("{{url('admin/product')}}" + '/' + id + '/edit', function (data) {
                $('#id').val(data.id);
                $('#name').val(data.title);
                $('#price').val(data.price);
                $('#description').val(data.description);
                $('#image').attr('src', data.image);
                $('.updateModal').modal('show');
            })
}
</script>