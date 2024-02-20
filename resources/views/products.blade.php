@extends('master');


<!-- header starts -->
@section('content')
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
<!-- header ends -->

    {{-- //showing error or success message starts--}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
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
     {{-- //showing error or success message ends --}}

    <!-- /.card-header -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Products</h3>
        </div>
        <!-- form start -->
        <form action="{{ isset($id) ? '/edit_product/' . $id : '/insert_product' }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Product Name : </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="proname" name="proname" value="<?php echo isset($id) ? $data['product_name'] : ''; ?>"
                            placeholder="Product Name">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"> Category </label>
                    <div class="col-sm-10">
                        <select id="catname" name="catname" class="form-control">
                            @if (isset($id))
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $data->cat_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->cat_name }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->cat_name }}</option>
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Product Image:</label>
                    <div class="col-sm-10">
                        @if(isset($id) && isset($data['product_image']))
                            <img src="{{ $basePath .'/'. $data['product_image'] }}"  height="100px" width="100px" alt="Current Product Image">
                        @endif
                        <input type="file" id="image" name="image" accept="image/*">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status </label>
                    <div class="col-sm-10">
                        <label>
                            <input type="radio" name="status" value="active" <?php echo isset($id) && $data['is_active'] == '1' ? 'checked' : ''; ?>>
                            Active
                        </label>
                        <label>
                            <input type="radio" name="status" value="inactive" <?php echo isset($id) && $data['is_active'] == '0' ? 'checked' : ''; ?>>
                            Inactive
                        </label>
                    </div>
                </div>

            </div>
            {{-- insert or update button --}}
            <div class="card-footer">
                <button type="submit" class="btn btn-info">{{ isset($id) ? 'Update' : 'Insert' }}</button>
                <a href="/products" class="btn btn-outline-success">
                    {{ isset($id) ? 'Cancel' : 'Reset' }}
                </a>
            </div>

        </form>
    </div>
    <!-- form ends -->
    {{-- showing all records in datatable starts --}}
    <form method="POST" action="/delete-multiple">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-outline-danger"
            onclick="return confirm('Are you sure you want to delete the selected records?')">Delete Selected</button>

        @if ($status == 200)
            <table id="myTable" class="table table-striped dataTable responsive" style="width:100%">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Product Image</th>
                        <th>Status</th>
                        <th>&nbsp;Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $item)
                        <tr>
                            <td><input type="checkbox" name="selectedRecords[]" value="{{ $item->id }}"></td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->member_rel->cat_name }}</td>
                            <td>   
                                <img src="{{ $basePath.'/'.$item->product_image }}" alt="{{ $item->product_image }}" height="100" width="100">
                            </td>
                        
                            <td>
                                @if ($item->is_active == 1)
                                    Active
                                @else
                                    Inactive
                                @endif
                            </td>
                            <td>
                                <a href="/view_product/{{ $item->id }}" class="btn btn-outline-success"><i
                                        class="fas fa-eye"></i></a>
                                &nbsp;
                                <a href="/edit_product/{{ $item->id }}" class="btn btn-outline-success"><i
                                        class="fas fa-pencil-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>
        @else
            <p>No data available.</p>
        @endif
    </form>
    {{-- showing all records in datatable ends --}}

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function() {
          var table = $('#myTable').DataTable({
              "order": [
                  [6, 'desc']
              ]
          });
      });
  </script>
  




@endsection
