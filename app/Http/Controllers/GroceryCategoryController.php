<?php

namespace App\Http\Controllers;

use App\GroceryCategory;
use App\GroceryShop;
use App\GroceryItem;
use App\Imports\GroceryCategoryImport;
use Illuminate\Http\Request;
use Excel;

class GroceryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = GroceryCategory::orderBy('id', 'DESC')->paginate(7);
        foreach ($data as $item) {
            $shop = GroceryShop::get();
            $shops = array();
            foreach ($shop as $value) {
                $likes=array_filter(explode(',',$value->category_id));
                if(count(array_keys($likes,$item->id))>0){
                    if (($key = array_search($item->id, $likes)) !== false) {
                        array_push($shops,$value->id);
                    }
                }
            }
            $item->total_shop = count($shops);
        }
        return view('mainAdmin.GroceryCategory.viewGroceryCategory',['categories'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('mainAdmin.GroceryCategory.addGroceryCategory');
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
            'name' => 'bail|required|unique:grocery_category',
            'status' => 'bail|required',
            'image' => 'bail|required',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        GroceryCategory::create($data);
        return redirect('mainAdmin/GroceryCategory');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GroceryCategory  $groceryCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GroceryCategory  $groceryCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = GroceryCategory::findOrFail($id);
        return view('mainAdmin.GroceryCategory.editGroceryCategory',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroceryCategory  $groceryCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'bail|required|unique:grocery_category,name,' . $id . ',id',
            'status' => 'bail|required',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        GroceryCategory::findOrFail($id)->update($data);

        return redirect('mainAdmin/GroceryCategory');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroceryCategory  $groceryCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        try {
            $item = GroceryItem::where('category_id',$id)->get();
            if(count($item)==0){
                $delete = GroceryCategory::find($id);
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

    public function importExcel(Request $request)
    {
        try
        {
            Excel::import(new GroceryCategoryImport,request()->file('file'));
        }
        catch (\Throwable $th)
        {
            // return redirect()->back()->with(['error',$th]);
        }
        return redirect('mainAdmin/GroceryCategory');
    }
}
