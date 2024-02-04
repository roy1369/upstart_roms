<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title')</title>
        <meta name="description" content="勤怠管理システムアカウント登録ページ" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        {{-- <meta name="viewport" content="user-scalable=no" /> --}}
        <link rel="stylesheet" href='/css/checkout.css' />
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div class="container">
            <header>
                <div class="logo-header">
                <img src="{{ asset('images/logo.png') }}" height="75" alt="ロゴを表示する">
                </div>
            </header>
            <div class="content">
                @yield('content')
            </div>
            <div class="footer">
                Copyright © 2023 株式会社UPSTART. All Rights Reserved.
            </div>
        </div>
    </body>
</html>