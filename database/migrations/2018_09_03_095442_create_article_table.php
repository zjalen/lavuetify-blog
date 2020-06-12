<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200)->comment('标题');
            $table->integer('author_id')->comment('作者id');
            $table->string('description', 200)->comment('简述');
            $table->mediumText('content_md')->comment('markdown内容');
            $table->mediumText('content_html')->comment('html内容');
            $table->string('cover')->comment('封面图片');
            $table->tinyInteger('is_top')->default(0)->comment('置顶');
            $table->integer('pageviews')->comment('点击量');
            $table->integer('category_id')->comment('类目id');
            $table->tinyInteger('is_draft')->default(0)->comment('是否是草稿');
            $table->integer('topic_id')->comment('主题id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article');
    }
}
