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

            <!-- パスワード（確認用）入力フォーム -->
            <div class="password-input-container">
                <label class="label">パスワード（確認用）</label>
                <p>
                    <input type="password" name="checkPassword" id="checkPasswordInput" placeholder="英小文字と数字で8桁以上" class="mailform" pattern="^(?=.*[a-z])(?=.*\d)[a-z\d]{8,}$" title="英小文字と数字で8桁以上を入力してください" required autocomplete="off" oninput="checkPasswordMatch()">
                    <span class="toggle-password" onclick="toggleCheckPasswordVisibility()">　
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" id="checkPasswordIcon" style="vertical-align: middle;">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </p>
            </div>
            <!-- パスワード確認一致エラーメッセージ表示領域 -->
            <div id="passwordMatchError" class="error-message" style="color: red;"></div>

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
                    勤怠管理システム 利用規約<br><br>

                    1. 受諾<br>

                    1.1 本利用規約（以下、「本規約」といいます）は、株式会社UPSTART（以下、「運営者」といいます）が提供する勤怠管理システム（以下、「本システム」といいます）の利用条件を定めるものです。<br>

                    1.2 本システムを利用するには、本規約に同意する必要があります。本システムを使用することで、本規約のすべての条件に同意したものとみなされます。<br><br>

                    2. アカウント<br>

                    2.1 本システムを利用するためには、アカウントの登録が必要です。アカウントの登録に際しては、真実かつ正確な情報を提供することが求められます。<br>

                    2.2 アカウントは個人または組織ごとに1つしか作成できません。アカウントの共有は禁止されています。<br><br>

                    3. プライバシー<br>

                    3.1 運営者は、ユーザーの個人情報を適切に管理し、プライバシーを尊重します。詳細については、プライバシーポリシーを参照してください。<br><br>

                    4. 利用制限<br>

                    4.1 ユーザーは、本システムを以下のような目的で使用してはなりません：<br>
                    - 法令に違反する目的での利用<br>
                    - 他のユーザーへの迷惑行為<br>
                    - システムやネットワークの妨害、破壊、乗取り<br><br>

                    5. サービスの変更と終了<br>

                    5.1 運営者は、事前の通知なしに、本システムの提供を変更、中断、または終了する権利を有します。<br><br>

                    6. 免責事項<br>

                    6.1 本システムは、現状有姿で提供されます。運営者は、本システムの利用により生じた損害に対して一切の責任を負いません。<br><br>

                    7. その他の規定<br>

                    7.1 本規約に定められていない事項については、運営者の裁量により判断されます。<br><br>

                    8. 連絡先<br>

                    8.1 本システムに関する問い合わせや連絡は、株式会社UPSTARTまでお願いします。<br><br>

                    9. 規約の変更<br>

                    9.1 運営者は、本規約を変更する権利を有します。変更がある場合は、ユーザーに対して適切な方法で通知いたします。<br><br>

                    10. 法的効力<br>

                    10.1 本規約は日本法に基づいて解釈され、適用されます。<br>
                                        
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
            // パスワードフィールドとエラーメッセージをリセット
            $('#passwordInput, #checkPasswordInput').val('');
            $('#passwordMatchError').text('');
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
             } else {
                passwordInput.type = 'password';
             }
        }

        function toggleCheckPasswordVisibility() {
            var passwordInput = document.getElementById('checkPasswordInput');
            var passwordIcon = document.getElementById('checkPasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
             }
        }

        function checkPasswordMatch() {
            var passwordInput = document.getElementById('passwordInput');
            var checkPasswordInput = document.getElementById('checkPasswordInput');
            var passwordMatchError = document.getElementById('passwordMatchError');
            var agreeTermsCheckbox = document.getElementById('agreeTerms');

            if (passwordInput.value === checkPasswordInput.value) {
                passwordMatchError.textContent = ''; // 一致している場合はエラーメッセージをクリア
                if (agreeTermsCheckbox.checked) {
                    $('#registerButton').prop('disabled', false); // 利用規約に同意している場合にボタンを有効化
                }
            } else {
                passwordMatchError.textContent = 'パスワードが一致しません';
                $('#registerButton').prop('disabled', true); // パスワードが一致しない場合はボタンを無効化
            }
        }
    </script>
@endsection