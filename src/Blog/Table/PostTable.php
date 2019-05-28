<?php
namespace App\Blog\Table;


use Framework\Database\PaginatedQuery;

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
     * Pagines articles
     * @return PaginatedQuery
     */
    public function findPaginated(): PaginatedQuery
    {
        return new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM posts',
            'SELECT COUNT(id) FROM posts'
        );
    }


    /**
     *Get an article from his id
     * @param int $id
     * @return \stdClass
     */
    public function find(int $id): \stdClass
    {
        $query = $this->pdo
            ->prepare('SELECT * FROM posts WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }
}
