/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
window.axios = require('axios');
window.toastr = require('toastr');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//toastr設定
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "2000",
    "timeOut": "2000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
//APIトークンの付与
window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + Laravel.apiToken;

//共通処理の定義
window.axios.interceptors.response.use(function (response) {
    //リクエスト成功時、実行結果に応じてtoast表示
    if (response.data.message) {
        if(response.data.status=="error"){
            toastr.error(response.data.message);
        }
        if(response.data.status=="warning"){
            toastr.warning(response.data.message);
        }
        if(response.data.status=="info"){
            toastr.info(response.data.message);
        }
        if(response.data.status=="success"){
            toastr.success(response.data.message);
        }
    }

    //responseを返す。（Toast表示以外の各処理は実装側で）
    return response;
}, function (error) {
    // 認証エラー時の処理
    if (error.response.status === 401) {
        //TODO:エラー発生時のハンドリング仮実装
        toastr.error(response.data.message);
    } else if (error.response.status === 500) {
        //TODO:エラー発生時のハンドリング仮実装
        toastr.error(error.response.data.message);
    }
    return Promise.reject(error)
});

Vue.use(require('vue-moment'));

Vue.component('admin-setting-component', require('./components/admin/SettingComponent.vue'));

const adminSettingsComponent = new Vue({
    el: '#admin-settings'
});

Vue.component('pagination', require('./components/admin/PaginationComponent.vue'));