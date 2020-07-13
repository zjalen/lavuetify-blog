<?php


namespace App\Http\Controllers\Admin;


use App\Models\Category;

class CategoriesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Category::query();
        $this->setBuilder($builder);
        $this->setModel(new Category());
    }
}
