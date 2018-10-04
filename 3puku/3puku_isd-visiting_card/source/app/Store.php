<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Store extends Authenticatable
{
    protected $table = 'stores';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_name', 'store_name_kana', 'note', 'is_deleted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getListStore($name,$page_limit,$page_number){

        $sql = "SELECT * FROM stores WHERE is_deleted = 0";
        $sql_count = "SELECT COUNT(*) AS total_items FROM stores WHERE is_deleted = 0";
        if(!empty($name)){

            $sql .= " AND (store_name LIKE '%".$name."%' OR store_name_kana LIKE '%".$name."%'";
            $sql_count .= " AND (store_name LIKE '%".$name."%' OR store_name_kana LIKE  '%".$name."%'";

            $ary_tmp_word = preg_split('/[\s]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
            $count_tmp_word = count($ary_tmp_word);
            for ($i = 0; $i < $count_tmp_word; $i++) {
                $sql .= " OR store_name LIKE '%".$ary_tmp_word[$i]."%' OR store_name_kana LIKE '%".$ary_tmp_word[$i]."%'";
                $sql_count .= " OR store_name LIKE '%".$ary_tmp_word[$i]."%' OR store_name_kana LIKE '%".$ary_tmp_word[$i]."%'";
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

    public static function getStoreDetail($store_id){
        $query = Store::query();
        $result = $query->select('store_name_kana','store_name','note')->where('id',$store_id)->where('is_deleted',0)->get();
        return $result;
    }

    public static function addStore($store_name,$store_name_kana,$note){
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $id_store = DB::table('stores')->insertGetId(array(
            'store_name' => $store_name,
            'store_name_kana' => $store_name_kana,
            'note' => $note,
            'is_deleted' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ));

        return $id_store;
    }

    public static function editStore($store_id,$store_name,$store_name_kana,$note){
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $info_store = Store::find($store_id);
        if($info_store){
            $info_store->store_name = $store_name;
            $info_store->store_name_kana = $store_name_kana;
            $info_store->note = $note;
            $info_store->updated_at = $updated_at;
            $info_store->save();
            return $info_store->id;
        }else{
            return null;
        }
    }

    public static function deleteStore($store_id){
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $info_store = Store::find($store_id);
        if($info_store){
            $info_store->is_deleted = 1;
            $info_store->save();
            return true;
        }else{
            return false;
        }
    }

}
