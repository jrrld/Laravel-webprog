<?php

namespace App\Http\Controllers;

use App\Province;
use App\Municipality;
use App\Barangay;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.provinces.index', ['provinces' => $provinces]);
    }

    public function table(Request $request){
        if($request->ajax()){
            $provinces = Province::all()->paginate(10);
            return view('admin.provinces.table')->with('provinces', $provinces); 
        }
        else {
            return redirect(route('clients.index'));
        }
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
        
    }
    public function save(Request $request)
    {
        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $municipalities = Municipality::where('fkmunicipality_provinces',$id)->get();
        
        return view('admin.provinces.municipalities.municipalities')->with('municipalities', $municipalities);
    }

    public function showBarangays($id)
    {
        $barangays = Barangay::where('fkbarangays_municipalities',$id)->get();
        
        return view('admin.provinces.municipalities.barangays')->with('barangays', $barangays);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function edit(Province $province)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Province $province)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province)
    {
        //
    }
}
