<?php

namespace App\Http\Controllers;

use App\GrocerySubCategory;
use App\GroceryCategory;
use App\GroceryItem;
use Auth;
use App\GroceryShop;
use Illuminate\Http\Request;

class GrocerySubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $data = GroceryCategory::orderBy('id', 'DESC')->get();
        $data = GrocerySubCategory::orderBy('id', 'DESC')->where('owner_id',Auth::user()->id)->paginate(7);
        return view('admin.SubCategory.viewSubCategory',['categories'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $shop = GroceryShop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $category = GroceryCategory::orderBy('id', 'DESC')->get();    
        return view('admin.SubCategory.addSubCategory',['category'=>$category,'shop'=>$shop]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'bail|required|unique:grocery_sub_category',
            'status' => 'bail|required',
            'category_id' => 'bail|required',
            'shop_id' => 'bail|required',
            'image' => 'bail|required',
        ]);
        $data = $request->all();
        $data['owner_id'] =Auth::user()->id;
        // dd($data);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        GrocerySubCategory::create($data);
        return redirect('owner/GrocerySubCategory');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GrocerySubCategory  $grocerySubCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GrocerySubCategory  $grocerySubCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = GrocerySubCategory::findOrFail($id);        
        $category = GroceryShop::find($data->shop_id)->category_id; 
        $category = GroceryCategory::whereIn('id',explode(",",$category))->get();
        $shop = GroceryShop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('admin.SubCategory.editSubCategory',['category'=>$category,'shop'=>$shop,'data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GrocerySubCategory  $grocerySubCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'bail|required|unique:grocery_sub_category,name,' . $id . ',id',
            'status' => 'bail|required',
            'category_id' => 'bail|required',
            'shop_id' => 'bail|required',
        ]);
        $data = $request->all();
        $data['owner_id'] =Auth::user()->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        GrocerySubCategory::findOrFail($id)->update($data);
        return redirect('owner/GrocerySubCategory');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GrocerySubCategory  $grocerySubCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $item = GroceryItem::where('subcategory_id',$id)->get();           
            if(count($item)==0){
                $delete = GrocerySubCategory::find($id);
                $delete->delete();
                return 'true';
            }     
            else{
                return response('Data is Connected with other Data', 400);
            }                               
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }
}
