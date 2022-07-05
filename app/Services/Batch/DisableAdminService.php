<?php
/**
 * Created by PhpStorm.
 * User: rgy
 * Date: 5/22/2019
 * Time: 12:24 AM
 */

namespace App\Services\Batch;


use App\Models\Admin;
use Carbon\Carbon;

class DisableAdminService
{
    public function run() : int
    {
        $exception_count = 0;

        $admins = app(Admin::class)->where("enabled", true)->get();
        foreach ($admins as $admin){
            $max_login_date = $admin->adminLoginLogs()->where("state", true)->max("created_at");
            if(isset($max_login_date)){
                $expiration_date = $admin->adminRole->expiration_date;

                $min = Carbon::parse($max_login_date);
                $max = Carbon::now();
                $days = $min->diffInDays($max);

                if($days > $expiration_date + 1 && $expiration_date > 0){
                    try{
                        $admin->enabled = false;
                        $admin->save();
                    }
                    catch (\Exception $e){
                        $exception_count += 1;
                    }
                }
            }
        }

        return $exception_count;
    }
}