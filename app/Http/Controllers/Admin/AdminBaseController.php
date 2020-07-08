<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AbstractBaseController;
use App\Http\Controllers\Traits\ApiResponse;

abstract class AdminBaseController extends AbstractBaseController
{
    use ApiResponse;

    public function __construct()
    {
        parent::__construct();
    }
}
