{{-- view single product details --}}
{{-- Controller method
     //view single perticular record
    public function view_product_data($id)
    {
        $data = Product::find($id);
        $categories = Categorie::with('cat_rel')->find($data->cat_id);
        $basePath = Storage::url('uploadTemp');
        return view('viewproducts', [
            'status' => 200,
            'data' => $data,
            'category' => $categories,
            'id' => $id,
            'basePath' => $basePath,
        ]);
    } --}}
@extends('master')
@section('content')
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #007bff;
    }

    ul {
        list-style: none;
        padding: 0;
    }

    li {
        margin-bottom: 10px;
    }

    strong {
        font-weight: bold;
    }

    p {
        font-style: italic;
    }
</style>
<div class="container">
    <h1>Product Details</h1>

    @if ($status == 200 && $data )
        <ul>
           
            <li><strong>Product Name:</strong> {{ $data->product_name }}</li>
            <li><strong>Category Name:</strong> {{ $category->cat_name }}</li>
            <li><strong>Product Image:</strong>  <img src="{{ $basePath.'/'.$data->product_image }}" height="280px" width="280px" alt="Product Image">
               </li>
            <li><strong>Status:</strong> {{ $data->is_active ? 'Active' : 'Inactive' }}</li>
            <li><a href="/products" class="btn btn-outline-danger">Cancel</a></li>
            
            <!-- Add more details as needed -->
        </ul>
    @else
        <p>No data available.</p>
    @endif
</div>
@endsection