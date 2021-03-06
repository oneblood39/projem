<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

   public function __construct(){
     $this->middleware('auth:api');
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    // return User::where('id',  '!=', '1')->paginate(5);
      //  return Users::collection(User::all());
      //  return new UserCollection(User::all());
        return UserResource::collection(User::where('id', '!=', '1')->paginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request,[
        'name' => 'required|string|max:110',
        'email' => 'required|string|email|max:100|unique:users',
        'password' => 'required|string|min:5'
      ]);
       return User::create([
         'name' => $request['name'],
         'email' => $request['email'],
         'type' => $request['type'],
         'bio' => $request['bio'],
         'photo' => $request['photo'],
         'password' => Hash::make($request['password']),
       ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request,[
          'name' => 'required|string|max:110',
          'email' => 'required|string|email|max:100|unique:users,email,'.$user->id,
          'password' => 'sometimes|string|min:5'
        ]);
        $user->update($request->all());
        return ['message' => 'Updated user info.'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['message' => 'User Deleted'];
    }
}
