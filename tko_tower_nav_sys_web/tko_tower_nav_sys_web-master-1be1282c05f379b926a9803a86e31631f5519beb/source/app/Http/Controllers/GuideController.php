<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Support\FileProcess;
use App\Guide;
use App\Beacon;
use Storage;
use Carbon\Carbon;


class GuideController extends Controller
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
                $data = Guide::getListGuide($page_limit,$page_number);
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
            $beacon_id = $request->input('beacon_id');
            $ary_data = array();
			$current_timestamp = Carbon::now()->timestamp;
            if ($request->hasFile('panaromic_photo_noon')) {
                $panaromic_photo_noon = $request->file('panaromic_photo_noon');
                $upload_panaromic_photo_noon = FileProcess::upload('panaromic/noon/'.$current_timestamp.'-'.$panaromic_photo_noon->getClientOriginalName(),$panaromic_photo_noon->path());
                if ($upload_panaromic_photo_noon) {
                    $ary_data['panaromic_photo_noon'] = $current_timestamp.'-'.$panaromic_photo_noon->getClientOriginalName();
                }else{
                    $ary_data['panaromic_photo_noon'] = null;
                }
            }else{
                $ary_data['panaromic_photo_noon'] = null;
            }
            
            if ($request->hasFile('panaromic_photo_night')) {
                $panaromic_photo_night = $request->file('panaromic_photo_night');
                $upload_panaromic_photo_night = FileProcess::upload('panaromic/night/'.$current_timestamp.'-'.$panaromic_photo_night->getClientOriginalName(),$panaromic_photo_night->path());
                if ($upload_panaromic_photo_night) {
                    $ary_data['panaromic_photo_night'] = $current_timestamp.'-'.$panaromic_photo_night->getClientOriginalName();
                }else{
                    $ary_data['panaromic_photo_night'] = null;
                }
            }else{
                $ary_data['panaromic_photo_night'] = null;
            }
            

            $add_guide = Guide::addGuide($ary_data);
            if ($add_guide) {
				if($beacon_id){
					$update_guide_id = Beacon::updateGuideId($beacon_id,$add_guide);
					if($update_guide_id){
						$success = true;
						$data = $add_guide;
					}else{
						$success = false;
					}
				}else{
					$success = true;
					$data = $add_guide;
				}
                
                
            }else{
                $success = false;
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
		$check = null;
		try{
			$type = $request->input('type');
			$beacon_id = $request->input('beacon_id');
			$guide_id = $request->input('guide_id');
			$check = Beacon::checkExistBeaconAndGuide($beacon_id,$guide_id);
			$guide = Guide::getDetailById($guide_id);
			
			$current_timestamp = Carbon::now()->timestamp;
			
			$ary_data = array();
			if($type=='pana'){
				if ($request->hasFile('panaromic_photo_noon')) {
					if($guide->panaromic_photo_noon) FileProcess::remove('panaromic/noon/'.$guide->panaromic_photo_noon);
					$panaromic_photo_noon = $request->file('panaromic_photo_noon');
					$upload_panaromic_photo_noon = FileProcess::upload('panaromic/noon/'.$current_timestamp.'-'.$panaromic_photo_noon->getClientOriginalName(),$panaromic_photo_noon->path());
					if ($upload_panaromic_photo_noon) {
						$ary_data['panaromic_photo_noon'] = $current_timestamp.'-'.$panaromic_photo_noon->getClientOriginalName();
					}else{
						$ary_data['panaromic_photo_noon'] = null;
					}
				}else{
					$ary_data['panaromic_photo_noon'] = null;
				}
				
				if ($request->hasFile('panaromic_photo_night')) {
					if($guide->panaromic_photo_night) FileProcess::remove('panaromic/night/'.$guide->panaromic_photo_night);
					$panaromic_photo_night = $request->file('panaromic_photo_night');
					$upload_panaromic_photo_night = FileProcess::upload('panaromic/night/'.$current_timestamp.'-'.$panaromic_photo_night->getClientOriginalName(),$panaromic_photo_night->path());
					if ($upload_panaromic_photo_night) {
						$ary_data['panaromic_photo_night'] = $current_timestamp.'-'.$panaromic_photo_night->getClientOriginalName();
					}else{
						$ary_data['panaromic_photo_night'] = null;
					}
				}else{
					$ary_data['panaromic_photo_night'] = null;
				}
			}
				
			
			if($type=='ja'){
				if ($request->hasFile('guide_voice_ja')) {
					if($guide->guide_voice_ja) FileProcess::remove('voice/ja/'.$guide->guide_voice_ja);
					$guide_voice_ja = $request->file('guide_voice_ja');
					$upload_voice_ja = FileProcess::upload('voice/ja/'.$current_timestamp.'-'.$guide_voice_ja->getClientOriginalName(),$guide_voice_ja->path());
					if ($upload_voice_ja) {
						$ary_data['guide_voice_ja'] = $current_timestamp.'-'.$guide_voice_ja->getClientOriginalName();
					}else{
						$ary_data['guide_voice_ja'] = null;
					}
				}else{
					$ary_data['guide_voice_ja'] = null;
				}

				if ($request->hasFile('guide_text_ja')) {
					if($guide->guide_text_ja) FileProcess::remove('text/ja/'.$guide->guide_text_ja);
					$guide_text_ja = $request->file('guide_text_ja');
					$upload_text_ja = FileProcess::upload('text/ja/'.$current_timestamp.'-'.$guide_text_ja->getClientOriginalName(),$guide_text_ja->path());
					if ($upload_text_ja) {
						$ary_data['guide_text_ja'] = $current_timestamp.'-'.$guide_text_ja->getClientOriginalName();
					}else{
						$ary_data['guide_text_ja'] = null;
					}
				}else{
					$ary_data['guide_text_ja'] = null;
				}

				if ($request->hasFile('landscape_ja')) {
					if($guide->landscape_ja) FileProcess::remove('landscape/ja/'.$guide->landscape_ja);
					$landscape_ja = $request->file('landscape_ja');
					$upload_landscape_ja = FileProcess::upload('landscape/ja/'.$current_timestamp.'-'.$landscape_ja->getClientOriginalName(),$landscape_ja->path());
					if ($upload_landscape_ja) {
						$ary_data['landscape_ja'] = $current_timestamp.'-'.$landscape_ja->getClientOriginalName();
					}else{
						$ary_data['landscape_ja'] = null;
					}
				}else{
					$ary_data['landscape_ja'] = null;
				}

				if ($request->hasFile('tag_ja')) {
					if($guide->tag_ja) FileProcess::remove('tag/ja/'.$guide->tag_ja);
					$tag_ja = $request->file('tag_ja');
					$upload_tag_ja = FileProcess::upload('tag/ja/'.$current_timestamp.'-'.$tag_ja->getClientOriginalName(),$tag_ja->path());
					if ($upload_tag_ja) {
						$ary_data['tag_ja'] = $current_timestamp.'-'.$tag_ja->getClientOriginalName();
					}else{
						$ary_data['tag_ja'] = null;
					}
				}else{
					$ary_data['tag_ja'] = null;
				}
			}
			if($type=='en'){
				if ($request->hasFile('guide_voice_en')) {
					if($guide->guide_voice_en) FileProcess::remove('voice/en/'.$guide->guide_voice_en);
					$guide_voice_en = $request->file('guide_voice_en');
					
					$upload_voice_en = FileProcess::upload('voice/en/'.$current_timestamp.'-'.$guide_voice_en->getClientOriginalName(),$guide_voice_en->path());
					if ($upload_voice_en) {
						$ary_data['guide_voice_en'] = $current_timestamp.'-'.$guide_voice_en->getClientOriginalName();
					}else{
						$ary_data['guide_voice_en'] = null;
					}
				}else{
					$ary_data['guide_voice_en'] = null;
				}

				if ($request->hasFile('guide_text_en')) {
					if($guide->guide_text_en) FileProcess::remove('text/en/'.$guide->guide_text_en);
					$guide_text_en = $request->file('guide_text_en');
					$upload_text_en = FileProcess::upload('text/en/'.$current_timestamp.'-'.$guide_text_en->getClientOriginalName(),$guide_text_en->path());
					if ($upload_text_en) {
						$ary_data['guide_text_en'] = $current_timestamp.'-'.$guide_text_en->getClientOriginalName();
					}else{
						$ary_data['guide_text_en'] = null;
					}
				}else{
					$ary_data['guide_text_en'] = null;
				}

				if ($request->hasFile('landscape_en')) {
					if($guide->landscape_en) FileProcess::remove('landscape/en/'.$guide->landscape_en);
					$landscape_en = $request->file('landscape_en');
					$upload_landscape_en = FileProcess::upload('landscape/en/'.$current_timestamp.'-'.$landscape_en->getClientOriginalName(),$landscape_en->path());
					if ($upload_landscape_en) {
						$ary_data['landscape_en'] = $current_timestamp.'-'.$landscape_en->getClientOriginalName();
					}else{
						$ary_data['landscape_en'] = null;
					}
				}else{
					$ary_data['landscape_en'] = null;
				}

				if ($request->hasFile('tag_en')) {
					if($guide->tag_en) FileProcess::remove('tag/en/'.$guide->tag_en);
					$tag_en = $request->file('tag_en');
					$upload_tag_en = FileProcess::upload('tag/en/'.$current_timestamp.'-'.$tag_en->getClientOriginalName(),$tag_en->path());
					if ($upload_tag_en) {
						$ary_data['tag_en'] = $current_timestamp.'-'.$tag_en->getClientOriginalName();
					}else{
						$ary_data['tag_en'] = null;
					}
				}else{
					$ary_data['tag_en'] = null;
				}
			}
			if($type=='cn_simplified'){
				if ($request->hasFile('guide_voice_cn_simplified')) {
					if($guide->guide_voice_cn_simplified) FileProcess::remove('voice/cn_simplified/'.$guide->guide_voice_cn_simplified);
					$guide_voice_cn_simplified = $request->file('guide_voice_cn_simplified');
					$upload_voice_cn_simplified = FileProcess::upload('voice/cn_simplified/'.$current_timestamp.'-'.$guide_voice_cn_simplified->getClientOriginalName(),$guide_voice_cn_simplified->path());
					if ($upload_voice_cn_simplified) {
						$ary_data['guide_voice_cn_simplified'] = $current_timestamp.'-'.$guide_voice_cn_simplified->getClientOriginalName();
					}else{
						$ary_data['guide_voice_cn_simplified'] = null;
					}
				}else{
					$ary_data['guide_voice_cn_simplified'] = null;
				}

				if ($request->hasFile('guide_text_cn_simplified')) {
					if($guide->guide_text_cn_simplified) FileProcess::remove('text/cn_simplified/'.$guide->guide_text_cn_simplified);
					$guide_text_cn_simplified = $request->file('guide_text_cn_simplified');
					$upload_text_cn_simplified = FileProcess::upload('text/cn_simplified/'.$current_timestamp.'-'.$guide_text_cn_simplified->getClientOriginalName(),$guide_text_cn_simplified->path());
					if ($upload_text_cn_simplified) {
						$ary_data['guide_text_cn_simplified'] = $current_timestamp.'-'.$guide_text_cn_simplified->getClientOriginalName();
					}else{
						$ary_data['guide_text_cn_simplified'] = null;
					}
				}else{
					$ary_data['guide_text_cn_simplified'] = null;
				}

				if ($request->hasFile('landscape_cn_simplified')) {
					if($guide->landscape_cn_simplified) FileProcess::remove('landscape/cn_simplified/'.$guide->landscape_cn_simplified);
					$landscape_cn_simplified = $request->file('landscape_cn_simplified');
					$upload_landscape_cn_simplified = FileProcess::upload('landscape/cn_simplified/'.$current_timestamp.'-'.$landscape_cn_simplified->getClientOriginalName(),$landscape_cn_simplified->path());
					if ($upload_landscape_cn_simplified) {
						$ary_data['landscape_cn_simplified'] = $current_timestamp.'-'.$landscape_cn_simplified->getClientOriginalName();
					}else{
						$ary_data['landscape_cn_simplified'] = null;
					}
				}else{
					$ary_data['landscape_cn_simplified'] = null;
				}

				if ($request->hasFile('tag_cn_simplified')) {
					if($guide->tag_cn_simplified) FileProcess::remove('tag/cn_simplified/'.$guide->tag_cn_simplified);
					$tag_cn_simplified = $request->file('tag_cn_simplified');
					$upload_tag_cn_simplified = FileProcess::upload('tag/cn_simplified/'.$current_timestamp.'-'.$tag_cn_simplified->getClientOriginalName(),$tag_cn_simplified->path());
					if ($upload_tag_cn_simplified) {
						$ary_data['tag_cn_simplified'] = $current_timestamp.'-'.$tag_cn_simplified->getClientOriginalName();
					}else{
						$ary_data['tag_cn_simplified'] = null;
					}
				}else{
					$ary_data['tag_cn_simplified'] = null;
				}
			}
			if($type=='cn_traditional'){
				if ($request->hasFile('guide_voice_cn_traditional')) {
					if($guide->guide_voice_cn_traditional) FileProcess::remove('voice/cn_traditional/'.$guide->guide_voice_cn_traditional);
					$guide_voice_cn_traditional = $request->file('guide_voice_cn_traditional');
					$upload_voice_cn_traditional = FileProcess::upload('voice/cn_traditional/'.$current_timestamp.'-'.$guide_voice_cn_traditional->getClientOriginalName(),$guide_voice_cn_traditional->path());
					if ($upload_voice_cn_traditional) {
						$ary_data['guide_voice_cn_traditional'] = $current_timestamp.'-'.$guide_voice_cn_traditional->getClientOriginalName();
					}else{
						$ary_data['guide_voice_cn_traditional'] = null;
					}
				}else{
					$ary_data['guide_voice_cn_traditional'] = null;
				}

				if ($request->hasFile('guide_text_cn_traditional')) {
					if($guide->guide_text_cn_traditional) FileProcess::remove('text/cn_traditional/'.$guide->guide_text_cn_traditional);
					$guide_text_cn_traditional = $request->file('guide_text_cn_traditional');
					$upload_text_cn_traditional = FileProcess::upload('text/cn_traditional/'.$current_timestamp.'-'.$guide_text_cn_traditional->getClientOriginalName(),$guide_text_cn_traditional->path());
					if ($upload_text_cn_traditional) {
						$ary_data['guide_text_cn_traditional'] = $current_timestamp.'-'.$guide_text_cn_traditional->getClientOriginalName();
					}else{
						$ary_data['guide_text_cn_traditional'] = null;
					}
				}else{
					$ary_data['guide_text_cn_traditional'] = null;
				}

				if ($request->hasFile('landscape_cn_traditional')) {
					if($guide->landscape_cn_traditional) FileProcess::remove('landscape/cn_traditional/'.$guide->landscape_cn_traditional);
					$landscape_cn_traditional = $request->file('landscape_cn_traditional');
					$upload_landscape_cn_traditional = FileProcess::upload('landscape/cn_traditional/'.$current_timestamp.'-'.$landscape_cn_traditional->getClientOriginalName(),$landscape_cn_traditional->path());
					if ($upload_landscape_cn_traditional) {
						$ary_data['landscape_cn_traditional'] = $current_timestamp.'-'.$landscape_cn_traditional->getClientOriginalName();
					}else{
						$ary_data['landscape_cn_traditional'] = null;
					}
				}else{
					$ary_data['landscape_cn_traditional'] = null;
				}

				if ($request->hasFile('tag_cn_traditional')) {
					if($guide->tag_cn_traditional) FileProcess::remove('tag/cn_traditional/'.$guide->tag_cn_traditional);
					$tag_cn_traditional = $request->file('tag_cn_traditional');
					$upload_tag_cn_traditional = FileProcess::upload('tag/cn_traditional/'.$current_timestamp.'-'.$tag_cn_traditional->getClientOriginalName(),$tag_cn_traditional->path());
					if ($upload_tag_cn_traditional) {
						$ary_data['tag_cn_traditional'] = $current_timestamp.'-'.$tag_cn_traditional->getClientOriginalName();
					}else{
						$ary_data['tag_cn_traditional'] = null;
					}
				}else{
					$ary_data['tag_cn_traditional'] = null;
				}
			}
			if($type=='kr'){
				if ($request->hasFile('guide_voice_kr')) {
					if($guide->guide_voice_kr) FileProcess::remove('voice/kr/'.$guide->guide_voice_kr);
					$guide_voice_kr = $request->file('guide_voice_kr');
					$upload_voice_kr = FileProcess::upload('voice/kr/'.$current_timestamp.'-'.$guide_voice_kr->getClientOriginalName(),$guide_voice_kr->path());
					if ($upload_voice_kr) {
						$ary_data['guide_voice_kr'] = $current_timestamp.'-'.$guide_voice_kr->getClientOriginalName();
					}else{
						$ary_data['guide_voice_kr'] = null;
					}
				}else{
					$ary_data['guide_voice_kr'] = null;
				}

				if ($request->hasFile('guide_text_kr')) {
					if($guide->guide_text_kr) FileProcess::remove('text/kr/'.$guide->guide_text_kr);
					$guide_text_kr = $request->file('guide_text_kr');
					$upload_text_kr = FileProcess::upload('text/kr/'.$current_timestamp.'-'.$guide_text_kr->getClientOriginalName(),$guide_text_kr->path());
					if ($upload_text_kr) {
						$ary_data['guide_text_kr'] = $current_timestamp.'-'.$guide_text_kr->getClientOriginalName();
					}else{
						$ary_data['guide_text_kr'] = null;
					}
				}else{
					$ary_data['guide_text_kr'] = null;
				}

				if ($request->hasFile('landscape_kr')) {
					if($guide->landscape_kr) FileProcess::remove('landscape/kr/'.$guide->landscape_kr);
					$landscape_kr = $request->file('landscape_kr');
					$upload_landscape_kr = FileProcess::upload('landscape/kr/'.$current_timestamp.'-'.$landscape_kr->getClientOriginalName(),$landscape_kr->path());
					if ($upload_landscape_kr) {
						$ary_data['landscape_kr'] = $current_timestamp.'-'.$landscape_kr->getClientOriginalName();
					}else{
						$ary_data['landscape_kr'] = null;
					}
				}else{
					$ary_data['landscape_kr'] = null;
				}

				if ($request->hasFile('tag_kr')) {
					if($guide->tag_kr) FileProcess::remove('tag/kr/'.$guide->tag_kr);
					$tag_kr = $request->file('tag_kr');
					$upload_tag_kr = FileProcess::upload('tag/kr/'.$current_timestamp.'-'.$tag_kr->getClientOriginalName(),$tag_kr->path());
					if ($upload_tag_kr) {
						$ary_data['tag_kr'] = $current_timestamp.'-'.$tag_kr->getClientOriginalName();
					}else{
						$ary_data['tag_kr'] = null;
					}
				}else{
					$ary_data['tag_kr'] = null;
				}
			}
			if($type=='es'){
				if ($request->hasFile('guide_voice_es')) {
					if($guide->guide_voice_es) FileProcess::remove('voice/es/'.$guide->guide_voice_es);
					$guide_voice_es = $request->file('guide_voice_es');
					$upload_voice_es = FileProcess::upload('voice/es/'.$current_timestamp.'-'.$guide_voice_es->getClientOriginalName(),$guide_voice_es->path());
					if ($upload_voice_es) {
						$ary_data['guide_voice_es'] = $current_timestamp.'-'.$guide_voice_es->getClientOriginalName();
					}else{
						$ary_data['guide_voice_es'] = null;
					}
				}else{
					$ary_data['guide_voice_es'] = null;
				}

				if ($request->hasFile('guide_text_es')) {
					if($guide->guide_text_es) FileProcess::remove('text/es/'.$guide->guide_text_es);
					$guide_text_es = $request->file('guide_text_es');
					$upload_text_es = FileProcess::upload('text/es/'.$current_timestamp.'-'.$guide_text_es->getClientOriginalName(),$guide_text_es->path());
					if ($upload_text_es) {
						$ary_data['guide_text_es'] = $current_timestamp.'-'.$guide_text_es->getClientOriginalName();
					}else{
						$ary_data['guide_text_es'] = null;
					}
				}else{
					$ary_data['guide_text_es'] = null;
				}

				if ($request->hasFile('landscape_es')) {
					if($guide->landscape_es) FileProcess::remove('landscape/es/'.$guide->landscape_es);
					$landscape_es = $request->file('landscape_es');
					$upload_landscape_es = FileProcess::upload('landscape/es/'.$current_timestamp.'-'.$landscape_es->getClientOriginalName(),$landscape_es->path());
					if ($upload_landscape_es) {
						$ary_data['landscape_es'] = $current_timestamp.'-'.$landscape_es->getClientOriginalName();
					}else{
						$ary_data['landscape_es'] = null;
					}
				}else{
					$ary_data['landscape_es'] = null;
				}

				if ($request->hasFile('tag_es')) {
					if($guide->tag_es) FileProcess::remove('tag/es/'.$guide->tag_es);
					$tag_es = $request->file('tag_es');
					$upload_tag_es = FileProcess::upload('tag/es/'.$current_timestamp.'-'.$tag_es->getClientOriginalName(),$tag_es->path());
					if ($upload_tag_es) {
						$ary_data['tag_es'] = $current_timestamp.'-'.$tag_es->getClientOriginalName();
					}else{
						$ary_data['tag_es'] = null;
					}
				}else{
					$ary_data['tag_es'] = null;
				}
			}
			if($type=='gf'){
				if ($request->hasFile('guide_voice_gf')){
					if($guide->guide_voice_gf) FileProcess::remove('voice/gf/'.$guide->guide_voice_gf);
					$guide_voice_gf = $request->file('guide_voice_gf');
					$upload_voice_gf = FileProcess::upload('voice/gf/'.$current_timestamp.'-'.$guide_voice_gf->getClientOriginalName(),$guide_voice_gf->path());
					if ($upload_voice_gf) {
						$ary_data['guide_voice_gf'] = $current_timestamp.'-'.$guide_voice_gf->getClientOriginalName();
					}else{
						$ary_data['guide_voice_gf'] = null;
					}
				}else{
					$ary_data['guide_voice_gf'] = null;
				}

				if ($request->hasFile('guide_text_gf')){
					if($guide->guide_text_gf) FileProcess::remove('text/gf/'.$guide->guide_text_gf);
					$guide_text_gf = $request->file('guide_text_gf');
					$upload_text_gf = FileProcess::upload('text/gf/'.$current_timestamp.'-'.$guide_text_gf->getClientOriginalName(),$guide_text_gf->path());
					if ($upload_text_gf) {
						$ary_data['guide_text_gf'] = $current_timestamp.'-'.$guide_text_gf->getClientOriginalName();
					}else{
						$ary_data['guide_text_gf'] = null;
					}
				}else{
					$ary_data['guide_text_gf'] = null;
				}

				if ($request->hasFile('landscape_gf')){
					if($guide->landscape_gf) FileProcess::remove('landscape/gf/'.$guide->landscape_gf);
					$landscape_gf = $request->file('landscape_gf');
					$upload_landscape_gf = FileProcess::upload('landscape/gf/'.$current_timestamp.'-'.$landscape_gf->getClientOriginalName(),$landscape_gf->path());
					if ($upload_landscape_gf) {
						$ary_data['landscape_gf'] = $current_timestamp.'-'.$landscape_gf->getClientOriginalName();
					}else{
						$ary_data['landscape_gf'] = null;
					}
				}else{
					$ary_data['landscape_gf'] = null;
				}

				if ($request->hasFile('tag_gf')){
					if($guide->tag_gf) FileProcess::remove('tag/gf/'.$guide->tag_gf);
					$tag_gf = $request->file('tag_gf');
					$upload_tag_gf = FileProcess::upload('tag/gf/'.$current_timestamp.'-'.$tag_gf->getClientOriginalName(),$tag_gf->path());
					if ($upload_tag_gf) {
						$ary_data['tag_gf'] = $current_timestamp.'-'.$tag_gf->getClientOriginalName();
					}else{
						$ary_data['tag_gf'] = null;
					}
				}else{
					$ary_data['tag_gf'] = null;
				}
			}
			if($type=='it'){
				if ($request->hasFile('guide_voice_it')){
					if($guide->guide_voice_it) FileProcess::remove('voice/it/'.$guide->guide_voice_it);
					$guide_voice_it = $request->file('guide_voice_it');
					$upload_voice_it = FileProcess::upload('voice/it/'.$current_timestamp.'-'.$guide_voice_it->getClientOriginalName(),$guide_voice_it->path());
					if ($upload_voice_it) {
						$ary_data['guide_voice_it'] = $current_timestamp.'-'.$guide_voice_it->getClientOriginalName();
					}else{
						$ary_data['guide_voice_it'] = null;
					}
				}else{
					$ary_data['guide_voice_it'] = null;
				}

				if ($request->hasFile('guide_text_it')) {
					if($guide->guide_text_it) FileProcess::remove('text/it/'.$guide->guide_text_it);
					$guide_text_it = $request->file('guide_text_it');
					$upload_text_it = FileProcess::upload('text/it/'.$current_timestamp.'-'.$guide_text_it->getClientOriginalName(),$guide_text_it->path());
					if ($upload_text_it) {
						$ary_data['guide_text_it'] = $current_timestamp.'-'.$guide_text_it->getClientOriginalName();
					}else{
						$ary_data['guide_text_it'] = null;
					}
				}else{
					$ary_data['guide_text_it'] = null;
				}

				if ($request->hasFile('landscape_it')){
					if($guide->landscape_it) FileProcess::remove('landscape/it/'.$guide->landscape_it);
					$landscape_it = $request->file('landscape_it');
					$upload_landscape_it = FileProcess::upload('landscape/it/'.$current_timestamp.'-'.$landscape_it->getClientOriginalName(),$landscape_it->path());
					if ($upload_landscape_it) {
						$ary_data['landscape_it'] = $current_timestamp.'-'.$landscape_it->getClientOriginalName();
					}else{
						$ary_data['landscape_it'] = null;
					}
				}else{
					$ary_data['landscape_it'] = null;
				}

				if ($request->hasFile('tag_it')) {
					if($guide->tag_it) FileProcess::remove('tag/it/'.$guide->tag_it);
					$tag_it = $request->file('tag_it');
					$upload_tag_it = FileProcess::upload('tag/it/'.$current_timestamp.'-'.$tag_it->getClientOriginalName(),$tag_it->path());
					if ($upload_tag_it) {
						$ary_data['tag_it'] = $current_timestamp.'-'.$tag_it->getClientOriginalName();
					}else{
						$ary_data['tag_it'] = null;
					}
				}else{
					$ary_data['tag_it'] = null;
				}
			}
			if($type=='de'){
				if ($request->hasFile('guide_voice_de')) {
					if($guide->guide_voice_de) FileProcess::remove('voice/de/'.$guide->guide_voice_de);
					$guide_voice_de = $request->file('guide_voice_de');
					$upload_voice_de = FileProcess::upload('voice/de/'.$current_timestamp.'-'.$guide_voice_de->getClientOriginalName(),$guide_voice_de->path());
					if ($upload_voice_de) {
						$ary_data['guide_voice_de'] = $current_timestamp.'-'.$guide_voice_de->getClientOriginalName();
					}else{
						$ary_data['guide_voice_de'] = null;
					}
				}else{
					$ary_data['guide_voice_de'] = null;
				}

				if ($request->hasFile('guide_text_de')){
					if($guide->guide_text_de) FileProcess::remove('text/de/'.$guide->guide_text_de);
					$guide_text_de = $request->file('guide_text_de');
					$upload_text_de = FileProcess::upload('text/de/'.$current_timestamp.'-'.$guide_text_de->getClientOriginalName(),$guide_text_de->path());
					if ($upload_text_de) {
						$ary_data['guide_text_de'] = $current_timestamp.'-'.$guide_text_de->getClientOriginalName();
					}else{
						$ary_data['guide_text_de'] = null;
					}
				}else{
					$ary_data['guide_text_de'] = null;
				}

				if ($request->hasFile('landscape_de')){
					if($guide->landscape_de) FileProcess::remove('landscape/de/'.$guide->landscape_de);
					$landscape_de = $request->file('landscape_de');
					$upload_landscape_de = FileProcess::upload('landscape/de/'.$current_timestamp.'-'.$landscape_de->getClientOriginalName(),$landscape_de->path());
					if ($upload_landscape_de) {
						$ary_data['landscape_de'] = $current_timestamp.'-'.$landscape_de->getClientOriginalName();
					}else{
						$ary_data['landscape_de'] = null;
					}
				}else{
					$ary_data['landscape_de'] = null;
				}

				if ($request->hasFile('tag_de')) {
					if($guide->tag_de) FileProcess::remove('tag/de/'.$guide->tag_de);
					$tag_de = $request->file('tag_de');
					$upload_tag_de = FileProcess::upload('tag/de/'.$current_timestamp.'-'.$tag_de->getClientOriginalName(),$tag_de->path());
					if ($upload_tag_de) {
						$ary_data['tag_de'] = $current_timestamp.'-'.$tag_de->getClientOriginalName();
					}else{
						$ary_data['tag_de'] = null;
					}
				}else{
					$ary_data['tag_de'] = null;
				}
			}
			if($type=='ru'){
				if ($request->hasFile('guide_voice_ru')) {
					if($guide->guide_voice_ru) FileProcess::remove('voice/ru/'.$guide->guide_voice_ru);
					$guide_voice_ru = $request->file('guide_voice_ru');
					$upload_voice_ru = FileProcess::upload('voice/ru/'.$current_timestamp.'-'.$guide_voice_ru->getClientOriginalName(),$guide_voice_ru->path());
					if ($upload_voice_ru) {
						$ary_data['guide_voice_ru'] = $current_timestamp.'-'.$guide_voice_ru->getClientOriginalName();
					}else{
						$ary_data['guide_voice_ru'] = null;
					}
				}else{
					$ary_data['guide_voice_ru'] = null;
				}

				if ($request->hasFile('guide_text_ru')) {
					if($guide->guide_text_ru) FileProcess::remove('text/ru/'.$guide->guide_text_ru);
					$guide_text_ru = $request->file('guide_text_ru');
					$upload_text_ru = FileProcess::upload('text/ru/'.$current_timestamp.'-'.$guide_text_ru->getClientOriginalName(),$guide_text_ru->path());
					if ($upload_text_ru) {
						$ary_data['guide_text_ru'] = $current_timestamp.'-'.$guide_text_ru->getClientOriginalName();
					}else{
						$ary_data['guide_text_ru'] = null;
					}
				}else{
					$ary_data['guide_text_ru'] = null;
				}

				if ($request->hasFile('landscape_ru')) {
					if($guide->landscape_ru) FileProcess::remove('landscape/ru/'.$guide->landscape_ru);
					$landscape_ru = $request->file('landscape_ru');
					$upload_landscape_ru = FileProcess::upload('landscape/ru/'.$current_timestamp.'-'.$landscape_ru->getClientOriginalName(),$landscape_ru->path());
					if ($upload_landscape_ru) {
						$ary_data['landscape_ru'] = $current_timestamp.'-'.$landscape_ru->getClientOriginalName();
					}else{
						$ary_data['landscape_ru'] = null;
					}
				}else{
					$ary_data['landscape_ru'] = null;
				}

				if ($request->hasFile('tag_ru')) {
					if($guide->tag_ru) FileProcess::remove('tag/ru/'.$guide->tag_ru);
					$tag_ru = $request->file('tag_ru');
					$upload_tag_ru = FileProcess::upload('tag/ru/'.$current_timestamp.'-'.$tag_ru->getClientOriginalName(),$tag_ru->path());
					if ($upload_tag_ru) {
						$ary_data['tag_ru'] = $current_timestamp.'-'.$tag_ru->getClientOriginalName();
					}else{
						$ary_data['tag_ru'] = null;
					}
				}else{
					$ary_data['tag_ru'] = null;
				}
			}
			if($type=='th'){
				if ($request->hasFile('guide_voice_th')){
					if($guide->guide_voice_th) FileProcess::remove('voice/th/'.$guide->guide_voice_th);
					$guide_voice_th = $request->file('guide_voice_th');
					$upload_voice_th = FileProcess::upload('voice/th/'.$current_timestamp.'-'.$guide_voice_th->getClientOriginalName(),$guide_voice_th->path());
					if ($upload_voice_th) {
						$ary_data['guide_voice_th'] = $current_timestamp.'-'.$guide_voice_th->getClientOriginalName();
					}else{
						$ary_data['guide_voice_th'] = null;
					}
				}else{
					$ary_data['guide_voice_th'] = null;
				}

				if ($request->hasFile('guide_text_th')) {
					if($guide->guide_text_th) FileProcess::remove('text/th/'.$guide->guide_text_th);
					$guide_text_th = $request->file('guide_text_th');
					$upload_text_th = FileProcess::upload('text/th/'.$current_timestamp.'-'.$guide_text_th->getClientOriginalName(),$guide_text_th->path());
					if ($upload_text_th) {
						$ary_data['guide_text_th'] = $current_timestamp.'-'.$guide_text_th->getClientOriginalName();
					}else{
						$ary_data['guide_text_th'] = null;
					}
				}else{
					$ary_data['guide_text_th'] = null;
				}

				if ($request->hasFile('landscape_th')){
					if($guide->landscape_th) FileProcess::remove('landscape/th/'.$guide->landscape_th);
					$landscape_th = $request->file('landscape_th');
					$upload_landscape_th = FileProcess::upload('landscape/th/'.$current_timestamp.'-'.$landscape_th->getClientOriginalName(),$landscape_th->path());
					if ($upload_landscape_th) {
						$ary_data['landscape_th'] = $current_timestamp.'-'.$landscape_th->getClientOriginalName();
					}else{
						$ary_data['landscape_th'] = null;
					}
				}else{
					$ary_data['landscape_th'] = null;
				}

				if ($request->hasFile('tag_th')) {
					if($guide->tag_th) FileProcess::remove('tag/th/'.$guide->tag_th);
					$tag_th = $request->file('tag_th');
					$upload_tag_th = FileProcess::upload('tag/th/'.$current_timestamp.'-'.$tag_th->getClientOriginalName(),$tag_th->path());
					if ($upload_tag_th) {
						$ary_data['tag_th'] = $current_timestamp.'-'.$tag_th->getClientOriginalName();
					}else{
						$ary_data['tag_th'] = null;
					}
				}else{
					$ary_data['tag_th'] = null;
				}
			}
			if($type=='vn'){
				if ($request->hasFile('guide_voice_vn')){
					if($guide->guide_voice_vn) FileProcess::remove('voice/vn/'.$guide->guide_voice_vn);
					$guide_voice_vn = $request->file('guide_voice_vn');
					$upload_voice_vn = FileProcess::upload('voice/vn/'.$current_timestamp.'-'.$guide_voice_vn->getClientOriginalName(),$guide_voice_vn->path());
					if ($upload_voice_vn) {
						$ary_data['guide_voice_vn'] = $current_timestamp.'-'.$guide_voice_vn->getClientOriginalName();
					}else{
						$ary_data['guide_voice_vn'] = null;
					}
				}else{
					$ary_data['guide_voice_vn'] = null;
				}

				if ($request->hasFile('guide_text_vn')){
					if($guide->guide_text_vn) FileProcess::remove('text/vn/'.$guide->guide_text_vn);
					$guide_text_vn = $request->file('guide_text_vn');
					$upload_text_vn = FileProcess::upload('text/vn/'.$current_timestamp.'-'.$guide_text_vn->getClientOriginalName(),$guide_text_vn->path());
					if ($upload_text_vn) {
						$ary_data['guide_text_vn'] = $current_timestamp.'-'.$guide_text_vn->getClientOriginalName();
					}else{
						$ary_data['guide_text_vn'] = null;
					}
				}else{
					$ary_data['guide_text_vn'] = null;
				}

				if ($request->hasFile('landscape_vn')){
					if($guide->landscape_vn) FileProcess::remove('landscape/vn/'.$guide->landscape_vn);
					$landscape_vn = $request->file('landscape_vn');
					$upload_landscape_vn = FileProcess::upload('landscape/vn/'.$current_timestamp.'-'.$landscape_vn->getClientOriginalName(),$landscape_vn->path());
					if ($upload_landscape_vn) {
						$ary_data['landscape_vn'] = $current_timestamp.'-'.$landscape_vn->getClientOriginalName();
					}else{
						$ary_data['landscape_vn'] = null;
					}
				}else{
					$ary_data['landscape_vn'] = null;
				}

				if ($request->hasFile('tag_vn')){
					if($guide->tag_vn) FileProcess::remove('tag/vn/'.$guide->tag_vn);
					$tag_vn = $request->file('tag_vn');
					$upload_tag_vn = FileProcess::upload('tag/vn/'.$current_timestamp.'-'.$tag_vn->getClientOriginalName(),$tag_vn->path());
					if ($upload_tag_vn) {
						$ary_data['tag_vn'] = $current_timestamp.'-'.$tag_vn->getClientOriginalName();
					}else{
						$ary_data['tag_vn'] = null;
					}
				}else{
					$ary_data['tag_vn'] = null;
				}
			}
			if($type=='id'){
				if ($request->hasFile('guide_voice_id')){
					if($guide->guide_voice_id) FileProcess::remove('voice/id/'.$guide->guide_voice_id);
					$guide_voice_id = $request->file('guide_voice_id');
					$upload_voice_id = FileProcess::upload('voice/id/'.$current_timestamp.'-'.$guide_voice_id->getClientOriginalName(),$guide_voice_id->path());
					if ($upload_voice_id) {
						$ary_data['guide_voice_id'] = $current_timestamp.'-'.$guide_voice_id->getClientOriginalName();
					}else{
						$ary_data['guide_voice_id'] = null;
					}
				}else{
					$ary_data['guide_voice_id'] = null;
				}

				if ($request->hasFile('guide_text_id')){
					if($guide->guide_text_id) FileProcess::remove('text/id/'.$guide->guide_text_id);
					$guide_text_id = $request->file('guide_text_id');
					$upload_text_id = FileProcess::upload('text/id/'.$current_timestamp.'-'.$guide_text_id->getClientOriginalName(),$guide_text_id->path());
					if ($upload_text_id) {
						$ary_data['guide_text_id'] = $current_timestamp.'-'.$guide_text_id->getClientOriginalName();
					}else{
						$ary_data['guide_text_id'] = null;
					}
				}else{
					$ary_data['guide_text_id'] = null;
				}

				if ($request->hasFile('landscape_id')){
					if($guide->landscape_id) FileProcess::remove('landscape/id/'.$guide->landscape_id);
					$landscape_id = $request->file('landscape_id');
					$upload_landscape_id = FileProcess::upload('landscape/id/'.$current_timestamp.'-'.$landscape_id->getClientOriginalName(),$landscape_id->path());
					if ($upload_landscape_id) {
						$ary_data['landscape_id'] = $current_timestamp.'-'.$landscape_id->getClientOriginalName();
					}else{
						$ary_data['landscape_id'] = null;
					}
				}else{
					$ary_data['landscape_id'] = null;
				}
 
				if ($request->hasFile('tag_id')){
					if($guide->tag_id) FileProcess::remove('tag/id/'.$guide->tag_id);
					$tag_id = $request->file('tag_id');
					$upload_tag_id = FileProcess::upload('tag/id/'.$current_timestamp.'-'.$tag_id->getClientOriginalName(),$tag_id->path());
					if ($upload_tag_id) {
						$ary_data['tag_id'] = $current_timestamp.'-'.$tag_id->getClientOriginalName();
					}else{
						$ary_data['tag_id'] = null;
					}
				}else{
					$ary_data['tag_id'] = null;
				}
			}
			$edit_guide = Guide::editGuide($guide_id,$ary_data,$type);
            if ($edit_guide) {
				if(!$check){
					Beacon::deleteGuideId($guide->beacon_id);
					Beacon::updateGuideId($beacon_id,$edit_guide);
				}
				$success = true;
				$data['id'] = $edit_guide;
				$data['type'] = $type;
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
		try{
			$id = $request->input('id');
			if(empty($id)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$data = Guide::getDetailById($id);
				$success = true;
				$error = null;
            }
		}catch(\Illuminate\Database\QueryException $ex) {
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}catch(\Illuminate\Exception $ex){
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}
		return $this->doResponse($success,$data,$error);
	}
   
	public function delete(Request $request){
		try{
			$guide_id = $request->input('guide_id');
			if(empty($guide_id)){
                $success = false;
                $error = \Lang::get('common_message.error.MISS_PARAM');
            }else{
				$guide =  Guide::getDetailById($guide_id);
				if($guide){
					if($guide->panaromic_photo_noon) FileProcess::remove('panaromic/noon/'.$guide->panaromic_photo_noon);
					if($guide->panaromic_photo_night) FileProcess::remove('panaromic/night/'.$guide->panaromic_photo_night);
					
					if($guide->guide_voice_ja) FileProcess::remove('voice/ja/'.$guide->guide_voice_ja);
					if($guide->guide_text_ja) FileProcess::remove('text/ja/'.$guide->guide_text_ja);
					if($guide->landscape_ja) FileProcess::remove('landscape/ja/'.$guide->landscape_ja);
					if($guide->tag_ja) FileProcess::remove('tag/ja/'.$guide->tag_ja);
					
					if($guide->guide_voice_en) FileProcess::remove('voice/en/'.$guide->guide_voice_en);
					if($guide->guide_text_en) FileProcess::remove('text/en/'.$guide->guide_text_en);
					if($guide->landscape_en) FileProcess::remove('landscape/en/'.$guide->landscape_en);
					if($guide->tag_en) FileProcess::remove('tag/en/'.$guide->tag_en);
					
					if($guide->guide_voice_cn_simplified) FileProcess::remove('voice/cn_simplified/'.$guide->guide_voice_cn_simplified);
					if($guide->guide_text_cn_simplified) FileProcess::remove('text/cn_simplified/'.$guide->guide_text_cn_simplified);
					if($guide->landscape_cn_simplified) FileProcess::remove('landscape/cn_simplified/'.$guide->landscape_cn_simplified);
					if($guide->tag_cn_simplified) FileProcess::remove('tag/cn_simplified/'.$guide->tag_cn_simplified);
					
					if($guide->guide_voice_cn_traditional) FileProcess::remove('voice/cn_traditional/'.$guide->guide_voice_cn_traditional);
					if($guide->guide_text_cn_traditional) FileProcess::remove('text/cn_traditional/'.$guide->guide_text_cn_traditional);
					if($guide->landscape_cn_traditional) FileProcess::remove('landscape/cn_traditional/'.$guide->landscape_cn_traditional);
					if($guide->tag_cn_traditional) FileProcess::remove('tag/cn_traditional/'.$guide->tag_cn_traditional);
					
					if($guide->guide_voice_kr) FileProcess::remove('voice/kr/'.$guide->guide_voice_kr);
					if($guide->guide_text_kr) FileProcess::remove('text/kr/'.$guide->guide_text_kr);
					if($guide->landscape_kr) FileProcess::remove('landscape/kr/'.$guide->landscape_kr);
					if($guide->tag_kr) FileProcess::remove('tag/kr/'.$guide->tag_kr);
					
					if($guide->guide_voice_es) FileProcess::remove('voice/es/'.$guide->guide_voice_es);
					if($guide->guide_text_es) FileProcess::remove('text/es/'.$guide->guide_text_es);
					if($guide->landscape_es) FileProcess::remove('landscape/es/'.$guide->landscape_es);
					if($guide->tag_es) FileProcess::remove('tag/es/'.$guide->tag_es);
					
					if($guide->guide_voice_gf) FileProcess::remove('voice/gf/'.$guide->guide_voice_gf);
					if($guide->guide_text_gf) FileProcess::remove('text/gf/'.$guide->guide_text_gf);
					if($guide->landscape_gf) FileProcess::remove('landscape/gf/'.$guide->landscape_gf);
					if($guide->tag_gf) FileProcess::remove('tag/gf/'.$guide->tag_gf);
					
					if($guide->guide_voice_it) FileProcess::remove('voice/it/'.$guide->guide_voice_it);
					if($guide->guide_text_it) FileProcess::remove('text/it/'.$guide->guide_text_it);
					if($guide->landscape_it) FileProcess::remove('landscape/it/'.$guide->landscape_it);
					if($guide->tag_it) FileProcess::remove('tag/it/'.$guide->tag_it);
					
					if($guide->guide_voice_de) FileProcess::remove('voice/de/'.$guide->guide_voice_de);
					if($guide->guide_text_de) FileProcess::remove('text/de/'.$guide->guide_text_de);
					if($guide->landscape_de) FileProcess::remove('landscape/de/'.$guide->landscape_de);
					if($guide->tag_de) FileProcess::remove('tag/de/'.$guide->tag_de);
					
					if($guide->guide_voice_ru) FileProcess::remove('voice/ru/'.$guide->guide_voice_ru);
					if($guide->guide_text_ru) FileProcess::remove('text/ru/'.$guide->guide_text_ru);
					if($guide->landscape_ru) FileProcess::remove('landscape/ru/'.$guide->landscape_ru);
					if($guide->tag_ru) FileProcess::remove('tag/ru/'.$guide->tag_ru);
					
					if($guide->guide_voice_th) FileProcess::remove('voice/th/'.$guide->guide_voice_th);
					if($guide->guide_text_th) FileProcess::remove('text/th/'.$guide->guide_text_th);
					if($guide->landscape_th) FileProcess::remove('landscape/th/'.$guide->landscape_th);
					if($guide->tag_th) FileProcess::remove('tag/th/'.$guide->tag_th);
					
					if($guide->guide_voice_vn) FileProcess::remove('voice/vn/'.$guide->guide_voice_vn);
					if($guide->guide_text_vn) FileProcess::remove('text/vn/'.$guide->guide_text_vn);
					if($guide->landscape_vn) FileProcess::remove('landscape/vn/'.$guide->landscape_vn);
					if($guide->tag_vn) FileProcess::remove('tag/vn/'.$guide->tag_vn);
					
					if($guide->guide_voice_id) FileProcess::remove('voice/id/'.$guide->guide_voice_id);
					if($guide->guide_text_id) FileProcess::remove('text/id/'.$guide->guide_text_id);
					if($guide->landscape_id) FileProcess::remove('landscape/id/'.$guide->landscape_id);
					if($guide->tag_id) FileProcess::remove('tag/id/'.$guide->tag_id);
					
					
					Beacon::deleteGuideId($guide->beacon_id); 
					$deleteGuide = Guide::deleteGuide($guide_id);
					
					if($deleteGuide) {
						$success = true;
						$error = null;
					}else{
						$success = false;
						$error = "Delete Guide fail!";
					}
				}
            }
		}catch(\Illuminate\Database\QueryException $ex) {
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}catch(\Illuminate\Exception $ex){
			$success = false;
			\Log::error("[".__METHOD__."][".__LINE__."]"."error:".$ex->getMessage());
			$error = $ex->getMessage();
		}

		return $this->doResponse($success,null,$error);
	}
   
}
