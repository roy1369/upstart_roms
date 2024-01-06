@extends('layouts.reg_layout')

@section('title', '勤怠管理システム登録完了ページ')

@section('content')

    <div class="centering_parent">
        <div class="centering">
            <h3>
                <p>イベント決済システム申込</p>
                <p>登録が完了しました！</p>
                <p>下記URLが管理用のサイトです</p>
                <a href="{{ config('services.app.url') }}/attendancemanagement/login">こちら</a>
            </h3>
        </div>     
    </div>
@endsection

