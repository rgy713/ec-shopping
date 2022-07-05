<?php


namespace App\Services;

use App\Common\SystemMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Class FlashToastrMessageService
 * @package App\Services
 * @author k.yamamoto@balocco.info
 */
class FlashToastrMessageService
{
    CONST SESSION_KEY="flash_toastr_message";

    /**
     * toastrメッセージを追加する
     * @param string $level info,warning,error,success のいずれか
     * @param string $message 表示するメッセージ内容
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|Collection|mixed
     * @author k.yamamoto@balocco.info
     */
    public function add($level,$message)
    {
        //メッセージ初期化
        $systemMessage = new SystemMessage();
        $systemMessage->message = $message;
        $systemMessage->level = $level;

        $toastrMessage = $this->get();
        if (!empty($systemMessage->message)) {
            $toastrMessage->push($systemMessage);
        }
        session()->flash(self::SESSION_KEY, $toastrMessage);

        return $toastrMessage;
    }

    /**
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|Collection|mixed
     * @author k.yamamoto@balocco.info
     */
    public function get(){
        $toastrMessage = session(self::SESSION_KEY);
        if ($toastrMessage instanceof Collection) {
            return $toastrMessage;
        }else{
            return collect();
        }
    }

    /**
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|Collection|mixed
     * @author k.yamamoto@balocco.info
     */
    public function pull(){
        //型を保証するためにsession()->pull() ではなく$this->get()にしている。
        $toastrMessage=$this->get();

        //削除
        session()->forget(self::SESSION_KEY);
        return $toastrMessage;
    }

}