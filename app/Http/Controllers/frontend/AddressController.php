<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\controller;
use App\User;
use App\UserAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'soc_name' => 'required',
            'street' => 'required',
            'city' => 'required',
            'zipcode' => 'bail|required|numeric|digits:6'
        ]);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        if($request->set_as_default == 'checked')
        {
            $address = UserAddress::create($data);
            $user = User::find(auth()->user()->id);
            $user->address_id = $address->id;
            $user->lat = $address->lat;
            $user->lang = $address->lang;
            $user->save();
        }
        else
        {
            $address = UserAddress::create($data);
        }
        return ['data' => $address, 'success' => true];
    }

    public function user_select_address(Request $request)
    {
        $user_address = UserAddress::find($request->id);
        $address_display = '';
        $address_display .= '<input type="hidden" name="" id="address_id" value="'.$user_address->id.'">';
        $address_display .= '<img src=" '.url('frontend/image/icon/Icon awesome-map-marker-alt.png').'" alt="" class="ml-3">&nbsp';
        $address_display .= $user_address->soc_name .','.  $user_address->street .','.  $user_address->city  .','.  $user_address->zipcode;
        // <div class="col-md-6 col-sm-6 col-6">
        // <img src="http://127.0.0.1:8000/image/icon/Icon awesome-map-marker-alt.png" alt="" class="ml-3">&nbsp;
        // gondal road,5,rjt,456133
        // </div>
        return response(['success'=>true , 'data'=>$address_display]);
        // dd($address_display);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
