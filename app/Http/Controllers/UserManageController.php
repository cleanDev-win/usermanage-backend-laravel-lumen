<?php

namespace App\Http\Controllers;

use App\User;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
class UserManageController extends Controller
{

    public function addNewUser(Request $request) {
        $user = new User;
        $user['firstname'] = $request->firstname;
        $user['lastname'] = $request->lastname;
        $user['email'] = $request->email;
        $user['password'] = $request->password;
        $user['status'] = 1;
        $user['admin'] = 0;
        if($user->save()) {
            return json_encode("success");
        } else {
            return json_encode("failed");
        }
        
    }

    public function updateUser(Request $request) {
        $id = $request->id;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        
        $user = User::find($id);
        $user['firstname'] = $firstname;
        $user['lastname'] = $lastname;
        $user['email'] = $email;
        if($user->save()){
            return new JsonResponse( ['status_code' => 200]);
        }
        
    }

    public function deleteUser(Request $request) {
        $id = $request[0];
        User::destroy($id);
        return new JsonResponse( ['status_code' => 200, 'data' => 'Deleted successfully.']);
    }

    public function getUsers() {
        $data = User::all();
        try
        {
            return new JsonResponse( ['status_code' => 200, 'data' => $data]);
        }
        catch(ValidationException $e)
        {
            return $e->getResponse();
        }
    }

    public function updatePassword(Request $request) {
        $id = $request->id;
        $password = $request->password;
        $user = User::find($id);
        $user['password'] = $password;
        if($user->save()){
            return new JsonResponse( ['status_code' => 200]);
        }
        
    }

    public function activeUser(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $user = User::find($id);
        $user['status'] = $status;
        if($user->save()) {
            return new JsonResponse(['status_code' => 200]);
        }
    }
    
}