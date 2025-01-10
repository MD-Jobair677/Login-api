<?php

namespace App\Http\Controllers\api;

// use auth;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use function Laravel\Prompts\error;
use App\Http\Controllers\Controller;
use App\Models\Todo;
// use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class apiController extends Controller
{



  // REGISTER API
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name'     => 'required',
      'email'    => 'required|email|unique:users,email',
      'password' => 'required|min:6'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'  => false,
        'message' => 'Validation failed',
        'errors'  => $validator->errors()
      ], 422);
    } else {




      $user = new User();

      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = hash::make($request->password);
      $user->save();





      $token = $user->createToken('token-name', ['server:update'])->plainTextToken;




      return response()->json([
        'status'  => true,
        'message' => 'Validation passed',
        'token' => $token,

      ], 422);
    }
  }



  // REGIDTER API END



  // LOGIN API START


  function login(Request $request)
  {
    $validator = Validator::make($request->all(), [

      'email'    => 'required|email',
      'password' => 'required|min:6'
    ]);


    if($validator->fails()){
      return response()->json([
        'status' => false,
   
        'message' => $validator->errors() ,


       
      ], 200);


    }else{


      $user = User::where('email', $request->email)->first();
      if (Auth::attempt([
        'email' => $request->email,
        'password' => $request->password
  
      ])) {
  
       
        $token = $user->createToken('api-token')->plainTextToken;
  
        return response()->json([
          'status' => true,
          'message' => 'successfully login',
          'token' => $token,
          'user' => $user,
        ], 200);

      }else{

        return response()->json([
          'status' => false,
          'message' => 'rong email or password',
          
        ], 400);



      };



    }



  }

  // GET ALL USER LFUNCTION
  function alluser(){

  $user =User::get();
  return response()->json([
    'status'=>true,
    'message'=>'all user',
    'user'=> $user,
  ]);


  }



// STORE TODO

function Store(Request $request){

  $validator = Validator::make($request->all(), [

    'name'    => 'required',
    'description' => 'required|min:6'

  ]);

  if($validator->fails()){
    return response()->json([
      'status' => false,
      'message' => $validator->errors() ,

    ], 200);


  }else{
    $todo = new Todo();
    $todo->name = $request->name;
    $todo->description = $request->description;
    $todo->user_id = auth()->user()->id;

    $todo->save();
    
$user = auth()->user();
    $todos = Todo::where('user_id',$user->id)->get();

   return response()->json([
      'status'=>true,
      'message'=> 'store successfully',
      'user'=>$user,
      'todos'=> $todos,
    ],300);

  }




}









}
