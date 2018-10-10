<?php

namespace App;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
class Beacon extends Model
{
    protected $table = 'beacons';
    protected $fillable = [
        'name', 'uuid', 'major', 'minor', 'type', 'guide_id', 'rev', 'is_deleted', 
    ];

    public static function getListBeacon($page_limit,$page_number){
        $page_number = ($page_number - 1)*$page_limit;
        $beacon = Beacon::query()->where('is_deleted', 0) ->orderBy('id', 'desc')->offset($page_number)->limit($page_limit)->get();
		$data = array();
        $data['data'] = $beacon;
		$data['total_items']  = Beacon::where('is_deleted', 0)->count();
        if($data){
            return $data;
        }else{
            return null;
        }

    }

    public static function addBeacon($name,$uuid,$major,$minor,$type){
        $beacon = new Beacon;
        $beacon->name = $name;
        $beacon->uuid = $uuid;
        $beacon->major = $major;
        $beacon->minor = $minor;
        $beacon->type = $type;
        $beacon->is_deleted = 0;
        $beacon->rev = 1;
        $beacon->save();
        return $beacon->id;
    }

    public static function checkExistBeaconAdd($uuid,$major,$minor){
        $beacon = Beacon::query()
        ->where('uuid',$uuid)
        ->where('major',$major)
        ->where('minor',$minor)
        ->where('is_deleted', 0)
        ->first();
        return $beacon;

    }

    public static function checkExistBeaconEdit($id,$uuid,$major,$minor){
        $beacon = Beacon::query()
        ->where('id', '!=', $id)
        ->where('uuid', $uuid)
        ->where('major', $major)
        ->where('minor', $minor)
        ->where('is_deleted', 0)
        ->first();
        return $beacon;
    }

    public static function updateBeacon($id,$name,$uuid,$major,$minor,$type){
        $info_beacon = Beacon::find($id);
        if($info_beacon){
            $info_beacon->name = $name;
            $info_beacon->uuid = $uuid;
            $info_beacon->major = $major;
            $info_beacon->minor = $minor;
            $info_beacon->type = $type;
			$info_beacon->rev = $info_beacon->rev + 1;
            $info_beacon->save();
            return $info_beacon->id;
        }else{
            return null;
        }
    }

    public static function deleteBeacon($id){
        $info_beacon = Beacon::find($id);
        if($info_beacon){
            $info_beacon->is_deleted = 1;
			$info_beacon->guide_id = null;
            $info_beacon->save();
            return true;
        }else{
            return false;
        }
    }

    public static function getDetailBeacon($id){
        $beacon = Beacon::query()
        ->where('id', $id)
        ->where('is_deleted', 0)
        ->first();
        return $beacon;
    }

    public static function getListBeaconAddGuide(){
        $beacon = Beacon::select('id','name','uuid')
        ->where('guide_id', null)
        ->where('is_deleted', 0)
        ->get();
        return $beacon;
    }

    public static function getListBeaconEditGuide($guide_id){
        $beacon = Beacon::select('id','name','uuid')
        ->where('is_deleted', 0)
        ->where('guide_id', null)
        ->orWhere('guide_id', $guide_id)
        ->get();
        return $beacon;
    }
	
	public static function updateGuideId($id,$guide_id){
        $info_beacon = Beacon::find($id);
        if($info_beacon){
            $info_beacon->guide_id = $guide_id;
            $info_beacon->save();
            return $info_beacon->id;
        }else{
            return null;
        }
    }
	
	public static function deleteGuideId($id){
		$info_beacon = Beacon::find($id);
        if($info_beacon){
            $info_beacon->guide_id = null;
            $info_beacon->save();
            return $info_beacon->id;
        }else{
            return null;
        }
	}
	
    public static function increaseRev($beacon_id){
        $info_beacon = Beacon::find($beacon_id);
        if($info_beacon){
            $info_beacon->rev = $info_beacon->rev + 1;
            $info_beacon->save();
            return $info_beacon->id;
        }else{
            return null;
        }
    }
	
	public static function checkExistBeaconAndGuide($beacon_id,$guide_id){
		$beacon = Beacon::query()
        ->where('id', $beacon_id)
        ->where('guide_id', $guide_id)
        ->where('is_deleted', 0)
        ->first();
        if($beacon){
            return true;
        }else{
            return false;
        }
	}

