x<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Biodata;
use App\User;
use Illuminate\Http\Facedes\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
//  public function displayFoto($bg)

// {

 
//         $storagePath = storage_path('/assets/' . $bg .'.jpg');
//         return Image::make($storagePath)->fit(50, 50)->response();
// }   
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
    public function showUser(Request $request)
    {
            $search=$request->search;
   $members = DB::table('users')
                            ->join('biodatas', 'biodatas.user_id', 'users.id')
                            ->select('biodatas.name','biodatas.username','biodatas.photo','users.id','users.email',)
                            ->where('users.email', 'LIKE',"%".$search."%")
                            ->orWhere('biodatas.username','LIKE',"%".$search."%")
                            ->get();
         return response()->json([
                    'status' => true,
                    'list'=>$members
                ]);}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $name = $request->var_name;
        $username = $request->var_username;
        $initial = $request->var_initial;
        $bio = $request->var_bio;
        $phone = $request->var_phone;
        $address = $request->var_address;
        // $email = $request->var_email;
        // $id1 = $request->var_id;

        // dd($id);

        $data = Biodata::where('users_id',$id)->first();
        $data->name = $name;
        $data->username = $username;
        $data->initial = $initial;
        $data->bio = $bio;
        $data->phone = $phone;
        $data->address = $address;

        // dd($request->file('inputFoto'));

        if($request->file('inputFoto') != null){

            $ext = explode(".", $_FILES['inputFoto']['name']);
            // dd($ext[1]);

            $name = "User_".date('dmy_his').".".$ext[1];
            $target_dir = "image-profil/";
            $target_file = $target_dir . basename($_FILES["inputFoto"]["name"]);
            move_uploaded_file($_FILES['inputFoto']['tmp_name'],$target_dir.$name);

            $data->foto = $name;

        }

        if($data->save()){

                return response()->json([
                    'status' => true
                ]);

        } 
        else{
            return response()->json([
                'status' => false
            ]);

        }
    }

    public function updateFoto(Request $request, $id)
    {
        
        // $id_user = $request->input('var_id');

        // $data = Biodata::where('user_id',$id_user)->first();

        // if($request->file('var_foto') != null){

        //     $ext = explode(".", $_FILES['var_foto']['name']);
        //     // dd($ext[1]);

        //     $name = "User_".date('dmy_his').".".$ext[1];
        //     $target_dir = "image-profil/";
        //     $target_file = $target_dir . basename($_FILES["var_foto"]["name"]);
        //     move_uploaded_file($_FILES['var_foto']['tmp_name'],$target_dir.$name);

        //     $data->foto = $name;

        // }

        // if($data->save()){

        //     return response()->json([
        //         'status' => true
        //     ]);

        // }
        // else{

        //     return response()->json([
        //         'status' => false
        //     ]);

        // }

        $data = Biodata::where('user_id',$id)->first();

        // dd($request->file('inputFoto'));

        if($request->file('inputFoto') != null){
            $ext = explode(".", $_FILES['inputFoto']['name']);
            // dd($ext[1]);

            $name = "User_".date('dmy_his').".".$ext[1];
            $target_dir = "image-profil/";
            $target_file = $target_dir . basename($_FILES["inputFoto"]["name"]);
            move_uploaded_file($_FILES['inputFoto']['tmp_name'],$target_dir.$name);

            $data->foto = $name;

            $data->save();
        }

        return response()->json([
            'status' => true
        ]);
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
