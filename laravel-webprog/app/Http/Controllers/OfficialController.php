<?php

namespace App\Http\Controllers;

use App\Official;
use App\Province;
use App\Municipality;
use Illuminate\Http\Request;
use Alert;
class OfficialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $officials = Official::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.officials.index', ['officials' => $officials]);
    }

    public function table(Request $request){
        if($request->ajax()){
            $officials = Official::all();
            return view('admin.officials.table')->with('officials', $officials); 
        }
        else {
            return redirect(route('official.index'));
        }
    }

    public function tableProvince(Request $request,$id){
        if($request->ajax()){
            
            $municipality_id = Municipality::where('fkmunicipality_provinces', $id)->pluck('municipality_id');

            $officials = Official::whereIn('fkofficial_province', $municipality_id)->get();

            return view('admin.officials.table')->with('officials', $officials);
        }
        else {
            return redirect(route('official.index'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Province::all();
        $municipalities = Municipality::all();
        return view('admin.officials.form')  
                    ->with('provinces', $provinces)
                    ->with('municipalities', $municipalities)
                    ->with('type', "CREATE")
                    ->with('title', "ADD")
                    ->with('action', 'btn-success add')
                    ->with('button_text', 'Add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax()){

            $this->validate($request, [
                
                'fkofficial_province' => 'required',
                'official_first' => 'required',
                'official_last' => 'required'
            ]);
            $official = new Official([

                'fkofficial_province' => $request->input('fkofficial_province'),
                'official_first' => $request->input('official_first'),
                'official_middle' => $request->input('official_middle'),
                'official_last' => $request->input('official_last')
            ]);
            
           
            $message = $official->save() ? [
                'message'   => "Successfully added official",
                'alert'     => 'success'
            ]
            : [
                'message'    => "Sorry it appears there was a problem 
                                adding this official",
                'alert' => 'error',
            ];

            return response()->json($message);
        }
        else {
            return redirect(route('official.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Official  $official
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()) {
            $official = Official::findOrFail($id);
            $provinces = Province::all();
            $municipalities = Municipality::all();            
            $title = "Official";
           return view('admin.officials.form')
                ->with('provinces', $provinces)
                ->with('municipalities', $municipalities)
                ->with('official', $official)
                ->with('title', $title)
                ->with('type', "SHOW")
                ->with('button_text', "Save Changes")
                ->with('action', "btn-warning view");
        }else{
            return redirect(route('official.index'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Official  $official
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->ajax()) {

            $official = Official::findOrFail($id);
            $provinces = Province::all();
            $municipalities = Municipality::all(); 
            $title= "Edit Official Information";
           return view('admin.officials.form')
                ->with('provinces', $provinces)
                ->with('municipalities', $municipalities)
                ->with('official', $official)
                ->with('title', $title)
                ->with('type', "EDIT")
                ->with('button_text', "Save Changes")
                ->with('action', "btn-primary edit");
        }else{
            return redirect(route('official.index'));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Official  $official
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Official  $official)
    {
        if($request->ajax()){
            $this->validate($request, [

                'fkofficial_province' => 'required',
                'official_first' => 'required',
                'official_last' => 'required'
            ]);
            $official = Official::find($request->input('id'));
        
            $official->fkofficial_province = $request->input('fkofficial_province');
            $official->official_first = $request->input('official_first');
            $official->official_middle = $request->input('official_middle');
            $official->official_last = $request->input('official_last');


            //$official->update();
            $message = $official->update() ? [
                'message'   => "Successfully updated official",
                'alert'     => 'success'
            ]
            : [
                'message'    => "Sorry it appears there was a problem 
                                updating this official",
                'alert' => 'error',
            ];

            return response()->json($message);
            //$post->tags()->detach();
            //$post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));
            //$post->tags()->sync($request->input('tags') === null ? [] : $request->input('tags'));
            //return redirect()->route('official.index')->with('info', 'Official Info edited for: ' . $request->input('official_first'));
        }
        else{
            return redirect(route('official.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Official  $official
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $official = Official::findOrFail($id);
        $message = $official->destroy($id)
            ? [
                    'message'    => "Successfully deleted official",
                    'alert' => 'success',
            ]
            : [
                    'message'    => "Sorry it appears there was a problem deleting this official",
                    'alert' => 'error',
            ];

        return response()->json($message);
    }
}
