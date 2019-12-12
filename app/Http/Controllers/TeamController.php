<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Teams;
use App\Members;
use App\User;
Use Auth;
use Validator;
class TeamController extends Controller
{

     public $successStatus = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
       

                          
    }
  public function showUser(Request $request)
    {
            $search=$request->search;
   // $members = DB::table('users')
   //                          ->join('biodatas', 'biodatas.user_id', 'users.id')
   //                          ->select('biodatas.name','biodatas.username','biodatas.photo','users.id','users.email',)
   //                          ->where('users.email', 'LIKE',"%".$search."%")
   //                          ->orWhere('biodatas.username','LIKE',"%".$search."%")
   //                          ->get();
             $members = Members::where('team_id', $request->team_id)->get();

        foreach ($members as $member) {
            $data[] = $member->user_id;
        }

        $user = User::whereNotIn('id', $data)->where('email', 'LIKE',"%".$search."%")->with('biodata')->get();

        // return response()->json($user);
         return response()->json([
                    'status' => true,
                    'list'=>$user
                ]);}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inviteMember(Request $request)
    {
        $member=new Members;
        $member->team_id=$request->get('team_id');
        $member->user_id=$request->get('user_id');
        $member->created_by=$request->get('created_by');
//  $validator = Validator::make($request->all(), [
//             'team_id' => 'required|unique:members,team_id',
//             'user_id' => 'required|unique:members,user_id',
        
//         ]);

//         if ($validator->fails()) {
//          return response()->json([
//                             'status' => true,
//                             'message' =>'Member already exist'

// //                             ],$this->successStatus);      
// $user = Members::where('team_id', $member->team_id)->get();
// $userr = Members::where('user_id', $member->user_id)->get();
// if ($user != null && $userr !=null ) {
//   return response()->json([
//                             'status' => true,
//                             'message' =>'Member already exist'

//                             ],$this->successStatus);  
// }
// if($team=Members::('team_id','=',$member->team_id)){
//     if ($user=Members::('user_id','=',$member->user_id)) {
        
//   return response()->json([
//                             'status' => true,
//                             'message' =>'Member already exist'

//                             ],$this->successStatus);  
//     }
// }
// else{
        if($member->save()){
                        return response()->json([
                            'status' => true,
                            'message' =>'Invite the Member Successful'

                            ],$this->successStatus);            
                }
                else{
                        return response()->json([
                            'status' => false,
                            'message' =>'Invite the Member Fail'

                            ],$this->successStatus);               
                }

    
}
    public function showMember($id){
        $members = DB::table('users')->join('members', 'members.user_id', 'users.id')
                                    ->join('biodatas', 'biodatas.user_id', 'users.id')
                                            ->select('biodatas.name','biodatas.username','biodatas.photo','users.email','members.id','members.team_id', 'members.user_id',
                                                    'members.created_by')
                                            ->where('members.team_id', '=', $id)->get();
  return response()->json([
                            'status' => true,
                            'message' =>'Succes',
                            'member' => $members
                            ],$this->successStatus);   
        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team = new Teams;

        $team->team_name = $request->get('team_name');
        $team->team_description = $request->get('team_description');
        $team->created_by = $request->get('created_by');
        
        
        $id_user_created_by = $team->created_by;
  

        if($team->save()){
            $team_id = $team->id;
                $members = new Members;
                $members->team_id=$team_id;
                $members->user_id=$id_user_created_by;
                $members->created_by=$id_user_created_by;

                if($members->save()){
                        return response()->json([
                            'status' => true,
                            'message' =>'Make the Team Successful'

                            ],$this->successStatus);            
                }
                else{
                        return response()->json([
                            'status' => false,
                            'message' =>'make the Team Fail'

                            ],$this->successStatus);               
                }
        } 
    
        else {
             return response()->json([
                            'status' => 'gagal',
                            'message' =>'Make the Team Successful'

                            ],$this->successStatus);      
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  $user = Auth::user();
        // $id =Auth::user()->id;
        // $user_id = Teams::where('user_id', $id)->get();

        if($id){
            $members = DB::table('members')->join('teams', 'members.team_id', 'teams.id')
                                            ->select('members.id','members.team_id', 'members.user_id',
                                                    'teams.team_name', 'teams.team_description')
                                            ->where('members.user_id', '=', $id)->get();

        //$members = Members::with('teams')->where('user_id', $user_id)->get();
        // foreach ($members as $member) {
        //     # code...
        // }
        return response()->json([
                            'status' => true,
                            'message' =>'Succes',
                            'data' => $members
                            ],$this->successStatus);   
        }
  else{
          return response()->json([
                            'status' => false,
                            'message' =>'Fail',
                            ],$this->successStatus);   

  }
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
            
        Teams::where('id', $id)->update([
            'team_name' => $request->team_name,
            'team_description' => $request->team_description

        ]);
         return response()->json([
                            'status' => true,
                            'message' =>'Succes',
                            ],$this->successStatus);   

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
               $teams = Teams::find($id)->forceDelete();
        return response()->json(['status' => true]);
    }
    public function leaveTeam(Request $request){
        $id_team=$request->id_team;
        $id_user=$request->id_user;
        $leaveTeam=Members::where('team_id',$id_team)->where('user_id',$id_user)->delete();
        return response()->json(['status' => true]);

    }
}
