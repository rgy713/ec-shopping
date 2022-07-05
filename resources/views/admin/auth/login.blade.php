<!DOCTYPE html>
<html lang="en">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title></title>

    {{-- 全管理画面で共通のスタイル --}}
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/coreui.css') }}" rel="stylesheet">
</head>
<body class="app flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        <h1>Login</h1>

                        <form class="form-horizontal" method="POST" action="/admin/login">
                            {{ csrf_field() }}
                            <p class="text-muted">Sign In to your account</p>
                            <div class="input-group mb-3 ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="icon-user"></i>
                                    </span>
                                </div>
                                <input id="account" type="account" name="account" value="{{ old('account') }}" class="form-control @isInvalid($errors,'account')" type="text" placeholder="Username" required autofocus>
                                <div class="invalid-feedback">{{ $errors->first('account') }}</div>
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="icon-lock"></i>
                                    </span>
                                </div>
                                <input id="password" type="password" class="form-control @isInvalid($errors,'password')" name="password" placeholder="Password" required>
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary px-4 btn-block" type="submit">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 全管理画面で共通のjavascriptファイルをロードする --}}
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/coreui.js') }}"></script>

</body>
</html>