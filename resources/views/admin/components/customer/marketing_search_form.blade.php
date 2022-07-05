{{-- マーケティング検索のフォーム --}}
@inject('birthMonthList', 'App\Common\KeyValueLists\BirthMonthList')
@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')
@inject('telFlagList', 'App\Common\KeyValueLists\CustomerTelFlagList')
@inject('customerStatusList', 'App\Common\KeyValueLists\CustomerStatusList')
@inject('mailMagazineFlagList', 'App\Common\KeyValueLists\MailMagazineFlagList')
@inject('directMailFlagList', 'App\Common\KeyValueLists\DirectMailFlagList')
@inject('portfolioStatusList', 'App\Common\KeyValueLists\PortfolioStatusList')
@inject('mediaCodeList', 'App\Common\KeyValueLists\MediaCodeList')
@inject('itemLineupList', 'App\Common\KeyValueLists\ItemLineupList')
@inject('SalesTargetList', 'App\Common\KeyValueLists\SalesTargetList')
@inject('salesRouteList', 'App\Common\KeyValueLists\SalesRouteList')
@inject('productVolumeList', 'App\Common\KeyValueLists\ProductVolumeList')
@inject('periodicOrderStopFlagList', 'App\Common\KeyValueLists\PeriodicOrderStopFlagList')
@inject('undeliveredSummaryClassificationList', 'App\Common\KeyValueLists\UndeliveredSummaryClassificationList')
@inject('marketingSummaryClassificationList', 'App\Common\KeyValueLists\MarketingSummaryClassificationList')
@inject('itemLineupSample1List', 'App\Common\KeyValueLists\ItemLineupSample1List')
@inject('itemLineupSampleAList', 'App\Common\KeyValueLists\ItemLineupSampleAList')

