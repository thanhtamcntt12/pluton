import { Component, OnInit } from '@angular/core';
import {RestfulService} from '../services/restful.service';
import { ActivatedRoute, Router  } from '@angular/router';
import { Http, Headers, RequestOptions} from '@angular/http';
import { Observable } from 'rxjs/Observable';
import { environment } from '../../environments/environment';
declare var $: any;

@Component({
  selector: 'app-guide-edit',
  templateUrl: './guide-edit.component.html',
  styleUrls: ['./guide-edit.component.scss']
})
export class GuideEditComponent implements OnInit {
	private beacons: any;
	private guide_id: number;
	private file_panaromic_photo_noon: any;
	private file_panaromic_photo_night: any;
	
	private file_guide_voice_ja: any;
	private file_guide_text_ja: any;
	private file_landscape_ja: any;
	private file_tag_ja: any;
	
	private file_guide_voice_en: any;
	private file_guide_text_en: any;
	private file_landscape_en: any;
	private file_tag_en: any;
	
	private file_guide_voice_cn_simplified: any;
	private file_guide_text_cn_simplified: any;
	private file_landscape_cn_simplified: any;
	private file_tag_cn_simplified: any;
	
	private file_guide_voice_cn_traditional: any;
	private file_guide_text_cn_traditional: any;
	private file_landscape_cn_traditional: any;
	private file_tag_cn_traditional: any;
	
	private file_guide_voice_kr: any;
	private file_guide_text_kr: any;
	private file_landscape_kr: any;
	private file_tag_kr: any;
	
	private file_guide_voice_es: any;
	private file_guide_text_es: any;
	private file_landscape_es: any;
	private file_tag_es: any;
	
	private file_guide_voice_gf: any;
	private file_guide_text_gf: any;
	private file_landscape_gf: any;
	private file_tag_gf: any;
	
	private file_guide_voice_it: any;
	private file_guide_text_it: any;
	private file_landscape_it: any;
	private file_tag_it: any;
	
	private file_guide_voice_de: any;
	private file_guide_text_de: any;
	private file_landscape_de: any;
	private file_tag_de: any;
	
	private file_guide_voice_ru: any;
	private file_guide_text_ru: any;
	private file_landscape_ru: any;
	private file_tag_ru: any;
	
	private file_guide_voice_th: any;
	private file_guide_text_th: any;
	private file_landscape_th: any;
	private file_tag_th: any;
	
	private file_guide_voice_vn: any;
	private file_guide_text_vn: any;
	private file_landscape_vn: any;
	private file_tag_vn: any;
	
	private file_guide_voice_id: any;
	private file_guide_text_id: any;
	private file_landscape_id: any;
	private file_tag_id: any;
	
	//-------------
	private image_panaromic_photo_noon: any;
	private image_panaromic_photo_night: any;
	
	
	private url_guide_voice_ja: any;
	private url_guide_voice_en: any;
	private url_guide_voice_cn_simplified: any;
	private url_guide_voice_cn_traditional: any;
	private url_guide_voice_kr: any;
	private url_guide_voice_es: any;
	private url_guide_voice_gf: any;
	private url_guide_voice_it: any;
	private url_guide_voice_de: any;
	private url_guide_voice_ru: any;
	private url_guide_voice_th: any;
	private url_guide_voice_vn: any;
	private url_guide_voice_id: any;
	
	
	//--------------
	private guide_voice_ja: any;
	private guide_text_ja: any;
	private landscape_ja: any;
	private tag_ja: any;
	
	private guide_voice_en: any;
	private guide_text_en: any;
	private landscape_en: any;
	private tag_en: any;
	
	private guide_voice_cn_simplified: any;
	private guide_text_cn_simplified: any;
	private landscape_cn_simplified: any;
	private tag_cn_simplified: any;
	
	private guide_voice_cn_traditional: any;
	private guide_text_cn_traditional: any;
	private landscape_cn_traditional: any;
	private tag_cn_traditional: any;
	
	private guide_voice_kr: any;
	private guide_text_kr: any;
	private landscape_kr: any;
	private tag_kr: any;
	
