<?php

require_once('autoload.php');

use Tables\Shop;
use Tables\Order;
use Tables\Client;

$pdo = new PDO('sqlite:database.db');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $shopTable = $pdo->exec(
//     'CREATE TABLE IF NOT EXISTS shops (
//         id INTEGER PRIMARY KEY AUTOINCREMENT, 
//         name TEXT, 
//         address TEXT 
//       )'
// );

// $orderTable = $pdo->exec(
//     'CREATE TABLE IF NOT EXISTS orders (
//             id INTEGER PRIMARY KEY AUTOINCREMENT,
//             date TEXT
//         )'
// );

// $clientTable = $pdo->exec(
//     'CREATE TABLE IF NOT EXISTS clients (
//                 id INTEGER PRIMARY KEY AUTOINCREMENT, 
//                 number TEXT, 
//                 name TEXT
//               )'
// );

function smartTableEdit($pdo)
{
    echo '
    1. Магазины
    2. Заказы
    3. Клиенты' . PHP_EOL;
    $start = readline('Выберите таблицу: ');

    if ($start == 1) {
        echo "\033[32m ТАБЛИЦА: МАГАЗИНЫ \033[0m" . PHP_EOL;

        echo '
        1. Внести новый магазин в таблицу
        2. Внести правку в магазин
        3. Удалить магазин из таблицы' . PHP_EOL;
        $init = readline('Выберите операцию: ');

        if ($init == 1) {

            $shopName = readline('Название магазина: ');
            $shopName = mb_convert_case($shopName, MB_CASE_TITLE);
            $values['name'] = $shopName;
            $tableColumns['name'] = $values['name'];

            $shopAddress = readline('Адрес магазина: ');
            $shopAddress = mb_convert_case($shopAddress, MB_CASE_TITLE);
            $values['address'] = $shopAddress;
            $tableColumns['address'] = $values['address'];

            $shop = new Shop($shopName, $shopAddress);
            $shop->insert($tableColumns, $values, $pdo);
        } elseif ($init == 2) {

            $shopId = readline('Для изменений потребуется ID магазина: ');

            $shopName = readline('Изменить название магазина: ');
            $shopName = mb_convert_case($shopName, MB_CASE_TITLE);
            $values['name'] = $shopName;

            $shopAddress = readline('Изменить адрес магазина: ');
            $shopAddress = mb_convert_case($shopAddress, MB_CASE_TITLE);
            $values['address'] = $shopAddress;

            $shop = new Shop($shopName, $shopAddress);
            $shop->update($shopId, $values, $pdo);
        } elseif ($init == 3) {

            $shopId = readline('Для удаления потребуется ID магазина: ');
            $shop = new Shop(null, null);
            $shop->delete($shopId, $pdo);
        } else {

            echo "\033[31m ТАКОЙ ОПЕРАЦИИ НЕ СУЩЕСТВУЕТ \033[0m";
        }
    } elseif ($start == 2) {

        echo "\033[32m ТАБЛИЦА: ЗАКАЗЫ \033[0m";

        echo '
        1. Внести новый заказ в таблицу
        2. Внести правку в заказ
        3. Удалить заказ из таблицы' . PHP_EOL;

        $init = readline('Выберите операцию: ');

        if ($init == 1) {

            $orderDate = readline('Укажите дату желаемой доставки ( "чч-мм-гггг" ): ');
            $values['date'] = $orderDate;
            $tableColumns['date'] = $values['date'];

            $order = new Order($orderDate);
            $order->insert($tableColumns, $values, $pdo);
        } elseif ($init == 2) {

            $orderId = readline('Для изменений потребуется ID заказа: ');

            $orderDate = readline('Изменить дату заказа: ');
            $values['date'] = $orderDate;

            $order = new Order($orderDate);
            $order->update($orderId, $values, $pdo);
        } elseif ($init == 3) {

            $orderId = readline('Для удаления потребуется ID заказа: ');
            $order = new Order(null);
            $order->delete($orderId, $pdo);
        } else {

            echo "\033[31m ТАКОЙ ОПЕРАЦИИ НЕ СУЩЕСТВУЕТ \033[0m";
        }
    } elseif ($start == 3) {

        echo "\033[32m ТАБЛИЦА: КЛИЕНТЫ \033[0m";

        echo '
        1. Внести нового клиента в таблицу
        2. Внести правку клиента
        3. Удалить клиента из таблицы' . PHP_EOL;

        $init = readline('Выберите операцию: ');

        if ($init == 1) {

            $clientNumber = readline('Номер телефона: ');
            $values['phoneNumber'] = $clientNumber;
            $tableColumns['phoneNumber'] = $values['phoneNumber'];

            $clientName = readline('Имя клиента: ');
            $clientName = mb_convert_case($clientName, MB_CASE_TITLE);
            $values['clientName'] = $clientName;
            $tableColumns['clientName'] = $values['clientName'];

            $client = new Client($clientNumber, $clientName);
            $client->insert($tableColumns, $values, $pdo);
        } elseif ($init == 2) {

            $clientId = readline('Для изменений потребуется ID клиента: ');

            $clientNumber = readline('Изменить номер телефона клиента: ');
            $values['phoneNumber'] = $clientNumber;

            $clientName = readline('Изменить имя клиента: ');
            $clientName = mb_convert_case($clientName, MB_CASE_TITLE);
            $values['clientName'] = $clientName;

            $client = new Client($clientNumber, $clientName);
            $client->update($clientId, $values, $pdo);
        } elseif ($init == 3) {

            $clientId = readline('Для удаления потребуется ID клиента: ');
            $client = new Client(null, null);
            $client->delete($clientId, $pdo);
        } else {

            echo "\033[31m ТАКОЙ ОПЕРАЦИИ НЕ СУЩЕСТВУЕТ \033[0m";
        }
    } else {

        echo "\033[31m ТАКОЙ ТАБЛИЦЫ НЕ СУЩЕСТВУЕТ \033[0m";
    }
}

function viewTable($pdo)
{
    echo '
    1. Магазины
    2. Заказы
    3. Клиенты' . PHP_EOL;

    $start = readline('Выберите таблицу: ');

    if ($start == 1) {

        echo "\033[32m ТАБЛИЦА: МАГАЗИНЫ \033[0m";
        echo '
        1. Просмотреть все магазины
        2. Посмотреть магазин по ID' . PHP_EOL;

        $init = readline('Выберите операцию: ');

        if ($init == 1) {

            $shops = $pdo->query("SELECT * FROM shops");

            foreach ($shops->fetchAll(PDO::FETCH_ASSOC) as $shop) {
                print_r($shop);
            }
        } elseif ($init == 2) {
            $shopID = readline('Введите ID магазина: ');
            $shop = new Shop(null, null);
            $shop->find($shopID, $pdo);
        } else {
            echo "\033[31m ТАКОЙ ОПЕРАЦИИ НЕ СУЩЕСТВУЕТ \033[0m";
        }
    } elseif ($start == 2) {

        echo "\033[32m ТАБЛИЦА: ЗАКАЗЫ \033[0m";
        echo '
        1. Просмотреть все заказы
        2. Посмотреть заказ по ID' . PHP_EOL;

        $init = readline('Выберите операцию: ');
        if ($init == 1) {
            $orders = $pdo->query("SELECT * FROM orders");

            foreach ($orders->fetchAll(PDO::FETCH_ASSOC) as $order) {
                print_r($order);
            }
        } elseif ($init == 2) {
            $orderID = readline('Введите ID заказа: ');
            $order = new Order(null);
            $order->find($orderID, $pdo);
        } else {
            echo "\033[31m ТАКОЙ ОПЕРАЦИИ НЕ СУЩЕСТВУЕТ \033[0m";
        }
    } elseif ($start == 3) {

        echo "\033[32m ТАБЛИЦА: КЛИЕНТЫ \033[0m";
        echo '
    1. Просмотреть всех клиентов
    2. Посмотреть клиента по ID' . PHP_EOL;

        $init = readline('Выберите операцию: ');
        if ($init == 1) {

            $clients = $pdo->query("SELECT * FROM clients");

            foreach ($clients->fetchAll(PDO::FETCH_ASSOC) as $client) {
                print_r($client);
            }
        } elseif ($init == 2) {
            $clientID = readline('Введите ID клиента: ');
            $client = new Client(null, null);
            $client->find($clientID, $pdo);
        } else {
            echo "\033[31m ТАКОЙ ОПЕРАЦИИ НЕ СУЩЕСТВУЕТ \033[0m";
        }
    } else {
        echo "\033[31m ТАКОЙ ТАБЛИЦЫ НЕ СУЩЕСТВУЕТ \033[0m";
    }
}

do {
    echo '
    1. Редактировать таблицы
    2. Просмотр таблицы
    Выберите операцию: ';

    $operationNumber = trim(fgets(STDIN));

    switch ($operationNumber) {

        case 1:
            smartTableEdit($pdo);
            break;

        case 2:
            viewTable($pdo);
            break;
    }
} while ($operationNumber > 0);

// echo "\033[31m красный \033[0m";
// echo "\033[32m зелёный \033[0m";