<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\{Auth,Validator};
use App\Traits\Authcode;
use Hash;
use Laravel\Passport\Passport;
use Carbon\Carbon;
use DB;

class AuthController extends Controller
{
    use Authcode;
	public function signup(Request $request) {
		$request_data = $request->all();
		$validator_data = Validator::make($request_data, [
            'name'    => 'required|regex:/^[\pL\s]+$/u',
            'email'   => 'required|email|unique:users,email',
            'password'=> 'required|min:6',
            'mobile'  => 'required|numeric|digits_between:8,12',
        ]);
		$file_name = null;
		if ($validator_data->fails()) {
            return $this->result(false, [], $validator_data->errors()->messages(), 'validation error!', 400);
        } else {
			$user = new User;
			$user->name     = $request_data['name'];
			$user->email    = trim($request_data['email']);
			$user->mobile   = $request_data['mobile'];
			$user->password = bcrypt($request_data['password']);
			$user->status   = 1;
			$user->save();
			return $this->result(true, [], [], 'User register successfully');
		}	
	}
	public function login(Request $request) {
		$request_data = $request->all();
		$validator_data = Validator::make($request_data, [
            'email'   => 'required|email',
            'password'=> 'required',
        ]);
		if ($validator_data->fails()) {
            return $this->result(false, [], $validator_data->errors()->messages(), 'validation error!', 400);
        } else {
			if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();
				if(!$user->status) {
				  return $this->result(false, [], [], 'Your account is not activated');
				}
            } else {
				return $this->result(false, [], [], 'Username or password may be incorrect.');
			}
			$data['id'] = $user['id'];
			$data['name'] = $user['name'];
			$data['email']  = $user['email'];
			$data['mobile'] = $user['mobile'];
			$data['status'] = $user['status'];
			$data['token'] = $user->createToken('mobile')->accessToken;
			return $this->result(true, $data, [], 'User logged in successfully');
		}	
	}
	public function logout(Request $request) {
		$user = Auth::user();
		$user->token()->revoke();
        $user->token()->delete();
        return $this->result(true, [], [], 'Logout successfully');
    }
}
