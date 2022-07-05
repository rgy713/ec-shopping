<?php


namespace App\Common;


use Illuminate\Notifications\Notifiable;

/**
 * メールアドレスのみで通知を送信するために噛ませるクラス
 * パスワード再設定などの通知送信時に利用する想定。
 * Class TmpNotifiable
 * @package App\Common
 * @author k.yamamoto@balocco.info
 */
class TmpNotifiable
{
    use Notifiable;

    /**
     * TmpNotifiable constructor.
     */
    public function __construct($email)
    {
        $this->email=$email;
    }
}