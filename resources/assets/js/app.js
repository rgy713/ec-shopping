//アプリケーション固有のjs
//これは、開発環境、ステージング環境のみで実行したい・・・
import $ from 'jquery';
import 'bootstrap';
window.$ = window.jQuery = $;
window.app={};
window.autokana=require('vanilla-autokana');

window.app.functions={

    /**
     * formの入力要素をリセット
     * @param targetClass
     */
    resetForm: function (targetClass) {
        $(targetClass).find('input[type=text], input[type=date], input[type=time], input[type=number], textarea, select').val('');
        $(targetClass).find('input[type=checkbox]').not('.switch-input').prop('checked', false);
    },

    /**
     * 郵便番号を住所に変換してフォームに反映
     */
    setAddress:function(selectorZipcode,selectorPref,selectorAddress1,selectorAddress2){
        var zipcode=$(selectorZipcode).val();
        if(!zipcode){
            toastr.error("郵便番号が入力されていません。");
            //ボタンに割り当てた場合にsubmitしないようfalseを返します。
            return false;
        }

        PostalCode.get(zipcode,function(address){
            $(selectorPref).val(app.functions.prefNameToId(address.prefecture));
            $(selectorAddress1).val(address.city + address.area);
            $(selectorAddress2).val(address.street);
            toastr.success("住所情報（"+ address.prefecture +" "+address.city +" "+address.area +" "+address.street +"）を反映しました。");
            //ボタンに割り当てた場合にsubmitしないようfalseを返します。
            return false;
        });

        //ボタンに割り当てた場合にsubmitしないようfalseを返します。
        return false;
    },
    /**
     * 都道府県名をIDに変換する
     * @param prefName
     */
    prefNameToId: function(prefName){
        //TODO:都道府県リストはサーバーから取ってきたほうが良いかもしれないが、ハードコーディングの方が速そう？
        var list={
            1:"北海道",
            2:"青森県",
            3:"岩手県",
            4:"宮城県",
            5:"秋田県",
            6:"山形県",
            7:"福島県",
            8:"茨城県",
            9:"栃木県",
            10:"群馬県",
            11:"埼玉県",
            12:"千葉県",
            13:"東京都",
            14:"神奈川県",
            15:"新潟県",
            16:"富山県",
            17:"石川県",
            18:"福井県",
            19:"山梨県",
            20:"長野県",
            21:"岐阜県",
            22:"静岡県",
            23:"愛知県",
            24:"三重県",
            25:"滋賀県",
            26:"京都府",
            27:"大阪府",
            28:"兵庫県",
            29:"奈良県",
            30:"和歌山県",
            31:"鳥取県",
            32:"島根県",
            33:"岡山県",
            34:"広島県",
            35:"山口県",
            36:"徳島県",
            37:"香川県",
            38:"愛媛県",
            39:"高知県",
            40:"福岡県",
            41:"佐賀県",
            42:"長崎県",
            43:"熊本県",
            44:"大分県",
            45:"宮崎県",
            46:"鹿児島県",
            47:"沖縄県",
        };
        const result = Object.keys(list).filter( (key) => {
            return list[key] === prefName;
        });
        return result;
    },

    /**
     * 引数をコンソールに出力
     * @param arg
     */
    testFunc : function(arg){
        console.log(arg);
    },
    /**
     *
     * @param status
     * @param message
     */
    displayToastr : function(status,message){
        if (message) {
            if(status=="success"){
                toastr.success(message);
            }else if(status=="error"){
                toastr.error(message);
            }else if(status=="warning"){
                toastr.warning(message);
            }else if(status=="info"){
                toastr.info(message);
            }else{
                toastr.info(message);
            }
        }
    },

    /**
     *
     * @param el
     */
    trim : function trim (el) {
        el.value = el.value.
        replace (/(^\s*)|(\s*$)/gi, ""). // removes leading and trailing spaces
        replace (/[ ]{2,}/gi," ").       // replaces multiple spaces with one space
        replace (/\n +/,"\n");           // Removes spaces after newlines
        return;
    },

    gf_Convert2ByteChar2: function (obj) {
        var x_char = obj.value;
        var x_2byteChar = new String;
        var len = x_char.length;
        for (var i = 0; i < len; i++) {
            var c = x_char.charCodeAt(i);
            if (c >= 65281 && c <= 65374 && c != 65340) {
                x_2byteChar += String.fromCharCode(c - 65248);
            } else if (c == 8217) {
                x_2byteChar += String.fromCharCode(39);
            } else if (c == 8221) {
                x_2byteChar += String.fromCharCode(34);
            } else if (c == 12288) {
                x_2byteChar += String.fromCharCode(32);
            } else if (c == 65507) {
                x_2byteChar += String.fromCharCode(126);
            } else if (c == 65509) {
                x_2byteChar += String.fromCharCode(92);
            } else {
                x_2byteChar += x_char.charAt(i);
            }
        }
        obj.value = x_2byteChar;
    },

    only_number_key: function(evt){
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            evt.preventDefault();
        }
    },
};
