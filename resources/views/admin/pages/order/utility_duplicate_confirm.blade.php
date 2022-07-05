@inject('customerStatusList', 'App\Common\KeyValueLists\CustomerStatusList')
@inject('prefectureList', 'App\Common\KeyValueLists\PrefectureList')
@inject('mediaCodeList', 'App\Common\KeyValueLists\MediaCodeList')

@extends('admin.layouts.main.contents')

@section('title') 受注管理 > 受注管理ツール > 重複に対する処理 @endsection

@section('contents')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    重複に対する処理
                    <div class="card-header-actions">
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>受注ID</th>
                            <th>
                                A顧客主要情報<br/>
                                B顧客主要情報
                            </th>
                            <th>
                                A顧客氏名<br/>
                                B顧客氏名
                            </th>
                            <th>
                                A購入者住所<br/>
                                B購入者住所
                            </th>
                            <th>
                                A購入者電話<br/>
                                B購入者電話
                            </th>
                            <th>
                                A購入者誕生日<br/>
                                B購入者誕生日
                            </th>
                            <th>
                                A購入者広告番号<br/>
                                B購入者広告番号
                            </th>
                            <th>広告番号選択ボックス</th>
                            <th>
                                Aに統合　ボタン<br/>
                                Bに統合　ボタン
                            </th>
                            <th>
                                統合しないボタン
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($duplicates))
                        @foreach($duplicates as $one)
                            <tr class="border border-0">
                                {{-- 受注ID --}}
                                <td class="pb-1" rowspan="2">
                                    {{$one->order->id}}
                                </td>

                                {{-- 顧客主要情報 --}}
                                <td class="pb-1">
                                    {{$customerStatusList[$one->customerA->customer_status_id]}}
                                </td>

                                {{-- 顧客氏名 --}}
                                <td class="pb-1">
                                    {{$one->customerA->name01}}{{$one->customerA->name02}}
                                </td>

                                {{-- 購入者住所 --}}
                                <td class="pb-1">
                                    {{$prefectureList[$one->customerA->prefecture_id]}}{{$one->customerA->address01}}{{$one->customerA->address02}}
                                </td>

                                {{-- 購入者電話 --}}
                                <td class="pb-1">
                                    {{$one->customerA->phone_number01}}
                                </td>

                                {{-- 購入者誕生日 --}}
                                <td class="pb-1">
                                    {{$one->customerA->birthday}}
                                </td>

                                {{-- 購入者広告番号 --}}
                                <td class="pb-1">
                                    {{$one->customerA->advertising_media_code}}
                                </td>

                                {{-- 広告番号選択ボックス --}}
                                <td class="pb-1" rowspan="2">
                                    @if($one->customerA->advertising_media_code != null || $one->customerB->advertising_media_code != null)
                                        <select id="media_code_{{$one->id}}" class="form-control form-control-sm">
                                            @if(isset($one->customerA->advertising_media_code))
                                                <option value="{{$one->customerA->advertising_media_code}}">{{$one->customerA->advertising_media_code}}</option>
                                            @endif
                                            @if(isset($one->customerB->advertising_media_code))
                                                <option value="{{$one->customerB->advertising_media_code}}">{{$one->customerB->advertising_media_code}}</option>
                                            @endif
                                        </select>
                                    @endif
                                </td>

                                {{--Aに統合　ボタン--}}
                                <td>
                                    <button type="button" class="btn btn-sm btn-block" data-id="{{$one->id}}" data-merge_type="A" onclick="merge_customer(this)">Aに統合</button>
                                </td>

                                {{--統合しないボタン--}}
                                <td>
                                    <button type="button" class="btn btn-sm btn-block" data-id="{{$one->id}}" data-merge_type="N" onclick="merge_customer(this)">統合しない</button>
                                </td>
                            </tr>

                            {{--2行目--}}
                            <tr class="border border-0">
                                {{-- 重複疑い対象顧客主要情報 --}}
                                <td class="pb-1">
                                    {{$customerStatusList[$one->customerB->customer_status_id]}}
                                </td>

                                {{-- 重複疑い対象氏名 --}}
                                <td class="pb-1">
                                    {{$one->customerB->name01}}{{$one->customerB->name02}}
                                </td>

                                {{-- 重複疑い対象住所 --}}
                                <td class="pb-1">
                                    {{$prefectureList[$one->customerB->prefecture_id]}}{{$one->customerB->address01}}{{$one->customerB->address02}}
                                </td>

                                {{-- 重複疑い対象電話 --}}
                                <td class="pb-1">
                                    {{$one->customerB->phone_number01}}
                                </td>

                                {{-- 重複疑い対象誕生日 --}}
                                <td class="pb-1">
                                    {{$one->customerB->birthday}}
                                </td>

                                {{-- 重複疑い対象広告番号 --}}
                                <td class="pb-1">
                                    {{$one->customerB->advertising_media_code}}
                                </td>

                                {{--Bに統合　ボタン--}}
                                <td>
                                    <button type="button" class="btn btn-sm btn-block" data-id="{{$one->id}}" data-merge_type="B" onclick="merge_customer(this)">Bに統合</button>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-3"></div>
        <div class="col-6">
            <a href="{{route("admin.order.utility")}}"><button class="btn btn-sm btn-secondary btn-block" type="button"><i class="{{__('admin.icon_class.back')}}"></i>&nbsp;{{__('admin.operation.back')}}</button></a>
        </div>
        <div class="col-3"></div>
    </div>
    <form id="merge_customer_form" method="post" style="width: 100%;">
        {{csrf_field()}}
        <input type="hidden" name="id">
        <input type="hidden" name="media_code_id">
        <input type="hidden" name="merge_type">
    </form>
@endsection
@push("content_js")
    <script>
        function merge_customer(e){
            const $form = $("#merge_customer_form");
            const id = $(e).data("id");
            const merge_type = $(e).data("merge_type");

            $("#merge_customer_form input[name=id]").val(id);
            $("#merge_customer_form input[name=merge_type]").val(merge_type);

            const media_code_id = $("select#media_code_"+ id).val();
            if(media_code_id)
                $("#merge_customer_form input[name=media_code_id]").val(media_code_id);

            $form.submit();
        }
    </script>
@endpush