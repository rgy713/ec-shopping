<?php

namespace App\Models;

use App\Models\Masters\AdminRole;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Interfaces\KeyValueListModelInterface;
use App\Models\Traits\DefaultKeyValueListTrait;

class Admin extends Authenticatable implements KeyValueListModelInterface
{
//    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'account',
        'password',
        'api_token',
        'option_left_menu',
        'option_right_menu'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    use DefaultKeyValueListTrait;

    static public function getColumnNameOfListOrderBy()
    {
        return 'id';
    }

    public function adminLoginLogs()
    {
        return $this->hasMany(AdminLoginLog::class,'admin_id','id');
    }

    public function adminRole()
    {
        return $this->hasOne(AdminRole::class,'id','admin_role_id');
    }
}
