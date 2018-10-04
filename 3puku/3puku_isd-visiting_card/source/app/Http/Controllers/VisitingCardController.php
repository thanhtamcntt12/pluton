<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Customers;
use App\VisitingCard;
use Mail;

class VisitingCardController extends Controller
{
    public function add(Request $request){

		\Log::info("[".__METHOD__."][".__LINE__."]"."## start visitingcard add ##");
		\Log::debug("[".__METHOD__."][".__LINE__."]".$request);

		$data = 0;
		$result = -1;
        $message = "error";
        $object = Input::all(); 
        try{
			$store_id = isset($object['store_id']) ?  trim($object['store_id']) : null;
			$customer_id = isset($object['customer_id']) ?  trim($object['customer_id']) : 0;
			$last_name = isset($object['last_name']) ? trim($object['last_name']) : null;
			$first_name = isset($object['first_name']) ? trim($object['first_name']) : null;
			$last_name_kana = isset($object['last_name_kana']) ? trim($object['last_name_kana']) : null;
			$first_name_kana = isset($object['first_name_kana']) ? trim($object['first_name_kana']) : null;
			$birthday = isset($object['birthday']) ? trim($object['birthday']) : $birthday = null;
			
            if(empty($store_id) || empty($birthday) || !isset($customer_id)){
                $message = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$check = false;
                if($customer_id == 0){
					$check = false;
                }else{
                    $check_customerid = Customers::check_customer_id($customer_id);
                    if($check_customerid){
						$check = true;
                    }else{
						$check = false;
                    }
                }
				if($check){
					$data = Customers::updateCustomer2($customer_id, $store_id, $last_name, $first_name, $last_name_kana, $first_name_kana, $birthday);
					$data = $customer_id;
					$result = 0;
					$message = "success";
				}else{
					$data = Customers::add($last_name, $first_name, $last_name_kana, $first_name_kana, $birthday, $store_id, null);
					$result = 0;
					$message = "success";
				}
            }
        }catch(\Illuminate\Database\QueryException $ex) {
			$result = -1;
      		\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$message = $ex->getMessage();
		}catch(\Illuminate\Exception $ex){
			$result = -1;
      		\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$message = $ex->getMessage();
        }
		return $this->doResponse2($data,$result,$message);
    }

