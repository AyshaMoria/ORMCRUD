<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;




class ProductController extends Controller
{

    // insert code starts
    function insert(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'proname' => 'required',
            'status' => 'required|in:active,inactive',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'proname.required' => 'Product Name is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status value.',
            'image.required' => 'Image is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Allowed image formats are jpeg, png, jpg, gif, and svg.',
            'image.max' => 'The maximum file size allowed is 2048 kilobytes.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $file = $req->file('image');
        $pro = new Product;
        $pro->product_name = $req->input('proname');
        $pro->cat_id = $req->input('catname');
        //enter image as simplifies name as uploads/image_1706781028.jpg
        $originalFilename = $req->file('image')->getClientOriginalName();// Get the original filename
        $filename = pathinfo($originalFilename, PATHINFO_FILENAME);// Extract only the filename without the folder path
        $simplifiedImageName = 'image_' . time() . '.' . $req->file('image')->getClientOriginalExtension();
        $filePath = $file->storeAs('public/uploadTemp', $simplifiedImageName);
        $pro->product_image = $simplifiedImageName;
        $pro->is_active = ($req->input('status') == 'active') ? '1' : '0';
        $result = $pro->save();
        if ($result) {
            return redirect('/products')->with('success', 'Product saved successfully');
        } else {
            return redirect('/products')->with('error', 'Failed to save Product');
        }
    }


    //showing product data
    function get_product_data()
    {
        $test_data = Product::orderBy('updated_at', 'DESC')->get();
        $categories = Categorie::all();
        $basePath = Storage::url('uploadTemp');
        return view('products', [
            'status' => 200,
            'records' => $test_data,
            'categories' => $categories,
            'basePath' => $basePath,
        ]);
    }

    //getting old values while click on edit
    public function editForm($id)
    {
        $data = Product::find($id);
        $test_data = Product::all();
        $categories = Categorie::with('cat_rel')->get();
        $basePath = Storage::url('uploadTemp');
        return view('products', [
            'status' => 200,
            'data' => $data,
            'records' => $test_data,
            'categories' => $categories,
            'id' => $id,
            'basePath' => $basePath,
        ]);
    }

    //updating new data 
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'proname' => 'required',
            'catname' => 'required|exists:categories,id',
            'status' => 'in:active,inactive',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'proname.required' => 'Product Name is required.',
            'catname.required' => 'Category is required.',
            'catname.exists' => 'Invalid category selected.',
            'status.in' => 'Invalid status selected.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Allowed image formats are jpeg, png, jpg, gif, and svg.',
            'image.max' => 'The maximum file size allowed is 2048 kilobytes.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data = Product::find($id);
        
        $data->product_name = $request->input('proname');
        $data->cat_id = $request->input('catname');
        $data->is_active = ($request->input('status') == 'active') ? '1' : '0';
        
        // Check if a new image has been uploaded
        if ($request->hasFile('image')) {
            $newImage = $request->file('image');
        
            // Validate and store the new image
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        
            // Delete the old image if it exists
            if ($data->product_image) {
                Storage::delete('public/uploadTemp/' . $data->product_image);
            }
        
            // Generate filename with timestamp
            $imageName = time() . '.' . $newImage->getClientOriginalExtension();
            
            // Store the new image without folder name
            $newImage->storeAs('public/uploadTemp', $imageName);
            $data->product_image = $imageName;
        }
        
        $data->save();
        
        return redirect('/products')->with('success', 'Product updated successfully');
        
    }



    //deleting multiple records
    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'selectedRecords' => 'required|array',
            'selectedRecords.*' => 'exists:products,id',
        ]);
    
        $selectedRecords = $request->input('selectedRecords');
    
        // Fetch the product records to get their associated image filenames
        $products = Product::whereIn('id', $selectedRecords)->get();
    
        foreach ($products as $product) {
            // Delete the record
            $product->delete();
            // Extract the image filename from the database and delete the associated image
            $imageFilename = $product->product_image; // Assuming 'product_image' is the column with the filename
            if ($imageFilename && Storage::exists('public/uploadTemp/' . $imageFilename)) {
                Storage::delete('public/uploadTemp/' . $imageFilename);
            }
        }
        return redirect('/products')->with('success', 'Selected records deleted successfully');
    }



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
    }
}
