<?php


namespace App\Http\Controllers\Api;


use App\Models\Category;

class CategoriesController extends ApiBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Category::query();
        $this->setBuilder($builder);
        $this->setModel(new Category());
        $this->setAppendCondition([['column' => 'level', 'value' => 1],['column' => 'show_as_menu', 'value' => 1]]);
    }

    public function index ()
    {
        $categories = Category::where(['level' => 1, 'show_as_menu' => 1])->orderBy('order')->get();
        return $this->success(['items' => $categories]);
    }
}
