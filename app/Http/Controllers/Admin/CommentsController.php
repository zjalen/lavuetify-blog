<?php


namespace App\Http\Controllers\Admin;


use App\Models\Comment;

class CommentsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Comment::query();
        $this->setBuilder($builder);
        $this->setModel(new Comment());
    }
}
