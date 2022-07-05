<?php


namespace App\Http\ViewComposers\Admin;

use App\Services\SystemLogService;
use Carbon\Carbon;
use Illuminate\View\View;

class SystemLogComposer
{

    /**
     * @var SystemLogService
     */
    protected $service;

    /**
     * SystemLogComposer constructor.
     */
    public function __construct(SystemLogService $service)
    {
        $this->service = $service;
    }


    public function compose(View $view)
    {
        $view->with([
            'systemLogsToday' => $this->service->getToday(),
            'systemLogsYesterday' => $this->service->getYesterday()
        ]);

    }
}