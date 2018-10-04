<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Customers;
use App\Staff;
use App\Step1;
use App\Step2;
use App\Step3;
use App\Step4;
use App\Step5;
use App\Diagnoses;

class CustomerController extends Controller
{
    /*
     * seach customer by @name and @created_at
     * @return list of customer with id, last_name, first_name, last_name_kana, first_name_kana, created_at
     */
    public function search_by_store(Request $request)
    {
        $store_id = $request->input('store_id');
        $name = $request->input('name');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $pager = $request->input('page');
        $number_per_page = $request->input('number_per_page');
        $success = false;
        $result = null;
        $error = '';
        if (empty($store_id) || empty($number_per_page)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::search($store_id, $name, $date_from, $date_to, $pager, $number_per_page);
                $success = true;
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);

    }

    public function search_by_admin(Request $request)
    {
        $name = $request->input('name');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $pager = $request->input('page');
        $number_per_page = $request->input('number_per_page');
        $success = false;
        $result = null;
        $error = '';
        if (empty($number_per_page)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::search_all($name, $date_from, $date_to, $pager, $number_per_page);
                $success = true;
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);

    }

    public function detail_by_admin(Request $request)
    {
        $id = $request->input('id');
        $success = false;
        $result = null;
        $error = '';
        if (empty($id)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::detail_by_admin($id);
                if ($result) {
                    $success = true;
                }else {
                    $error = \Lang::get('common_message.error.ACCOUNT_DOES_NOT_EXIST');
                }
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }

    /*
     * get customer detail by @id
     * @return customer info, compatibilities and steps
     */
    public function detail_list(Request $request)
    {
        $id = $request->input('id');
        $success = false;
        $result = null;
        $error = '';
        if (empty($id)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $customer = Customers::getDetailDiagnoses($id);
                $result['customer'] = $customer;
                if ($customer) {
                    $result['compatibilities'] = Staff::getCompatibilities($customer['store_id'], $customer['type_3_diagnosis'], $customer['type_12_diagnosis']);
                    $result['step1'] = Step1::getAdvices($customer['type_12_surface_diagnosis']);
                    $result['step2'] = Step2::getAdvices($customer['type_60_diagnosis']);
                    $result['step3'] = Step3::getAdvices($customer['type_60_diagnosis']);
                    $result['step4'] = Step4::getAdvices($customer['type_12_intent_diagnosis']);
                    $result['step5'] = Step5::getAdvices($customer['type_60_diagnosis']);
                    $success = true;
                }else {
                    $error = \Lang::get('common_message.error.ACCOUNT_DOES_NOT_EXIST');
                }
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }

    /*
     * Add new customer
     */
    public function add(Request $request)
    {
        $last_name = $request->input("params.last_name");
        $first_name = $request->input("params.first_name");
        $last_name_kana = $request->input("params.last_name_kana");
        $first_name_kana = $request->input("params.first_name_kana");
        $birthday = $request->input("params.birthday");
        $store_id = $request->input("params.store_id");
        $note1 = $request->input("params.note1");
        $success = false;
        $result = null;
        $error = '';
        if ( empty($birthday) || empty($store_id)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::add($last_name, $first_name, $last_name_kana, $first_name_kana, $birthday, $store_id, $note1);
                $this->isdDiagnoses($birthday, $result, false);
                $success = true;
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }

    /*
     * Update customer profile
     */
    public function update(Request $request)
    {
        $id = $request->input("params.id");
        $last_name = $request->input("params.last_name");
        $first_name = $request->input("params.first_name");
        $last_name_kana = $request->input("params.last_name_kana");
        $first_name_kana = $request->input("params.first_name_kana");
        $birthday = $request->input("params.birthday");
        $note1 = $request->input("params.note1");
		$note2 = $request->input("params.note2");
        $note3 = $request->input("params.note3");
		$created_at = $request->input("params.created_at");
        $success = false;
        $result = null;
        $error = '';
        if (empty($id) || empty($birthday)
        ) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::updateCustomer($id, $last_name, $first_name, $last_name_kana, $first_name_kana, $birthday, $note1, $note2, $note3,$created_at);
                $this->isdDiagnoses($birthday, $id, true);
                $success = true;
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }

    /*
     * ISD diagnoses
     */
    private function isdDiagnoses($birthday, $customer_id, $update)
    {
        $birth = Carbon::createFromFormat('Y-m-d', $birthday);
        $params = 'year1=' . $birth->year . '&month1=' . $birth->month . '&day1=' . $birth->day . '&time1=0';
        $url = $_ENV['ISD_URL'] . $params;
        if (preg_match('#\((.*?)\)#', file_get_contents($url), $match)) {
            $diagnoses = json_decode($match[1], true);
        }
        if ($update) {
            Diagnoses::updateCustomer($diagnoses, $customer_id);
        } else if ($diagnoses && $customer_id) {
            Diagnoses::addCustomer($diagnoses, $customer_id);
        }
    }
    /*
     * Delete customer
     */
    public function del(Request $request)
    {
        $id = $request->input('id');
        $success = false;
        $result = null;
        $error = '';
        if (empty($id)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::del($id);
                $success = true;
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }
    /*
     * customer detail
     */
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $success = false;
        $result = null;
        $error = '';
        if (empty($id)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::detail($id);
                if ($result) {
                    $success = true;
                }else {
                    $error = \Lang::get('common_message.error.ACCOUNT_DOES_NOT_EXIST');
                }
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }
    /*
     * update status
     */
    public function status(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        $result = null;
        $success = false;
        $error = '';
        if (empty($id) || empty($status)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::update_status($id, $status);
                $success = true;
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }
    /*
     * update staff in charge
     */
    public function staff_id(Request $request){
        $id = $request->input('id');
        $staff_id = $request->input('staff_id');
        $result = null;
        $success = false;
        $error = '';
        if (empty($id) || empty($staff_id)) {
            $error = \Lang::get('common_message.error.MISS_PARAM');
        } else {
            try {
                $result = Customers::update_staff($id, $staff_id);
                $success = true;
            } catch (\Illuminate\Database\QueryException $e) {
                \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$e->getMessage());
                $error = $e->getMessage();
            }
        }
        return $this->doResponse($success, $result, $error);
    }
}
