<?php


namespace App\Http\Controllers\Admin;


use App\Models\Topic;

class TopicsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Topic::query();
        $this->setBuilder($builder);
        $this->setModel(new Topic());
    }
}
