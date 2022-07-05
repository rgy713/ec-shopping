@extends('admin.layouts.main.contents')

@section('title') 受注管理 > 受注管理ツール @endsection

@section('contents')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    1.取り込み予約ステータスの一括処理
                    <div class="card-header-actions">
                    </div>
                </div>

                <div class="card-body">
                    新規受付から取り込み予約、取り込み予約から新規受付へのステータス変更を一括で行います。
                    <table class="table table-sm table-bordered">
                        <thed>
                            <tr>
                                <th></th>
                                <th>更新対象件数</th>
                                <th>詳細</th>
                            </tr>
                        </thed>
                        <tbody>
                        <tr>
                            <th>→ 取り込み予約</th>
                            <td>{{$toImport}}件</td>
                            <td>ステータスが「新規受付」「定期受付」、お届け日が{{\Carbon\Carbon::today()->addDays(6)->format("Y/m/d")}}以降の受注を「取り込み予約」ステータスへ変更します。</td>
                        </tr>

                        <tr>
                            <th>→ 新規受付</th>
                            <td>{{$toNew}}件</td>
                            <td>ステータスが「取り込み予約」「定期受付」、お届け日が{{\Carbon\Carbon::today()->addDays(5)->format("Y/m/d")}}までの受注を「新規受付」ステータスへ変更します。</td>
                        </tr>
                        </tbody>
                    </table>

                </div>

                <div class="card-footer">
                    <a href="{{route('admin.order.utility.reservation')}}">
                        <button class="btn btn-sm btn-primary">詳細</button>
                    </a>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    2.重複疑い検出
                    <div class="card-header-actions">
                    </div>
                </div>

                <div class="card-body">
                    検査対象：{{$checkNewCount}}件（新規受付、定期受付）<br>
                    重複疑いのあるデータを検出し、統合処理を行います。
                </div>

                <div class="card-footer">
                    <a href="{{route('admin.order.utility.duplicate')}}">
                        <button class="btn btn-sm btn-primary">詳細</button>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    3.決済処理中キャンセル
                    <div class="card-header-actions">
                    </div>
                </div>

                <div class="card-body">
                    対象：{{$toCancel}}件（決済処理中、定期決済処理中）<br>
                    決済処理中ステータスの受注をキャンセルし、メール送信を行います。
                </div>

                <div class="card-footer">
                    <a href="{{route('admin.order.utility.cancel')}}">
                        <button class="btn btn-sm btn-primary">詳細</button>
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection

