<?php
namespace Framework\Database;



use Pagerfanta\Pagerfanta;

class Table
{


    /**
     * @var \PDO
     */
    protected $pdo;


    /**
     * Name of the table on BDD
     * @var string
     */
    protected $table;


    /**
     * Entity use
     * @var string|null
     */
    protected $entity;

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }


    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Pagine elements
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     *
     */
    public function findPaginated($perPage, $currentPage): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->pdo,
            $this->paginationQuery(),
            "SELECT COUNT(id) FROM {$this->table}",
            $this->entity
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    protected function paginationQuery()
    {
        return 'SELECT * FROM ' . $this->table;
    }


    /**
     * Retrieves a key value list of our records
     * @return array
     */
    public function findList(): array
    {
        $results = $this->pdo->query("SELECT id, name FROM {$this->table}")
            ->fetchAll(\PDO::FETCH_NUM);
        $list = [];
        foreach ($results as $result) {
            $list[$result[0]] = $result[1];
        }
        return $list;
    }

    public function findAll(): array
    {
        $statement = $this->pdo->query("SELECT * FROM {$this->table}");
        if ($this->entity) {
            $statement->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        } else {
            $statement->setFetchMode(\PDO::FETCH_OBJ);
        }
        return $statement->fetchAll();
    }

    /**
     * Get an element from his id
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $query = $this->pdo
            ->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $query->execute([$id]);
        if ($this->entity) {
            $query->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        }

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
        $statement = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id");
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
        $values = join(', ', array_map(function ($field) {
            return ':' . $field;
        }, $fields));
        $fields = join(', ', $fields);
        $statement = $this->pdo->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");
        return $statement->execute($params);
    }

    /**
     * Delete a registration
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $statement->execute([$id]);
    }

    /**
     * Tchek if registration exists
     * @param $id
     * @return bool
     */
    public function exists($id)
    {
        $statement = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE id = ?");
        $statement->execute([$id]);
        return $statement->fetchColumn() !== false;
    }

    private function buildFieldQuery(array $params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }
}
