<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Admin;
use Mail;

class AdminController extends Controller
{
	public function login(Request $request) {
		$data = null;
		if(!empty($request->input('email')) && !empty($request->input('password'))){
			if(Auth::guard('admin-web')->once(['email' => $request->input('email'), 'password' => $request->input('password'), 'is_deleted' => 0])){
				$info_admin = array();
				$api_token = str_random(60);

				$admin = Auth::guard('admin-web')->user();
				$admin->api_token = $api_token;
				$admin->save();
				$info_admin['id'] = $admin->id;
				$info_admin['api_token'] = $admin->api_token;
				$info_admin['last_name'] = $admin->last_name;
				$info_admin['first_name'] = $admin->first_name;

				$success = true;
				$data = $info_admin;
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
			if(!empty($request->input('admin_id'))){
				$info_admin = Admin::find($request->input('admin_id'));
				if($info_admin){
					$info_admin->api_token = null;
					$info_admin->save();
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

	public function send_email(Request $request){
		try{
			if(!empty($request->input('email'))){
				$email = $request->input('email');
				$info_admin = Admin::getAdminByEmail($email);
				if($info_admin){

					if(!empty($info_admin[0]->api_token)){
						$api_token = $info_admin[0]->api_token;
					}else{
						$api_token = str_random(60);
						$update_admin = Admin::find($info_admin[0]->id);
						$update_admin->api_token = $api_token;
						$update_admin->save();
					}

                    $link = '/admin/forgot-password/new-password/'.$info_admin[0]->id.'_'.$api_token;

				    Mail::send('emails.admin_reset_password', ['root_url'=>$request->getSchemeAndHttpHost(),'link' => $link], function ($message) use ($email) {
				        $message->to($email)->subject('【賃貸接客アドバイスシステム管理】パスワード再設定');
				    });

					$success = true;
					$error = null;
				}else{
					$success = false;
					$error = \Lang::get('common_message.error.EMAIL_NOT_FOUND');
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
        }catch(\Exception $ex){
            $success = false;
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
            $error = $ex->getMessage();
		}

		return $this->doResponse($success,null,$error);
	}

	public function reset_password(Request $request){
		try{
			$id = $request->input('id');
			$api_token = $request->input('token');
			$new_password = $request->input('password');

			if(!empty($id) && !empty($api_token) && !empty($new_password)){
				$check_admin = Admin::checkAdminByApiToken($id,$api_token);
				if($check_admin){
					$info_admin = Admin::find($id);
					$info_admin->password = bcrypt($new_password);
					$info_admin->save();
					$success = true;
					$error = null;
				}else{
					$success = false;
					$error = \Lang::get('common_message.error.ACCOUNT_DOES_NOT_EXIST!');
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
				$data = Admin::getListAdmin($name,$page_limit,$page_number);
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
			$admin_id = $request->input('admin_id');
			if(empty($admin_id)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$data = Admin::getAdminDetail($admin_id);
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
			$email = $request->input('email');
			$password = $request->input('password');
			$another_user_id = $request->input('another_user_id');
			$note = $request->input('note');
			if(empty($email) || empty($password)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
            	$check_email = Admin::checkEmail($email);
            	if($check_email){
            		$success = false;
					$error = \Lang::get('common_message.error.ACCOUNT_ALREADY_EXISTS');
            	}else{
            		$data = Admin::addAdmin($last_name,$first_name,$last_name_kana,$first_name_kana,$email,$password,$another_user_id,$note);
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
			$admin_id = $request->input('admin_id');
			$last_name = $request->input('last_name');
			$first_name = $request->input('first_name');
			$last_name_kana = $request->input('last_name_kana');
			$first_name_kana = $request->input('first_name_kana');
			$email = $request->input('email');
			$another_user_id = $request->input('another_user_id');
			$note = $request->input('note');
			if(empty($email) || empty($admin_id) || empty($last_name) || empty($first_name) || empty($last_name_kana) || empty($first_name_kana)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
	            $check_email = Admin::checkEmailEdit($admin_id,$email);
            	if($check_email){
            		$success = false;
					$error = \Lang::get('common_message.error.ACCOUNT_ALREADY_EXISTS');
            	}else{
            		$data = Admin::editAdmin($admin_id,$last_name,$first_name,$last_name_kana,$first_name_kana,$email,$another_user_id,$note);
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
			$admin_id = $request->input('admin_id');
			if(empty($admin_id)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$data = Admin::deleteAdmin($admin_id);
				if($data) {
					$success = true;
					$error = null;
				}else{
					$success = false;
					$error = "Delete Admin fail!";
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
}
