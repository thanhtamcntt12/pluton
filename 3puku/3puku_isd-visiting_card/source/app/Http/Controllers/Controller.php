<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
	
	protected function doResponse($success = false, $data = null, $error = null) {
		return response()->json([
				'success' => $success,
				'data' => $data,
				'error' => $error
		]);
	}
	
	protected function doResponse2($customer_id = 0, $result = -1, $message = "error") {
		return response()->json([
				'customer_id' => $customer_id,
				'results' => [
					'result' => $result,
					"message"=> $message
				]
		]);
	}
	protected function doResponse3($result = -1, $message = "error") {
		return response()->json([
				'results' => [
					'result' => $result,
					"message"=> $message
				]
		]);
	}
}
