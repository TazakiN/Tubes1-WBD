<?php

namespace app\repositories;

use PDO;
use app\database\DatabaseConnection;

// Using PHP Data Objects Extension


abstract class BaseRepository
{
    protected static $instance;
    // Holds instance of PDO connection
    protected $pdo;
    // Database table
    protected $tableName = '';

    protected function __construct()
    {
        $this->pdo = DatabaseConnection::getInstance()->getPDO();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM $this->tableName";
        return $this->pdo->query($sql);
    }

    // Where (key: column name, value(0: value, 1: data type, 2: type of comparison either LIKE or =)) 

    public function countRow($where = [])
    {
        $sql = "SELECT COUNT(*) FROM $this->tableName";
    
        $conditions = [];
    
        // Mapping where
        if (count($where) > 0) {
            foreach ($where as $key => $value) {
                if (isset($value[2]) && $value[2] == 'IN' && isset($value[3]) && is_array($value[3])) {
                    $placeholders = array();
                    foreach ($value[3] as $k => $v) {
                        $placeholders[] = ":{$key}_{$k}";
                    }
                    $conditions[] = "$key IN (" . implode(", ", $placeholders) . ")";
                } else {
                    $columns = [$key];
                    if (isset($value[3]) && is_array($value[3]) && $value[2] != 'IN') {
                        $columns = [$key] + $value[3];
                    }
                    $subConditions = [];
                    foreach ($columns as $column) {
                        if (isset($value[2]) && $value[2] == 'LIKE') {
                            $subConditions[] = "LOWER($column) LIKE LOWER(:$column)";
                        } else {
                            $subConditions[] = "$column = :$column";
                        }
                    }
                    $conditions[] = "(" . implode(" OR ", $subConditions) . ")";
                }
            }
    
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
    
        // Hydrating statement, for sanitizing
        $stmt = $this->pdo->prepare($sql);
        
        // Bind values
        foreach ($where as $key => $value) {
            if (isset($value[2]) && $value[2] == 'IN' && isset($value[3]) && is_array($value[3])) {
                // Bind values for IN clause
                foreach ($value[3] as $k => $v) {
                    $stmt->bindValue(":{$key}_{$k}", $v, $value[1]);
                }
            } else {
                $columns = [$key];
                if (isset($value[3]) && is_array($value[3]) && $value[2] != 'IN') {
                    $columns = [$key] + $value[3];
                }
                foreach ($columns as $column) {
                    if (isset($value[2]) && $value[2] == 'LIKE') {
                        $stmt->bindValue(":$column", "%$value[0]%", $value[1]);
                    } else {
                        $stmt->bindValue(":$column", $value[0], $value[1]);
                    }
                }
            }
        }
    
        $stmt->execute();
    
        return $stmt->fetchColumn();
    }

    public function findAll(
        $where = [],
        $order = null,
        $pageNo = null,
        $pageSize = null,
        $sort = "asc",
    ) {
        $sql = "SELECT * FROM $this->tableName";
    
        $conditions = [];
    
        // Mapping where
        if (count($where) > 0) {
            foreach ($where as $key => $value) {
                if (isset($value[2]) && $value[2] == 'IN' && isset($value[3]) && is_array($value[3])) {
                    // Handle IN clause
                    $placeholders = array();
                    foreach ($value[3] as $k => $v) {
                        $placeholders[] = ":{$key}_{$k}";
                    }
                    $conditions[] = "$key IN (" . implode(", ", $placeholders) . ")";
                } else {
                    $columns = [$key];
                    if (isset($value[3]) && is_array($value[3])) {
                        $columns = [$key] + $value[3];
                    }
                    $subConditions = [];
                    foreach ($columns as $column) {
                        if (isset($value[2]) && $value[2] == 'LIKE') {
                            $subConditions[] = "LOWER($column) LIKE LOWER(:$column)";
                        } else {
                            $subConditions[] = "$column = :$column";
                        }
                    }
                    $conditions[] = "(" . implode(" OR ", $subConditions) . ")";
                }
            }
    
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
    
        if ($order) {
            $sql .= " ORDER BY $order";
        }
    
        if ($sort == "desc") {
            $sql .= " DESC";
        }
    
        if ($pageSize && $pageNo) {
            $sql .= " LIMIT :pageSize";
            $sql .= " OFFSET :pageNo";
        }
    
        // Hydrating statement, for sanitizing
        $stmt = $this->pdo->prepare($sql);
    
        foreach ($where as $key => $value) {
            if (isset($value[2]) && $value[2] == 'IN' && isset($value[3]) && is_array($value[3])) {
                // Bind values for IN clause
                foreach ($value[3] as $k => $v) {
                    $stmt->bindValue(":{$key}_{$k}", $v, $value[1]);
                }
            } else {
                $columns = [$key];
                if (isset($value[3]) && is_array($value[3])) {
                    $columns = [$key] + $value[3];
                }
                foreach ($columns as $column) {
                    if (isset($value[2]) && $value[2] == 'LIKE') {
                        $stmt->bindValue(":$column", "%$value[0]%", $value[1]);
                    } else {
                        $stmt->bindValue(":$column", $value[0], $value[1]);
                    }
                }
            }
        }
    
        if (isset($pageSize) && isset($pageNo)) {
            $offset = $pageSize * ($pageNo - 1);
            $stmt->bindValue(":pageSize", $pageSize, PDO::PARAM_INT);
            $stmt->bindValue(":pageNo", $offset, PDO::PARAM_INT);
        }
    
        $stmt->execute();
    
        return $stmt->fetchAll();
    }

    public function findOne($where)
    {
        $sql = "SELECT * FROM $this->tableName";

        if (count($where) > 0) {
            $sql .= " WHERE ";
            $sql .= implode(" AND ", array_map(function ($key, $value) {
                if (isset($value[2]) and $value[2] == 'LIKE') {
                    return "$key LIKE :$key";
                }

                return "$key = :$key";
            }, array_keys($where), array_values($where)));
        }

        // Hydrating statement, for sanitizing

        $stmt = $this->pdo->prepare($sql);

        foreach ($where as $key => $value) {
            if (isset($value[2]) and $value[2] == 'LIKE') {
                $stmt->bindValue(":$key", "%$value[0]%", $value[1]);
            } else {
                $stmt->bindValue(":$key", $value[0], $value[1]);
            }
        }

        $stmt->execute();

        return $stmt->fetch();
    }

    public function insert($model, $arrParams)
    {
        $sql = "INSERT INTO $this->tableName (";
        $sql .= implode(", ", array_keys($arrParams));
        $sql .= ") VALUES (";
        $sql .= implode(", ", array_map(function ($key, $value) {
            return ":$key";
        }, array_keys($arrParams), array_values($arrParams)));
        $sql .= ")";

        $stmt = $this->pdo->prepare($sql);
        // Hydrating and sanitizing
        foreach ($arrParams as $key => $value) {
            if (is_null($value)) {
                $stmt->bindValue(":$key", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":$key", $model->get($key));
            }
        }

        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function update($model, $arrParams)
    {
        $sql = "UPDATE $this->tableName SET ";
        $sql .= implode(", ", array_map(function ($key, $value) {
            return "$key = :$key";
        }, array_keys($arrParams), array_values($arrParams)));
        $primaryKey = $model->get('_primary_key');

        if (is_array($primaryKey)) {
            $sql .= " WHERE ";
            $sql .= implode(" AND ", array_map(function ($key, $value) {
                return "$value = :$value"; // Menggunakan nama parameter yang sesuai
            }, array_keys($primaryKey), array_values($primaryKey)));

            $stmt = $this->pdo->prepare($sql);

            foreach ($primaryKey as $key => $value) {
                $stmt->bindValue(":$value", $model->get($value), PDO::PARAM_STR); // Menggunakan nama parameter yang sesuai
            }
        } else {
            $sql .= " WHERE ";
            $sql .= "$primaryKey = :primaryKey";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":primaryKey", $model->get($primaryKey), PDO::PARAM_INT);
        }

        // Hydrating and sanitizing
        foreach ($arrParams as $key => $value) {
            $stmt->bindValue(":$key", $model->get($key), $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete($model)
    {
        $sql = "DELETE FROM $this->tableName WHERE ";
        $primaryKey = $model->get('_primary_key');
        if (is_array($primaryKey)) {
            $sql .= implode(" AND ", array_map(function ($key, $value) {
                return "$value = :$value";
            }, array_keys($primaryKey), array_values($primaryKey)));

            $stmt = $this->pdo->prepare($sql);

            foreach ($primaryKey as $key => $value) {
                $stmt->bindValue(":$value", $model->get($value), PDO::PARAM_STR);
            }
        } else {
            $sql .= "$primaryKey = :primaryKey";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":primaryKey", $model->get($primaryKey), PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getNLastRow($N)
    {
        $sql = "SELECT COUNT(*) FROM $this->tableName";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count < $N) {
            $N = $count;
        }

        $offset = $count - $N;
        $sql = "SELECT * FROM $this->tableName LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":limit", $N, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getDistinctValues($columnName)
    {
        $sql = "SELECT DISTINCT $columnName FROM $this->tableName";
        $stmt = $this->pdo->query($sql);

        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            return [];
        }
    }
}
