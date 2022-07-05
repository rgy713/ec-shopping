<?php

namespace App\Http\ViewComposers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthComposer
{

    public function compose(View $view)
    {
        $view->with([
            'admin' => Auth::user()
        ]);
    }


}