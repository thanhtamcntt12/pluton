import { Component } from '@angular/core';
import {MyDatePicker} from 'mydatepicker';
import * as moment from 'moment/moment';
declare var $: any;
	
@Component({
	selector: 'app-root',
	template: `<router-outlet></router-outlet>`
})
export class AppComponent {
    ngOnInit() {
        $(function () {
            // change messages to Japanese
            $.validator.messages.required = '必須項目です。';
            $.validator.messages.remote = "このフィールドを修正してください。";
            $.validator.messages.email = "有効なEメールアドレスを入力してください。";
            $.validator.messages.url = "有効なURLを入力してください。";
            $.validator.messages.date = "有効な日付を入力してください。",
            $.validator.messages.dateISO = "有効な日付(ISO)を入力してください。";
            $.validator.messages.number = "有効な数字を入力してください。";
            $.validator.messages.digits = "数字のみ入力してください。";
            $.validator.messages.equalTo = "同じ値をもう一度入力してください。";
            $.validator.messages.maxlength = $.validator.format( " {0} 文字以内で入力してください。 " );
            $.validator.messages.minlength = $.validator.format( "{0} 文字以上で入力してください。" );
            //overriding validation method
            $.validator.setDefaults({
                highlight: function (input) {
                    $(input).parents('.form-line').addClass('error');
                },
                unhighlight: function (input) {
                    $(input).parents('.form-line').removeClass('error');
                },
                errorPlacement: function (error, element) {
                    $(element).parents('.form-group').append(error);
                }
            });
            //validate katakana
            $.validator.addMethod("katakana", function(value, element){
                if( /^(?:[ァ-ヾ\ \　]+)*$/.test(value)){
                    return true;
                }
                return false;
            }, "カタカナで入力してください。");
            //validate kana
            $.validator.addMethod("kana", function(value, element){
                if( /^([\u3000-\u303F]|[\u3040-\u309F]|[\u30A0-\u30FF]|[\uFF00-\uFFEF]|[\u4E00-\u9FAF]|[\u2605-\u2606]|[\u2190-\u2195]|\u203B)*$/.test(value)){
                    return true;
                }
                return false;
            }, "日本語で入力してください。");
			
			// validate date format
			$.validator.addMethod("date_format", function(value, element){
                if(element.className.indexOf("invaliddate") == -1){
                    return true;
                }
                return false;
            }, "日付の形式が違っています。");
			
			$.validator.addMethod("date_invalid", function(value, element){
                if(value.indexOf("NaN") == -1){
                    return true;
                }
				
				if(value.includes("/")){
					var m = moment(value, 'YYYY/MM/DD');				
					return m.isValid();
				}
				else {
					var m = moment(value, 'YYYYMMDD');				
					return m.isValid();
				}
            }, "必須項目です。");
			
			
			//Overwrite onUserDateInput function for YYYYMMDD format
			MyDatePicker.prototype.onUserDateInput = function (value) {
				this.invalidDate = false;
				if (value.length === 0) {
					this.clearDate();
				}
				else {
					if(value.includes("/")){
						var date = this.utilService.isDateValid(value, this.opts.dateFormat, this.opts.minYear, this.opts.maxYear, this.opts.disableUntil, this.opts.disableSince, this.opts.disableWeekends, this.opts.disableWeekdays, this.opts.disableDays, this.opts.disableDateRanges, this.opts.monthLabels, this.opts.enableDays);
						if (date.day !== 0 && date.month !== 0 && date.year !== 0) {
							this.selectDate(date, 2);//CalToggle.CloseByDateSel=2
						}
						else {
							this.invalidDate = true;
						}
					}
					else {
						var m = moment(value, 'YYYYMMDD');							
						this.invalidDate = !m.isValid();
					}
				}
				if (this.invalidDate) {
					this.inputFieldChanged.emit({ value: value, dateFormat: this.opts.dateFormat, valid: !(value.length === 0 || this.invalidDate) });
					this.onChangeCb(null);
					this.onTouchedCb();
				}
			};
        });
		
    }
}
