<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Diagnoses extends Model
{
    protected $table = "diagnoses";

    public static function addCustomer($values, $customer_id)
    {
        $diagnoses = new Diagnoses;
        $diagnoses->user_id = $customer_id;
        $diagnoses->user_type = 1;
        $diagnoses->type_3_diagnosis = $values["type_3"];
        $diagnoses->type_12_diagnosis = $values["type_12"];
        $diagnoses->type_12_intent_diagnosis = $values["type_12_intent"];
        $diagnoses->type_12_surface_diagnosis = $values["type_12_surface"];
        $diagnoses->type_60_diagnosis = $values["type_60"];
        $diagnoses->is_deleted = 0;
        return $diagnoses->save();
    }

    public static function updateCustomer($values, $customer_id)
    {
        $result = Diagnoses::where('user_id', $customer_id)
            ->where('user_type', 1)
            ->where('is_deleted', 0)
            ->update(['type_3_diagnosis' => $values["type_3"],
                'type_12_diagnosis' => $values["type_12"],
                'type_12_intent_diagnosis' => $values["type_12_intent"],
                'type_12_surface_diagnosis' => $values["type_12_surface"],
                'type_60_diagnosis' => $values["type_60"],
            ]);

        return $result;
    }
    public static function addStaff($values, $staff_id)
    {
        $diagnoses = new Diagnoses;
        $diagnoses->user_id = $staff_id;
        $diagnoses->user_type = 2;
        $diagnoses->type_3_diagnosis = $values["type_3"];
        $diagnoses->type_12_diagnosis = $values["type_12"];
        $diagnoses->type_12_intent_diagnosis = $values["type_12_intent"];
        $diagnoses->type_12_surface_diagnosis = $values["type_12_surface"];
        $diagnoses->type_60_diagnosis = $values["type_60"];
        $diagnoses->is_deleted = 0;
        return $diagnoses->save();
    }
    public static function updateStaff($values, $staff_id)
    {
        $result = Diagnoses::where('user_id', $staff_id)
            ->where('user_type', 2)
            ->where('is_deleted', 0)
            ->update(['type_3_diagnosis' => $values["type_3"],
                'type_12_diagnosis' => $values["type_12"],
                'type_12_intent_diagnosis' => $values["type_12_intent"],
                'type_12_surface_diagnosis' => $values["type_12_surface"],
                'type_60_diagnosis' => $values["type_60"],
            ]);
        return $result;
    }
}