	private guide_voice_es: any;
	private guide_text_es: any;
	private landscape_es: any;
	private tag_es: any;
	
	private guide_voice_gf: any;
	private guide_text_gf: any;
	private landscape_gf: any;
	private tag_gf: any;
	
	private guide_voice_it: any;
	private guide_text_it: any;
	private landscape_it: any;
	private tag_it: any;
	
	private guide_voice_de: any;
	private guide_text_de: any;
	private landscape_de: any;
	private tag_de: any;
	
	private guide_voice_ru: any;
	private guide_text_ru: any;
	private landscape_ru: any;
	private tag_ru: any;
	
	private guide_voice_th: any;
	private guide_text_th: any;
	private landscape_th: any;
	private tag_th: any;
	
	private guide_voice_vn: any;
	private guide_text_vn: any;
	private landscape_vn: any;
	private tag_vn: any;
	
	private guide_voice_id: any;
	private guide_text_id: any;
	private landscape_id: any;
	private tag_id: any;
	
	//----------
	private check_file_panaromic_photo_noon = false;
	private check_file_panaromic_photo_night = false;
	private check_file_format = true;
	private check_changed = false;
	private checks = [];
	
	private pana_group = false;
	private ja_group = false;
	private en_group = false;
	private cn_simplified_group = false;
	private cn_traditional_group = false;
	private kr_group = false;
	private es_group = false;
	private gf_group = false;
	private it_group = false;
	private de_group = false;
	private ru_group = false;
	private th_group = false;
	private vn_group = false;
	private id_group = false;
	
	model = {beacon_id: ''};
	private times_send = 0;
	private times_response = 0;
	private old_beacon_id;
	
	constructor(private restfulService:RestfulService,
				 private activeRoute: ActivatedRoute,
				 private http: Http) { }

