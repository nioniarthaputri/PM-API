<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TableLists;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        $update = TableLists::find($id)->update([
            'list_name' => $request->list_name
        ]);

        if($update){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }
    }

    public function delete($id)
    {
        $list = TableLists::where('id',$id)->delete();
        
        if($list){
            
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function softdestroy($id)
    {
        
        $destroy = TableLists::destroy($id);

        if($destroy){

            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }
    }
    // nioni add destroy
    public function destroy()
    {
        $list = TableLists::onlyTrashed()->get();

        return response()->json([
            'status' => true,
            'list' => $list
        ]);
    }
}
