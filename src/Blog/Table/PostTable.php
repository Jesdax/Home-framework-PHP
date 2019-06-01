<?php
namespace App\Blog\Table;


use App\Blog\Entity\Post;
use Framework\Database\Table;

class PostTable extends Table
{


    protected $entity = Post::class;


    protected $table = 'posts';


    protected function paginationQuery()
    {
        return parent::paginationQuery() . " ORDER BY created_at DESC";
    }
}
