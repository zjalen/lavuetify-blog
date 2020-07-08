<?php


namespace App\Http\Controllers\Api;


use App\Models\Link;

class LinksController extends ApiBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Link::query();
        $this->setBuilder($builder);
        $this->setModel(new Link());
    }

    public function index()
    {
        $links = Link::orderBy('order')->get();
        return $this->success($links);
    }
}
