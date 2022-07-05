window._ = require('lodash');
import $ from 'jquery';
window.$ = window.jQuery = $;
require('bootstrap');

$(function () {

    // 仕様コメント表示
    $('[data-toggle="popover"]'+'.specification').popover("show");
    //仕様コメントのスタイル定義:bootstrapの内容なので、ココで定義
    $(".popover").css("background-color","#FF9999");
    $(".popover").css("opacity","0.6");
    $(".popover-body").css("color","#000000");

    //仕様コメント非表示
    // $('[data-toggle="popover"]'+'.specification').html("");

});