<template>
    <div>
        <h6>Settings</h6>
        <!-- 左メニュー設定 -->
        <div class="clearfix mt-4">
            <small>
                <b>左メニュー</b>
            </small>
            <label class="switch switch-label switch-pill switch-success switch-sm float-right" v-show="!loading">
                <input class="switch-input" type="checkbox" v-model="option_left_menu" v-on:change="saveLeftMenu">
                <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
            </label>
        </div>
        <div>
            <small class="text-muted">左側メニューのデフォルト表示設定の切り替えを行います。</small>
        </div>

        <!-- 右メニュー設定 -->
        <div class="clearfix mt-4">
            <small>
                <b>右メニュー</b>
            </small>
            <label class="switch switch-label switch-pill switch-success switch-sm float-right" v-show="!loading">
                <input class="switch-input" type="checkbox" v-model="option_right_menu" v-on:change="saveRightMenu">
                <span class="switch-slider" data-checked="On" data-unchecked="Off"></span>
            </label>
        </div>
        <div>
            <small class="text-muted">右側メニューのデフォルト表示設定の切り替えを行います。</small>
        </div>
    </div>
</template>

<script>
    //TODO:ここに書くのは多分駄目なのだが正しい書き方がわからない
    var old={};

    export default {
        data : function(){
            return {
                loading:true,
                option_left_menu:null,
                option_right_menu:null
            }
        },
        //監視：値が変更される時に、変更前の値を保持する
        watch: {
            option_left_menu:function(newVal, oldVal){
                old.option_left_menu=oldVal;
            },
            option_right_menu:function(newVal, oldVal){
                old.option_right_menu=oldVal;
            }
        },
        //各チェックボックス変更時の処理：
        methods:{
            saveLeftMenu: function(event){
                //thsn、catchで利用できるよう this を保持しておく
                var data=this;
                axios.patch('/api/admin/settings/left_menu',{
                    value: this.option_left_menu
                }).then(function(response){
                    if(response.data.status=="error"){
                        data.option_left_menu=old.option_left_menu;
                    }
                }).catch(function(error){
                    //エラー発生時、変更前の値に戻す
                    data.option_left_menu=old.option_left_menu;
                });
            },
            saveRightMenu: function(){
                //thsn、catchで利用できるよう this を保持しておく
                var data=this;
                axios.patch('/api/admin/settings/right_menu',{
                    value: this.option_right_menu
                }).then(function (response){
                    if(response.data.status=="error"){
                        data.option_right_menu=old.option_right_menu;
                    }
                }).catch(function(error){
                    //エラー発生時、変更前の値に戻す
                    data.option_left_menu=old.option_left_menu;
                });
            },

        },
        mounted() {
            axios.get('/api/admin/settings').then(response => {
                this.option_left_menu = response.data.option_left_menu;
                this.option_right_menu = response.data.option_right_menu;

                old.option_left_menu = response.data.option_left_menu;
                old.option_right_menu = response.data.option_left_menu;
                this.loading=false;
            })

        }
    }
</script>
