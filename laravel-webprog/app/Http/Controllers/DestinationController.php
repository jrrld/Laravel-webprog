<?php

namespace App\Http\Controllers;

use App\Destination;
use Illuminate\Http\Request;
use Alert;
class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $destinations = Destination::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.destinations.index', ['destinations' => $destinations]);
    }

    public function table(Request $request){
        if($request->ajax()){
            $destinations = Destination::all();
            return view('admin.destinations.table')->with('destinations', $destinations); 
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
        //$tags = Tag::all();
        return view('admin.destinations.form')  
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
            'dname' => 'required',
            'dlocation' => 'required',
            'ddesc' => 'required'
        ]);
        $destination = new Destination([
            'dname' => $request->input('dname'),
            'dlocation' => $request->input('dlocation'),
            'ddesc' => $request->input('ddesc')
        ]);
        
        $destination->save();
        Alert::success('Good job!')->persistent("Close");
        //$destination->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));
        return redirect()->route('destination.index')->with('info', 'Post created, Destination is: ' . $request->input('dname'));
    }
            else {
            return redirect(route('destination.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()) {
            $destination = Destination::findOrFail($id);
            $title = "Destination";
           return view('admin.destinations.form')
                ->with('destination', $destination)
                ->with('title', $title)
                ->with('type', "SHOW")
                ->with('button_text', "Save Changes")
                ->with('action', "btn-warning view");
        }else{
            return redirect(route('destination.index'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->ajax()) {
            $destination = Destination::findOrFail($id);
            $title= "Edit Destination Information";
           return view('admin.destinations.form')
                ->with('destination', $destination)
                ->with('title', $title)
                ->with('type', "EDIT")
                ->with('button_text', "Save Changes")
                ->with('action', "btn-primary edit");
        }else{
            return redirect(route('destination.index'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Destination  $destination)
    {
        if($request->ajax()){
        $this->validate($request, [
            'dname' => 'required',
            'dlocation' => 'required',
            'ddesc' => 'required'
        ]);
        $destination = Destination::find($request->input('id'));
        $destination->dname = $request->input('dname');
        $destination->dlocation = $request->input('dlocation');
        $destination->ddesc = $request->input('ddesc');

        $destination->update();
        //$post->tags()->detach();
        //$post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));
        //$post->tags()->sync($request->input('tags') === null ? [] : $request->input('tags'));
        return redirect()->route('destination.index')->with('info', 'Desination Info edited for: ' . $request->input('dname'));
        }else{
            return redirect(route('destination.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);
        $destination = $destination->destroy($id)
            ? [
                    'message'    => "Successfully deleted employee",
                    'alert' => 'success',
            ]
            : [
                    'message'    => "Sorry it appears there was a problem deleting this employee",
                    'alert' => 'error',
            ];

        return response()->json($destination);
    }
}
