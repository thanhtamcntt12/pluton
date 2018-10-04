<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Step4 extends Model
{
    protected $table = 'step4_advices';

    public static function getAdvices($type_12_intent_diagnosis)
    {
        $result = array();
        $query = Step4::query();
        $query = $query->select('type_12_advice', 'type_3_advice')
            ->where('type_12_intent_diagnosis', $type_12_intent_diagnosis)
            ->where('is_deleted', 0);
        $result = $query->first();
        return $result;
    }

    public static function getListStep4($type_3_diagnosis_id,$type_12_intent_diagnosis_id,$page_limit,$page_number){
        $sql = "SELECT st4.id, type3.type_3_diagnosis_name, type12i.type_12_intent_diagnosis_name, st4.type_3_advice, st4.type_12_advice FROM step4_advices AS st4
            INNER JOIN type_3_diagnoses AS type3 ON st4.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_intent_diagnoses AS type12i ON st4.type_12_intent_diagnosis = type12i.type_12_intent_diagnosis_id AND type12i.is_deleted = 0 
            WHERE st4.is_deleted = 0";
        $sql_count = "SELECT COUNT(*) AS total_items 
            FROM step4_advices AS st4
            INNER JOIN type_3_diagnoses AS type3 ON st4.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_intent_diagnoses AS type12i ON st4.type_12_intent_diagnosis = type12i.type_12_intent_diagnosis_id AND type12i.is_deleted = 0 
            WHERE st4.is_deleted = 0";
        
        if(!empty($type_3_diagnosis_id)){
            $sql .= " AND st4.type_3_diagnosis = '".$type_3_diagnosis_id."'";
            $sql_count .= " AND st4.type_3_diagnosis = ".$type_3_diagnosis_id;
        }

        if(!empty($type_12_intent_diagnosis_id)){
            $sql .= " AND st4.type_12_intent_diagnosis = '".$type_12_intent_diagnosis_id."'";
            $sql_count .= " AND st4.type_12_intent_diagnosis = ".$type_12_intent_diagnosis_id;
        }

        $sql .= " ORDER BY st4.created_at DESC";

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

    public static function getDetailStep4($step4_id){
        $sql = "SELECT st4.id, type3.type_3_diagnosis_name, type12i.type_12_intent_diagnosis_name, st4.type_3_advice, st4.type_12_advice FROM step4_advices AS st4
            INNER JOIN type_3_diagnoses AS type3 ON st4.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_intent_diagnoses AS type12i ON st4.type_12_intent_diagnosis = type12i.type_12_intent_diagnosis_id AND type12i.is_deleted = 0 
            WHERE st4.is_deleted = 0 AND st4.id = ".$step4_id;
        $query_data = DB::select($sql);
        if($query_data) return $query_data;
        else return null;
    }

    public static function editStep4($step4_id,$type_3_advice,$type_12_advice){
        $updated_at = date('Y-m-d H:i:s');
        $info_step4 = Step4::find($step4_id);
        if($info_step4){
            $info_step4->type_3_advice = $type_3_advice;
            $info_step4->type_12_advice = $type_12_advice;
            $info_step4->updated_at = $updated_at;
            $info_step4->save();
            return $info_step4->id;
        }else{
            return null;
        }
    }

}