	ngOnInit() {
		this.activeRoute.params.subscribe(params => {
            this.guide_id = params['id'];
        });
		this.getListBeacon();
		this.getGuideDetail();
	}
	private getListBeacon(){
		let params = {'id': this.guide_id};
		let url = 'beacon/list_edit_guide';
        this.restfulService.doGet(url,params).subscribe(commonResponse => this.handleResponseListBeacon(commonResponse));
	}
	private handleResponseListBeacon(commonResponse:any) {
		if(commonResponse==null) return;
        if (commonResponse.success) {
            this.beacons = commonResponse.data;
        } else {
            alert(commonResponse.error);
        }
    }
	private getGuideDetail(){
		let params = {'id': this.guide_id};
		let url = 'guide/detail';
        this.restfulService.doGet(url,params).subscribe(commonResponse => this.handleResponseGuideDetail(commonResponse));
	}
	private handleResponseGuideDetail(commonResponse:any) {
		if(commonResponse==null) return;
		
        if (commonResponse.success) {
			let path = 'http://192.168.64.132/tokyotower_lanh/storage/app/public/';
			let guide = commonResponse.data;
			this.image_panaromic_photo_noon = path +"panaromic/noon/"+guide['panaromic_photo_noon'];
			this.image_panaromic_photo_night = path +"panaromic/night/"+guide['panaromic_photo_night'];
			this.url_guide_voice_ja = path + "voice/ja/"+guide['guide_voice_ja'];
			this.url_guide_voice_en = path + "voice/en/"+guide['guide_voice_en'];
			
			this.url_guide_voice_cn_simplified = path + "voice/cn_simplified/"+guide['guide_voice_cn_simplified'];
			this.url_guide_voice_cn_traditional = path + "voice/cn_traditiona/"+guide['guide_voice_cn_traditional'];
			this.url_guide_voice_kr = path + "voice/cn_traditional/"+guide['guide_voice_kr'];
			this.url_guide_voice_es = path + "voice/es/"+guide['guide_voice_es'];
			this.url_guide_voice_gf = path + "voice/gf/"+guide['guide_voice_gf'];
			this.url_guide_voice_it = path + "voice/it/"+guide['guide_voice_it'];
			this.url_guide_voice_de = path + "voice/de/"+guide['guide_voice_de'];
			this.url_guide_voice_ru = path + "voice/ru/"+guide['guide_voice_ru'];
			this.url_guide_voice_th = path + "voice/th/"+guide['guide_voice_th'];
			this.url_guide_voice_vn = path + "voice/vn/"+guide['guide_voice_vn'];
			this.url_guide_voice_id = path + "voice/id/"+guide['guide_voice_id'];
			
			this.guide_voice_ja = guide['guide_voice_ja'];
			this.guide_text_ja = guide['guide_text_ja'];
			this.landscape_ja = guide['landscape_ja'];
			this.tag_ja = guide['tag_ja'];
			this.guide_voice_en = guide['guide_voice_en'];
			this.guide_text_en = guide['guide_text_en'];
			this.landscape_en = guide['landscape_en'];
			this.tag_en = guide['tag_en'];
			this.guide_voice_cn_simplified = guide['guide_voice_cn_simplified'];
			this.guide_text_cn_simplified = guide['guide_text_cn_simplified'];
			this.landscape_cn_simplified = guide['landscape_cn_simplified'];
			this.tag_cn_simplified = guide['tag_cn_simplified'];
			this.guide_voice_cn_traditional = guide['guide_voice_cn_traditional'];
			this.guide_text_cn_traditional = guide['guide_text_cn_traditional'];
			this.landscape_cn_traditional = guide['landscape_cn_traditional'];
			this.tag_cn_traditional = guide['tag_cn_traditional'];
			this.guide_voice_kr = guide['guide_voice_kr'];
			this.guide_text_kr = guide['guide_text_kr'];
			this.landscape_kr = guide['landscape_kr'];
			this.tag_kr = guide['tag_kr'];
			this.guide_voice_es = guide['guide_voice_es'];
			this.guide_text_es = guide['guide_text_es'];
			this.landscape_es = guide['landscape_es'];
			this.tag_es = guide['tag_es'];
			this.guide_voice_gf = guide['guide_voice_gf'];
			this.guide_text_gf = guide['guide_text_gf'];
			this.landscape_gf = guide['landscape_gf'];
			this.tag_gf = guide['tag_gf'];
			this.guide_voice_it = guide['guide_voice_it'];
			this.guide_text_it = guide['guide_text_it'];
			this.landscape_it = guide['landscape_it'];
			this.tag_it = guide['tag_it'];
			this.guide_voice_de = guide['guide_voice_de'];
			this.guide_text_de = guide['guide_text_de'];
			this.landscape_de = guide['landscape_de'];
			this.tag_de = guide['tag_de'];
			this.guide_voice_ru = guide['guide_voice_ru'];
			this.guide_text_ru = guide['guide_text_ru'];
			this.landscape_ru = guide['landscape_ru'];
			this.tag_ru = guide['tag_ru'];
			this.guide_voice_th = guide['guide_voice_th'];
			this.guide_text_th = guide['guide_text_th'];
			this.landscape_th = guide['landscape_th'];
			this.tag_th = guide['tag_th'];
			this.guide_voice_vn = guide['guide_voice_vn'];
			this.guide_text_vn = guide['guide_text_vn'];
			this.landscape_vn = guide['landscape_vn'];
			this.tag_vn = guide['tag_vn'];
			this.guide_voice_id = guide['guide_voice_id'];
			this.guide_text_id = guide['guide_text_id'];
			this.landscape_id = guide['landscape_id'];
			this.tag_id = guide['tag_id'];
			
			this.model.beacon_id = guide['beacon_id'];
			this.old_beacon_id = guide['beacon_id'];
        } else {
            alert(commonResponse.error);
        }
    }
	
