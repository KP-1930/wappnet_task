<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends BaseController
{
    public function index()
    {
       $product = Product::all();       
        return $product;
        
    }

    public function store(Request $request)
    {        
        $input = $request->all();        
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required'
            
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }   
        
        if ($image = $request->file('image')) {
            $destinationPath = 'product/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
    
       $product = Product::create($input);
                 
       return response(['Message'=>'Product Created'], 201);


    } 

    public function getProductById($id){
        $product = Product::find($id);
        if (is_null($product)){
            return response()->json(['message' => 'Product Not Found.'], 404);
        }
        return response()->json($product::find($id), 200);

    }

    public function update(Request $request,Product $product){
    
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required'

        
        ]);
  
        $input = $request->all();
  
        if ($image = $request->file('image')) {
            $destinationPath = 'product/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }else{
            unset($input['image']);
        }
          
        $product->update($input);
        return response()->json(['message' => 'Product Updated Successfully.'], 200);

    }

    public function destroy(Product $product)
    {
        $product->delete();
   
        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
