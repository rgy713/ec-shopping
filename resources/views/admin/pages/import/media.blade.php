@extends('admin.layouts.main.contents')
@section('title') CSVインポート > 広告媒体 @endsection

@section('contents')

    <div class="row">
        <div class="col-6">
            <div class="card">
                <form action="" method="post">
                    {{ csrf_field() }}

                    <div class="card-header">
                        1.アドセント形式(ファイル名：sales_YYYYMMDDhhmmss******)
                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-2 col-form-label" for="file-input">インポート</label>
                            <div class="col-10">
                                <input id="file-input" type="file" name="file-input">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-2 col-form-label" for=""></label>
                            <div class="col-10">
                                <button class="btn btn-sm btn-block btn-primary">確認</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <form action="" method="post">
                    {{ csrf_field() }}

                    <div class="card-header">
                        入力フォーマット
                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>列番号</th>
                                <th>内容</th>
                                <th>必須</th>
                                <th>フォーマット</th>
                                <th>備考</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>???</td>
                                <td>◯</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>???</td>
                                <td>◯</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>???</td>
                                <td>☓</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <form action="" method="post">

                    <div class="card-header">
                        2.アドエビス形式（ファイル名：成果リスト_*******）
                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-2 col-form-label" for="file-input">インポート</label>
                            <div class="col-10">
                                <input id="file-input" type="file" name="file-input">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-2 col-form-label" for=""></label>
                            <div class="col-10">
                                <button class="btn btn-sm btn-block btn-primary">確認</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <form action="" method="post">

                    <div class="card-header">
                        入力フォーマット
                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>列番号</th>
                                <th>内容</th>
                                <th>必須</th>
                                <th>フォーマット</th>
                                <th>備考</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>???</td>
                                <td>◯</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>???</td>
                                <td>◯</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>???</td>
                                <td>☓</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <span class="specification" data-toggle="popover" data-placement="right" data-content="現行の画面の仕様を踏襲の認識だったが、「マクロの機能をこの画面にマージする」という発言があった。※マクロの仕様については石橋マターのため、ヒアリングの上、・現状の仕様　および　・リニューアルにあたり追加する機能　の精査が必要。">&nbsp;</span>

@endsection

