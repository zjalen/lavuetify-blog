<?php


namespace App\Http\Controllers\Admin;


use App\Models\User;

class UsersController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = User::query();
        $this->setBuilder($builder);
        $this->setModel(new User());
    }
}
