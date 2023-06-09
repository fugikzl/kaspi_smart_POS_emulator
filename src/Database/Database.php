<?php

namespace App\Database;

use PDO;

class Database
{
    private ?PDO $pdo = null;

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        if($this->pdo === null){
            $this->pdo = new PDO("sqlite:".__DIR__."/../../db/database.db");
        }

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("PRAGMA foreign_keys = ON;");
        return $this->pdo;
    }

    public function insert(string $table, array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':'.implode(', :', array_keys($data));

        $stmt = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, string $condition)
    {
        $fields = '';
        foreach($data as $key => $value){
            $fields .= "$key=:$key,";
        }
        $fields = rtrim($fields, ',');

        $stmt = $this->pdo->prepare("UPDATE $table SET $fields WHERE $condition");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
    }

    public function delete(string $table, string $condition)
    {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE $condition");
        $stmt->execute();
    }

    public function select(string $table, string $condition = null)
    {
        $query = "SELECT * FROM $table";
        if($condition !== null){
            $query .= " WHERE $condition";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
