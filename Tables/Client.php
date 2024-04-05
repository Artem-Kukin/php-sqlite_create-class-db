<?php

namespace Tables;

interface DatabaseWraper
{
    // вставляет новую запись в таблицу, возвращает полученный объект как массив
    public function insert(array $tableColumns, array $values, object $pdo): array;
    // редактирует строку под конкретным id, возвращает результат после изменения
    public function update(int $id, array $values, object $pdo): array;
    // поиск по id
    public function find(int $id, object $pdo): array;
    // удаление по id
    public function delete(int $id, object $pdo): bool;
}
class Client implements DatabaseWraper
{
    public $phoneNumber;
    public $name;

    public function __construct($phoneNumber, $name)
    {
        $this->phoneNumber = $phoneNumber;
        $this->name = $name;
    }

    function insert(array $tableColumns, array $values, $pdo): array
    {
        $stmt = $pdo->prepare(
            "INSERT INTO clients (number, name) 
                          VALUES (:number, :name)"
        );
        $stmt->bindValue(':number', $values['phoneNumber']);
        $stmt->bindValue(':name', $values['clientName']);
        $stmt->execute();

        print_r($tableColumns);
        return $tableColumns;
    }

    public function update(int $id, array $values, object $pdo): array
    {

        $sqlNumber = "UPDATE clients SET number = '$values[phoneNumber]' WHERE id = " . $id;
        $stmt = $pdo->prepare($sqlNumber);
        $stmt->execute();

        $sqlName = "UPDATE clients SET name = '$values[clientName]' WHERE id = " . $id;
        $stmt = $pdo->prepare($sqlName);
        $stmt->execute();

        print_r($values);
        return $values;
    }

    public function delete(int $id, object $pdo): bool
    {
        $sql = "DELETE FROM clients WHERE id= " . $id;
        $pdo->exec($sql);
        if ($pdo->query($sql) == TRUE) {
            echo "Record deleted successfully";
            return true;
        } else {
            echo "Error deleting record: " . $pdo->error;
            return false;
        }
    }
    public function find(int $id, object $pdo): array
    {
        $stmt = $pdo->prepare('SELECT * FROM clients WHERE id=:id');

        $stmt->execute([':id' => $id]);

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tasks = [
                'id' => $row['id'],
                'number' => $row['number'],
                'name' => $row['name']
            ];
        }
        print_r($tasks);
        return $tasks;
    }
}
