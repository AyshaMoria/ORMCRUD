@extends('master');

 <!-- Content Header (Page header) -->
 
  <!-- /.content-header -->
@section('content')

<link rel="stylesheet" href="css/jquery.dataTables.min.css">
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Categories</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Categories</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  @if(session('success'))
  <div class="alert alert-success">
      {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger">
      {{ session('error') }}
  </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">Categories</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form action="{{ isset($id) ? '/edit/' . $id : '/insert' }}" class="form-horizontal" method="POST">
      @csrf
     
      <div class="card-body">
      
        <div class="form-group row">
          <label  class="col-sm-2 col-form-label">Category Name : </label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="catname" name="catname" value="<?php echo isset($id) ? $data['cat_name'] : ''; ?>" placeholder="Category Name">
          </div>
        </div>
       
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-info">{{ isset($id) ? 'Update' : 'Insert' }}</button>
        <a href="/categories" class="btn btn-outline-success">
          {{ isset($id) ? 'Cancel' : 'Reset' }}
      </a>
            </div>
      <!-- /.card-footer -->
    </form>
  </div>

  @if ($status == 200)
  <table id="myTable" class="table table-striped dataTable responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Category Name</th>
                   
                    <th>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $item)
                    <tr>
                        <td>{{ $item->cat_name }}</td>
                       
                        <td><a href="/edit/{{ $item->id }}"  class="btn btn-outline-success"><i class="fas fa-pencil-alt"></i></a>
                            &nbsp;
                            <form method="POST" action="/delete/{{ $item->id }}" style="display: inline;">
                              @csrf
                              @method('DELETE')
                          
                              <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')"> <i class="fas fa-trash-alt"></i> </button>
                          </form>
                          
                           
                          
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th>Category Name</th>
                  
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    @else
        <p>No data available.</p>
    @endif

    
<script src="plugins/jquery/jquery.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
 $(document).ready(function () {
    var table = $('#myTable').DataTable();
     "order": [[3, 'desc']],
});
</script>

@endsection
