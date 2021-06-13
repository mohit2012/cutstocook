<?php

namespace App\Http\Controllers;

use App\Category;
use App\Imports\CategoryImport;
use Auth;
use App\Item;
use Illuminate\Http\Request;
use Excel;
// use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Excel;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Category::orderBy('id', 'DESC')->paginate(7);
        if(Auth::check()){
            return view('admin.category.viewCategory',['categories'=>$data]);
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return view('mainAdmin.category.viewCategory',['categories'=>$data]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::check())
        {
            return view('admin.category.addCategory');
        }
        elseif(Auth::guard('mainAdmin')->check())
        {
            return view('mainAdmin.category.addCategory');
        }

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
            'name' => 'bail|required|unique:category',
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
        Category::create($data);

        if(Auth::check()){
            return redirect('owner/Category');
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return redirect('mainAdmin/AdminCategory');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = Category::findOrFail($id);
        if(Auth::check()){
            return view('admin.category.editCategory',['data'=>$data]);
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return view('mainAdmin.category.editCategory',['data'=>$data]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required|unique:category,name,' . $id . ',id',
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
        Category::findOrFail($id)->update($data);
        if(Auth::check()){
            return redirect('owner/Category');
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return redirect('mainAdmin/AdminCategory');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $item = Item::where('category_id',$id)->get();
            if(count($item)==0){
                $delete = Category::findOrFail($id);
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

    public function viewCategory(){
        $data = Category::where('status',0)->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }


    public function importExcel(Request $request)
    {
        try
        {
            Excel::import(new CategoryImport,request()->file('file'));
        }
        catch (\Throwable $th)
        {

        }
        if(Auth::check())
        {
            return redirect('owner/Category');
        }
        elseif(Auth::guard('mainAdmin')->check())
        {
            return redirect('mainAdmin/AdminCategory');
        }
    }
}
