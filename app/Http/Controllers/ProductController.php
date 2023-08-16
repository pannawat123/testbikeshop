<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Config, Validator;

class ProductController extends Controller
{
    var $rp = 2;

    public function index() {
        $products = Product::paginate($this->rp);
        return view('product/index', compact('products'));
    }

    public function search (Request $request) {
        $query = $request->q;
        if($query) {
            $products = Product::where('name' , 'like' , '%'.$query.'%')
            ->orwhere('code' , 'like' , '%'.$query.'%')
            ->paginate($this->rp);

        } else {
            $products = Product::paginate($this->rp);
        }
        
        return view('product/index', compact('products'));
    }

    public function edit ($id = null) {
        $categories = Category::pluck('name' , 'id')->prepend('เลือกรายการ'  , ''); // คำสั่ง pluck ดึงข้อมูลมาแสดง
        $product = Product::find($id); // คำสั่ง find ค้นหาข้อมูลที่ต้องการแก้ไข
    
        
        return view('product/edit')
        ->with('product' , $product)
        ->with('categories' , $categories); // คำสั่ง with ส่งข้อมูลไปยัง view
    
    
    }
 
    public function update(Request $request) {
        $rules = array(
            'code' => 'required', 'name' => 'required',
            'category_id' => 'required|numeric', 
            'price' => 'numeric',
            'stock_qty' => 'numeric',
        );

        $messages = array(
            'required' => 'กรุณากรอกข้อมูล :attribute ให้ครบถ้วน', 'numeric' => 'กรุณากรอกข้อมูล
            :attribute ให้เป็นตัวเลข',
        );

        $id = $request->id;
        $temp = array('name' => $request->name, 
                      'code' => $request->code, 
                      'category_id' => $request->category_id, 
                      'price' => $request->price, 
                      'stock_qty' => $request->stock_qty);
       

       
        $validator = Validator::make($temp, $rules, $messages);
        if ($validator->fails()) {
            return redirect('product/edit/'.$id)
            ->withErrors($validator)
            ->withInput();
        }

        $product = Product::find($id);
        $product->code = $request->code;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->stock_qty = $request->stock_qty;

        $product->save();


        
        return redirect('product')
        ->with('ok' , 'true')
        ->with('msg' ,'บันทึกข้อมูลเรียบร้อยแล้ว');
    }


}

