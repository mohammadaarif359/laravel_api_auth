<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Authcode;
use Illuminate\Support\Facades\{DB,Auth,Validator};

class UserController extends Controller
{
    use Authcode;
	public function userProfile() {
		$user = Auth::user();
		$data = $user;
		return $this->result(true, $data, [], 'User profile get successfully');
	}
	public function userUpdate(Request $request) {
		$user = Auth::user();
		$request_data = $request->all();
		$validator_data = Validator::make($request_data, [
            'name'    => 'required|regex:/^[\pL\s]+$/u',
            'email'   => 'required|email|unique:users,email,'.$user->id,
            'mobile'  => 'required|numeric|digits_between:8,12',
        ]);
		if ($validator_data->fails()) {
            return $this->result(false, [], $validator_data->errors()->messages(), 'validation error!', 400);
        } else {
			$user->name     = $request_data['name'];
			$user->email    = trim($request_data['email']);
			$user->mobile   = $request_data['mobile'];
			$user->save();
			return $this->result(true, [], [], 'User updated successfully');
		}
	}
}
