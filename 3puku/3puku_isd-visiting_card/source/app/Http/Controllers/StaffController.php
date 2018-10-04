<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Staff;
use Mail;
use App\Diagnoses;

class StaffController extends Controller
{
	public function login(Request $request) {
		$data = null;
		if(!empty($request->input('email')) && !empty($request->input('password'))){
			if(Auth::guard('staff-web')->once(['email' => $request->input('email'), 'password' => $request->input('password'), 'is_deleted' => 0])){
				$info_staffs = array();
				$api_token = str_random(60);

				$staff = Auth::guard('staff-web')->user();
				$staff->api_token = $api_token;
				$staff->save();
				$info_staffs['id'] = $staff->id;
				$info_staffs['api_token'] = $staff->api_token;
				$info_staffs['store_id'] = $staff->store_id;
				$info_staffs['last_name'] = $staff->last_name;
				$info_staffs['first_name'] = $staff->first_name;

				$success = true;
				$data = $info_staffs;
				$error = null;

			}else{
				$success = false;
				$error = \Lang::get('common_message.error.LOGIN_FAIL');
			}
		}else{
			$success = false;
			$error = \Lang::get('common_message.error.MISS_PARAM');
		}

		return $this->doResponse($success,$data,$error);
	}

	public function logout(Request $request){
		try{
			if(!empty($request->input('staff_id'))){
				$info_staff = Staff::find($request->input('staff_id'));
				if($info_staff){
					$info_staff->api_token = null;
					$info_staff->save();
					Auth::logout();
					$success = true;
					$error = null;
				}else{
					$success = false;
					$error = \Lang::get('common_message.error.LOGOUT_FAIL');
				}
			}else{
				$success = false;
				$error = \Lang::get('common_message.error.MISS_PARAM');
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

		return $this->doResponse($success,null,$error);
	}

	public function index(Request $request){
		$data = null;
		try{

			$page_limit = $request->input('page_limit');
			$page_number = $request->input('page_number');
			if(empty($page_number) || empty($page_limit)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$data = Staff::getListStaff(null,null,$page_limit,$page_number);
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

	public function search(Request $request){
		$data = array();
		try{

			$page_limit = $request->input('page_limit');
			$page_number = $request->input('page_number');
			if(empty($page_number) || empty($page_limit)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
            	$name = $request->input('name');
            	$store_id = $request->input('store_id');
				$data = Staff::getListStaff($name,$store_id,$page_limit,$page_number);
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
			$staff_id = $request->input('staff_id');
			if(empty($staff_id)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$data = Staff::getStaffDetail($staff_id);
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

	public function add(Request $request){
		$data = null;
		try{

			$last_name = $request->input('last_name');
			$first_name = $request->input('first_name');
			$last_name_kana = $request->input('last_name_kana');
			$first_name_kana = $request->input('first_name_kana');
			$birthday = $request->input('birthday');
			$email = $request->input('email');
			$password = $request->input('password');
			$store_id = $request->input('store_id');
			$another_user_id = $request->input('another_user_id');
			$note = $request->input('note');
			if(empty($email) || empty($password)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
            	$check_email = Staff::checkEmail($email);
            	if($check_email){
            		$success = false;
					$error = \Lang::get('common_message.error.ACCOUNT_ALREADY_EXISTS');
            	}else{
            		$data = Staff::addStaff($last_name,$first_name,$last_name_kana,$first_name_kana,$birthday,$email,$password,$store_id,$another_user_id,$note);
	                $this->isdDiagnoses($birthday, $data, false);
					$success = true;
					$error = null;
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

	public function edit(Request $request){
		$data = null;
		try{
			$staff_id = $request->input('staff_id');
			$last_name = $request->input('last_name');
			$first_name = $request->input('first_name');
			$last_name_kana = $request->input('last_name_kana');
			$first_name_kana = $request->input('first_name_kana');
			$birthday = $request->input('birthday');
			$email = $request->input('email');
			$store_id = $request->input('store_id');
			$another_user_id = $request->input('another_user_id');
			$note = $request->input('note');
			if(empty($email) || empty($staff_id) || empty($last_name) || empty($first_name) || empty($last_name_kana) || empty($first_name_kana)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
            	$check_email = Staff::checkEmailEdit($staff_id,$email);
            	if($check_email){
            		$success = false;
					$error = \Lang::get('common_message.error.ACCOUNT_ALREADY_EXISTS');
            	}else{
            		$data = Staff::editStaff($staff_id,$last_name,$first_name,$last_name_kana,$first_name_kana,$birthday,$email,$store_id,$another_user_id,$note);
					$this->isdDiagnoses($birthday, $staff_id, true);
					$success = true;
					$error = null;
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

	public function delete(Request $request){
		try{
			$staff_id = $request->input('staff_id');
			if(empty($staff_id)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$data = Staff::deleteStaff($staff_id);
				if($data) {
					$success = true;
					$error = null;
				}else{
					$success = false;
					$error = "Delete Staff fail!";
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

		return $this->doResponse($success,null,$error);
	}
    /*
     * send email to reset password
     */
    public function send_reset_password_email(Request $request){
        $email = $request->input("email");
        $result = null;
        $success = false;
        $error = '';
        if(empty($email)){
            $error = \Lang::get('common_message.error.MISS_PARAM');
        }else{
            try{
                $staff = Staff::getDetailByEmail($email);
                if($staff){
                    $api_token='';
                    if(empty($staff->api_token)){
                        $api_token = Staff::updateApiToken($staff->id);
                    }else{
                        $api_token = $staff->api_token;
                    }

                    $link = '/staff/forgot-password/new-password/'.$staff->id.'_'.$api_token;

                    $result = Mail::send('emails.staff_reset_password', ['root_url'=>$request->getSchemeAndHttpHost(),'link' => $link], function ($message) use ($email) {
                        $message->to($email)->subject('【賃貸接客アドバイスシステム】パスワード再設定');
                    });
                    $success = true;
                }else{
                    $error = \Lang::get('common_message.error.EMAIL_NOT_FOUND');
                }
            }catch(\Illuminate\Database\QueryException $ex) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
                $error = $ex->getMessage();
            }catch(\Illuminate\Exception $ex){
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
                $error = $ex->getMessage();
            }catch(\Exception $ex){
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
                $error = $ex->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }
    /*
     * reset password by using id and api_token
     */
    public function reset_password(Request $request){
        $id = $request->input("id");
        $api_token = $request->input("token");
        $password = $request->input("password");
        $success = false;
        $result = null;
        $error = '';
        if(empty($id) || empty($api_token) || empty($password)){
            $error = \Lang::get('common_message.error.MISS_PARAM');
        }else{
            try{
                if(Staff::checkExist($id, $api_token)){
                    $result = Staff::updatePassword($id, $password);
                    if($result) $success = true;
                    else {
                        $error = \Lang::get('common_message.error.CANNOT_RESET_PASSWORD');
                    }
                }else{
                    $error = \Lang::get('common_message.error.ACCOUNT_DOES_NOT_EXIST');
                }
            }catch(\Illuminate\Database\QueryException $ex) {
            	\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
            	$error = $ex->getMessage();
            }catch(\Illuminate\Exception $ex){
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
                $error = $ex->getMessage();
            }catch(\Exception $ex){
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
                $error = $ex->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }
    /*
     * ISD diagnoses
     */
    private function isdDiagnoses($birthday, $staff_id, $update)
    {
        $birth = Carbon::createFromFormat('Y-m-d', $birthday);
        $params = 'year1=' . $birth->year . '&month1=' . $birth->month . '&day1=' . $birth->day . '&time1=0';
        $url = $_ENV['ISD_URL'] . $params;
        if (preg_match('#\((.*?)\)#', file_get_contents($url), $match)) {
            $diagnoses = json_decode($match[1], true);
        }
        if ($update) {
            Diagnoses::updateStaff($diagnoses, $staff_id);
        } else if ($diagnoses && $staff_id) {
            Diagnoses::addStaff($diagnoses, $staff_id);
        }
    }
}
