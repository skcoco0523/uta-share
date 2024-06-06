<?php

//namespace App\Helpers;

// app/Helpers/Mail_send.php
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\HtmlString;
use App\Jobs\SendMailJob;

class MailContent extends Mailable
{
    //protected $mailMessage;

    public function __construct($mailMessage)
    {
        $this->view(['html' => $mailMessage->render()])
        ->subject($mailMessage->subject);
    }

}

//メール送信関数　テンプレート内データ,送信先
if (! function_exists('mail_send')) {
    function mail_send($send_info, $mail, $tmpl){
        make_error_log("mail_send","mail=".$mail."  tmpl=".$tmpl);
        $mailMessage = get_MailMessage($send_info, $tmpl);

        if ($mailMessage) {
            $mailable = new MailContent($mailMessage);
            //dd($mailable);
            //即時実行
            //Mail::to($mail)->send($mailable);

            // SendMailJobをディスパッチしてバックグラウンドで実行
            SendMailJob::dispatch($mail,$mailable);
        }else{
            make_error_log("mail_send","not_tmpl");
        }
    }
}

//テンプレートからメッセージ取得
if (! function_exists('get_MailMessage')) {
    function get_MailMessage($send_info, $tmpl)
    {
        //app01\vendor\laravel\framework\src\Illuminate\Notifications\Messages\MailMessage.php
        switch($tmpl){
            case 'password_reset':
                $MailMessage = (new MailMessage)
                    ->markdown('emails.mail')
                    ->subject(Lang::get('パスワードリセット'))
                    ->line(Lang::get('本メールはパスワードリセットのご案内です。'))
                    ->action(Lang::get('リセットはこちらから'), url(config('app.url').route('password.reset', ['token' => $send_info->token, 'email' => $send_info->mail], false)))
                    ->line(Lang::get('このパスワード リセット リンクは :count 分後に期限切れになります。', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                    ->line(Lang::get('もしパスワード再発行をリクエストしていない場合、操作は不要です。'));

                return $MailMessage;
            break;
                    
            default:
                return null;
        }
    }
}
