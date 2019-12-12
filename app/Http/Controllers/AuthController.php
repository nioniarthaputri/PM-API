<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Mail;
Use Auth;
Use App\Biodata;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
class AuthController extends Controller
{

// for LOGIN -- azriel & profile
    public $successStatus = 200;

    public function logIn(Request $request){

        // return [request('var_email'), request('var_password')];
        if(Auth::attempt(['email' => request('var_email'), 'password' => request('var_password')])){
                            
            $user = Auth::user();
            $id =Auth::user()->id;
            $token =  $user->createToken('nApp')->accessToken;

            $coba = Biodata::where('user_id', $id)->get();

            
            foreach ($coba as $key) {
                
            }

            // $success["user"]["email"] = $user["email"];
            return response()->json(
                [

                'data' => $key,
                'status' => true,
                'token' => $token

                ],
                $this->successStatus);
            
             $cek = $this->Login_model->Getuser($username,$password);
             $hasil = $cek->result_array();

            
        
        }

        else{
            return response()->json([
                'error'=>'Unauthorised',
                'status' => false
            ]);
        }

    }

      public function details()
    {
       $id =Auth::user()->id;
       $user =Biodata::where('user_id', $id)->get();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function logout()
    {
        Auth::user()->AauthAcessToken()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    
 
}