<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Step4;
use App\Diagnoses;

class StepFourController extends Controller
{
	public function search(Request $request){
		$data = array();
		try{

			$page_limit = $request->input('page_limit');
			$page_number = $request->input('page_number');
			if(empty($page_number) || empty($page_limit)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
            	$type_3_diagnosis_id = $request->input('type_3_diagnosis_id');
            	$type_12_intent_diagnosis_id = $request->input('type_12_intent_diagnosis_id');
				$data = Step4::getListStep4($type_3_diagnosis_id,$type_12_intent_diagnosis_id,$page_limit,$page_number);
				$success = true;
				$error = null;
            }
		}catch(\Illuminate\Database\QueryException $ex) {
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}catch(\Illuminate\Exception $ex){
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}

		return $this->doResponse($success,$data,$error);
	}

	public function detail(Request $request){
		$data = array();
		try{
			$step4_id = $request->input('step4_id');
			if(empty($step4_id)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$data = Step4::getDetailStep4($step4_id);
				$success = true;
				$error = null;
            }
		}catch(\Illuminate\Database\QueryException $ex) {
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}catch(\Illuminate\Exception $ex){
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}

		return $this->doResponse($success,$data,$error);
	}

	public function edit(Request $request){
		$data = array();
		try{
			$step4_id = $request->input('step4_id');
			$type_3_advice = $request->input('type_3_advice');
			$type_12_advice = $request->input('type_12_advice');
			if(empty($step4_id)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$data = Step4::editStep4($step4_id,$type_3_advice,$type_12_advice);
				if($data){
					$success = true;
					$error = null;
				}else{
					$success = false;
					$error = \Lang::get('common_message.error.EDIT_FAIL');
				}
            }
		}catch(\Illuminate\Database\QueryException $ex) {
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}catch(\Illuminate\Exception $ex){
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}

		return $this->doResponse($success,$data,$error);
	}

}
