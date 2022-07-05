{{-- メルマガ不達CSVインポート --}}
@extends('admin.layouts.main.contents')

@section('title') CSVインポート > メルマガ不達 @endsection

@section('contents')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <form action="" method="post">

                    <div class="card-header">
                        メルマガ不達CSV
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
                                <td>不明</td>
                                <td>◯</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>不明</td>
                                <td>☓</td>
                                <td>YYYY-MM-DD</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
@endsection
