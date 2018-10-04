<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Admin extends Authenticatable
{
    protected $table = 'administrators';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name', 'first_name', 'last_name_kana', 'first_name_kana', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getAdminByEmail($email){
        $sql = "SELECT * FROM administrators  WHERE email = '".$email."'";

        $query_data = DB::select($sql);

        return $query_data;
    }

    public static function checkAdminByApiToken($id,$api_token){
        $sql = "SELECT * FROM administrators  WHERE id = ".$id." AND api_token = '".$api_token."'";

        $query_data = DB::select($sql);

        return $query_data;
    }

    public static function getListAdmin($name,$page_limit,$page_number){

        $sql = "SELECT id, last_name, first_name, last_name_kana, first_name_kana FROM administrators WHERE is_deleted = 0";
        $sql_count = "SELECT COUNT(*) AS total_items FROM administrators WHERE is_deleted = 0";
        if(!empty($name)){
            
            $sql .= " AND (first_name LIKE '%".$name."%' OR last_name LIKE '%".$name."%'  OR first_name_kana LIKE  '%".$name."%'  OR last_name_kana LIKE  '%".$name."%'";
            $sql_count .= " AND (first_name LIKE '%".$name."%' OR last_name LIKE  '%".$name."%'  OR first_name_kana LIKE  '%".$name."%'  OR last_name_kana LIKE  '%".$name."%'";


            $ary_tmp_word = preg_split('/[\s]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
            $count_tmp_word = count($ary_tmp_word);
            for ($i = 0; $i < $count_tmp_word; $i++) {
                $sql .= " OR first_name LIKE '%".$ary_tmp_word[$i]."%' OR last_name LIKE '%".$ary_tmp_word[$i]."%' OR first_name_kana LIKE '%".$ary_tmp_word[$i]."%' OR last_name_kana LIKE '%".$ary_tmp_word[$i]."%'";
                $sql_count .= " OR first_name LIKE '%".$ary_tmp_word[$i]."%' OR last_name LIKE '%".$ary_tmp_word[$i]."%' OR first_name_kana LIKE '%".$ary_tmp_word[$i]."%' OR last_name_kana LIKE '%".$ary_tmp_word[$i]."%'";
            }

            $sql .= " )";
            $sql_count .= ")";
        }

        $sql .= " ORDER BY created_at DESC";

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

    public static function getAdminDetail($admin_id){
        $sql = "SELECT last_name, first_name, last_name_kana, first_name_kana, email, note FROM administrators WHERE is_deleted = 0 AND id = ".$admin_id;

        $query_data = DB::select($sql);

        return $query_data;
    }

    public static function addAdmin($last_name,$first_name,$last_name_kana,$first_name_kana,$email,$password,$another_user_id,$note){
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $id_admin = DB::table('administrators')->insertGetId(array(
            'last_name' => $last_name,
            'first_name' => $first_name,
            'last_name_kana' => $last_name_kana,
            'first_name_kana' => $first_name_kana,
            'email' => $email,
            'password' => bcrypt($password),
            'another_user_id' => $another_user_id,
            'note' => $note,
            'is_deleted' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ));

        $login_id = "M".sprintf("%05d", $id_admin);
        $info_admin = Admin::find($id_admin);
        $info_admin->login_id = $login_id;
        $info_admin->save();

        return $id_admin;
    }

    public static function checkEmail($email)
    {
        $admin = Admin::query()
            ->where('email', $email)
            ->where('is_deleted', 0)
            ->first();
        return $admin;
    }

    public static function editAdmin($admin_id,$last_name,$first_name,$last_name_kana,$first_name_kana,$email,$another_user_id,$note){
        $updated_at = date('Y-m-d H:i:s');
        $info_admin = Admin::find($admin_id);
        if($info_admin){
            $info_admin->last_name = $last_name;
            $info_admin->first_name = $first_name;
            $info_admin->last_name_kana = $last_name_kana;
            $info_admin->first_name_kana = $first_name_kana;
            $info_admin->email = $email;
            $info_admin->another_user_id = $another_user_id;
            $info_admin->note = $note;
            $info_admin->updated_at = $updated_at;
            $info_admin->save();
            return $info_admin->id;
        }else{
            return null;
        }
    }
    public static function checkEmailEdit($admin_id,$email)
    {
        $admin = Admin::query()
            ->where('id', '!=', $admin_id)
            ->where('email', $email)
            ->where('is_deleted', 0)
            ->first();
        return $admin;
    }

    public static function deleteAdmin($admin_id){
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $info_admin = Admin::find($admin_id);
        if($info_admin){
            $info_admin->is_deleted = 1;
            $info_admin->save();
            return true;
        }else{
            return false;
        }
    }

}
