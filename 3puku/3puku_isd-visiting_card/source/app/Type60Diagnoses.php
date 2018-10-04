<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Type60Diagnoses extends Authenticatable
{
    protected $table = 'type_60_diagnoses';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_60_diagnosis_id', 'type_60_diagnosis_name', 'is_deleted',
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
        $query = Type60Diagnoses::query();
        $result = $query->select('type_60_diagnosis_id','type_60_diagnosis_name')->where('is_deleted',0)->get();
        return $result;
    }

}
