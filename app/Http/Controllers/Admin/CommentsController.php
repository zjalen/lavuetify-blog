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

    public function index()
    {
        $filters = request()->query();

        $query = $this->buildQuery($this->getBuilder(), $filters, $this->getAllowSearchColumns(), $this->getAllowShowColumns());
        $total_count = $query->count();
        $query = $this->buildQuery($this->getBuilder(), $filters, $this->getAllowSearchColumns(), $this->getAllowShowColumns(), true);
        $items = $query->with('article')->get();
        return $this->success(['items' => $items, 'total_count' => $total_count]);
    }
}
