<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    function insert(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'catname'=>'required',
            
        ], [
            'catname.required'=>'Category Name is required.',
           
        ]);
        
if ($validator->fails()) {
    return redirect()->back()->withErrors($validator)->withInput();
}
        $cat=new Categorie;
        $cat->cat_name=$req['catname'];
           
           $result = $cat->save();
     
           if ($result) {
            // Flash success message
            return redirect('/categories')->with('success', 'Category saved successfully');
        } else {
            // Flash error message if saving failed
            return redirect('/categories')->with('error', 'Failed to save category Name');
        }
       

    }

     function getData()
    {
        $test_data = Categorie::orderBy('updated_at', 'DESC')->get();
       //echo "<pre>";print_r($test_data);die;
    
        return view('categories', [
            'status' => 200,
            'records' => $test_data,
        ]);
    }


    public function editForm($id)
{
    $data = Categorie::find($id);
    //$test_data = Categorie::orderBy('updated_at', 'DESC')->get();

    $test_data = Categorie::with('cat_rel')->get();

    return view('categories', [
        'status' => 200,
        'data' => $data,
        'records' => $test_data,
        'id' => $id,
    ]);
}

public function update(Request $request, $id)
{
    $data = Categorie::find($id);

    // Validate the request
    $validator = Validator::make($request->all(), [
        'catname' => 'required',
    ], [
        'catname.required' => 'Category Name is required.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Update the category name
    $data->cat_name = $request->input('catname');
    $data->save();

    return redirect('/categories')->with('success', 'Category updated successfully');
}


    public function delete($id)
{
    $categorie = Categorie::find($id);

    if ($categorie) {
        $categorie->delete();
        return redirect('/categories')->with('success', 'Category Deleted successfully');
    } else {
        return redirect('/categories')->with('error', 'Record not found');
    }
}

}
