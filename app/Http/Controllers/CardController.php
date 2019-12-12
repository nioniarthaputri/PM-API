<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cards;
use App\CardChecklist;
use App\CardChecklistDetail;
use App\CardComments;
use App\HistoryCard;
use App\HistoryProgress;
use App\CardLabels;
use App\CardMembers;
use App\User;
use App\TableLists;
use Auth;
use Illuminate\Support\Facades\DB;

Use \Carbon\Carbon;

class cardController extends Controller
{

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
        $create = Cards::create([
            'list_id' => $request->list_id,
            'card_name' => $request->card_name,
            'description' => '',
            'estimated_hour' => 0,
            'created_by' => $request->created_by
        ]);

        if($create){
    
            return response()->json(['status' => true]);
    
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Card
    public function show($id)
    {
        $card = Cards::where('id', $id)->with('card_checklist.checklist_detail', 'card_attachment', 'table_list', 'card_label', 'card_comment.user.biodata')->first(); //rubah menjadi first
        return $card;
            
    }

    //tambah adi show card member
    public function showCardMembers($id)
    {
        $card = Cards::where('id', $id)->with('card_member.user.biodata')->first();
        return $card;
            
    }

    //menambahkan search member card
    public function searchMemberCard($id)
    {

        $card_members = CardMembers::where('card_id', $id)->get();

        foreach ($card_members as $card_member) {
            $data[] = $card_member->user_id;
        }

        if(empty($data)){
            $user = User::with('biodata')->get();
        }
        else{
            $user = User::whereNotIn('id', $data)->with('biodata')->get();
        }

        return response()->json([
            'user' => $user
        ]);
    }

    //menambahkan invite member card
    public function inviteMemberCard(Request $request){

        $id_card = $request->card_id;
        $id_user = $request->user_id;

        $invite =  CardMembers::create([

            'card_id' => $id_card,
            'user_id' => $id_user,
            'adder_id' => Auth::user()->id,
            'remover_id' => 0

        ]);

        if($invite){
            return response()->json([
                'status' => true
            ]);
        }

    }

    // menambahkan remove member card

    public function removeMemberCard($id){

        

        $update = CardMembers::find($id)->update([
            'remover_id' => Auth::user()->id
        ]);

        $delete = CardMembers::destroy($id);

        if($delete){
            return response()->json([
                'status' => true
            ]);
        }
    }

    //menambahkan edit card name
    public function editCardName(Request $request, $id){

        $update = Cards::find($id)->update([
            'card_name' => $request->card_name
        ]);

        if($update){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }
    }

    //menambahkan edit card description
    public function editCardDescription(Request $request, $id){

        $update = Cards::find($id)->update([
            'description' => $request->card_description
        ]);

        if($update){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }
    }

    public function update(Request $request, $id)
    {
        $card_name = $request->var_card_name;
        $description = $request->var_description;
        $created_by = $request->var_created_by;

        $card = Cards::where('id', $id)->first();
        $card->card_name = $card_name;
        $card->description = $description;
        $card->created_by = $created_by;

        if($card->save()){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }

    }
    //Card Detail

    //Edit List
    public function moveList(Request $request, $id) //move card
    {
           $table_lists = $request->var_list_id;

            $datacard = Cards::where('id', $id)->first();
            $card = Cards::where('id', $id)->first();

                $historycard = HistoryCard::create([
                'card_id' => $id,
                'from_list' => $datacard->list_id,
                'to_list' => $table_lists,
                'since' => Carbon::now(),
                'until' => null,
                'created_by' => 1
                // 'created_by' => Auth::user()->id

                ]);

            if ($table_lists == 4) {

                $historyprogress = HistoryProgress::create([
                'user_id' => 1,
                // 'user_id' => Auth::user()->id,
                'card_id' => $datacard->id,
                'from_list' => $datacard->list_id,
                'to_list' => $table_lists,
                'since' => Carbon::now(),
                'until' => null,
                'estimated_time' => 0
            ]);
            
            }

            if ($card->list_id == 4){

                $historyprogress = HistoryProgress::where('card_id', $id)->where('until', null)->first(); 
                $since = $historyprogress->since;
                $until = Carbon::now();
                $estimated = $until->diffInMinutes($since);
                

                $historyprogress->until = $until;
                $historyprogress->estimated_time = $estimated;
                $historyprogress->save();
            }




            
            $card->list_id = $table_lists;

            // $boardId = $request->$var_board_id;
            // $list = TableLists::where('id', $id)->first();

            // $list->board_id = $boardId;

        if($card->save() /*&& $list->save()*/){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }


    }

    //menambahkan dd card
    public function ddCard(Request $request, $id) //dd card
    {

        $data = $request->data;

        $list_id = $id;

        /**
         * 1. Kalo list_id lama != list_id baru
         *    - Inset history
         * 2. Kalo list yg baru = In progress
         *    - Cek history_progress terakhir by card_id yg until = null
         *         - Kalo kosong
         *              - Insert progress
         * 
         */


        foreach ($data as $key => $value) {


            if ($value['list_id'] != $list_id) {

                $history_card = HistoryCard::create([
                    'card_id' => $value['id'],
                    'from_list' => $value['list_id'],
                    'to_list' => $list_id,
                    'since' => $date = Carbon::now(),
                    'created_by' => Auth::user()->id
                ]);



                $check_update_id_list_progress = TableLists::find($value['list_id']);

                $data_progress = HistoryProgress::where('card_id', $value['id'])->orderBy('card_id', 'desc')->first();

                if($check_update_id_list_progress->list_name == 'In Progress' && $data_progress->until == null){

                    $since = $data_progress->since;
                    $until = Carbon::now();

                    $estimated = $until->diffInMinutes($data_progress->since);

                    $update_hp = HistoryProgress::where('card_id', $value['id'])->update([
                        'until' => $until,
                        'estimated_time' => $estimated
                    ]);

                }

                

            }

            $check_insert_id_list_progress = TableLists::find($list_id);

            if($check_insert_id_list_progress->list_name == 'In Progress'){

                $check_card_history_progress = DB::table('history_progresses')
                    ->where('card_id', $value['id'])
                    ->where('until', null)
                    ->orderBy('card_id', 'desc')
                    ->first();

                if(empty($check_card_history_progress)){

                    $insert_hp = HistoryProgress::create([
                        'user_id' => Auth::user()->id,
                        'card_id' => $value['id'],
                        'from_list' => $value['list_id'],
                        'to_list' => $list_id,
                        'since' => Carbon::now(),
                        'estimated_time' => 0
                    ]);

                }

            }


            

            $card = Cards::find($value['id'])->update([
                'list_id' => $list_id,
                'position' => $key
            ]);     

        }

        return response()->json(['status' => true]);


    }
    //menambahkan function historyCard
    public function historyCard($id){

        $card_history = HistoryCard::where('card_id' ,$id)->with('biodata', 'from_list', 'to_list')->orderby('since', 'desc')->get();

    
        return response()->json(['card_history' => $card_history]);

    }

    //menambahkan add label card
    public function addLabel(Request $request){
        $create = CardLabels::create([
            'card_id' => $request->card_id,
            'label' => $request->label,
            'label_color' => $request->label_color,
            'added_by' => Auth::user()->id,
            'removed_by' => 0
        ]);

        if($create){
            return response()->json([
                'status' => true
            ]);
        }
    }

    //menambahkan remove label card
    public function removeLabel($id){
        
        $delete = CardLabels::find($id)->delete();

        if($delete){
            return response()->json([
                'status' => true
            ]);
        }
    }


    //Checklist
    public function getChecklist($id)
    {
        $checklist = CardChecklist::where('id', $id)->with('checklist_detail')->get();
        return $checklist;
    }

    public function createChecklist(Request $request)
    {
        $checklist = new CardChecklist;
        $checklist->card_id = $request->var_card_id;
        $checklist->title = $request->var_title;
        $checklist->created_by = Auth::user()->id;

        
        if($checklist->save() ){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }

    }

    public function createChecklistDetail(Request $request)
    {    
        $detchecklist = new CardChecklistDetail;
        $detchecklist->checklist_id = $request->var_checklist_id;
        $detchecklist->list = $request->var_list;
        $detchecklist->checked = 0;
        $detchecklist->checked_by = 0;
        $detchecklist->created_by = Auth::user()->id;

        if($detchecklist->save() ){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }
    }

    public function editChecklistDetail(Request $request, $id)
    {
            $list = $request->var_list;
            $checked = $request->var_checked;
            $detchecklist = CardChecklistDetail::where('id', $id)->first();
            $detchecklist->list = $list;
            $detchecklist->checked = $checked;

        if($detchecklist->save()){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        } 
    }

    
    public function editChecklist(Request $request, $id)
    {
        $title = $request->var_title;
        $checklist = CardChecklist::where('id', $id)->first(); //ubah menjadi cardChecklist asalya checklist
        $checklist->title = $title;

        if($checklist->save()){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        } 
    }

    public function editNameDetailChecklist(Request $request, $id)
    {
        $list = $request->var_title;
        $detchecklist = CardChecklistDetail::where('id', $id)->first();
        $detchecklist->list = $list;

        if($detchecklist->save()){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        } 
    }

    //menambahkan remove checklist
    public function removeChecklist($id){

        $delete = CardChecklist::find($id)->delete();

        if($delete){
            return response()->json(['status' => true]);
        }
        else{
            return response()->json(['status' => false]);
        }

    }

    //menambahkan remove detail checklist
    public function removeDetailChecklist($id){

        $delete = CardChecklistDetail::find($id)->delete();

        if($delete){
            return response()->json(['status' => true]);
        }
        else{
            return response()->json(['status' => false]);
        }

    }

    //Comment & Atach
    public function commentAttach(Request $request)
    {
        $cardcomment = new CardComments;
        $cardcomment->card_id = $request->var_card_id;
        $cardcomment->comment = $request->var_comment;

        if(!empty($request->attachment)){
            $cardcomment->attachment = $request->var_attachment;
        }
        else{
            $cardcomment->attachment = '';
        }
        
        $cardcomment->created_by = Auth::user()->id;
        
        if($cardcomment->save()){
    
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }
    }

    // progress
    public function userProgress(){


        $progress = HistoryProgress::whereBetween('since', [Carbon::now()->startOfWeek(), Carbon::now()->startOfWeek()]);


        return response()->json([
            'days' => $progress
        ]);
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //add permanent delete

    public function delete($id)
    {
        $list = Cards::where('id',$id);
        $list->forceDelete();
        
        if($list){
            
            return response()->json(['status' => true]);
    
        }
        else{
        
            return response()->json(['status' => false]);
        
        }
    }

    public function softdestroy($id)
    {
        
        $destroy = Cards::destroy($id);

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
        $cards = Cards::onlyTrashed()->get();

        return response()->json([
            'status' => true,
            'card' => $cards
        ]);
    }
 public function userProgressDay($id){


        // //week
        // $progress_week = HistoryProgress::where('user_id', Auth::user()->id)
        // // ->with('cards.table_list.boards')
        // ->whereBetween('since', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        // ->get();


        // //month
        $date = Carbon::now();
        // $month = Carbon::now()->month;
        $day = date("Y-m-d");

    
        $progress_month = HistoryProgress::where('user_id', $id)
        ->whereDate('since', '=', $date)
        ->get();

        

        $total=$progress_month->sum('estimated_time');


        return response()->json([
            'progress_user' => $total,
        
        ]);
    }   


}
