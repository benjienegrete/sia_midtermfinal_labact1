<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use DB;
Class StudentController extends Controller {
use ApiResponser;
private $request;
public function __construct(Request $request) {
$this->request = $request;
}
public function getUsers(){ //list user
//$users = User::all();
$users = DB::connection('mysql')
->select("Select * from tbl_user");
return $this->successResponse($users);
//return response()->json($users, 200);
}
/**
* Return the list of users
* @return Illuminate\Http\Response
*/

public function index() 
{
$users = User::all();
return $this->successResponse($users);
}
public function add(Request $request ){ //add
$rules = [
'username' => 'required|max:20',
'password' => 'required|max:20',
'gender' => 'required|in:Male,Female',
];
$this->validate($request,$rules);
$user = User::create($request->all());
return $this->successResponse($user,Response::HTTP_CREATED);
}


public function show($id) //show id
{
//$user = User::findOrFail($id);
$user = User::where('id',$id)->first();
if($user){
return $this->successResponse($user);
}
{
return $this->errorResponse('user ID Does Not Exists', Response::HTTP_NOT_FOUND);
}
}
/**
* Update an existing author
* @return Illuminate\Http\Response
*/
public function update(Request $request,$id) //update
{
$rules = [
'username' => 'max:20', 
'password' => 'max:20',
'gender' => 'in:Male,Female',
];
$this->validate($request, $rules);
$user = User::findOrFail($id);
$user->fill($request->all());

// if no changes happen
if ($user->isClean()) {
return $this->errorResponse('At least one value must
change', Response::HTTP_UNPROCESSABLE_ENTITY);
}
$user->save();
return $this->successResponse($user);

}
/**
* Remove an existing user
* @return Illuminate\Http\Response
*/
public function delete($id){
$user = User::where('id',$id)->delete();
if($user){
return $this->successResponse($user);
}
{
return $this->errorResponse('the User ID not found', Response::HTTP_NOT_FOUND);
}
}
}