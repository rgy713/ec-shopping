@extends('admin.layouts.main.contents')

@section('title') 受注管理 > 伝票番号取り込み @endsection

@section('contents')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <form method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                    {{csrf_field()}}
                    <div class="card-header">
                        伝票番号CSV

                        <span class="specification" data-toggle="popover" data-placement="right" data-content="同時に2枚インポート可能とする（2018-11-02打ち合わせ要望）">&nbsp;</span>

                        <div class="card-header-actions">
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-2 col-form-label" for="file-input1">インポート1</label>
                            <div class="col-10">
                                <input class="form-control form-control-sm @isInvalid($errors,'file1') @isInvalid($errors,'file1_extension') @isInvalid($errors,'error_msg1')" id="file-input1" type="file" name="file1" accept=".csv" required>
                                <div class="invalid-feedback">{{$errors->first('file1')}}</div>
                                <div class="invalid-feedback">{{$errors->first('file1_extension')}}</div>
                                <div class="invalid-feedback">{!! $errors->first('error_msg1') !!}</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-2 col-form-label" for="file-input2">インポート2</label>
                            <div class="col-10">
                                <input class="form-control form-control-sm @isInvalid($errors,'file2') @isInvalid($errors,'file2_extension') @isInvalid($errors,'error_msg2')" id="file-input2" type="file" name="file2" accept=".csv">
                                <div class="invalid-feedback">{{$errors->first('file2')}}</div>
                                <div class="invalid-feedback">{{$errors->first('file2_extension')}}</div>
                                <div class="invalid-feedback">{!! $errors->first('error_msg2') !!}</div>
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
                                <td>受注番号</td>
                                <td>◯</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>伝票番号</td>
                                <td>◯</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>出荷日</td>
                                <td>☓</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
@endsection

