<?php


namespace App\Http\ViewComposers\Admin;

use App\Services\SystemInfoService;
use Illuminate\View\View;

/**
 * Class SystemInfoComposer
 * 管理画面右側メニュー内に表示するシステム情報を生成
 * @package App\Http\ViewComposers\Admin
 * @author k.yamamoto@balocco.info
 */
class SystemInfoComposer
{

    /** @var SystemInfoService $systemInfo */
    protected $systemInfo;

    /**
     * SystemInfoComposer constructor.
     * @param SystemInfoService $systemInfo
     */
    public function __construct(SystemInfoService $systemInfo)
    {
        $this->systemInfo = $systemInfo;
    }

    public function compose(View $view)
    {
        $this->systemInfo->phpVersion();
        $this->systemInfo->phpInfo();
        $this->systemInfo->phpLogoPath();
        $this->systemInfo->postgresVersion();

        $systemInfo = [
            "PHP" => [
                "version" => $this->systemInfo->phpVersion(),
                "info" => "Result of php_uname()".$this->systemInfo->phpInfo(),
                "logo" => $this->systemInfo->phpLogoPath()
            ],
            "postgresql" => [
                "version" => $this->systemInfo->postgresVersion(),
                "info" => "Result of SELECT version()".$this->systemInfo->postgresInfo(),
                "logo" => $this->systemInfo->postgresLogoPath()
            ],
            "Laravel" => [
                "version" => $this->systemInfo->laravelVersion(),
                "info" => $this->systemInfo->laravelInfo(),
                "logo" => $this->systemInfo->laravelLogoPAth()
            ]
        ];

        $view->with([
            'systemInfo' => $systemInfo
        ]);

    }
}