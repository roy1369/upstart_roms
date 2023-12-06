<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class RegistrationController extends Controller
{
    /**
     * 利用登録ページを表示するため
     * @param Request
     * @return 利用登録ページ
     */
    public function index()
    {  
        $emailErrorMessage = '';
        return view('registrations.registration', ['emailErrorMessage' => $emailErrorMessage]);
    }

    /**
     * 利用登録メール送信ページを表示するため
     * @param Request
     * @return 利用登録メール送信ページ
     */
    public function send(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $view = 'registrations.send';
        $emailErrorMessage = '';

        if (is_null($user)) {
            // ユーザーテーブルにレコードを保存
            $user = new User;
            $user->name = $request->name;                                 // ユーザー名
            $user->name_kana = $request->name_kana;                       // ふりがな
            $user->email = $request->email;                               // メールアドレス
            $user->password = $request->password;                         // パスワード
            $user->save();
            
            // 認証コードの生成とメール送信
            $this->sendConfirmationEmail($request->email);

        } elseif (is_null($user->email_verified_at)) {
            // 認証コードの生成とメール送信
            $this->sendConfirmationEmail($request->email);

        } else {
            // メールアドレスが既に存在する場合のエラーメッセージ
            $emailErrorMessage = 'このメールアドレスは既に登録されています。別のメールアドレスを使用してください。';
            $view = 'registrations.registration';
        }
        return view($view, ['emailErrorMessage' => $emailErrorMessage]);
    }

    /**
     * 認証コード確認ページを表示するため
     * @param Request
     * @return 認証コード確認ページ
     */
    public function check()
    {   
        // セッションデータを取得する
        $sessionData = Session::get('session_data');
        $invalidFlag = false;
        $userFlag = false;
        $emailVerifiedFlag = false;
        // セッションデータが存在するかチェック
        if (is_null($sessionData)) {
            $invalidFlag = true;
        } else {
            
            // 認証コードを送信した時間を取得する
            $sendTime = $sessionData['now'];
            // 現在の時間を取得する
            $nowTime = now();
            // 現在の時間から20分前の時間を取得する
            $twentyMinutesAgo = $nowTime->subMinutes(20);
            if ($sendTime < $twentyMinutesAgo) {
                $invalidFlag = true;
            } else {
                $user = User::where('email', $sessionData['email'])->first();
                if (!is_null($user)) {
                    $userFlag = true;
                    if (is_null($user->email_verified_at)) {
                        $emailVerifiedFlag = true;
                    } 
                }
            }
        }
        
        return view('registrations.check', [
            'invalidFlag' => $invalidFlag, 
            'userFlag' => $userFlag, 
            'emailVerifiedFlag' => $emailVerifiedFlag,
        ]);
    }

    /**
     * 利用登録完了ページを表示するため
     * @param Request
     * @return 利用登録完了ページ
     */
    public function comp(Request $request)
    {
        // 現在の日付の格納
        $now = CarbonImmutable::now();
        // セッションデータを取得する
        $sessionData = Session::get('session_data');
        // アドレスを取得する
        $email = $sessionData['email'];
        // 画面で入力された確認コードを取得
        $userCode = (int)$request->input('code');
        // セッションに保存していた確認コードを取得
        $storedCode = $sessionData['confirmation_code'];
        // 結果に応じた分岐処理
        if ($userCode === $storedCode) {
            // ユーザーレコードのアップデート
            $user = User::where('email', $email)->first();
            $user->email_verified_at = $now;
            $user->save();

            // メールでの通知処理
            // 件名をカスタマイズ
            $subject = '[勤怠管理システム]仮登録完了のご案内';

            // 本文をカスタマイズ
            $messageText = "勤怠管理システムをご利用いただき誠にありがとうございます。
                \n\n以下の内容で登録を受け付けました。
                \n\n ---------- 
                \n■個人名： $user->name 
                \n■ふりがな： $user->name_kana 
                \n■メールアドレス： $user->email 
                \n ---------- 
                \n\nなお、この内容にお心あたりのない場合やご不明な点がある場合は株式会社upstartまでお問い合わせください。
                \n本メールは送信専用のため、返信はお受けしておりませんので、ご了承ください。";
        
            // 通知メールを送信
            Mail::raw($messageText, function ($message) use ($email, $subject) {
                $message->to($email);
                $message->subject($subject);
            });

            $view = 'registrations.comp';
        } else {
            $view = 'registrations.error';
        }

        return view($view);
    }

    private function sendConfirmationEmail($email) 
    {
        // 現在の日付の格納
        $now = CarbonImmutable::now();
        // $now = $time->format('Y-m-d H:i:s.u');
        // 認証コードの生成
        $confirmationCode = rand(100000, 999999);
        // セッションにデータを保存
        $sessionData = [
            'confirmation_code'=> $confirmationCode,
            'email' => $email,
            'now' => $now,
        ];
        Session::put('session_data', $sessionData);

        // メールでの通知処理
        $url = config('services.app.url') . '/registration/check';
        // 件名をカスタマイズ
        $subject = '[勤怠管理システム]アカウント登録用メール送付のお知らせ';

        // 本文をカスタマイズ
        $messageText = "勤怠管理システムアカウントにご登録いただき、誠にありがとうございます。
            \n本メールは、勤怠管理システムアカウント管理システムにより仮登録された
            \nメールアドレスの確認を行うために自動送信されています。
            \n\n以下に記載された「認証コード」下記URLをクリックして入力し、メールアドレスの登録を完了させてください。
            \n\n認証コード： $confirmationCode 
            \n\n $url 
            \n\n認証コードは送信より20分経過すると無効になりますので、
            \n時間内にコードを入力してメールアドレスの登録を完了させてください。
            \n\n本メールにお心あたりのない場合は、お手数ですが本メールの破棄をお願いいたします。
            \n\nまた、この内容にお心あたりのない場合やご不明な点がある場合は株式会社upstartまでお問い合わせください。
            \n本メールは送信専用のため、返信はお受けしておりませんので、ご了承ください。";
    
        // 通知メールを送信
        Mail::raw($messageText, function ($message) use ($email, $subject) {
            $message->to($email);
            $message->subject($subject);
        });
    }
}