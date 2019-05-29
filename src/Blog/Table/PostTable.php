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
     * @return Post|null
     */
    public function find($id): ?Post
    {
        $query = $this->pdo
            ->prepare('SELECT * FROM posts WHERE id = ?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        return $query->fetch() ?: null;
    }

    /**
     * Updates a record at the database level
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update($id, array $params): bool
    {

        $fieldQuery = $this->buildFieldQuery($params);
        $params["id"] = $id;
        $statement = $this->pdo->prepare("UPDATE posts SET $fieldQuery WHERE id = :id");
        return $statement->execute($params);
    }


    /**
     * Create a registration
     * @param array $params
     * @return bool
     */
    public function insert(array $params): bool
    {
        $fields = array_keys($params);
        $values = array_map(function ($field) {
            return ':' . $field;
        }, $fields);
        $statement = $this->pdo->prepare(
            "INSERT INTO posts (" . join(',', $fields) . ") VALUES (" . join(',', $values) . ")"
        );
        return $statement->execute($params);
    }

    /**
     * Delete a registration
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE id = ?');
        return $statement->execute([$id]);
    }

    private function buildFieldQuery(array $params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }
}
