<?php

namespace App\Mail;

use App\Common\SystemSetting;
use App\Models\MailLayout;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

/**
 * Class PreviewTemplateMail
 * @package App\Mail
 */
class PreviewTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var
     */
    protected $mailLayout;

    /**
     * @var
     */
    protected $mailSubject;

    protected $mailBody_file_path;

    /**
     * @var SystemSetting
     */
    protected $systemSetting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailLayoutId, $subject, $mailBodyFilePath)
    {
        $model = app(MailLayout::class);
        $this->systemSetting = app(SystemSetting::class);
        $this->mailLayout = $model->find($mailLayoutId);
        $this->mailSubject = $subject;
        $this->mailBody_file_path = $mailBodyFilePath;
    }

    /**
     * @return PreviewTemplateMail
     * @throws \Throwable
     */
    public function build()
    {
        return $this->text('mail.template_mail')
            ->with('headerFilePath', $this->mailLayout->header_file_path)
            ->with('bodyFilePath', $this->mailBody_file_path)
            ->with('footerFilePath', $this->mailLayout->footer_file_path)
            ->from($this->systemSetting->systemSenderMailAddress(), $this->systemSetting->systemSenderSignature())
            ->subject($this->mailSubject);
    }
}