    public static function getAllListBeacon(){
        $beacon = Beacon::join('guides', function($join) {
                        $join->on('beacons.guide_id', '=', 'guides.id');
                        $join->where('guides.status','=', 1);
                        $join->where('guides.is_deleted','=', 0);
                    })
                ->where('beacons.is_deleted', 0)
                ->get([
                    'beacons.id',
                    'beacons.name',
                    'beacons.uuid',
                    'beacons.major',
                    'beacons.minor',
                    'beacons.type',
                    'beacons.guide_id',
                    'beacons.rev',
                    'beacons.created_at',
                    'beacons.updated_at',
                    'guides.panaromic_photo_noon',
                    'guides.panaromic_photo_night',

                    'guides.guide_voice_ja',
                    'guides.guide_text_ja',
                    'guides.landscape_ja',
                    'guides.tag_ja',

                    'guides.guide_voice_en',
                    'guides.guide_text_en',
                    'guides.landscape_en',
                    'guides.tag_en',

                    'guides.guide_voice_cn_simplified',
                    'guides.guide_text_cn_simplified',
                    'guides.landscape_cn_simplified',
                    'guides.tag_cn_simplified',

                    'guides.guide_voice_cn_traditional',
                    'guides.guide_text_cn_traditional',
                    'guides.landscape_cn_traditional',
                    'guides.tag_cn_traditional',

                    'guides.guide_voice_kr',
                    'guides.guide_text_kr',
                    'guides.landscape_kr',
                    'guides.tag_kr',

                    'guides.guide_voice_es',
                    'guides.guide_text_es',
                    'guides.landscape_es',
                    'guides.tag_es',

                    'guides.guide_voice_gf',
                    'guides.guide_text_gf',
                    'guides.landscape_gf',
                    'guides.tag_gf',

                    'guides.guide_voice_it',
                    'guides.guide_text_it',
                    'guides.landscape_it',
                    'guides.tag_it',

                    'guides.guide_voice_de',
                    'guides.guide_text_de',
                    'guides.landscape_de',
                    'guides.tag_de',

                    'guides.guide_voice_ru',
                    'guides.guide_text_ru',
                    'guides.landscape_ru',
                    'guides.tag_ru',

                    'guides.guide_voice_th',
                    'guides.guide_text_th',
                    'guides.landscape_th',
                    'guides.tag_th',

                    'guides.guide_voice_vn',
                    'guides.guide_text_vn',
                    'guides.landscape_vn',
                    'guides.tag_vn',

                    'guides.guide_voice_id',
                    'guides.guide_text_id',
                    'guides.landscape_id',
                    'guides.tag_id'
                ]);
        if($beacon){
            $ary_data = array();
            for ($i=0; $i < count($beacon); $i++) { 
                $ary_data[$i]['beacon_id'] = $beacon[$i]['id'];
                $ary_data[$i]['name'] = $beacon[$i]['name'];
                $ary_data[$i]['uuid'] = $beacon[$i]['uuid'];
                $ary_data[$i]['major'] = $beacon[$i]['major'];
                $ary_data[$i]['minor'] = $beacon[$i]['minor'];
                $ary_data[$i]['type'] = $beacon[$i]['type'];
                $ary_data[$i]['guide_id'] = $beacon[$i]['guide_id'];
                $ary_data[$i]['rev'] = $beacon[$i]['rev'];
                $ary_data[$i]['created_at'] = $beacon[$i]['created_at']->format('Y-m-d');
                $ary_data[$i]['updated_at'] = $beacon[$i]['updated_at']->format('Y-m-d');
                if (!empty($beacon[$i]['panaromic_photo_noon'])) {
                    $ary_data[$i]['panaromic_photo_noon'] = 'panaromic/noon/'.$beacon[$i]['panaromic_photo_noon'];
                }else{
                    $ary_data[$i]['panaromic_photo_noon'] = null;
                }
                
                if (!empty($beacon[$i]['panaromic_photo_night'])) {
                    $ary_data[$i]['panaromic_photo_night'] = 'panaromic/night/'.$beacon[$i]['panaromic_photo_night'];
                }else{
                    $ary_data[$i]['panaromic_photo_night'] = null;
                }
                $ary_data[$i]['lang'] = array();
                $ary_ja = array();
                $ary_en = array();
                $ary_cn_simplified = array();
                $ary_cn_traditional = array();
                $ary_kr = array();
                $ary_es = array();
                $ary_gf = array();
                $ary_it = array();
                $ary_de = array();
                $ary_ru = array();
                $ary_th = array();
                $ary_vn = array();
                $ary_id = array();

                if (!empty($beacon[$i]['guide_voice_ja'])) {
                    $ary_ja['voice'] = 'voice/ja/'.$beacon[$i]['guide_voice_ja'];
                }else{
                    $ary_ja['voice'] = null;
                }

                $ary_ja['text'] = $beacon[$i]['guide_text_ja'];
                $ary_ja['landscape'] = $beacon[$i]['landscape_ja'];
                $ary_ja['tag'] = $beacon[$i]['tag_ja'];
                $ary_ja['type'] = 'ja';
                $ary_data[$i]['lang'][] = $ary_ja;

                if (!empty($beacon[$i]['guide_voice_en'])) {
                    $ary_en['voice'] = 'voice/en/'.$beacon[$i]['guide_voice_en'];
                }else{
                    $ary_en['voice'] = null;
                }
                $ary_en['text'] = $beacon[$i]['guide_text_en'];
                $ary_en['landscape'] = $beacon[$i]['landscape_en'];
                $ary_en['tag'] = $beacon[$i]['tag_en'];
                $ary_en['type'] = 'us';
                $ary_data[$i]['lang'][] = $ary_en;

                if (!empty($beacon[$i]['guide_voice_cn_simplified'])) {
                    $ary_cn_simplified['voice'] = 'voice/cn_simplified/'.$beacon[$i]['guide_voice_cn_simplified'];
                }else{
                    $ary_cn_simplified['voice'] = null;
                }
                $ary_cn_simplified['text'] = $beacon[$i]['guide_text_cn_simplified'];
                $ary_cn_simplified['landscape'] = $beacon[$i]['landscape_cn_simplified'];
                $ary_cn_simplified['tag'] = $beacon[$i]['tag_cn_simplified'];
                $ary_cn_simplified['type'] = 'zh_simplified';
                $ary_data[$i]['lang'][] = $ary_cn_simplified;

                if (!empty($beacon[$i]['guide_voice_cn_traditional'])) {
                    $ary_cn_traditional['voice'] = 'voice/cn_traditional/'.$beacon[$i]['guide_voice_cn_traditional'];
                }else{
                    $ary_cn_traditional['voice'] = null;
                }
                $ary_cn_traditional['text'] = $beacon[$i]['guide_text_cn_traditional'];
                $ary_cn_traditional['landscape'] = $beacon[$i]['landscape_cn_traditional'];
                $ary_cn_traditional['tag'] = $beacon[$i]['tag_cn_traditional'];
                $ary_cn_traditional['type'] = 'zh_traditional';
                $ary_data[$i]['lang'][] = $ary_cn_traditional;

                if (!empty($beacon[$i]['guide_voice_kr'])) {
                    $ary_kr['voice'] = 'voice/kr/'.$beacon[$i]['guide_voice_kr'];
                }else{
                    $ary_kr['voice'] = null;
                }
                $ary_kr['text'] = $beacon[$i]['guide_text_kr'];
                $ary_kr['landscape'] = $beacon[$i]['landscape_kr'];
                $ary_kr['tag'] = $beacon[$i]['tag_kr'];
                $ary_kr['type'] = 'ko';
                $ary_data[$i]['lang'][] = $ary_kr;

                if (!empty($beacon[$i]['guide_voice_es'])) {
                    $ary_es['voice'] = 'voice/es/'.$beacon[$i]['guide_voice_es'];
                }else{
                    $ary_es['voice'] = null;
                }
                $ary_es['text'] = $beacon[$i]['guide_text_es'];
                $ary_es['landscape'] = $beacon[$i]['landscape_es'];
                $ary_es['tag'] = $beacon[$i]['tag_es'];
                $ary_es['type'] = 'es';
                $ary_data[$i]['lang'][] = $ary_es;

                if (!empty($beacon[$i]['guide_voice_gf'])) {
                    $ary_gf['voice'] = 'voice/gf/'.$beacon[$i]['guide_voice_gf'];
                }else{
                    $ary_gf['voice'] = null;
                }
                $ary_gf['text'] = $beacon[$i]['guide_text_gf'];
                $ary_gf['landscape'] = $beacon[$i]['landscape_gf'];
                $ary_gf['tag'] = $beacon[$i]['tag_gf'];
                $ary_gf['type'] = 'fr';
                $ary_data[$i]['lang'][] = $ary_gf;

                if (!empty($beacon[$i]['guide_voice_it'])) {
                    $ary_it['voice'] = 'voice/it/'.$beacon[$i]['guide_voice_it'];
                }else{
                    $ary_it['voice'] = null;
                }
                $ary_it['text'] = $beacon[$i]['guide_text_it'];
                $ary_it['landscape'] = $beacon[$i]['landscape_it'];
                $ary_it['tag'] = $beacon[$i]['tag_it'];
                $ary_it['type'] = 'it';
                $ary_data[$i]['lang'][] = $ary_it;

                if (!empty($beacon[$i]['guide_voice_de'])) {
                    $ary_de['voice'] = 'voice/de/'.$beacon[$i]['guide_voice_de'];
                }else{
                    $ary_de['voice'] = null;
                }
                $ary_de['text'] = $beacon[$i]['guide_text_de'];
                $ary_de['landscape'] = $beacon[$i]['landscape_de'];
                $ary_de['tag'] = $beacon[$i]['tag_de'];
                $ary_de['type'] = 'de';
                $ary_data[$i]['lang'][] = $ary_de;

                if (!empty($beacon[$i]['guide_voice_ru'])) {
                    $ary_ru['voice'] = 'voice/ru/'.$beacon[$i]['guide_voice_ru'];
                }else{
                    $ary_ru['voice'] = null;
                }
                $ary_ru['text'] = $beacon[$i]['guide_text_ru'];
                $ary_ru['landscape'] = $beacon[$i]['landscape_ru'];
                $ary_ru['tag'] = $beacon[$i]['tag_ru'];
                $ary_ru['type'] = 'ru';
                $ary_data[$i]['lang'][] = $ary_ru;

                if (!empty($beacon[$i]['guide_voice_th'])) {
                    $ary_th['voice'] = 'voice/th/'.$beacon[$i]['guide_voice_th'];
                }else{
                    $ary_th['voice'] = null;
                }
                $ary_th['text'] = $beacon[$i]['guide_text_th'];
                $ary_th['landscape'] = $beacon[$i]['landscape_th'];
                $ary_th['tag'] = $beacon[$i]['tag_th'];
                $ary_th['type'] = 'th';
                $ary_data[$i]['lang'][] = $ary_th;

                if (!empty($beacon[$i]['guide_voice_vn'])) {
                    $ary_vn['voice'] = 'voice/vn/'.$beacon[$i]['guide_voice_vn'];
                }else{
                    $ary_vn['voice'] = null;
                }
                $ary_vn['text'] = $beacon[$i]['guide_text_vn'];
                $ary_vn['landscape'] = $beacon[$i]['landscape_vn'];
                $ary_vn['tag'] = $beacon[$i]['tag_vn'];
                $ary_vn['type'] = 'vi';
                $ary_data[$i]['lang'][] = $ary_vn;

                if (!empty($beacon[$i]['guide_voice_id'])) {
                    $ary_id['voice'] = 'voice/id/'.$beacon[$i]['guide_voice_id'];
                }else{
                    $ary_id['voice'] = null;
                }
                $ary_id['text'] = $beacon[$i]['guide_text_id'];
                $ary_id['landscape'] = $beacon[$i]['landscape_id'];
                $ary_id['tag'] = $beacon[$i]['tag_id'];
                $ary_id['type'] = 'in';
                $ary_data[$i]['lang'][] = $ary_id;                
            }
            return $ary_data;
        }else{
            return null;
        }

    }
}