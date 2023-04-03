<?php namespace App\Traits;

use Log;
use Auth;
use App\Models\User;
use Mail;
use Exception;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\{File, Storage};
use DB;

trait AuthCode
{
    public function result($status = false, $data = [], $errors = [], $msg = '', $httpResponse = 200) {
        if (empty($data) && empty($errors)) {
            return response()->json(['status' => $status, 'message' => $msg, 'data' => []], $httpResponse);
        } elseif (empty($data) && !empty($errors)) {
            return response()->json(
                ['status' => $status, 'message' => $msg, 'data' => [], 'errors' => $errors],
                $httpResponse
            );
        } elseif (!empty($data) && empty($errors)) {
            return response()->json(['status' => $status, 'message' => $msg, 'data' => $data], $httpResponse);
        } else {
            return response()->json(
                ['status' => $status, 'message' => $msg, 'data' => $data, 'errors' => $errors],
                $httpResponse
            );
        }
    }
}
?>