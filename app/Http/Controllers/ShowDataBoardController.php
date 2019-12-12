<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Boards;
use App\TableLists;
use App\Teams;
use App\Members;
Use Auth;

class ShowDataBoardController extends Controller
{
    public function showBoard(Request $request)
    {
        $id = $request->id_board;
        $board = Boards::find($id);

        $id_team = $board->team_id;

        $team = Teams::where('id', $id_team)->first();

        $members = Members::where('team_id', $id_team)->with('user.biodata')->get();
            
        $lists = TableLists::where('board_id', $id)->with('cards.card_member.user.biodata')->get();

        return response()->json([
            
            'team' => $team,
            'members' => $members,
            'boards' => $board,
            'lists' => $lists,

        ],200);
    }

    public function showTeam()
    {

        $user_id = Auth::user()->id;

        $team = Members::where('user_id', $user_id)->with('teams.boards')->get();

        return response()->json([
            
            'team' => $team,
            
        ],200);
    }
}
