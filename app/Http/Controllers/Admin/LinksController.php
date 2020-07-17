<?php


namespace App\Http\Controllers\Admin;


use App\Models\Link;

class LinksController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Link::query();
        $this->setBuilder($builder);
        $this->setModel(new Link());
    }
}
