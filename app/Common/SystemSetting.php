<?php


namespace App\Common;
use App\Models\SystemSetting as SystemSettingModel;

/**
 * Class SystemSetting
 * @package App\Common
 * @author k.yamamoto@balocco.info
 */
class SystemSetting
{
    /**
     * @var SystemSettingModel
     */
    protected $model;

    /**
     * SystemSetting constructor.
     */
    public function __construct(SystemSettingModel $systemSetting)
    {
        $this->model = $systemSetting->all()->first();
    }

    /**
     * システム管理者のメールアドレスを返す
     * @return mixed|string
     * @author k.yamamoto@balocco.info
     */
    public function systemAdminMailAddress(){
        return $this->model->system_admin_mail_address;
    }

    /**
     * 運営管理者のメールアドレスを返す
     * @return mixed|string
     * @author k.yamamoto@balocco.info
     */
    public function operationAdminMailAddress(){
        return $this->model->operation_admin_mail_address;
    }

    /**
     * システムメールの送信者アドレスを返す
     * @return mixed|string
     * @author k.yamamoto@balocco.info
     *
     */
    public function systemSenderMailAddress(){
        return $this->model->system_sender_mail_address;
    }

    /**
     * システムメールの送信者署名を返す
     * @return mixed|string
     * @author k.yamamoto@balocco.info
     */
    public function systemSenderSignature(){
        return $this->model->system_sender_signature;
    }


}