<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Type12IntentDiagnoses extends Authenticatable
{
    protected $table = 'type_12_intent_diagnoses';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_12_intent_diagnosis_id', 'type_12_intent_diagnosis_name', 'is_deleted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getList(){
        $query = Type12IntentDiagnoses::query();
        $result = $query->select('type_12_intent_diagnosis_id','type_12_intent_diagnosis_name')->where('is_deleted',0)->get();
        return $result;
    }

}
