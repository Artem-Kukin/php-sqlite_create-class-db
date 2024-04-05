<?php

namespace Tables;

interface DatabaseWrapper
{
    // вставляет новую запись в таблицу, возвращает полученный объект как массив
    public function insert(array $tableColumns, array $values, object $pdo): array;
    // редактирует строку под конкретным id, возвращает результат после изменения
    public function update(int $id, array $values, object $pdo): array;
    // поиск по id
    public function find(int $id, object $pdo): array;
    // удаление по id
    public function delete(int $id, object $pdo): bool;
};
class Order implements DatabaseWrapper
{
    public $date;
    public function __construct($date)
    {
        $this->date = $date;
    }

    public function insert(array $tableColumns, array $values, object $pdo): array
    {
        $stmt = $pdo->prepare(
            "INSERT INTO orders (date) 
                          VALUES (:date)"
        );
        $stmt->bindValue(':date', $values['date']);
        $stmt->execute();

        print_r($tableColumns);
        return $tableColumns;
    }

    public function update(int $id, array $values, object $pdo): array
    {
        $sql = "UPDATE orders SET date = '$values[date]' WHERE id =" . $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        print_r($values);
        return $values;
    }

    public function delete(int $id, object $pdo): bool
    {
        $sql = "DELETE FROM orders WHERE id= " . $id;
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
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id=:id');

        $stmt->execute([':id' => $id]);

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tasks = [
                'id' => $row['id'],
                'date' => $row['date']
            ];
        }
        print_r($tasks);
        return $tasks;
    }
}
