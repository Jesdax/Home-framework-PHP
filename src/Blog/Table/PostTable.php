<?php
namespace App\Blog\Table;


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
     * @param int $perPage
     * @return Pagerfanta
     * Pagines articles
     */
    public function findPaginated($perPage, int $currentPage)
    {
        $query = new PaginatedQuery(
            $this->pdo,
            'SELECT * FROM posts',
            'SELECT COUNT(id) FROM posts'
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
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
