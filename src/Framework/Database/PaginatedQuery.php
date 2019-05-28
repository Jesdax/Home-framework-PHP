<?php
namespace Framework\Database;



use Pagerfanta\Adapter\AdapterInterface;

class PaginatedQuery implements AdapterInterface
{

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $countQuery;


    /**
     * PaginatedQuery constructor.
     * @param \PDO $pdo
     * @param string $query Query to retrieve x results
     * @param string $countQuery Query to count the total number of results
     */
    public function __construct(\PDO $pdo, $query, $countQuery)
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
    }

    /**
     * @return false|int|\PDOStatement
     */
    public function getNbResults()
    {
        return $this->pdo->query($this->countQuery)->fetchColumn();
    }

    /**
     * @param int $offset
     * @param int $length
     * @return array|\Traversable
     */
    public function getSlice($offset, $length)
    {
        $statement = $this->pdo->prepare($this->query . ' LIMIT :offset, :length');
        $statement->bindParam('offset', $offset, \PDO::PARAM_INT);
        $statement->bindParam('length', $length, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
