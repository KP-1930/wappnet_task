<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class CategoryController extends BaseController
{
    public function index()
    {
       $category = Category::all();       
        return $category;
        
    }

    public function store(Request $request)
    {        
        $input = $request->all();        
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }   
        
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
    
       $category = Category::create($input);
                 
       return response(['Message'=>'Category Created'], 201);


    } 

    public function getCategoryById($id){
        $category = Category::find($id);
        if (is_null($category)){
            return response()->json(['message' => 'Category Not Found.'], 404);
        }
        return response()->json($category::find($id), 200);

    }

    public function update(Request $request,Category $category){
    
        $request->validate([
            'name' => 'required'
        
        ]);
  
        $input = $request->all();
  
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }else{
            unset($input['image']);
        }
          
        $category->update($input);
        return response()->json(['message' => 'Category Updated Successfully.'], 200);

    }

    public function destroy(Category $category)
    {
        $category->delete();
   
        return $this->sendResponse([], 'Category deleted successfully.');
    }
}
