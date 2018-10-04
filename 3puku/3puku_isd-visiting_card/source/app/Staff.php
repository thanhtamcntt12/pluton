<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Staff extends Authenticatable
{
    protected $table = 'staffs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name', 'first_name', 'last_name_kana', 'first_name_kana', 'birthday', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getCompatibilities($store_id, $type_3_diagnosis, $type_12_diagnosis)
    {
        $result;
        $query = Staff::query();
        $result = $query->select('staffs.id', 'staffs.last_name', 'staffs.first_name', 'compatibilities.compatibility')
            ->leftjoin('diagnoses',function($q){
                $q->on('diagnoses.user_id', '=', 'staffs.id')
                    ->where('diagnoses.user_type','=',2);
            })
            ->leftjoin('compatibilities',function($q){
                $q->on('compatibilities.staff_type_3_diagnosis', '=', 'diagnoses.type_3_diagnosis')
               ->on('compatibilities.staff_type_12_diagnosis', '=', 'diagnoses.type_12_diagnosis');
            })
            ->where('staffs.store_id', $store_id)
            ->where('compatibilities.customer_type_3_diagnosis', $type_3_diagnosis)
            ->where('compatibilities.customer_type_12_diagnosis', $type_12_diagnosis)
            ->where('staffs.is_deleted', 0)
            ->where('diagnoses.is_deleted', 0)
            ->where('compatibilities.is_deleted', 0)
            ->orderBy('compatibilities.compatibility','DESC')
            ->get();
        return $result;
    }
    public static function getListStaff($name,$store_id,$page_limit,$page_number){

        $sql = "SELECT stf.id, stf.last_name, stf.first_name, stf.last_name_kana, stf.first_name_kana, str.store_name FROM staffs AS stf INNER JOIN stores AS str ON stf.store_id = str.id AND str.is_deleted=0 WHERE stf.is_deleted = 0";
        $sql_count = "SELECT COUNT(*) AS total_items FROM staffs AS stf INNER JOIN stores AS str ON stf.store_id = str.id AND str.is_deleted=0 WHERE stf.is_deleted = 0";
        if(!empty($name)){
            
            $sql .= " AND (stf.first_name LIKE '%".$name."%' OR stf.last_name LIKE '%".$name."%'  OR stf.first_name_kana LIKE  '%".$name."%'  OR stf.last_name_kana LIKE  '%".$name."%'";
            $sql_count .= " AND (stf.first_name LIKE '%".$name."%' OR stf.last_name LIKE  '%".$name."%'  OR stf.first_name_kana LIKE  '%".$name."%'  OR stf.last_name_kana LIKE  '%".$name."%'";


            $ary_tmp_word = preg_split('/[\s]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
            $count_tmp_word = count($ary_tmp_word);
            for ($i = 0; $i < $count_tmp_word; $i++) {
                $sql .= " OR stf.first_name LIKE '%".$ary_tmp_word[$i]."%' OR stf.last_name LIKE '%".$ary_tmp_word[$i]."%' OR stf.first_name_kana LIKE '%".$ary_tmp_word[$i]."%' OR stf.last_name_kana LIKE '%".$ary_tmp_word[$i]."%'";
                $sql_count .= " OR stf.first_name LIKE '%".$ary_tmp_word[$i]."%' OR stf.last_name LIKE '%".$ary_tmp_word[$i]."%' OR stf.first_name_kana LIKE '%".$ary_tmp_word[$i]."%' OR stf.last_name_kana LIKE '%".$ary_tmp_word[$i]."%'";
            }

            $sql .= " )";
            $sql_count .= ")";
        }

        if(!empty($store_id)){
            $sql .= " AND stf.store_id = '".$store_id."'";
            $sql_count .= " AND stf.store_id = ".$store_id;
        }

        $sql .= " ORDER BY stf.created_at DESC";

        if(!empty($page_limit) && !empty($page_number)){
            $start_number = $page_limit*($page_number-1);
            $sql .= ' LIMIT '.$page_limit.' OFFSET '.$start_number;
        }

        $query_data = DB::select($sql);
        $total_items = DB::select($sql_count);

        $data = array();
        $data['data'] = $query_data;
        $data['total_items'] = $total_items[0]->total_items;

        return $data;


    }

    public static function getStaffDetail($staff_id){
        $sql = "SELECT stf.last_name, stf.first_name, stf.last_name_kana, stf.first_name_kana, stf.birthday, stf.email, stf.note, stf.store_id, str.store_name FROM staffs AS stf LEFT JOIN stores AS str ON stf.store_id = str.id WHERE stf.is_deleted = 0 AND stf.id = ".$staff_id;

        $query_data = DB::select($sql);

        return $query_data;
    }

    public static function addStaff($last_name,$first_name,$last_name_kana,$first_name_kana,$birthday,$email,$password,$store_id,$another_user_id,$note){
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $id_staff = DB::table('staffs')->insertGetId(array(
            'last_name' => $last_name,
            'first_name' => $first_name,
            'last_name_kana' => $last_name_kana,
            'first_name_kana' => $first_name_kana,
            'birthday' => $birthday,
            'email' => $email,
            'password' => bcrypt($password),
            'store_id' => $store_id,
            'another_user_id' => $another_user_id,
            'note' => $note,
            'is_deleted' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ));

        $login_id = "S".sprintf("%05d", $id_staff);
        $info_staff = Staff::find($id_staff);
        $info_staff->login_id = $login_id;
        $info_staff->save();


        return $id_staff;
    }

    public static function editStaff($staff_id,$last_name,$first_name,$last_name_kana,$first_name_kana,$birthday,$email,$store_id,$another_user_id,$note){
        $updated_at = date('Y-m-d H:i:s');
        $info_staff = Staff::find($staff_id);
        if($info_staff){
            $info_staff->last_name = $last_name;
            $info_staff->first_name = $first_name;
            $info_staff->last_name_kana = $last_name_kana;
            $info_staff->first_name_kana = $first_name_kana;
            $info_staff->birthday = $birthday;
            $info_staff->email = $email;
            $info_staff->store_id = $store_id;
            $info_staff->another_user_id = $another_user_id;
            $info_staff->note = $note;
            $info_staff->updated_at = $updated_at;
            $info_staff->save();
            return $info_staff->id;
        }else{
            return null;
        }
    }

    public static function deleteStaff($staff_id){
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $info_staff = Staff::find($staff_id);
        if($info_staff){
            $info_staff->is_deleted = 1;
            $info_staff->save();
            return true;
        }else{
            return false;
        }
    }
    public static function getDetailByEmail($email)
    {
        $staff = Staff::query()
            ->where('email', $email)
            ->where('is_deleted', 0)
            ->first();
        return $staff;
    }
    public static function checkExist($id, $api_token)
    {
        $staff = Staff::query()
            ->where('id', $id)
            ->where('api_token', $api_token)
            ->where('is_deleted', 0)
            ->first();
        return $staff;
    }
    public static function updatePassword($id, $password)
    {
        $staff = Staff::find($id);
        if($staff){
            $staff->password = bcrypt($password);
            return $staff->save();
        }else{
            return false;
        }
    }
    public static function updateApiToken($id){
        $staff = Staff::find($id);
        if($staff){
            $staff->api_token = str_random(60);
            $staff->save();
            return $staff->api_token;
        }
        return '';
    }
    public static function checkEmail($email)
    {
        $staff = Staff::query()
            ->where('email', $email)
            ->where('is_deleted', 0)
            ->first();
        return $staff;
    }

    public static function checkEmailEdit($staff_id,$email)
    {
        $staff = Staff::query()
            ->where('id', '!=', $staff_id)
            ->where('email', $email)
            ->where('is_deleted', 0)
            ->first();
        return $staff;
    }
}
