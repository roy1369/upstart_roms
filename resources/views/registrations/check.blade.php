@extends('layouts.reg_layout')

@section('title', '勤怠管理システム認証確認ページ')

@section('content')

<div class="centering_parent">
    @if ($invalidFlag === true)
        <div class="centering">
            <h3>
                <p>認証コードが無効です。</p>
                最初からお願いします。
            </h3>
        </div>
    @elseif ($userFlag === true && $emailVerifiedFlag === false)
        <div class="centering">
            <h3>
                既に登録済みです。
            </h3>
        </div>
    @else
        <div class="centering">
            <h3>
                勤怠管理システム登録
            </h3>
        </div>

        <form id="checkForm" action="/registration/comp" method="post">
            @csrf
            <p>
                <input name="code" placeholder="6桁の認証コード" class="checkform" pattern="\d{6}" title="半角数字6桁で入力してください" required>
            </p>
            <!-- エラーメッセージ表示領域 -->
            <div id="emailError" class="error-message" style="color: red;"></div>
            <button type="button" id="checkButton" class="paystripe">認証コードの確認</button>
        </form>
    @endif
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // 利用規約に同意して登録ボタンの処理を追加
        $('#checkButton').click(function () {
                // ボタンを非活性化
                $('#checkButton').prop('disabled', true); 
                // formの制御の有効化
                var elem = document.getElementById("checkForm");
                if(!elem.reportValidity()) {
                    // 入力検証エラーがある場合はボタンを再び活性化
                    $('#checkButton').prop('disabled', false);
                    return false;
                }
                $('#checkForm').submit();
                
            });
    </script>
@endsection