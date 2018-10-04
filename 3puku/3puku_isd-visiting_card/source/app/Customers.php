<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customers extends Model
{
    protected $table = 'customers';

    public static function search($store_id, $name, $date_from, $date_to, $page, $number_per_page)
    {
        $result = array();
        $query = Customers::query();
        $query = $query->select('customers.id', 'customers.last_name', 'customers.first_name', 'customers.last_name_kana', 'customers.first_name_kana',
                                'customers.customer_status','customers.note2','customers.note3','customers.created_at',
                                'staffs.last_name as s_last_name', 'staffs.first_name as s_first_name')
                ->leftjoin('staffs',function($q){
                    $q->on('staffs.id','=','customers.staff_id');
                    $q->where('staffs.is_deleted','=', 0);
                })
                ->where('customers.store_id', $store_id)
                ->where('customers.is_deleted', 0);

        if (!empty($name)) $query = $query->where(function ($q) use ($name) {
            $q->where('customers.last_name_kana', 'like', '%' . $name . '%');
            $ary_tmp_word = preg_split('/[\s]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
            $count_tmp_word = count($ary_tmp_word);
            for ($i = 0; $i < $count_tmp_word; $i++) {
                $q = $q->orWhere('customers.first_name_kana', 'like', '%' . $ary_tmp_word[$i] . '%')
                    ->orWhere('customers.last_name', 'like', '%' . $ary_tmp_word[$i] . '%')
                    ->orWhere('customers.first_name', 'like', '%' . $ary_tmp_word[$i] . '%');
            }
        });
        if (!empty($date_from)) $query = $query->where('customers.created_at', '>=', new \DateTime($date_from));
        if (!empty($date_to)) $query = $query->where('customers.created_at', '<=', new \DateTime($date_to. ' 23:59:59'));

        $query->orderBy('customers.created_at','DESC');
        $result['total'] = $query->get()->count();

        if (!empty($page)) $query = $query->offset((intval($page) - 1) * $number_per_page);
        $query = $query->limit($number_per_page);

        $result['data'] = $query->get();

        return $result;
    }

    public static function search_all($name, $date_from, $date_to, $page, $number_per_page)
    {
        $result = array();
        $query = Customers::query();
        $query = $query->select('customers.id', 'customers.last_name', 'customers.first_name', 'customers.last_name_kana', 'customers.first_name_kana',
                                'customers.customer_status','customers.note2','customers.note3','customers.created_at',
                                'staffs.last_name as s_last_name', 'staffs.first_name as s_first_name','stores.store_name', 'stores.store_name_kana')
                ->leftjoin('staffs',function($q){
                    $q->on('staffs.id','=','customers.staff_id');
                    $q->where('staffs.is_deleted','=', 0);
                })
                ->leftjoin('stores',function($q){
                    $q->on('stores.id','=','customers.store_id');
                    $q->where('stores.is_deleted','=', 0);
                })
                ->where('customers.is_deleted', 0);

        if (!empty($name)) $query = $query->where(function ($q) use ($name) {
            $q->where('customers.last_name_kana', 'like', '%' . $name . '%');
            $ary_tmp_word = preg_split('/[\s]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
            $count_tmp_word = count($ary_tmp_word);
            for ($i = 0; $i < $count_tmp_word; $i++) {
                $q = $q->orWhere('customers.first_name_kana', 'like', '%' . $ary_tmp_word[$i] . '%')
                    ->orWhere('customers.last_name', 'like', '%' . $ary_tmp_word[$i] . '%')
                    ->orWhere('customers.first_name', 'like', '%' . $ary_tmp_word[$i] . '%');
            }
        });
        if (!empty($date_from)) $query = $query->where('customers.created_at', '>=', new \DateTime($date_from));
        if (!empty($date_to)) $query = $query->where('customers.created_at', '<=', new \DateTime($date_to. ' 23:59:59'));

        $query->orderBy('customers.created_at','DESC');
        $result['total'] = $query->get()->count();

        if (!empty($page)) $query = $query->offset((intval($page) - 1) * $number_per_page);
        $query = $query->limit($number_per_page);

        $result['data'] = $query->get();

        return $result;
    }

    /*
     * get customer info and diagnoses
     */
    public static function getDetailDiagnoses($id)
    {
        $result;
        $query = Customers::query();
        $query = $query->select('customers.id', 'customers.last_name', 'customers.first_name', 'customers.last_name_kana',
            'customers.first_name_kana', 'customers.created_at', 'customers.store_id', 'customers.birthday','customers.note1',
            'customers.customer_status', 'customers.note2', 'customers.note3', 'diagnoses.user_type', 'diagnoses.type_3_diagnosis', 'diagnoses.type_12_diagnosis', 'diagnoses.type_12_intent_diagnosis', 'diagnoses.type_12_surface_diagnosis', 'diagnoses.type_60_diagnosis', 'staffs.last_name as s_last_name', 'staffs.first_name as s_first_name','stores.store_name', 'stores.store_name_kana');
        $query = $query->leftjoin('diagnoses', 'customers.id', '=', 'diagnoses.user_id')
            ->leftjoin('staffs',function($q){
                $q->on('staffs.id','=','customers.staff_id');
                $q->where('staffs.is_deleted','=', 0);
            })
            ->leftjoin('stores',function($q){
                $q->on('stores.id','=','customers.store_id');
                $q->where('stores.is_deleted','=', 0);
            })
            ->where('customers.id', $id)
            ->where('diagnoses.user_type', 1)
            ->where('customers.is_deleted', 0)
            ->where('diagnoses.is_deleted', 0);
        $result = $query->first();
        return $result;
    }

    /*
     * add new customer
     */
    public static function add($last_name, $first_name, $last_name_kana, $first_name_kana, $birthday, $store_id, $note1)
    {
        $note2 = '1.情報は有用であったか？○△×'."\n\n\n".'2. 1が△×の場合の理由'."\n\n\n".'3. 情報と合致した点'."\n\n\n".'4.情報と合致しなかった点'."\n\n\n".'5.ツールへの要望'."\n\n\n";
        $customer = new Customers;
        $customer->last_name = $last_name;
        $customer->first_name = $first_name;
        $customer->last_name_kana = $last_name_kana;
        $customer->first_name_kana = $first_name_kana;
        $customer->birthday = $birthday;
        $customer->store_id = $store_id;
        $customer->note1 = $note1;
        $customer->note2 = $note2;
        $customer->is_deleted = 0;
        $customer->save();
        return $customer->id;
    }

    /*
     * update customer profile
     */
    public static function updateCustomer($id, $last_name, $first_name, $last_name_kana, $first_name_kana, $birthday, $note1, $note2, $note3, $created_at)
    {
        $customer = Customers::find($id);
        $customer->last_name = $last_name;
        $customer->first_name = $first_name;
        $customer->last_name_kana = $last_name_kana;
        $customer->first_name_kana = $first_name_kana;
        $customer->birthday = $birthday;
        $customer->note1 = $note1;
		$customer->note2 = $note2;
        $customer->note3 = $note3; 
		$customer->created_at = $created_at; 
        return $customer->save();
    }

    public static function updateCustomer2($id,$store_id,$last_name, $first_name, $last_name_kana, $first_name_kana, $birthday)
    {
        $customer = Customers::find($id);
		$customer->store_id = $store_id;
        $customer->last_name = $last_name;
        $customer->first_name = $first_name;
        $customer->last_name_kana = $last_name_kana;
        $customer->first_name_kana = $first_name_kana;
        $customer->birthday = $birthday;
        return $customer->save();
    }
	
    public static function del($id)
    {
        $customer = Customers::find($id);
        $customer->is_deleted = 1;
        return $customer->save();
    }
    /*
     * update customer profile
     */
    public static function detail($id)
    {
        $customer = Customers::find($id);
        return $customer;
    }

    public static function detail_by_admin($id)
    {
        $result;
        $query = Customers::query();
        $query = $query->select('customers.id', 'customers.last_name', 'customers.first_name', 'customers.last_name_kana',
            'customers.first_name_kana', 'customers.created_at', 'customers.store_id', 'customers.birthday','customers.note1',
            'customers.customer_status', 'customers.note2', 'customers.note3','stores.store_name', 'stores.store_name_kana');
        $query = $query->leftjoin('stores',function($q){
                    $q->on('stores.id','=','customers.store_id');
                    $q->where('stores.is_deleted','=', 0);
                })
            ->where('customers.id', $id)
            ->where('customers.is_deleted', 0);
        $result = $query->first();
        return $result;
    }
    /*
     * update status
     */
    public static function update_status($id, $status){
        $customer = Customers::find($id);
        $customer->customer_status = $status;
        return $customer->save();
    }
    /*
     * update staff
     */
    public static function update_staff($id, $staff_id){
        $customer = Customers::find($id);
        $customer->staff_id = $staff_id;
        return $customer->save();
    }

    public static function check_customer_id($customer_id){
        $customer = Customers::query()
                    ->where('id',$customer_id)
                    ->first();
        return $customer;
    }
}