	changeFileEvent(event){
        let file = event.target.files[0];
		let check_format = false;
		let myReader:FileReader = new FileReader();
		
        if(file){
			myReader.readAsDataURL(file);
			this.check_changed = true;
			this.check_file_format = true;
			let file_extension = file.name.split('.').pop();
			if(event.currentTarget.id == "file_panaromic_photo_noon"){
				this.file_panaromic_photo_noon = file;
				if(file_extension.toLowerCase() != 'jpeg'&&file_extension.toLowerCase() != 'jpg'&&file_extension.toLowerCase() != 'png'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_panaromic_photo_night"){
				this.file_panaromic_photo_night = file;
				if(file_extension.toLowerCase() != 'jpeg'&&file_extension.toLowerCase() != 'jpg'&&file_extension.toLowerCase() != 'png'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_ja"){
				this.file_guide_voice_ja = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_ja").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_ja"){
				this.file_guide_text_ja = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_ja"){
				this.file_landscape_ja = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_ja"){
				this.file_tag_ja = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_en"){
				this.file_guide_voice_en = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_en").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_en"){
				this.file_guide_text_en = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_en"){
				this.file_landscape_en = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_en"){
				this.file_tag_en = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_cn_simplified"){
				this.file_guide_voice_cn_simplified = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_cn_simplified").attr('src', myReader.result);
					};					
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_cn_simplified"){
				this.file_guide_text_cn_simplified = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_cn_simplified"){
				this.file_landscape_cn_simplified = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_cn_simplified"){
				this.file_tag_cn_simplified = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_cn_traditional"){
				this.file_guide_voice_cn_traditional = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_cn_traditional").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_cn_traditional"){
				this.file_guide_text_cn_traditional = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_cn_traditional"){
				this.file_landscape_cn_traditional = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_cn_traditional"){
				this.file_tag_cn_traditional = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_kr"){
				this.file_guide_voice_kr = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_kr").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_kr"){
				this.file_guide_text_kr = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_kr"){
				this.file_landscape_kr = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_kr"){
				this.file_tag_kr = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_es"){
				this.file_guide_voice_es = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_es").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_es"){
				this.file_guide_text_es = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_es"){
				this.file_landscape_es = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_es"){
				this.file_tag_es = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_gf"){
				this.file_guide_voice_gf = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_gf").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_gf"){
				this.file_guide_text_gf = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_gf"){
				this.file_landscape_gf = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_gf"){
				this.file_tag_gf = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_it"){
				this.file_guide_voice_it = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_it").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_it"){
				this.file_guide_text_it = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_it"){
				this.file_landscape_it = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_it"){
				this.file_tag_it = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_de"){
				this.file_guide_voice_de = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_de").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_de"){
				this.file_guide_text_de = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_de"){
				this.file_landscape_de = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_de"){
				this.file_tag_de = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_ru"){
				this.file_guide_voice_ru = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_ru").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_ru"){
				this.file_guide_text_ru = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_ru"){
				this.file_landscape_ru = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_ru"){
				this.file_tag_ru = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_th"){
				this.file_guide_voice_th = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_th").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_th"){
				this.file_guide_text_th = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_th"){
				this.file_landscape_th = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_th"){
				this.file_tag_th = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_vn"){
				this.file_guide_voice_vn = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_vn").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_vn"){
				this.file_guide_text_vn = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_vn"){
				this.file_landscape_vn = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_vn"){
				this.file_tag_vn = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_voice_id"){
				this.file_guide_voice_id = file;
				if(file_extension.toLowerCase() != 'mp3'){
					check_format = false;
				}else{
					myReader.onloadend = function(e){
						$("#audio_guide_voice_id").attr('src', myReader.result);
					};
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_guide_text_id"){
				this.file_guide_text_id = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_landscape_id"){
				this.file_landscape_id = file;
				if(file_extension.toLowerCase() != 'csv'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else if(event.currentTarget.id == "file_tag_id"){
				this.file_tag_id = file;
				if(file_extension.toLowerCase() != 'zip'){
					check_format = false;
				}else{
					check_format = true;
				}
			}else{
				alert("Please select file to upload");
			}
			
			if(check_format)$("#"+event.currentTarget.id+"_error").css("display", "none");
			else $("#"+event.currentTarget.id+"_error").css("display", "block");
			
			this.checks[event.currentTarget.id]= check_format;
			for (var i in this.checks) {
			    if(!this.checks[i]){
				    this.check_file_format = false;
					break;
			    }
			} 
        }
    }
	
	playAudio(id){
		$("#"+id).get(0).play();
	}
	
	edit() {
		$("#upload_loading_pana").css("display", "block");
		if(this.model.beacon_id || this.file_panaromic_photo_noon || this.file_panaromic_photo_night){
			this.pana_group = true;
			$("#upload_loading_pana").css("display", "block");
		}
		if(this.file_guide_voice_ja || this.file_guide_text_ja || this.file_landscape_ja || this.file_tag_ja){
			this.ja_group = true;
			$("#upload_loading_ja").css("display", "block");
		}
		if(this.file_guide_voice_en || this.file_guide_text_en || this.file_landscape_en || this.file_tag_en){
			this.en_group = true;
			$("#upload_loading_en").css("display", "block");
		}
		if(this.file_guide_voice_cn_simplified || this.file_guide_text_cn_simplified || this.file_landscape_cn_simplified || this.file_tag_cn_simplified){
			this.cn_simplified_group = true;
			$("#upload_loading_cn_simplified").css("display", "block");
		}
		if(this.file_guide_voice_cn_traditional || this.file_guide_text_cn_traditional || this.file_landscape_cn_traditional || this.file_tag_cn_traditional){
			this.cn_traditional_group = true;
			$("#upload_loading_cn_traditional").css("display", "block");
		}
		if(this.file_guide_voice_kr || this.file_guide_text_kr || this.file_landscape_kr || this.file_tag_kr){
			this.kr_group = true;
			$("#upload_loading_kr").css("display", "block");
		}
		if(this.file_guide_voice_es || this.file_guide_text_es || this.file_landscape_es || this.file_tag_es){
			this.es_group = true;
			$("#upload_loading_es").css("display", "block");
		}
		if(this.file_guide_voice_gf || this.file_guide_text_gf || this.file_landscape_gf || this.file_tag_gf){
			this.gf_group = true;
			$("#upload_loading_gf").css("display", "block");
		}
		if(this.file_guide_voice_it || this.file_guide_text_it || this.file_landscape_it || this.file_tag_it){
			this.it_group = true;
			$("#upload_loading_it").css("display", "block");
		}
		if(this.file_guide_voice_de || this.file_guide_text_de || this.file_landscape_de || this.file_tag_de){
			this.de_group = true;
			$("#upload_loading_de").css("display", "block");
		}
		if(this.file_guide_voice_ru || this.file_guide_text_ru || this.file_landscape_ru || this.file_tag_ru){
			this.ru_group = true;
			$("#upload_loading_ru").css("display", "block");
		}
		if(this.file_guide_voice_th || this.file_guide_text_th || this.file_landscape_th || this.file_tag_th){
			this.th_group = true;
			$("#upload_loading_th").css("display", "block");
		}
		if(this.file_guide_voice_vn || this.file_guide_text_vn || this.file_landscape_vn || this.file_tag_vn){
			this.vn_group = true;
			$("#upload_loading_vn").css("display", "block");
		}
		if(this.file_guide_voice_id || this.file_guide_text_id || this.file_landscape_id || this.file_tag_id){
			this.id_group = true;
			$("#upload_loading_id").css("display", "block");
		}
		
		if(this.pana_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "pana");
			formData.append('guide_id', this.guide_id);
			if(this.file_panaromic_photo_noon) formData.append('panaromic_photo_noon', this.file_panaromic_photo_noon, this.file_panaromic_photo_noon.name);
			if(this.file_panaromic_photo_night) formData.append('panaromic_photo_night', this.file_panaromic_photo_night, this.file_panaromic_photo_night.name);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}	
			
			
		if(this.ja_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "ja");
			if(this.file_guide_voice_ja) formData.append('guide_voice_ja', this.file_guide_voice_ja, this.file_guide_voice_ja.name);
			if(this.file_guide_text_ja) formData.append('guide_text_ja', this.file_guide_text_ja, this.file_guide_text_ja.name);
			if(this.file_landscape_ja) formData.append('landscape_ja', this.file_landscape_ja, this.file_landscape_ja.name);
			if(this.file_tag_ja) formData.append('tag_ja', this.file_tag_ja, this.file_tag_ja.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.en_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "en");
			if(this.file_guide_voice_en) formData.append('guide_voice_en', this.file_guide_voice_en, this.file_guide_voice_en.name);
			if(this.file_guide_text_en) formData.append('guide_text_en', this.file_guide_text_en, this.file_guide_text_en.name);
			if(this.file_landscape_en) formData.append('landscape_en', this.file_landscape_en, this.file_landscape_en.name);
			if(this.file_tag_en) formData.append('tag_en', this.file_tag_en, this.file_tag_en.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.cn_simplified_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "cn_simplified");
			if(this.file_guide_voice_cn_simplified) formData.append('guide_voice_cn_simplified', this.file_guide_voice_cn_simplified, this.file_guide_voice_cn_simplified.name);
			if(this.file_guide_text_cn_simplified) formData.append('guide_text_cn_simplified', this.file_guide_text_cn_simplified, this.file_guide_text_cn_simplified.name);
			if(this.file_landscape_cn_simplified) formData.append('landscape_cn_simplified', this.file_landscape_cn_simplified, this.file_landscape_cn_simplified.name);
			if(this.file_tag_cn_simplified) formData.append('tag_cn_simplified', this.file_tag_cn_simplified, this.file_tag_cn_simplified.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.cn_traditional_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "cn_traditional");
			if(this.file_guide_voice_cn_traditional) formData.append('guide_voice_cn_traditional', this.file_guide_voice_cn_traditional, this.file_guide_voice_cn_traditional.name);
			if(this.file_guide_text_cn_traditional) formData.append('guide_text_cn_traditional', this.file_guide_text_cn_traditional, this.file_guide_text_cn_traditional.name);
			if(this.file_landscape_cn_traditional) formData.append('landscape_cn_traditional', this.file_landscape_cn_traditional, this.file_landscape_cn_traditional.name);
			if(this.file_tag_cn_traditional) formData.append('tag_cn_traditional', this.file_tag_cn_traditional, this.file_tag_cn_traditional.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.kr_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "kr");
			if(this.file_guide_voice_kr) formData.append('guide_voice_kr', this.file_guide_voice_kr, this.file_guide_voice_kr.name);
			if(this.file_guide_text_kr) formData.append('guide_text_kr', this.file_guide_text_kr, this.file_guide_text_kr.name);
			if(this.file_landscape_kr) formData.append('landscape_kr', this.file_landscape_kr, this.file_landscape_kr.name);
			if(this.file_tag_kr) formData.append('tag_kr', this.file_tag_kr, this.file_tag_kr.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.es_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "es");
			if(this.file_guide_voice_es) formData.append('guide_voice_es', this.file_guide_voice_es, this.file_guide_voice_es.name);
			if(this.file_guide_text_es) formData.append('guide_text_es', this.file_guide_text_es, this.file_guide_text_es.name);
			if(this.file_landscape_es) formData.append('landscape_es', this.file_landscape_es, this.file_landscape_es.name);
			if(this.file_tag_es) formData.append('tag_es', this.file_tag_es, this.file_tag_es.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.gf_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "gf");
			if(this.file_guide_voice_gf) formData.append('guide_voice_gf', this.file_guide_voice_gf, this.file_guide_voice_gf.name);
			if(this.file_guide_text_gf) formData.append('guide_text_gf', this.file_guide_text_gf, this.file_guide_text_gf.name);
			if(this.file_landscape_gf) formData.append('landscape_gf', this.file_landscape_gf, this.file_landscape_gf.name);
			if(this.file_tag_gf) formData.append('tag_gf', this.file_tag_gf, this.file_tag_gf.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.it_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "it");
			if(this.file_guide_voice_it) formData.append('guide_voice_it', this.file_guide_voice_it, this.file_guide_voice_it.name);
			if(this.file_guide_text_it) formData.append('guide_text_it', this.file_guide_text_it, this.file_guide_text_it.name);
			if(this.file_landscape_it) formData.append('landscape_it', this.file_landscape_it, this.file_landscape_it.name);
			if(this.file_tag_it) formData.append('tag_it', this.file_tag_it, this.file_tag_it.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.de_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "de");
			if(this.file_guide_voice_de) formData.append('guide_voice_de', this.file_guide_voice_de, this.file_guide_voice_de.name);
			if(this.file_guide_text_de) formData.append('guide_text_de', this.file_guide_text_de, this.file_guide_text_de.name);
			if(this.file_landscape_de) formData.append('landscape_de', this.file_landscape_de, this.file_landscape_de.name);
			if(this.file_tag_de) formData.append('tag_de', this.file_tag_de, this.file_tag_de.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.ru_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "ru");
			if(this.file_guide_voice_ru) formData.append('guide_voice_ru', this.file_guide_voice_ru, this.file_guide_voice_ru.name);
			if(this.file_guide_text_ru) formData.append('guide_text_ru', this.file_guide_text_ru, this.file_guide_text_ru.name);
			if(this.file_landscape_ru) formData.append('landscape_ru', this.file_landscape_ru, this.file_landscape_ru.name);
			if(this.file_tag_ru) formData.append('tag_ru', this.file_tag_ru, this.file_tag_ru.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.th_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "th");
			if(this.file_guide_voice_th) formData.append('guide_voice_th', this.file_guide_voice_th, this.file_guide_voice_th.name);
			if(this.file_guide_text_th) formData.append('guide_text_th', this.file_guide_text_th, this.file_guide_text_th.name);
			if(this.file_landscape_th) formData.append('landscape_th', this.file_landscape_th, this.file_landscape_th.name);
			if(this.file_tag_th) formData.append('tag_th', this.file_tag_th, this.file_tag_th.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.vn_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "vn");
			if(this.file_guide_voice_vn) formData.append('guide_voice_vn', this.file_guide_voice_vn, this.file_guide_voice_vn.name);
			if(this.file_guide_text_vn) formData.append('guide_text_vn', this.file_guide_text_vn, this.file_guide_text_vn.name);
			if(this.file_landscape_vn) formData.append('landscape_vn', this.file_landscape_vn, this.file_landscape_vn.name);
			if(this.file_tag_vn) formData.append('tag_vn', this.file_tag_vn, this.file_tag_vn.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
		if(this.id_group){
			this.times_send++;
			let formData: FormData = new FormData();
			formData.append('beacon_id', this.model.beacon_id);
			formData.append('type', "id");
			if(this.file_guide_voice_id) formData.append('guide_voice_id', this.file_guide_voice_id, this.file_guide_voice_id.name);
			if(this.file_guide_text_id) formData.append('guide_text_id', this.file_guide_text_id, this.file_guide_text_id.name);
			if(this.file_landscape_id) formData.append('landscape_id', this.file_landscape_id, this.file_landscape_id.name);
			if(this.file_tag_id) formData.append('tag_id', this.file_tag_id, this.file_tag_id.name);
			formData.append('guide_id', this.guide_id);
			let url = 'guide/edit';
			let headers = new Headers();
			headers.append('Accept', 'application/json');
			let options = new RequestOptions({ headers: headers });
			this.http.post(environment.API_ENDPOINT + url, formData,options)
				.map(res => res.json())
				.catch(error => Observable.throw(error))
				.subscribe(commonResponse => this.handleResponseUpdate(commonResponse));
		}
	}
	
	private handleResponseUpdate(commonResponse:any) {
		if(commonResponse==null) return;
        if (commonResponse.success) {
			this.times_response++;
        	$("#upload_loading_"+commonResponse.data.type).css("display", "none");
			if(this.times_send==this.times_response) {
				if(this.model.beacon_id){
					let url = 'beacon/rev';
					let data = {
								'new_beacon_id': this.model.beacon_id
								};
					if(this.old_beacon_id != this.model.beacon_id){
						data['old_beacon_id'] = this.old_beacon_id;
					}
					this.restfulService.doPost(url,data).subscribe(commonResponse => this.handleResponseBeaconRev(commonResponse));
				}else{
					window.location.reload();
				}
				
			}
        } else {
            alert(commonResponse.error);
        }
    }
	private handleResponseBeaconRev(commonResponse:any) {
		if(commonResponse==null) return;
        if (commonResponse.success) {
			window.location.reload();
        } else {
            alert(commonResponse.error);
        }
    }
}
