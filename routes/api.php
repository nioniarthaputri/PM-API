    <?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
 
Route::group(['prefix' => 'v1' ], function(){

    Route::post('/login', [
        'uses' => 'AuthController@logIn',
        'as' => 'login-register'
    ]);

    Route::resource('/user','UserController');
     Route::resource('/team','TeamController');
    Route::post('/edit-foto/{id}', [
        'uses' => 'UserController@updateFoto',
        'as' => 'edit-foto'
    ]);


                Route::resource('/card','CardController');
                Route::GET('/card/getLists/{id}', 'CardController@getListsName');
    
});

Route::group(['middleware' => 'auth:api'], function(){
    

   
 	Route::get('v1/details', 'AuthController@details');

    
        

    Route::POST('v1/update/{id}', 'UserController@update');

Route::post('v1/search-user','MemberController@searchInviteMember');

    Route::post('v1/create-list','BoardController@addTableList');
    Route::get('v1/show-card-members/{id}', 'CardController@showCardMembers');
        Route::POST('v1/card/comment', 'CardController@commentAttach');
        Route::GET('v1/card/checklist/{id}', 'CardController@getChecklist');
        Route::POST('v1/card/checklist', 'CardController@createChecklist');
        Route::POST('v1/card/checklistDetail', 'CardController@createChecklistDetail');
        Route::PUT('v1/card/checklist/{id}', 'CardController@editChecklist');
        Route::PUT('v1/card/checklistDetail/{id}', 'CardController@editChecklistDetail');

        Route::POST('v1/createboard','BoardController@createboard');
        Route::POST('v1/board/addtablelist','BoardController@addTableList');
        Route::GET('v1/board/getAllDataBoard/{id}','BoardController@getAllDataBoard');
        Route::GET('v1/board/getCardList{id}','BoardController@getCardList');
        Route::GET('v1/board/getBoardList{id}','BoardController@getBoardList');

});
  Route::post('/board/show-board', [
    'uses' => 'ShowDataBoardController@showBoard',
    'as' => 'show-board'
]);
        Route::PUT('v1/card/moveList/{id}', 'CardController@moveList');
Route::GET('v1/board/activity/{id}', 'BoardController@activityBoard');
Route::GET('v1/board/activity2/{id}', 'BoardController@activityBoard2');
 Route::GET('v1/listteam/{id}', 'TeamController@show');
 Route::POST('v1/board', 'BoardController@createboard');
 Route::GET('v1/show-board/{id}','BoardController@getAllDataBoard');
 Route::POST('v1/team', 'TeamController@store');
 Route::get('v1/listboard/{id}', 'BoardController@getListBoard');
 Route::post('v1/board-fav/{id}', 'BoardController@getBoardFavorite');
 Route::POST('v1/add-board-fav','BoardController@storeBoardFavorite');
Route::POST('v1/del-board-fav/{id}','BoardController@destroyBoardFavorite');
 Route::POST('v1/team/InviteMember', 'TeamController@inviteMember');
Route::POST('v1/showUser', 'TeamController@showUser');
Route::POST('v1/searchInviteMember', 'TeamController@searchInviteMember');
Route::GET('v1/team/showMember/{id}', 'TeamController@showMember');
Route::GET('image/{person}', 'UserController@displayFoto');
Route::GET('fromlist/{id}','BoardController@getFromList');
Route::GET('tolist/{id}','BoardController@getToList');

Route::POST('v1/board/add-list','BoardController@addTableList'); // nioni add list
Route::post('v1/create-board','BoardController@createBoard'); // nioni create board

//card
Route::post('v1/create-card', 'CardController@store'); // nioni create card
Route::get('v1/show-card/{id}', 'CardController@show'); // nioni show card
Route::delete('v1/soft-del-card/{id}', 'CardController@softdestroy');
Route::get('v1/show-del-card', 'CardController@destroy');

Route::delete('v1/soft-del-list/{id}', 'ListController@softdestroy');
Route::get('v1/show-del-list', 'ListController@destroy');
Route::get('v1/del-list/{id}', 'ListController@delete');
Route::get('v1/userProggres/{id}','CardController@userProgressDay');
Route::get('v1/deleteTeam/{id}','TeamController@destroy');
Route::get('v1/deleteBoard/{id}','BoardController@destroy');
Route::get('v1/getBoardTeam/{id}','BoardController@getBoardTeam');
Route::post('v1/leaveTeam','TeamController@leaveTeam');