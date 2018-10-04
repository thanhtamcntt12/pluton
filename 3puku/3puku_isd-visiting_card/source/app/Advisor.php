<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Advisor extends Authenticatable
{
    protected $table = 'advisors';
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

    public static function getDetailByEmail($email)
    {
        $advisor = Advisor::query()
            ->where('email', $email)
            ->where('is_deleted', 0)
            ->first();
        return $advisor;
    }

    public static function updateApiToken($id){
        $advisor = Advisor::find($id);
        if($advisor){
            $advisor->api_token = str_random(60);
            $advisor->save();
            return $advisor->api_token;
        }
        return '';
    }

    public static function checkExist($id, $api_token)
    {
        $advisor = Advisor::query()
            ->where('id', $id)
            ->where('api_token', $api_token)
            ->where('is_deleted', 0)
            ->first();
        return $advisor;
    }

    public static function updatePassword($id, $password)
    {
        $advisor = Advisor::find($id);
        if($advisor){
            $advisor->password = bcrypt($password);
            return $advisor->save();
        }else{
            return false;
        }
    }

}

?>