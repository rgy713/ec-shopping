<?php


namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /** @var string $token */
    protected $token;

    /**
     * BaseController constructor.
     */
    public function __construct(Request $request)
    {
        $this->token = $request->bearerToken();
    }
}