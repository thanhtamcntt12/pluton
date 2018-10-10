<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Guide extends Model
{
    protected $table = 'guides';

    public static function getListGuide($page_limit,$page_number){
        $page_number = ($page_number - 1)*$page_limit;
        $guide = Guide::leftJoin('beacons', function($join) {
                        $join->on('guides.id', '=', 'beacons.guide_id');
                    })
				->where('guides.is_deleted', 0) 
				->orderBy('guides.id', 'desc')
				->offset($page_number)
				->limit($page_limit)
				->get(['guides.id','guides.status','beacons.id as beacon_id','beacons.name as beacon_name']);
		$data = array();
        $data['data'] = $guide;
		$data['total_items']  = Guide::where('is_deleted', 0)->count();
        if($data){
            return $data;
        }else{
            return null;
        }

    }

	public static function getDetailById($id){
        $guide = DB::table('guides')
			->select('guides.id','guides.panaromic_photo_noon','guides.panaromic_photo_night','guides.guide_voice_ja','guides.guide_text_ja','guides.landscape_ja','guides.tag_ja',
					'guides.guide_voice_en','guides.guide_text_en','guides.landscape_en','guides.tag_en',
					'guides.guide_voice_cn_simplified','guides.guide_text_cn_simplified','guides.landscape_cn_simplified','guides.tag_cn_simplified',
					'guides.guide_voice_cn_traditional','guides.guide_text_cn_traditional','guides.landscape_cn_traditional','guides.tag_cn_traditional',
					'guides.guide_voice_kr','guides.guide_text_kr','guides.landscape_kr','guides.tag_kr',
					'guides.guide_voice_es','guides.guide_text_es','guides.landscape_es','guides.tag_es',
					'guides.guide_voice_gf','guides.guide_text_gf','guides.landscape_gf','guides.tag_gf',
					'guides.guide_voice_it','guides.guide_text_it','guides.landscape_it','guides.tag_it',
					'guides.guide_voice_de','guides.guide_text_de','guides.landscape_de','guides.tag_de',
					'guides.guide_voice_ru','guides.guide_text_ru','guides.landscape_ru','guides.tag_ru',
					'guides.guide_voice_th','guides.guide_text_th','guides.landscape_th','guides.tag_th',
					'guides.guide_voice_vn','guides.guide_text_vn','guides.landscape_vn','guides.tag_vn',
					'guides.guide_voice_id','guides.guide_text_id','guides.landscape_id','guides.tag_id',
					'guides.is_deleted','beacons.id as beacon_id','beacons.uuid')
			->leftjoin('beacons',function($q){
                $q->on('beacons.guide_id','=','guides.id');
                $q->where('beacons.is_deleted','=', 0);
            })
			->where('guides.id', '=', $id)
			->where('guides.is_deleted', '=', 0)
			->get();
		if($guide)
			return $guide[0];
		else return null;
    }
	
    public static function addGuide($ary_data){
        $guide = new Guide;

        $guide->panaromic_photo_noon = $ary_data['panaromic_photo_noon'];
        $guide->panaromic_photo_night = $ary_data['panaromic_photo_night'];
		
        $guide->is_deleted = 0;
        $guide->save();
        return $guide->id;
    }
	
	public static function editGuide($guide_id,$ary_data,$type){
        $guide = Guide::find($guide_id);
		if($type=='pana'){
			if($ary_data['panaromic_photo_noon']) $guide->panaromic_photo_noon = $ary_data['panaromic_photo_noon'];
			if($ary_data['panaromic_photo_night']) $guide->panaromic_photo_night = $ary_data['panaromic_photo_night'];
		}
		if($type=='ja'){
			if($ary_data['guide_voice_ja']) $guide->guide_voice_ja = $ary_data['guide_voice_ja'];
			if($ary_data['guide_text_ja']) $guide->guide_text_ja = $ary_data['guide_text_ja'];
			if($ary_data['landscape_ja']) $guide->landscape_ja = $ary_data['landscape_ja'];
			if($ary_data['tag_ja']) $guide->tag_ja = $ary_data['tag_ja'];
		}
		if($type=='en'){
			if($ary_data['guide_voice_en']) $guide->guide_voice_en = $ary_data['guide_voice_en'];
			if($ary_data['guide_text_en']) $guide->guide_text_en = $ary_data['guide_text_en'];
			if($ary_data['landscape_en']) $guide->landscape_en = $ary_data['landscape_en'];
			if($ary_data['tag_en']) $guide->tag_en = $ary_data['tag_en'];
		}
		if($type=='cn_simplified'){
			if($ary_data['guide_voice_cn_simplified']) $guide->guide_voice_cn_simplified = $ary_data['guide_voice_cn_simplified'];
			if($ary_data['guide_text_cn_simplified']) $guide->guide_text_cn_simplified = $ary_data['guide_text_cn_simplified'];
			if($ary_data['landscape_cn_simplified']) $guide->landscape_cn_simplified = $ary_data['landscape_cn_simplified'];
			if($ary_data['tag_cn_simplified']) $guide->tag_cn_simplified = $ary_data['tag_cn_simplified'];
		}
		if($type=='cn_traditional'){
			if($ary_data['guide_voice_cn_traditional']) $guide->guide_voice_cn_traditional = $ary_data['guide_voice_cn_traditional'];
			if($ary_data['guide_text_cn_traditional']) $guide->guide_text_cn_traditional = $ary_data['guide_text_cn_traditional'];
			if($ary_data['landscape_cn_traditional']) $guide->landscape_cn_traditional = $ary_data['landscape_cn_traditional'];
			if($ary_data['tag_cn_traditional']) $guide->tag_cn_traditional = $ary_data['tag_cn_traditional'];
		}
        if($type=='kr'){
			if($ary_data['guide_voice_kr']) $guide->guide_voice_kr = $ary_data['guide_voice_kr'];
			if($ary_data['guide_text_kr']) $guide->guide_text_kr = $ary_data['guide_text_kr'];
			if($ary_data['landscape_kr']) $guide->landscape_kr = $ary_data['landscape_kr'];
			if($ary_data['tag_kr']) $guide->tag_kr = $ary_data['tag_kr'];
		}
        if($type=='es'){
			if($ary_data['guide_voice_es']) $guide->guide_voice_es = $ary_data['guide_voice_es'];
			if($ary_data['guide_text_es']) $guide->guide_text_es = $ary_data['guide_text_es'];
			if($ary_data['landscape_es']) $guide->landscape_es = $ary_data['landscape_es'];
			if($ary_data['tag_es']) $guide->tag_es = $ary_data['tag_es'];
		}
		if($type=='gf'){
			if($ary_data['guide_voice_gf']) $guide->guide_voice_gf = $ary_data['guide_voice_gf'];
			if($ary_data['guide_text_gf']) $guide->guide_text_gf = $ary_data['guide_text_gf'];
			if($ary_data['landscape_gf']) $guide->landscape_gf = $ary_data['landscape_gf'];
			if($ary_data['tag_gf']) $guide->tag_gf = $ary_data['tag_gf'];
		}
		if($type=='it'){
			if($ary_data['guide_voice_it']) $guide->guide_voice_it = $ary_data['guide_voice_it'];
			if($ary_data['guide_text_it']) $guide->guide_text_it = $ary_data['guide_text_it'];
			if($ary_data['landscape_it']) $guide->landscape_it = $ary_data['landscape_it'];
			if($ary_data['tag_it']) $guide->tag_it = $ary_data['tag_it'];
		}
        if($type=='de'){
			if($ary_data['guide_voice_de']) $guide->guide_voice_de = $ary_data['guide_voice_de'];
			if($ary_data['guide_text_de']) $guide->guide_text_de = $ary_data['guide_text_de'];
			if($ary_data['landscape_de']) $guide->landscape_de = $ary_data['landscape_de'];
			if($ary_data['tag_de']) $guide->tag_de = $ary_data['tag_de'];
		}
		if($type=='ru'){
			if($ary_data['guide_voice_ru']) $guide->guide_voice_ru = $ary_data['guide_voice_ru'];
			if($ary_data['guide_text_ru']) $guide->guide_text_ru = $ary_data['guide_text_ru'];
			if($ary_data['landscape_ru']) $guide->landscape_ru = $ary_data['landscape_ru'];
			if($ary_data['tag_ru']) $guide->tag_ru = $ary_data['tag_ru'];
		}
		if($type=='th'){
			if($ary_data['guide_voice_th']) $guide->guide_voice_th = $ary_data['guide_voice_th'];
			if($ary_data['guide_text_th']) $guide->guide_text_th = $ary_data['guide_text_th'];
			if($ary_data['landscape_th']) $guide->landscape_th = $ary_data['landscape_th'];
			if($ary_data['tag_th']) $guide->tag_th = $ary_data['tag_th'];
		}
		if($type=='vn'){
			if($ary_data['guide_voice_vn']) $guide->guide_voice_vn = $ary_data['guide_voice_vn'];
			if($ary_data['guide_text_vn']) $guide->guide_text_vn = $ary_data['guide_text_vn'];
			if($ary_data['landscape_vn']) $guide->landscape_vn = $ary_data['landscape_vn'];
			if($ary_data['tag_vn']) $guide->tag_vn = $ary_data['tag_vn'];
		}
		if($type=='id'){
			if($ary_data['guide_voice_id']) $guide->guide_voice_id = $ary_data['guide_voice_id'];
			if($ary_data['guide_text_id']) $guide->guide_text_id = $ary_data['guide_text_id'];
			if($ary_data['landscape_id']) $guide->landscape_id = $ary_data['landscape_id'];
			if($ary_data['tag_id']) $guide->tag_id = $ary_data['tag_id'];
		}
        $guide->save();

        $update_status = Guide::updateStatus($guide->id);
        if ($update_status != null) {
            return $update_status;
        }else{
            return $guide->id;
        }        
    }
    
	 public static function deleteGuide($guide_id){
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $info_guide = Guide::find($guide_id);
        if($info_guide){
            $info_guide->is_deleted = 1;
            $info_guide->save();
            return true;
        }else{
            return false;
        }
    }

    public static function updateStatus($guide_id){
        $info_guide = Guide::where('id',$guide_id)
                            ->where('is_deleted',0)
                            ->first();
        if($info_guide){
            if(!empty($info_guide->panaromic_photo_noon) && !empty($info_guide->panaromic_photo_night) 
                && !empty($info_guide->guide_voice_ja) && !empty($info_guide->guide_text_ja) 
                && !empty($info_guide->landscape_ja) && !empty($info_guide->tag_ja)
                && !empty($info_guide->guide_voice_en) && !empty($info_guide->guide_text_en) 
                && !empty($info_guide->landscape_en) && !empty($info_guide->tag_en)
                && !empty($info_guide->guide_voice_cn_simplified) && !empty($info_guide->guide_text_cn_simplified) 
                && !empty($info_guide->landscape_cn_simplified) && !empty($info_guide->tag_cn_simplified)
                && !empty($info_guide->guide_voice_cn_traditional) && !empty($info_guide->guide_text_cn_traditional) 
                && !empty($info_guide->landscape_cn_traditional) && !empty($info_guide->tag_cn_traditional)
                && !empty($info_guide->guide_voice_kr) && !empty($info_guide->guide_text_kr) 
                && !empty($info_guide->landscape_kr) && !empty($info_guide->tag_kr)
                && !empty($info_guide->guide_voice_es) && !empty($info_guide->guide_text_es) 
                && !empty($info_guide->landscape_es) && !empty($info_guide->tag_es)
                && !empty($info_guide->guide_voice_gf) && !empty($info_guide->guide_text_gf) 
                && !empty($info_guide->landscape_gf) && !empty($info_guide->tag_gf)
                && !empty($info_guide->guide_voice_it) && !empty($info_guide->guide_text_it) 
                && !empty($info_guide->landscape_it) && !empty($info_guide->tag_it)
                && !empty($info_guide->guide_voice_de) && !empty($info_guide->guide_text_de) 
                && !empty($info_guide->landscape_de) && !empty($info_guide->tag_de)
                && !empty($info_guide->guide_voice_ru) && !empty($info_guide->guide_text_ru) 
                && !empty($info_guide->landscape_ru) && !empty($info_guide->tag_ru)
                && !empty($info_guide->guide_voice_th) && !empty($info_guide->guide_text_th) 
                && !empty($info_guide->landscape_th) && !empty($info_guide->tag_th)
                && !empty($info_guide->guide_voice_vn) && !empty($info_guide->guide_text_vn) 
                && !empty($info_guide->landscape_vn) && !empty($info_guide->tag_vn)
                && !empty($info_guide->guide_voice_id) && !empty($info_guide->guide_text_id) 
                && !empty($info_guide->landscape_id) && !empty($info_guide->tag_id)
            ){
                $info_guide->status = 1;
            }else{
                $info_guide->status = 0;
            }
            $info_guide->save();
            return $info_guide->id;
        }else{
            return null;
        }
    }
}
