<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Step2 extends Model
{
    protected $table = 'step2_advices';

    public static function getAdvices($type_60_diagnosis)
    {
        $result = array();
        $query = Step2::query();
        $query = $query->select('type_12_advice', 'type_3_advice', 'type_60_advice')
            ->where('type_60_diagnosis', $type_60_diagnosis)
            ->where('is_deleted', 0);
        $result = $query->first();
        return $result;
    }

    public static function getListStep2($type_3_diagnosis_id,$type_12_diagnosis_id,$type_60_diagnosis_id,$page_limit,$page_number){
        $sql = "SELECT st2.id, type3.type_3_diagnosis_name, type12.type_12_diagnosis_name, type60.type_60_diagnosis_name, st2.type_3_advice, st2.type_12_advice, st2.type_60_advice FROM step2_advices AS st2
            INNER JOIN type_3_diagnoses AS type3 ON st2.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_diagnoses AS type12 ON st2.type_12_diagnosis = type12.type_12_diagnosis_id AND type12.is_deleted = 0 
            INNER JOIN type_60_diagnoses AS type60 ON st2.type_60_diagnosis = type60.type_60_diagnosis_id AND type60.is_deleted = 0
            WHERE st2.is_deleted = 0";
        $sql_count = "SELECT COUNT(*) AS total_items 
            FROM step2_advices AS st2
            INNER JOIN type_3_diagnoses AS type3 ON st2.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_diagnoses AS type12 ON st2.type_12_diagnosis = type12.type_12_diagnosis_id AND type12.is_deleted = 0 
            INNER JOIN type_60_diagnoses AS type60 ON st2.type_60_diagnosis = type60.type_60_diagnosis_id AND type60.is_deleted = 0
            WHERE st2.is_deleted = 0";
        
        if(!empty($type_3_diagnosis_id)){
            $sql .= " AND st2.type_3_diagnosis = '".$type_3_diagnosis_id."'";
            $sql_count .= " AND st2.type_3_diagnosis = ".$type_3_diagnosis_id;
        }

        if(!empty($type_12_diagnosis_id)){
            $sql .= " AND st2.type_12_diagnosis = '".$type_12_diagnosis_id."'";
            $sql_count .= " AND st2.type_12_diagnosis = ".$type_12_diagnosis_id;
        }

        if(!empty($type_60_diagnosis_id)){
            $sql .= " AND st2.type_60_diagnosis = '".$type_60_diagnosis_id."'";
            $sql_count .= " AND st2.type_60_diagnosis = ".$type_60_diagnosis_id;
        }

        $sql .= " ORDER BY st2.created_at DESC";

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

    public static function getDetailStep2($step2_id){
        $sql = "SELECT st2.id, type3.type_3_diagnosis_name, type12.type_12_diagnosis_name, type60.type_60_diagnosis_name, st2.type_3_advice, st2.type_12_advice, st2.type_60_advice FROM step2_advices AS st2
            INNER JOIN type_3_diagnoses AS type3 ON st2.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_diagnoses AS type12 ON st2.type_12_diagnosis = type12.type_12_diagnosis_id AND type12.is_deleted = 0 
            INNER JOIN type_60_diagnoses AS type60 ON st2.type_60_diagnosis = type60.type_60_diagnosis_id AND type60.is_deleted = 0
            WHERE st2.is_deleted = 0 AND st2.id = ".$step2_id;
        $query_data = DB::select($sql);
        if($query_data) return $query_data;
        else return null;
    }

    public static function editStep2($step2_id,$type_3_advice,$type_12_advice,$type_60_advice){
        $updated_at = date('Y-m-d H:i:s');
        $info_step2 = Step2::find($step2_id);
        if($info_step2){
            $info_step2->type_3_advice = $type_3_advice;
            $info_step2->type_12_advice = $type_12_advice;
            $info_step2->type_60_advice = $type_60_advice;
            $info_step2->updated_at = $updated_at;
            $info_step2->save();
            return $info_step2->id;
        }else{
            return null;
        }
    }
}