<div class="card">

    {{-- 第1ブロック：顧客情報 start--}}
    <div class="card-header">
        {{__('admin.page_header_name.customer_information')}}1
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target="#search-customer-form-body-1" aria-expanded="true">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-customer-form-body-1" class="card-body collapse show">
        <div class="row">
            <input id="sort_input" name="sort" type="hidden" value="@if(isset($search_params)){{$search_params['sort']}}@endif">
            <input id="page_input" name="page" type="hidden" value="@if(isset($search_params)){{$search_params['page']}}@endif">

            {{-- 顧客ID --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.customer.id')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_id_min')" name="customer_id_min" type="number" min="1" max="2147483647" placeholder="顧客ID" value="@if(old('customer_id_min')){{old('customer_id_min')}}@elseif(isset($search_params)){{$search_params['customer_id_min']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <input class="form-control form-control-sm @isInvalid($errors,'customer_id_max')" name="customer_id_max" type="number" min="1" max="2147483647" placeholder="顧客ID" value="@if(old('customer_id_max')){{old('customer_id_max')}}@elseif(isset($search_params)){{$search_params['customer_id_max']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                    </div>
                </div>
            </div>

            {{-- 会員状態 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.customer.status')}}</label>
                    <div class="col-sm-8 col-form-label col-form-label-sm">
                        @foreach($customerStatusList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_status[]" id="customer-marketing-search-customer-status-{{$id}}" value="{{$id}}"
                                    @if(old('customer_status'))
                                        @foreach(old('customer_status') as $status)
                                            @if($id==$status) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_status']))
                                        @foreach($search_params['customer_status'] as $status)
                                            @if($id==$status) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-customer-status-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 架電禁止 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.customer.no_phone_call')}}</label>

                    <div class="col-sm-8 col-form-label col-form-label-sm">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="customer_no_phone_call" id="customer-marketing-search-no-phone-call" @if(old('customer_no_phone_call')) checked @elseif(isset($search_params['customer_no_phone_call']) && $search_params['customer_no_phone_call']) checked @endif value="1">
                            <label for="customer-marketing-search-no-phone-call" class="form-check-label"></label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- メルマガ --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.customer.mail_magazine_flag')}}</label>
                    <div class="col-sm-8 col-form-label col-form-label-sm">
                        @foreach($mailMagazineFlagList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_mail_flag[]" id="customer-marketing-search-mail-flag-{{$id}}" value="{{$id}}"
                                    @if(old('customer_mail_flag'))
                                        @foreach(old('customer_mail_flag') as $status)
                                            @if($id==$status) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_mail_flag']))
                                        @foreach($search_params['customer_mail_flag'] as $status)
                                            @if($id==$status) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-mail-flag-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- DM可否 --}}
            <div class="col-6 col-lg-3">
                <div class="row form-group">
                    <label class="col-sm-4 col-form-label col-form-label-sm">{{__('admin.item_name.customer.dm_flag')}}</label>
                    <div class="col-sm-8 col-form-label col-form-label-sm">
                        @foreach($directMailFlagList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_dm_flag[]" id="customer-marketing-search-dm-flag-{{$id}}" value="{{$id}}"
                                    @if(old('customer_dm_flag'))
                                        @foreach(old('customer_dm_flag') as $status)
                                            @if($id==$status) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_dm_flag']))
                                        @foreach($search_params['customer_dm_flag'] as $status)
                                            @if($id==$status) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-dm-flag-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 登録日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.customer.created_at')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_created_at_from" type="date" placeholder="" value="@if(old('customer_created_at_from')){{old('customer_created_at_from')}}@elseif(isset($search_params['customer_created_at_from'])){{$search_params['customer_created_at_from']}}@endif" v-on:change="min_max_date($event,'customer_created_at_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <input class="form-control form-control-sm @isInvalid($errors,'customer_created_at_to')" name="customer_created_at_to" type="date" placeholder="" value="@if(old('customer_created_at_to')){{old('customer_created_at_to')}}@elseif(isset($search_params['customer_created_at_to'])){{$search_params['customer_created_at_to']}}@endif" v-on:change="min_max_date($event,'customer_created_at_from', 1)">
                    </div>
                </div>
            </div>

            {{-- 更新日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.customer.updated_at')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_updated_at_from" type="date" placeholder="" value="@if(old('customer_updated_at_from')){{old('customer_updated_at_from')}}@elseif(isset($search_params['customer_updated_at_from'])){{$search_params['customer_updated_at_from']}}@endif" v-on:change="min_max_date($event,'customer_updated_at_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>

                    <div class="col-sm-5 pl-0">
                        <input class="form-control form-control-sm @isInvalid($errors,'customer_updated_at_to')" name="customer_updated_at_to" type="date" placeholder="" value="@if(old('customer_updated_at_to')){{old('customer_updated_at_to')}}@elseif(isset($search_params['customer_updated_at_to'])){{$search_params['customer_updated_at_to']}}@endif" v-on:change="min_max_date($event,'customer_updated_at_from', 1)">
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- 第1ブロック：顧客情報 end --}}

    {{-- 第2ブロック：顧客情報-2 start --}}
    <div class="card-header">
        {{__('admin.page_header_name.customer_information')}}2
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target="#search-customer-form-body-2" aria-expanded="true">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-customer-form-body-2" class="card-body collapse show">
        <div class="row">

            {{-- 都道府県 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("common.item_name.address.prefecture")}}</label>
                    <div class="col-sm-10">
                        <select class="form-control form-control-sm" name="customer_prefecture[]" multiple>
                            @foreach($prefectureList as $id => $name)
                                <option value="{{$id}}"
                                    @if(old('customer_prefecture'))
                                        @foreach(old('customer_prefecture') as $prefecture)
                                            @if($id==$prefecture) selected @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_prefecture']))
                                        @foreach($search_params['customer_prefecture'] as $prefecture)
                                            @if($id==$prefecture) selected @endif
                                        @endforeach
                                    @endif
                                >{{$name}}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">{{__("admin.help_text.select.multiple")}}</small>

                    </div>
                </div>
            </div>

            {{-- 誕生日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__('admin.item_name.customer.birthday')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_birthday_from" type="date" placeholder="" value="@if(old('customer_birthday_from')){{old('customer_birthday_from')}}@elseif(isset($search_params)){{$search_params['customer_birthday_from']}}@endif" v-on:change="min_max_date($event,'customer_birthday_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <input class="form-control form-control-sm @isInvalid($errors,'customer_birthday_to')" name="customer_birthday_to" type="date" placeholder=""  value="@if(old('customer_birthday_to')){{old('customer_birthday_to')}}@elseif(isset($search_params)){{$search_params['customer_birthday_to']}}@endif" v-on:change="min_max_date($event,'customer_birthday_from', 1)">
                    </div>
                </div>
            </div>

            {{-- 誕生月 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.customer.birth_month')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($birthMonthList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_birth_month[]" id="customer-marketing-search-birth-month-{{$id}}" value="{{$id}}"
                                    @if(old('customer_birth_month'))
                                        @foreach(old('customer_birth_month') as $month)
                                            @if($id==$month) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_birth_month']))
                                        @foreach($search_params['customer_birth_month'] as $month)
                                            @if($id==$month) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-birth-month-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- 第2ブロック：顧客情報-2 end --}}

    {{-- 第3ブロック：顧客情報-3 start --}}
    <div class="card-header">
        {{__('admin.page_header_name.customer_information')}}分析
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target="#search-customer-form-body-3" aria-expanded="true">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-customer-form-body-3" class="card-body collapse show">
        <div class="row">
            {{-- PFM属性 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.customer.pfm')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">

                        @foreach($portfolioStatusList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_pfm[]" id="customer-marketing-search-pfm-{{$id}}" value="{{$id}}"
                                    @if(old('customer_pfm'))
                                        @foreach(old('customer_pfm') as $pfm)
                                            @if($id==$pfm) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_pfm']))
                                        @foreach($search_params['customer_pfm'] as $pfm)
                                            @if($id==$pfm) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-pfm-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 広告番号 --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__("admin.item_name.media.code")}}</label>
                    <div class="col-10 col-lg-11">
                        <select class="form-control form-control-sm" name="customer_media_code[]" multiple>
                            @foreach($mediaCodeList as $id => $name)
                                <option value="{{$id}}"
                                    @if(old('customer_media_code'))
                                        @foreach(old('customer_media_code') as $code)
                                            @if($id==$code) selected @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_media_code']))
                                        @foreach($search_params['customer_media_code'] as $code)
                                            @if($id==$code) selected @endif
                                        @endforeach
                                    @endif
                                >{{$name}}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">{{__("admin.help_text.select.multiple")}}</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- 第3ブロック：顧客情報-3 end --}}

    {{-- 第4ブロック：受注情報 start --}}
    <div class="card-header">
        受注購入商品（※キャンセル、返品された受注は集計対象外）
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target="#search-order-form-body-1" aria-expanded="true">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-order-form-body-1" class="card-body collapse show">
        <div class="row">
            {{-- 商品ラインナップ --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.product.lineup')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_item_lineup[]" id="customer-marketing-search-item-lineup-{{$id}}" value="{{$id}}"
                                    @if(old('customer_item_lineup'))
                                        @foreach(old('customer_item_lineup') as $lineup)
                                            @if($id==$lineup) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_item_lineup']))
                                        @foreach($search_params['customer_item_lineup'] as $lineup)
                                            @if($id==$lineup) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-item-lineup-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 分類/顧客 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.product.target_customer')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label-sm">
                        @foreach($SalesTargetList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_sales_target[]" id="customer-marketing-search-target-customer-{{$id}}" value="{{$id}}"
                                    @if(old('customer_sales_target'))
                                        @foreach(old('customer_sales_target') as $target)
                                            @if($id==$target) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_sales_target']))
                                        @foreach($search_params['customer_sales_target'] as $target)
                                            @if($id==$target) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-target-customer-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 分類/経路 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.product.sales_route')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label-sm">
                        @foreach($salesRouteList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_sales_route[]" id="customer-marketing-search-sales-route-{{$id}}" value="{{$id}}"
                                    @if(old('customer_sales_route'))
                                        @foreach(old('customer_sales_route') as $route)
                                            @if($id==$route) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_sales_route']))
                                        @foreach($search_params['customer_sales_route'] as $route)
                                            @if($id==$route) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-sales-route-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 未発送集計区分 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.product.undelivered_summary_classification')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label-sm">
                        @foreach($undeliveredSummaryClassificationList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_undelivered_summary_classification[]" id="customer-marketing-search-product-type-{{$id}}" value="{{$id}}"
                                    @if(old('customer_undelivered_summary_classification'))
                                        @foreach(old('customer_undelivered_summary_classification') as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_undelivered_summary_classification']))
                                        @foreach($search_params['customer_undelivered_summary_classification'] as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-product-type-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 販売属性 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.product.marketing_summary_classification')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label-sm">
                        @foreach($marketingSummaryClassificationList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_marketing_summary_classification[]" id="customer-marketing-search-product-type-{{$id}}" value="{{$id}}"
                                    @if(old('customer_marketing_summary_classification'))
                                        @foreach(old('customer_marketing_summary_classification') as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_marketing_summary_classification']))
                                        @foreach($search_params['customer_marketing_summary_classification'] as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-product-type-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 分類/本数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.product.volume')}}</label>
                    <div class="col-sm-10 col-form-label col-form-label-sm">
                        @foreach($productVolumeList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_product_volume_list[]" id="customer-marketing-search-quantity-{{$id}}" value="{{$id}}"
                                    @if(old('customer_product_volume_list'))
                                        @foreach(old('customer_product_volume_list') as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_product_volume_list']))
                                        @foreach($search_params['customer_product_volume_list'] as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-quantity-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 購入商品名 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("admin.item_name.product.name")}}</label>
                    <div class="col-sm-10">
                        <input class="form-control form-control-sm" name="customer_product_name" type="text" placeholder="購入商品名（部分一致）"  value="@if(old('customer_product_name')){{old('customer_product_name')}}@elseif(isset($search_params)){{$search_params['customer_product_name']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>

            {{-- 商品コード --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("admin.item_name.product.code")}}</label>
                    <div class="col-sm-10">
                        <input class="form-control form-control-sm" name="customer_product_code" type="text" placeholder="商品コード（完全一致）" value="@if(old('customer_product_code')){{old('customer_product_code')}}@elseif(isset($search_params)){{$search_params['customer_product_code']}}@endif" onchange="return app.functions.trim(this);">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card-header">
        受注集計（※キャンセル、返品された受注は集計対象外）
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target="#search-order-form-body-2" aria-expanded="true">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-order-form-body-2" class="card-body collapse show">
        <div class="row">
            {{-- 購入金額 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("admin.item_name.customer.buy_total")}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_buy_total_min')" name="customer_buy_total_min" type="number" min="0" max="2147483647" placeholder="" value="@if(old('customer_buy_total_min')){{old('customer_buy_total_min')}}@elseif(isset($search_params)){{$search_params['customer_buy_total_min']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">円～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_buy_total_max')" name="customer_buy_total_max" type="number" min="0" max="2147483647" placeholder="" value="@if(old('customer_buy_total_max')){{old('customer_buy_total_max')}}@elseif(isset($search_params)){{$search_params['customer_buy_total_max']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">円</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 購入回数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("admin.item_name.customer.buy_times")}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_buy_times_min')" name="customer_buy_times_min" type="number" min="0" max="32767" placeholder="" value="@if(old('customer_buy_times_min')){{old('customer_buy_times_min')}}@elseif(isset($search_params)){{$search_params['customer_buy_times_min']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">回～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_buy_times_max')" name="customer_buy_times_max" type="number" min="0" max="32767" placeholder="" value="@if(old('customer_buy_times_max')){{old('customer_buy_times_max')}}@elseif(isset($search_params)){{$search_params['customer_buy_times_max']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">回</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 購入本数 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm">{{__("admin.item_name.customer.buy_volume")}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_buy_volume_min')" name="customer_buy_volume_min" type="number" min="0" max="32767"  placeholder="" value="@if(old('customer_buy_volume_min')){{old('customer_buy_volume_min')}}@elseif(isset($search_params)){{$search_params['customer_buy_volume_min']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">本～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_buy_volume_max')" name="customer_buy_volume_max" type="number" min="0" max="32767"  placeholder="" value="@if(old('customer_buy_volume_max')){{old('customer_buy_volume_max')}}@elseif(isset($search_params)){{$search_params['customer_buy_volume_max']}}@endif" onchange="return app.functions.trim(this);" v-on:keypress="only_number">
                            <span class="input-group-text form-control-sm">本</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 最終購入日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.customer.last_buy_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_last_buy_date_from" type="date" placeholder="" value="@if(old('customer_last_buy_date_from')){{old('customer_last_buy_date_from')}}@elseif(isset($search_params)){{$search_params['customer_last_buy_date_from']}}@endif" v-on:change="min_max_date($event,'customer_last_buy_date_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_last_buy_date_to')" name="customer_last_buy_date_to" type="date" placeholder="" value="@if(old('customer_last_buy_date_to')){{old('customer_last_buy_date_to')}}@elseif(isset($search_params)){{$search_params['customer_last_buy_date_to']}}@endif" v-on:change="min_max_date($event,'customer_last_buy_date_from', 1)">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 離脱日 --}}
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.customer.withdrawal_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_withdrawal_date_from" type="date" placeholder="" value="@if(old('customer_withdrawal_date_from')){{old('customer_withdrawal_date_from')}}@elseif(isset($search_params)){{$search_params['customer_withdrawal_date_from']}}@endif" v-on:change="min_max_date($event,'customer_withdrawal_date_to', 0)">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm @isInvalid($errors,'customer_withdrawal_date_to')" name="customer_withdrawal_date_to" type="date" placeholder="" value="@if(old('customer_withdrawal_date_to')){{old('customer_withdrawal_date_to')}}@elseif(isset($search_params)){{$search_params['customer_withdrawal_date_to']}}@endif" v-on:change="min_max_date($event,'customer_withdrawal_date_from', 1)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- 第4ブロック：受注情報 end --}}

    {{-- 第5ブロック：定期情報 start --}}{{--
    <div class="card-header">
        定期
        <div class="card-header-actions">
            <label class="switch-sm switch-label switch-outline-primary-alt" role="button" data-toggle="collapse" data-target="#search-periodic-form-body-1" aria-expanded="true">
                <input class="switch-input" type="checkbox"  checked="">
                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
            </label>
        </div>
    </div>

    <div id="search-periodic-form-body-1" class="card-body collapse show">
        <div class="row">
            <div class="col-12 col-lg-12">
                定期商品
                <hr>
            </div>

            --}}{{-- 商品ラインナップ --}}{{--
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm" >{{__('admin.item_name.product.lineup')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_product_lineup[]" id="customer-marketing-search-periodic-lineup-{{$id}}" value="{{$id}}"
                                    @if(isset($search_params['customer_product_lineup']))
                                        @foreach($search_params['customer_product_lineup'] as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-periodic-lineup-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-12">
                定期状態
                <hr>
            </div>

            --}}{{-- 稼働状況 --}}{{--
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <label class="col-2 col-lg-1 col-form-label col-form-label-sm">{{__('admin.item_name.periodic.stop_flag')}}</label>
                    <div class="col-10 col-lg-11 col-form-label col-form-label-sm">
                        @foreach($periodicOrderStopFlagList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_periodic_stop_flag[]" id="customer-marketing-search-periodic-stop-flag-{{$id}}" value="{{$id}}"
                                    @if(isset($search_params['customer_periodic_stop_flag']))
                                        @foreach($search_params['customer_periodic_stop_flag'] as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-periodic-stop-flag-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="customer_inline_checkbox" id="inlineCheckbox-31-99" value="1" @if(isset($search_params['customer_inline_checkbox']) && $search_params['customer_inline_checkbox']) checked @endif>
                            <label for="inlineCheckbox-31-99" class="form-check-label">未購入</label>
                        </div>

                    </div>
                </div>
            </div>

            --}}{{-- 定期停止日 --}}{{--
            <div class="col-12 col-lg-6">
                <div class="row form-group">
                    <label class="col-sm-2 col-form-label col-form-label-sm" >{{__('admin.item_name.periodic.stop_date')}}</label>
                    <div class="col-sm-5 pr-0">
                        <div class="input-group-prepend">
                            <input class="form-control form-control-sm" name="customer_periodic_stop_date_from" type="date" placeholder="" value="@if(isset($search_params)){{$search_params['customer_periodic_stop_date_from']}}@endif">
                            <span class="input-group-text form-control-sm">～</span>
                        </div>
                        <small class="form-text text-muted">※{{__("admin.help_text.periodic.stop_date1")}}</small>
                        <small class="form-text text-muted">※{{__("admin.help_text.periodic.stop_date2")}}</small>
                    </div>
                    <div class="col-sm-5 pl-0">
                        <input class="form-control form-control-sm" name="customer_periodic_stop_date_to" type="date" placeholder="" value="@if(isset($search_params)){{$search_params['customer_periodic_stop_date_to']}}@endif">
                    </div>
                </div>
            </div>

        </div>
    </div>
    --}}{{-- 第5ブロック：定期情報 end --}}

    {{-- 提案ブロック：顧客情報 start--}}
    <div class="card-header">
        マーケティングスクリーニング（案）
        <div class="card-header-actions">
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            {{-- 商品ラインナップ --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <div class="col-8 col-lg-2 col-form-label col-form-label-sm">
                        使用/未使用
                    </div>

                    <div class="col-4 col-lg-4">
                        @foreach($itemLineupSample1List as $id => $name)
                            <label>
                                <input type="radio" name="sample1" value="{{$id}}" v-model="sample1"
                                    @if(null!==old('sample1') && old('sample1')==$id)
                                       checked
                                    @elseif(isset($search_params['sample1']) && $search_params['sample1']==$id)
                                        checked
                                    @elseif(null===old('sample1') && !isset($search_params['sample1']) && !$id)
                                        checked
                                    @endif
                                >
                                {{$name}}</label>
                        @endforeach
                    </div>

                    <div class="col-8 col-lg-6 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_item_lineup_sample1[]" id="customer-marketing-search-item-lineup-sample1-{{$id}}" value="{{$id}}" v-bind:disabled="sample1==0"
                                    @if(old('customer_item_lineup_sample1'))
                                        @foreach(old('customer_item_lineup_sample1') as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_item_lineup_sample1']))
                                        @foreach($search_params['customer_item_lineup_sample1'] as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-item-lineup-sample1-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- 商品ラインナップ --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <div class="col-8 col-lg-2 col-form-label col-form-label-sm">
                        定期稼働条件（A）
                    </div>
                    <div class="col-4 col-lg-4">
                        @foreach($itemLineupSampleAList as $id => $name)
                            <label>
                                <input type="radio" name="sampleA" value="{{$id}}" v-model="sampleA"
                                    @if(null!==old('sampleA') && old('sampleA')==$id)
                                        checked
                                    @elseif(isset($search_params['sampleA']) && $search_params['sampleA']==$id)
                                        checked
                                    @elseif(null===old('sampleA') && !isset($search_params['sampleA']) && !$id)
                                        checked
                                    @endif
                                >
                                {{$name}}</label>
                        @endforeach
                    </div>

                    <div class="col-8 col-lg-6 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_item_lineup_sampleA[]" id="customer-marketing-search-item-lineup-sample2-{{$id}}" value="{{$id}}" v-bind:disabled="sampleA==0"
                                     @if(old('customer_item_lineup_sampleA'))
                                         @foreach(old('customer_item_lineup_sampleA') as $one)
                                             @if($id==$one) checked @endif
                                         @endforeach
                                     @elseif(isset($search_params['customer_item_lineup_sampleA']))
                                         @foreach($search_params['customer_item_lineup_sampleA'] as $one)
                                             @if($id==$one) checked @endif
                                         @endforeach
                                     @endif
                                >
                                <label for="customer-marketing-search-item-lineup-sample2-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- 商品ラインナップ --}}
            <div class="col-12 col-lg-12">
                <div class="row form-group">
                    <div class="col-8 col-lg-2 col-form-label col-form-label-sm">
                        定期稼働条件（B）
                    </div>
                    <div class="col-4 col-lg-4">
                        @foreach($itemLineupSampleAList as $id => $name)
                            <label>
                                <input type="radio" name="sampleB" value="{{$id}}" v-model="sampleB"
                                    @if(null!==old('sampleB') && old('sampleB')==$id)
                                        checked
                                    @elseif(isset($search_params['sampleB']) && $search_params['sampleB']==$id)
                                        checked
                                    @elseif(null===old('sampleB') && !isset($search_params['sampleB']) && !$id)
                                        checked
                                    @endif
                                >
                                {{$name}}</label>
                        @endforeach
                    </div>

                    <div class="col-8 col-lg-6 col-form-label col-form-label-sm">
                        @foreach($itemLineupList as $id => $name)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="customer_item_lineup_sampleB[]" id="customer-marketing-search-item-lineup-sample4-{{$id}}" value="{{$id}}" v-bind:disabled="sampleB==0"
                                    @if(old('customer_item_lineup_sampleB'))
                                        @foreach(old('customer_item_lineup_sampleB') as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @elseif(isset($search_params['customer_item_lineup_sampleB']))
                                        @foreach($search_params['customer_item_lineup_sampleB'] as $one)
                                            @if($id==$one) checked @endif
                                        @endforeach
                                    @endif
                                >
                                <label for="customer-marketing-search-item-lineup-sample4-{{$id}}" class="form-check-label">{{$name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
