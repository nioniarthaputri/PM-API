<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Boards;
use App\TableLists;
use App\Teams;
use App\BoardFavorite;
use App\Cards;
use Auth;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function createboard(Request $request)
    {
        $board = new Boards;
        $board->team_id = $request->team_id;
        $board->board_name = $request->board_name;
        $board->board_background = $request->board_background;
        $board->created_by = $request->created_by;
        $board->deadline=$request->deadline;
        $board->save();

        $id = $board->id;

        $data = [
            'Material','Plan', 'ToDo', 'In Progress', 'ToVerify', 'Done'
        ];
        
        for($i = 0; $i <= 5; $i++){
            TableLists::create([
                'board_id' => $id,
                'list_name' => $data[$i],
                'created_by' => 1
            ]);
        }

        return response()->json([
            'board' => $board->board_name,
            'data' => $data,
            'status' => true

        ]);
        
    }
    public function getBoardList($id)
    {
        $boardlist = Boards::with('tablelist')->where('id', $id)->get();

        return response()->json(['boardslist' => $boardlist]);
    }

    public function addTableList(Request $request)
    {
        $tablelist = new TableLists;

        $tablelist->board_id = $request->board_id;
        $tablelist->list_name = $request->list_name; 
        $tablelist->created_by = $request->created_by;
        $tablelist->save();

        return response()->json([
            'list' => $tablelist->list_name,
            'status' => true
        ]);
    }

    public function getAllDataBoard($id)
    {
       $boards = Boards::with('teams')->with('teams.members')->where('id', $id)->get();
        $tablelist = TableLists::with('cards')->get();
    
        foreach ($boards as $b){}

        return response()->json(['status' => true,
                                'board'=>$b,
                                'list' => $tablelist]);
    }
  public function getListBoard($id)
    {
        $listBoard = Boards::where('team_id', $id)->get();

        return response()->json([
            'status'=>true,
            'listBoard' => $listBoard
            
        ]);
    }

    public function getCardList($id)
    {
       $card = TableLists::with(['cards'])->where('id', $id)->get();
        /*$card = DB::table('table_lists')->join('cards', 'table_lists.id', '=', 'cards.table_lists_id')
                                        ->select('table_lists.list_name', 'cards.*')
                                        ->where('table_lists.id', '=', $id)
                                        ->get();*/
        return response()->json(['card' => $card]);
    }
    public function getBoardFavorite(Request $request,$id){
             $id_team = $request->id_team;
        // $board_favorite =  BoardFavorite::where('user_id',$id)->get();
        $board_favorite=DB::table('board_favorites')->join('boards', 'board_favorites.board_id', 'boards.id')
        ->join('teams','boards.team_id','teams.id')
                                            ->select('boards.*')
                                            ->where('board_favorites.user_id', '=', $id)
                                            ->where('teams.id',$id_team)
                                            ->get();

        return response()->json(['board_favorite' => $board_favorite]);
    }
 public function destroyBoardFavorite(Request $request,$id){

        $delete_board_favorite =  BoardFavorite::where('board_id', $id)->where('user_id',$request->id)
            ->first()->delete();

        return response()->json(['status' => true]);

    }

        public function storeBoardFavorite(Request $request){

        $create = BoardFavorite::create([
            'user_id' => $request->user_id,
            'board_id' => $request->board_id,
            'created_by' => $request->created_by,
        ]);

        if($create){
            return response()->json(['status' => true]);
        }

    }



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
               $teams = Boards::find($id)->forceDelete();
        return response()->json(['status' => true]);
    }

   public function activityBoard($id){
        $activity = Boards::where('id' ,$id)->with('tablelist.cards.card_history.biodata', 'tablelist.cards.card_history.from_list', 'tablelist.cards.card_history.to_list')->first();
        return response()->json([   
            'activity' => $activityBoard2y
        ]);
    }
    public function activityBoard2($id){

        // $activity = DB::table('boards')->join('table_lists', 'boards.id','table_lists.board_id')
        //                                 ->join('cards','table_lists.id','cards.list_id')
        //                                 ->join('history_cards','cards.id','history_cards.card_id')
        //                                 ->join('biodatas', 'history_cards.created_by', 'biodatas.user_id')
        //                                 ->join('history_cards','table_lists.id', 'history_cards.from_list')
        //                                 ->join('history_cards','table_lists.id', 'history_cards.to_list')
        //                                 // ->select('boards.board_name', 'cards.card_name', 'biodatas.username', 'history_cards.from_list', 'history_cards.to_list')
        //                                 ->select('table_lists.list_name','boards.board_name','cards.card_naem','history_cards.*','biodatas.username','table_lists.list_name as Nfrom_list','table_lists.list_name as Nto_list')
        //                                 ->where('boards.id', $id)
        //                                 ->get();

          $activity = DB::table('history_cards')->join('cards', 'history_cards.card_id', 'cards.id')
                                          
                                            ->join('table_lists','cards.list_id','table_lists.id')
                                            // ->join('table_lists','hi
                                            //     story_cards.from_list','table_lists.id')
                                            // ->join('table_lists','history_cards.to_list','table_lists.id')
                                            ->join('boards','table_lists.board_id','boards.id')
                                            ->join('biodatas','boards.created_by','biodatas.id')
                                            ->select('cards.card_name', 'boards.board_name','biodatas.username','biodatas.photo','history_cards.*')
                                            ->where('boards.id', '=', $id)->get();

        return response()->json([   
                        'status' => true,

            'activity' => $activity

        ]);
    }
    public function getFromList($id){
        $from_list=TableLists::where('id',$id)->get();
        foreach ($from_list as $key) {
            # code...
        }
          return response()->json([   
                        'status' => true,
            'from_list' => $key

        ]);
    }
    public function getBoardTeam($id){
        $board=Boards::where('team_id',$id)->get();
        $total=$board->count('team_id');
        return response()->json([
            'total_board' => $total,
        
        ]);
    }
     public function getToList($id){
        $to_list=TableLists::where('id',$id)->get();
        foreach ($to_list as $key) {
            # code...
        }
          return response()->json([   
            'status' => true,
            'to_list' => $key

        ]);
    }
}           