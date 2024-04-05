<?php

namespace Tables;



interface DatebaseWrapper
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

class Shop implements DatebaseWrapper
{
    public $name;
    public $address;
    public function __construct($name, $address)
    {
        $this->name = $name;
        $this->address = $address;
    }
    // // вставляет новую запись в таблицу, возвращает полученный объект как массив
    public function insert(array $tableColumns, array $values, object $pdo): array
    {

        $stmt = $pdo->prepare(
            "INSERT INTO shops (name, address) 
                          VALUES (:name, :address)"
        );
        $stmt->bindValue(':name', $values['name']);
        $stmt->bindValue(':address', $values['address']);
        $stmt->execute();

        print_r($tableColumns);
        return $tableColumns;
    }


    // // редактирует строку под конкретным id, возвращает результат после изменения
    public function update(int $id, array $values, object $pdo): array
    {


        $sqlName = "UPDATE shops SET name='$values[name]' WHERE id=" . $id;
        $stmt = $pdo->prepare($sqlName);
        $stmt->execute();

        $sqlAddress = "UPDATE shops SET address='$values[address]' WHERE id=" . $id;
        $stmt = $pdo->prepare($sqlAddress);
        $stmt->execute();

        print_r($values);
        return $values;
    }

    // // поиск по id
    public function find(int $id, object $pdo): array
    {
        $stmt = $pdo->prepare('SELECT * FROM shops WHERE id=:id');

        $stmt->execute([':id' => $id]);

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tasks = [
                'id' => $row['id'],
                'name' => $row['name'],
                'address' => $row['address']

            ];
        }
        print_r($tasks);
        return $tasks;
    }


    // // удаление по id
    public function delete(int $id, object $pdo): bool
    {
        $sql = "DELETE FROM shops WHERE id= " . $id;
        $pdo->exec($sql);
        if ($pdo->query($sql) === TRUE) {
            echo "Record deleted successfully";
            return true;
        } else {
            echo "Error deleting record: " . $pdo->error;
            return false;
        }
    }
}
