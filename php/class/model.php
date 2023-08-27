<?php

class model
{
    static function connect ()
    {
        static $pdo = null;

        if ($pdo !== null)
            return $pdo;

        // popular default values on localhost / MacOS
        $db_user = "root";
        $db_password = "root";
        // $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

        // connect to sqlite my-db-users.sqlite
        $db_name = __DIR__ . "/../../my-data/db-users.sqlite";
        $db_name = realpath($db_name);
        // var_dump($db_name);
        $dsn = "sqlite:$db_name";
        $pdo ??= new PDO($dsn, $db_user, $db_password);

        return $pdo;
    }

    static function read($table = null, $limit = 100, $offset = 0)
    {
        $table ??= "users";
        $order_by = "id DESC";

        // limit and offset must be positive
        $limit = max(0, $limit);
        $offset = max(0, $offset);

        $sql = "SELECT * FROM `$table` ORDER BY $order_by LIMIT $limit OFFSET $offset";
        model::send_sql($sql);

        return response::$rows;
    }

    static function send_sql ($sql)
    {
        // error_log($sql);
        $pdo = model::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        response::$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
