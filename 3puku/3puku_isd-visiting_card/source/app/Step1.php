<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Step1 extends Model
{
    protected $table = 'step1_advices';

    public static function getAdvices($type_12_surface_diagnosis)
    {
        $result = array();
        $query = Step1::query();
        $query = $query->select('type_12_advice', 'type_3_advice')
            ->where('type_12_surface_diagnosis', $type_12_surface_diagnosis)
            ->where('is_deleted', 0);
        $result = $query->first();
        return $result;
    }

    public static function getListStep1($type_3_diagnosis_id,$type_12_surface_diagnosis_id,$page_limit,$page_number){
        $sql = "SELECT st1.id, type3.type_3_diagnosis_name, type12s.type_12_surface_diagnosis_name, st1.type_3_advice, st1.type_12_advice FROM step1_advices AS st1
            INNER JOIN type_3_diagnoses AS type3 ON st1.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_surface_diagnoses AS type12s ON st1.type_12_surface_diagnosis = type12s.type_12_surface_diagnosis_id AND type12s.is_deleted = 0 
            WHERE st1.is_deleted = 0";
        $sql_count = "SELECT COUNT(*) AS total_items 
            FROM step1_advices AS st1
            INNER JOIN type_3_diagnoses AS type3 ON st1.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_surface_diagnoses AS type12s ON st1.type_12_surface_diagnosis = type12s.type_12_surface_diagnosis_id AND type12s.is_deleted = 0 
            WHERE st1.is_deleted = 0";
        
        if(!empty($type_3_diagnosis_id)){
            $sql .= " AND st1.type_3_diagnosis = '".$type_3_diagnosis_id."'";
            $sql_count .= " AND st1.type_3_diagnosis = ".$type_3_diagnosis_id;
        }

        if(!empty($type_12_surface_diagnosis_id)){
            $sql .= " AND st1.type_12_surface_diagnosis = '".$type_12_surface_diagnosis_id."'";
            $sql_count .= " AND st1.type_12_surface_diagnosis = ".$type_12_surface_diagnosis_id;
        }

        $sql .= " ORDER BY st1.created_at DESC";

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

    public static function getDetailStep1($step1_id){
        $sql = "SELECT st1.id, type3.type_3_diagnosis_name, type12s.type_12_surface_diagnosis_name, st1.type_3_advice, st1.type_12_advice FROM step1_advices AS st1
            INNER JOIN type_3_diagnoses AS type3 ON st1.type_3_diagnosis = type3.type_3_diagnosis_id AND type3.is_deleted = 0 
            INNER JOIN type_12_surface_diagnoses AS type12s ON st1.type_12_surface_diagnosis = type12s.type_12_surface_diagnosis_id AND type12s.is_deleted = 0 
            WHERE st1.is_deleted = 0 AND st1.id = ".$step1_id;
        $query_data = DB::select($sql);
        if($query_data) return $query_data;
        else return null;
    }

    public static function editStep1($step1_id,$type_3_advice,$type_12_advice){
        $updated_at = date('Y-m-d H:i:s');
        $info_step1 = Step1::find($step1_id);
        if($info_step1){
            $info_step1->type_3_advice = $type_3_advice;
            $info_step1->type_12_advice = $type_12_advice;
            $info_step1->updated_at = $updated_at;
            $info_step1->save();
            return $info_step1->id;
        }else{
            return null;
        }
    }
}