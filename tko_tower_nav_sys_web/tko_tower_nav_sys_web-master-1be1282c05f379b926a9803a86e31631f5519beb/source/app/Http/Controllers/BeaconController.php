<?php

namespace App\Http\Controllers;

use App\Beacon;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;

class BeaconController extends Controller
{
    public function index(Request $request){
        $data = null;
        $success = false;
        $error = null;
        try{
            $page_limit = $request->input('page_limit');
            $page_number = $request->input('page_number');
            if(empty($page_limit) || empty($page_number)){
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
                $data = Beacon::getListBeacon($page_limit,$page_number);
                $success = true;
                $error = null;
            }
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }

    public function add(Request $request){
        $data = null;
        $success = false;
        $error = null;
        try{
            $name = $request->input('name');
            $uuid = $request->input('uuid');
            $major = $request->input('major');
            $minor = $request->input('minor');
			$type = $request->input('type');
            if(empty($name) || empty($uuid) || !isset($type)){
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
                if(Uuid::validate($uuid)){
                    $check_beacon = Beacon::checkExistBeaconAdd($uuid,$major,$minor);
                    if($check_beacon){
                        $success = false;
                        $error = \Lang::get('common_message.error.BEACON_ALREADY_EXISTS');
                    }else{
                        $success = true;
                        $data = Beacon::addBeacon($name,$uuid,$major,$minor,$type);
                    }
                }else{
                    $success = false;
                    $error = \Lang::get('common_message.error.UUID_INCORRECT');
                }
            }
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }

    public function edit(Request $request){
        $data = null;
        $success = false;
        $error = null;
        try{
            $id = $request->input('id');
            $name = $request->input('name');
            $uuid = $request->input('uuid');
            $major = $request->input('major');
            $minor = $request->input('minor');
			$type = $request->input('type');
            if(empty($id) || empty($name) || empty($uuid) || !isset($type)){
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
                if(Uuid::validate($uuid)){
                    $check_beacon = Beacon::checkExistBeaconEdit($id,$uuid,$major,$minor);
                    if($check_beacon){
                        $success = false;
                        $error = ('common_message.error.BEACON_ALREADY_EXISTS');
                    }else{
                        $data = Beacon::updateBeacon($id,$name,$uuid,$major,$minor,$type);
                        $success = true;
                        $error = null;
                    }
                }else{
                    $success = false;
                    $error = \Lang::get('common_message.error.UUID_INCORRECT');
                }
            }
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }

    public function delete(Request $request){
        $data = null;
        $success = false;
        try{
            $id = $request->input('id');
            if(empty($id)){
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
                $data = Beacon::deleteBeacon($id);
                if($data){
                    $success = true;
                    $error = null;
                }else{
                    $success = false;
                    $error = \Lang::get('common_message.error.DELETE_BEACON_FAIL');
                }
            }
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }

    public function changeRev(Request $request){
        $data = array();
        $success = false;
        $error = null;
        try{
            $old_beacon_id = $request->input('old_beacon_id');
            $new_beacon_id = $request->input('new_beacon_id');
            if(empty($new_beacon_id)){
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
                Beacon::increaseRev($new_beacon_id);
                if (!empty($old_beacon_id)) {
                    Beacon::increaseRev($old_beacon_id);
                }
                $success = true;
                $error = null;      
            }
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }

    public function detail(Request $request){
        $data = array();
        $success = false;
        $error = null;
        try{
            $id = $request->input('id');
            if(empty($id)){
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
                $data = Beacon::getDetailBeacon($id);
                $success = true;
                $error = null;      
            }
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }

    public function list_add_guide(Request $request){
        $data = array();
        $success = false;
        $error = null;
        try{
            $data = Beacon::getListBeaconAddGuide();
            $success = true;
            $error = null; 
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }

    public function list_edit_guide(Request $request){
        $data = array();
        $success = false;
        $error = null;
        try{
            $guide_id = $request->input('id');
            if(empty($guide_id)){
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
                $data = Beacon::getListBeaconEditGuide($guide_id);
                $success = true;
                $error = null;      
            }
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }

    public function sync(Request $request){
        $data = array();
        $success = false;
        $error = null;
        try{
            $data['del'] = array();
            $data['new'] = array();
            $data['upd'] = array();
            $ary_param = $request->input('data');
            if(count($ary_param) > 0){
                $ary_beacon = Beacon::getAllListBeacon();
                $ary_beacon_id_in_param = array();
                $ary_beacon_id_in_result = array();
                for ($h=0; $h < count($ary_beacon); $h++) { 
                    $ary_beacon_id_in_result[] = $ary_beacon[$h]['beacon_id'];
                }
                for ($k=0; $k < count($ary_param); $k++) { 
                    $ary_beacon_id_in_param[] = $ary_param[$k]['beacon_id'];
                    //check beacon delete
                    if (in_array($ary_param[$k]['beacon_id'], $ary_beacon_id_in_result) == false) {
                        $data['del'][] = $ary_param[$k];
                    }   
                }
                for ($i=0; $i < count($ary_beacon); $i++) { 
                    // check new beacon
                    if (in_array($ary_beacon[$i]['beacon_id'], $ary_beacon_id_in_param) == false) {
                        $data['new'][] = $ary_beacon[$i];
                    }
                    //check beacon updated
                    for ($j=0; $j < count($ary_param); $j++) { 
                        if($ary_param[$j]['beacon_id'] == $ary_beacon[$i]['beacon_id']){
                            if ($ary_param[$j]['rev'] < $ary_beacon[$i]['rev']) {
                                $data['upd'][] = $ary_beacon[$i];
                            }
                        }                 
                    }
                }


            }else{
                $success = true;
                $error = null;
            }
        }catch(\Illuminate\Database\QueryException $ex) {
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }catch(\Illuminate\Exception $ex){
            $error = $ex->getMessage();
            \Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
        }
        return $this->doResponse($success,$data,$error);
    }
}