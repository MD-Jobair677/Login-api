<?php

namespace App\Http\Controllers\api;

// use auth;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Subtodo;
use App\Models\Todo;
use App\Models\User;
use function Laravel\Prompts\error;
// use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Promise\Create;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

      ], 200);
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


    if ($validator->fails()) {
      return response()->json([
        'status' => false,

        'message' => $validator->errors(),



      ], 200);
    } else {


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
          'userData' => $user,
        ], 200);
      } else {

        return response()->json([
          'status' => false,
          'message' => 'rong email or password',

        ], 400);
      };
    }
  }

  // GET ALL USER LFUNCTION
  function alluser()
  {

    $user = User::get();
    return response()->json([
      'status' => true,
      'message' => 'all user',
      'user' => $user,
    ]);
  }



  // STORE TODO

  function Store(Request $request)
  {
    // Log::info('store:'. $request->all());


    $validator = Validator::make($request->all(), [

      'name'    => 'required',
      'description' => 'required|min:6',
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

    ]);


    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'message' => $validator->errors(),

      ], 200);
    } else {



      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('asset/TodoImage', $imageName, 'public');

        $imgeUrl = asset('storage/asset/TodoImage/' . $imageName);
        $todo = new Todo();
        $todo->name = $request->name;
        $todo->description = $request->description;
        $todo->image = $imgeUrl;
        $todo->user_id = '1';

        $todo->save();
      } else {


        $todo = new Todo();
        $todo->name = $request->name;
        $todo->description = $request->description;
        $todo->user_id = '1';

        $todo->save();
      }




      $user = auth::user();
      $todos = Todo::where('user_id', 1)->get();

      return response()->json([
        'status' => true,
        'message' => 'store successfully',
        'user' => $user,
        'todos' => $todos,
      ], 200);
    }
  }

  // SUB TODO'

  function subStore(Request $request)
  {

    $validator = Validator::make($request->all(), [

      'title'    => 'required',
      'todo_id'    => 'required',
      'description' => 'required|min:6'

    ]);


    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'message' => $validator->errors(),

      ], 200);
    } else {








      $subtodo = new Subtodo();

      $subtodo->todo_id = $request->todo_id;
      $subtodo->title = $request->title;
      $subtodo->description = $request->description;
      $subtodo->user_id = 1;

      $subtodo->save();
      $user = auth::user();
      $subtodos = Subtodo::with('todo')->where('user_id', $user->id)->get();


      return response()->json([
        'status' => true,
        'message' => 'store successfully',
        'user' => $user,
        'subtodos' => $subtodos,
      ], 200);
    }
  }





  // EDITE TODO

  function update(Request $request, $id)
  {



    $validator = Validator::make($request->all(), [

      'name'    => 'required',
      'description' => 'required|min:6',
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => false,
        'message' => $validator->errors(),

      ], 200);
    } else {
      $findtodo = Todo::find($id);

      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('asset/TodoImage', $imageName, 'public');

        $imgeUrl = asset('storage/asset/TodoImage/' . $imageName);



        // $findtodo = new Todo();
        $findtodo->name = $request->name;
        $findtodo->description = $request->description;
        $findtodo->image = $imgeUrl;
        $findtodo->user_id = 1;


        $findtodo->save();
      } else {
        $findtodo->name = $request->name;
        $findtodo->description = $request->description;
        // $findtodo->image = $request->imageName;
        $findtodo->user_id = 1;
      }



      return response()->json([
        'status' => true,
        'message' => 'update successfully',

        'findtodo' => $findtodo,
      ], 200);
    }
  }

  //  DELETE TODO

  function deleteTodo(Request $request,  $id)
  {
    $todo = Todo::find($id);



    if ($todo == !null) {
      $todo->delete();
      return response()->json([
        'staus' => true,
        'message' => 'delete successfully',



      ]);
    } else {
      return response()->json([

        'satus' => false,
        'message' => 'data not pound',



      ]);
    }
  }





  function index()
  {
    $user = auth::user();
    $todos = Todo::orderBy('id', 'desc')->get();

    return response()->json([
      'status' => true,
      'message' => 'All todos',
      'todos' => $todos,
      'user' => $user,
    ], 200);
  }

  function singleTodo($id)
  {
    $user = auth::user();
    $todos = Todo::find($id);

    return response()->json([
      'status' => true,
      'message' => 'All todos',
      'todos' => $todos,
      'user' => $user,
    ], 200);
  }
}
