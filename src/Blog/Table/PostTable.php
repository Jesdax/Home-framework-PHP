<?php
namespace App\Blog\Table;


use App\Blog\Entity\Post;
use Framework\Database\PaginatedQuery;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;

class PostTable
{


    /**
     * @var \PDO
     */
    private $pdo;


    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param $perPage
     * @param $currentPage
     * @return Pagerfanta
     * Pagines articles
     */
    public function findPaginated($perPage, $currentPage)
    {
        $query = new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM posts ORDER BY created_at DESC',
            'SELECT COUNT(id) FROM posts',
            Post::class
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }


    /**
     * Get an article from his id
     * @param $id
     * @return Post
     */
    public function find($id): Post
    {
        $query = $this->pdo
            ->prepare('SELECT * FROM posts WHERE id = ?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        return $query->fetch();
    }
}
