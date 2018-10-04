<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Advisor;
use Mail;

class AdvisorController extends Controller
{
	public function login(Request $request) {
		$data = null;
		if(!empty($request->input('email')) && !empty($request->input('password'))){
			if(Auth::guard('advisor-web')->once(['email' => $request->input('email'), 'password' => $request->input('password'), 'is_deleted' => 0])){
				$info_advisor = array();
				$api_token = str_random(60);

				$advisor = Auth::guard('advisor-web')->user();
				$advisor->api_token = $api_token;
				$advisor->save();
				$info_advisor['id'] = $advisor->id;
				$info_advisor['api_token'] = $advisor->api_token;
				$info_advisor['last_name'] = $advisor->last_name;
				$info_advisor['first_name'] = $advisor->first_name;

				$success = true;
				$data = $info_advisor;
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
			if(!empty($request->input('advisor_id'))){
				$info_advisor = Advisor::find($request->input('advisor_id'));
				if($info_advisor){
					$info_advisor->api_token = null;
					$info_advisor->save();
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
                $advisor = Advisor::getDetailByEmail($email);

                if($advisor){
                    $api_token='';
                    if(empty($advisor->api_token)){
                        $api_token = Advisor::updateApiToken($advisor->id);
                    }else{
                        $api_token = $advisor->api_token;
                    }
                    $link = '/advisor/forgot-password/new-password/'.$advisor->id.'_'.$api_token;
                    $result = Mail::send('emails.advisor_reset_password', ['root_url'=>$request->getSchemeAndHttpHost(),'link' => $link], function ($message) use ($email) {
                        $message->to($email)->subject('【賃貸接客アドバイス管理】パスワード再設定');
                    });
                    $success = true;
                }else{
                    $error = \Lang::get('common_message.error.EMAIL_NOT_FOUND');
                }

            }catch(\Illuminate\Database\QueryException $ex) {
                $error = $ex->getMessage();
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
            }catch(\Illuminate\Exception $ex){
                $error = $ex->getMessage();
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
            }catch(\Exception $ex){
                $error = $ex->getMessage();
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
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
                if(Advisor::checkExist($id, $api_token)){
                    $result = Advisor::updatePassword($id, $password);
                    if($result) $success = true;
                    else {
                        $error = \Lang::get('common_message.error.CANNOT_RESET_PASSWORD');
                    }
                }else{
                    $error = \Lang::get('common_message.error.ACCOUNT_DOES_NOT_EXIST');
                }
            }catch(\Illuminate\Database\QueryException $ex) {
                $error = $ex->getMessage();
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
            }catch(\Illuminate\Exception $ex){
                $error = $ex->getMessage();
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
            }catch(\Exception $ex){
                $error = $ex->getMessage();
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
            }
        }
        return $this->doResponse($success, $result, $error);
    }
}
