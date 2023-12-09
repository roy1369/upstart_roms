@extends('layouts.reg_layout')

@section('title', '勤怠管理システムアカウント登録ページ')

@section('content')

    <div class="centering_parent">

        <div class="centering">
            <h3>
                勤怠管理システム登録
            </h3>
        </div>

        <form id="registrationForm" action="/registration" method="post">
            @csrf
            <label class="label">氏名</label>
            <p>
                <input name="name" placeholder="【例】山田太郎" class="mailform" required>
            </p>
            <label class="label">ふりがな</label>
            <p>
                <input name="name_kana" placeholder="ひらがなで入力" class="mailform">
            </p>
            <label class="label">メールアドレス</label>
            <p>
                <input type="email" name="email" placeholder="メールアドレス" class="mailform" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required autocomplete="off">
            </p>
            <!-- エラーメッセージ表示領域 -->
            <div id="emailError" class="error-message" style="color: red;"></div>
            <!-- パスワード入力フォーム -->
            <div class="password-input-container">
                <label class="label">パスワード</label>
                <p>
                    <input type="password" name="password" id="passwordInput" placeholder="英小文字と数字で8桁以上" class="mailform" pattern="^(?=.*[a-z])(?=.*\d)[a-z\d]{8,}$" title="英小文字と数字で8桁以上を入力してください" required autocomplete="off">
                    <span class="toggle-password" onclick="togglePasswordVisibility()">　
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" id="passwordIcon" style="vertical-align: middle;">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </p>
            </div>

            <div class="centering">
                <!-- 利用規約をポップアップ表示するためのリンク -->
                <a href="#" id="showTerms">利用規約はこちら</a>
            </div>

            

            <button type="button" id="registerButton" class="paystripe" disabled>利用規約に同意して登録</button>
        </form>

        <!-- 利用規約ポップアップ -->
        <div id="termsModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" id="closeTerms">&times;</span>
                <!-- 利用規約の内容をここに追加 -->
                <p>
                    利用規約の内容。<br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    
                    <br>
                    ああああああ
                </p>
                <!-- 利用規約同意チェックボックス -->
                <div class="checkbox-container">
                    <input type="checkbox" id="agreeTerms" class="checkbox-style" required>
                    <label for="agreeTerms" class="checkmark"></label>
                    <label for="agreeTerms">利用規約に同意する</label>
                </div>
            </div>
        </div>
        
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // メールアドレスのエラーメッセージ表示
        $('#emailError').text('{{ $emailErrorMessage }}');
        // jQueryを使用したスクリプト
        $(document).ready(function () {
            // 利用規約ポップアップ表示用
            $('#showTerms').click(function () {
                $('#termsModal').show();
            });

            // 利用規約ポップアップ非表示用
            $('#closeTerms').click(function () {
                $('#termsModal').hide();
            });

            // チェックボックスが変更されたときの処理
            $('#agreeTerms').change(function () {
                // チェックボックスの状態によって登録ボタンの有効/無効を切り替える
                $('#registerButton').prop('disabled', !this.checked);
                // 1秒待機してから処理を実行
                setTimeout(function () {
                    $('#termsModal').hide();
                }, 800);
            });

            // 利用規約に同意して登録ボタンの処理を追加
            $('#registerButton').click(function () {
                // ボタンを非活性化
                $('#registerButton').prop('disabled', true); 
                // formの制御の有効化
                var elem = document.getElementById("registrationForm");
                if(!elem.reportValidity()) {
                    // 入力検証エラーがある場合はボタンを再び活性化
                    $('#registerButton').prop('disabled', false);
                    return false;
                }
                // チェックボックスがチェックされている場合にフォームの送信処理を実行
                if ($('#agreeTerms').prop('checked')) {
                    $('#registrationForm').submit();
                } else {
                    alert("利用規約に同意してください。");
                }
            });
        });

        function togglePasswordVisibility() {
        var passwordInput = document.getElementById('passwordInput');
        var passwordIcon = document.getElementById('passwordIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.innerHTML = '<path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />';
        } else {
            passwordInput.type = 'password';
            passwordIcon.innerHTML = '<path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />';
        }
    }
    </script>
@endsection