    public function addCardInfo(Request $request){

		\Log::info("[".__METHOD__."][".__LINE__."]"."## start visitingcard addCardInfo ##");
		\Log::debug("[".__METHOD__."][".__LINE__."]".$request);

        $data = null;
		$result = -1;
        $message = "error";
        $object = Input::all();
        try{
            $store_id = isset($object['store_id']) ? trim($object['store_id']) : null;
			$customer_id = isset($object['customer_id']) ? trim($object['customer_id']) : null;
			$first_name = isset($object['first_name']) ? trim($object['first_name']) : null;
			$last_name = isset($object['last_name']) ? trim($object['last_name']) : null;
			$last_name_kana = isset($object['last_name_kana']) ? trim($object['last_name_kana']) : null;
			$first_name_kana = isset($object['first_name_kana']) ? trim($object['first_name_kana']) : null;
			$birthday = isset($object['birthday']) ? trim($object['birthday']) : null;
			$postcode = isset($object['postcode']) ? trim($object['postcode']) : null;
			$address1 = isset($object['address1']) ? trim($object['address1']) : null;
			$address2 = isset($object['address2']) ? trim($object['address2']) : null;
            $mobile_phone = isset($object['mobile_phone']) ? trim($object['mobile_phone']) : null;
			$phone = isset($object['phone']) ? trim($object['phone']) : null;
			$fax = isset($object['fax']) ? trim($object['fax']) : null;
			$email = isset($object['email']) ? trim($object['email']) : null;
			$office_name = isset($object['office_name']) ? trim($object['office_name']) : null;
			$office_postcode = isset($object['office_postcode']) ? trim($object['office_postcode']) : null;
			$office_address1 = isset($object['office_address1']) ? trim($object['office_address1']) : null;
			$office_address2 = isset($object['office_address2']) ? trim($object['office_address2']) : null;
			$question1_1_status = isset($object['question1_1_status']) ? trim($object['question1_1_status']) : null;
			$question1_1_detail = isset($object['question1_1_detail']) ? trim($object['question1_1_detail']) : null;
			$question2_1_1_status = isset($object['question2_1_1_status']) ? trim($object['question2_1_1_status']) : null;
			$question2_1_2_status = isset($object['question2_1_2_status']) ? trim($object['question2_1_2_status']) : null;
			$question2_1_3_status = isset($object['question2_1_3_status']) ? trim($object['question2_1_3_status']) : null;
			$question2_1_4_status = isset($object['question2_1_4_status']) ? trim($object['question2_1_4_status']) : null;
			$question2_1_5_status = isset($object['question2_1_5_status']) ? trim($object['question2_1_5_status']) : null;
			$question2_1_6_status = isset($object['question2_1_6_status']) ? trim($object['question2_1_6_status']) : null;
			$question2_1_6_detail = isset($object['question2_1_6_detail']) ? trim($object['question2_1_6_detail']) : null;
			$question2_2_1_status = isset($object['question2_2_1_status']) ? trim($object['question2_2_1_status']) : null;
			$question2_2_2_status = isset($object['question2_2_2_status']) ? trim($object['question2_2_2_status']) : null;
			$question2_2_3_status = isset($object['question2_2_3_status']) ? trim($object['question2_2_3_status']) : null;
			$question2_2_4_status = isset($object['question2_2_4_status']) ? trim($object['question2_2_4_status']) : null;
			$question2_2_5_status = isset($object['question2_2_5_status']) ? trim($object['question2_2_5_status']) : null;
			$question2_2_6_status = isset($object['question2_2_6_status']) ? trim($object['question2_2_6_status']) : null;
			$question2_2_6_detail = isset($object['question2_2_6_detail']) ? trim($object['question2_2_6_detail']) : null;
			$question2_3_1_status = isset($object['question2_3_1_status']) ? trim($object['question2_3_1_status']) : null;
			$question2_3_2_status = isset($object['question2_3_2_status']) ? trim($object['question2_3_2_status']) : null;
			$question2_3_3_status = isset($object['question2_3_3_status']) ? trim($object['question2_3_3_status']) : null;
			$question2_3_4_status = isset($object['question2_3_4_status']) ? trim($object['question2_3_4_status']) : null;
			$question2_3_5_status = isset($object['question2_3_5_status']) ? trim($object['question2_3_5_status']) : null;
			$question2_3_5_detail = isset($object['question2_3_5_detail']) ? trim($object['question2_3_5_detail']) : null;
			$question2_4_1_status = isset($object['question2_4_1_status']) ? trim($object['question2_4_1_status']) : null;
			$question2_4_2_status = isset($object['question2_4_2_status']) ? trim($object['question2_4_2_status']) : null;
			$question2_4_3_status = isset($object['question2_4_3_status']) ? trim($object['question2_4_3_status']) : null;
			$question2_4_4_status = isset($object['question2_4_4_status']) ? trim($object['question2_4_4_status']) : null;
			$question2_4_5_status = isset($object['question2_4_5_status']) ? trim($object['question2_4_5_status']) : null;
			$question2_4_6_status = isset($object['question2_4_6_status']) ? trim($object['question2_4_6_status']) : null;
			$question2_4_6_detail = isset($object['question2_4_6_detail']) ? trim($object['question2_4_6_detail']) : null;
			$question3_1_detail = isset($object['question3_1_detail']) ? trim($object['question3_1_detail']) : null;
			$question3_2_1_status = isset($object['question3_2_1_status']) ? trim($object['question3_2_1_status']) : null;
			$question3_2_2_status = isset($object['question3_2_2_status']) ? trim($object['question3_2_2_status']) : null;
			$question3_2_3_status = isset($object['question3_2_3_status']) ? trim($object['question3_2_3_status']) : null;
			$question4_detail = isset($object['question4_detail']) ? trim($object['question4_detail']) : null;
			$question5_1_status = isset($object['question5_1_status']) ? trim($object['question5_1_status']) : null;
			$question5_2_status = isset($object['question5_2_status']) ? trim($object['question5_2_status']) : null;
			$question5_3_status = isset($object['question5_3_status']) ? trim($object['question5_3_status']) : null;
			$question5_4_status = isset($object['question5_4_status']) ? trim($object['question5_4_status']) : null;
			$question5_4_detail = isset($object['question5_4_detail']) ? trim($object['question5_4_detail']) : null;
			$question5_5_status = isset($object['question5_1_status']) ? trim($object['question5_5_status']) : null;
			$question5_7_status = isset($object['question5_7_status']) ? trim($object['question5_7_status']) : null;
			$question5_8_status = isset($object['question5_8_status']) ? trim($object['question5_8_status']) : null;
			$question5_9_status = isset($object['question5_9_status']) ? trim($object['question5_9_status']) : null;
			$question5_10_status = isset($object['question5_10_status']) ? trim($object['question5_10_status']) : null;
			$question5_11_status = isset($object['question5_11_status']) ? trim($object['question5_11_status']) : null;
			$question5_11_detail = isset($object['question5_11_detail']) ? trim($object['question5_11_detail']) : null;
			$question6_detail = isset($object['question6_detail']) ? trim($object['question6_detail']) : null;
			$question7_detail = isset($object['question7_detail']) ? trim($object['question7_detail']) : null;
			$question8_1_1_date = isset($object['question8_1_1_date']) ? trim($object['question8_1_1_date']) : null;
			$question8_1_2_status = isset($object['question8_1_2_status']) ? trim($object['question8_1_2_status']) : null;
			$question8_2_1_num = isset($object['question8_2_1_num']) ? trim($object['question8_2_1_num']) : null;
			$question8_2_2_num = isset($object['question8_2_2_num']) ? trim($object['question8_2_2_num']) : null;
			$question8_2_3_num = isset($object['question8_2_3_num']) ? trim($object['question8_2_3_num']) : null;
			$question8_2_4_num = isset($object['question8_2_4_num']) ? trim($object['question8_2_4_num']) : null;
			$question8_3_1_num = isset($object['question8_3_1_num']) ? trim($object['question8_3_1_num']) : null;
			$question8_3_1_status = isset($object['question8_3_1_status']) ? trim($object['question8_3_1_status']) : null;
			$question8_3_2_status = isset($object['question8_3_2_status']) ? trim($object['question8_3_2_status']) : null;
			$question8_3_3_status = isset($object['question8_3_3_status']) ? trim($object['question8_3_3_status']) : null;
			$question8_3_4_status = isset($object['question8_3_4_status']) ? trim($object['question8_3_4_status']) : null;
			$question8_3_5_status = isset($object['question8_3_5_status']) ? trim($object['question8_3_5_status']) : null;
			$question8_3_6_status = isset($object['question8_3_6_status']) ? trim($object['question8_3_6_status']) : null;
			$question8_3_7_status = isset($object['question8_3_7_status']) ? trim($object['question8_3_7_status']) : null;
			$question8_3_8_status = isset($object['question8_3_8_status']) ? trim($object['question8_3_8_status']) : null;
			$question8_3_9_status = isset($object['question8_3_9_status']) ? trim($object['question8_3_9_status']) : null;
			$question8_4_1_status = isset($object['question8_4_1_status']) ? trim($object['question8_4_1_status']) : null;
			$question8_4_2_status = isset($object['question8_4_2_status']) ? trim($object['question8_4_2_status']) : null;
			$question8_4_3_status = isset($object['question8_4_3_status']) ? trim($object['question8_4_3_status']) : null;			
			$question8_5_1_status = isset($object['question8_5_1_status']) ? trim($object['question8_5_1_status']) : null;
			$question8_5_2_status = isset($object['question8_5_2_status']) ? trim($object['question8_5_2_status']) : null;
			$question8_6_1_num = isset($object['question8_6_1_num']) ? trim($object['question8_6_1_num']) : null;
			$question8_6_2_num = isset($object['question8_6_2_num']) ? trim($object['question8_6_2_num']) : null;
			$question8_7_1_num = isset($object['question8_7_1_num']) ? trim($object['question8_7_1_num']) : null;
			$question8_7_2_num = isset($object['question8_7_2_num']) ? trim($object['question8_7_2_num']) : null;
			$question8_8_1_status = isset($object['question8_8_1_status']) ? trim($object['question8_8_1_status']) : null;
			$question8_8_2_num = isset($object['question8_8_2_num']) ? trim($object['question8_8_2_num']) : null;
			$question8_8_3_num = isset($object['question8_8_3_num']) ? trim($object['question8_8_3_num']) : null;
			$question8_9_1_status = isset($object['question8_9_1_status']) ? trim($object['question8_9_1_status']) : null;
			$question8_9_2_status = isset($object['question8_9_2_status']) ? trim($object['question8_9_2_status']) : null;
			$question8_9_3_status = isset($object['question8_9_3_status']) ? trim($object['question8_9_3_status']) : null;
			$question8_10_1_detail = isset($object['question8_10_1_detail']) ? trim($object['question8_10_1_detail']) : null;                     

            if(empty($store_id) || empty($customer_id) || empty($birthday)){
                $message = \Lang::get('common_message.error.MISS_PARAM');
            }else{
                $data = VisitingCard::addVisitingCard($store_id,$customer_id,$last_name,$first_name,$last_name_kana,$first_name_kana,$birthday,$postcode,$address1,$address2,$mobile_phone,$phone,$fax,$email,$office_name,$office_postcode,$office_address1,$office_address2,$question1_1_status,$question1_1_detail,$question2_1_1_status,$question2_1_2_status,$question2_1_3_status,$question2_1_4_status,$question2_1_5_status,$question2_1_6_status,$question2_1_6_detail,$question2_2_1_status,$question2_2_2_status,$question2_2_3_status,$question2_2_4_status,$question2_2_5_status,$question2_2_6_status,$question2_2_6_detail,$question2_3_1_status,$question2_3_2_status,$question2_3_3_status,$question2_3_4_status,$question2_3_5_status,$question2_3_5_detail,$question2_4_1_status,$question2_4_2_status,$question2_4_3_status,$question2_4_4_status,$question2_4_5_status,$question2_4_6_status,$question2_4_6_detail,$question3_1_detail,$question3_2_1_status,$question3_2_2_status,$question3_2_3_status,$question4_detail,$question5_1_status,$question5_2_status,$question5_3_status,$question5_4_status,$question5_4_detail,$question5_5_status,$question5_7_status,$question5_8_status,$question5_9_status,$question5_10_status,$question5_11_status,$question5_11_detail,$question6_detail,$question7_detail,$question8_1_1_date,$question8_1_2_status,$question8_2_1_num,$question8_2_2_num,$question8_2_3_num,$question8_2_4_num,$question8_3_1_num,$question8_3_1_status,$question8_3_2_status,$question8_3_3_status,$question8_3_4_status,$question8_3_5_status,$question8_3_6_status,$question8_3_7_status,$question8_3_8_status,$question8_3_9_status,$question8_4_1_status,$question8_4_2_status,$question8_4_3_status,$question8_5_1_status,$question8_5_2_status,$question8_6_1_num,$question8_6_2_num,$question8_7_1_num,$question8_7_2_num,$question8_8_1_status,$question8_8_2_num,$question8_8_3_num,$question8_9_1_status,$question8_9_2_status,$question8_9_3_status,$question8_10_1_detail);
                if($data){
                   $result = 0;
					$message = "success";
                    $data = Mail::send('emails.visitingcard_send_email', ['visiting_cards'=>$request->null,'last_name'=>$last_name,'first_name'=>$first_name,'last_name_kana'=>$last_name_kana,'first_name_kana'=>$first_name_kana], function ($message) use ($email) {
                        $message->to($_ENV['MAIL_ADDRESS_ADD_CARD'])->subject('登録');
                    });
                }else{
                    $message = \Lang::get('common_message.error.EMAIL_NOT_FOUND');
                }
            }
        }catch(\Illuminate\Database\QueryException $ex) {
			$result = -1;
      		\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$message = $ex->getMessage();
		}catch(\Illuminate\Exception $ex){
			$result = -1;
      		\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$message = $ex->getMessage();
        }
        return $this->doResponse3($result,$message);
    }
}
