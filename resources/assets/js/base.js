//グローバルな名前空間
window._ = require('lodash');
import $ from 'jquery';
window.$ = window.jQuery = $;
window.Vue = require('vue');
window.axios = require('axios');
window.toastr = require('toastr');
window.PostalCode = require('japan-postal-code');
window.app={};