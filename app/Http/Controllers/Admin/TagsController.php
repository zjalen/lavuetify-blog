<?php


namespace App\Http\Controllers\Admin;


use App\Models\Tag;

class TagsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Tag::query();
        $this->setBuilder($builder);
        $this->setModel(new Tag());
    }

    public function index()
    {
        return $this->success(['items' => Tag::all()->pluck('name')]);
    }
}
