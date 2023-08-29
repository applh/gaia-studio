<?php
/**
 * xpa_model
 * 
 * created: 2023-08-29 11:36:36
 * author: applh/gaia
 * license: MIT
 */

/**
 * xpa_model
 */
class xpa_model
{
    //#class_start

    static $requests = [];

    static function connect ()
    {
        static $pdo = null;

        if ($pdo !== null)
            return $pdo;

        // retrive DSN from config
        $db_dsn = xpa_os::kv("db_dsn") ?? "";
        $db_user = xpa_os::kv("db_user") ?? "";
        $db_password = xpa_os::kv("db_password") ?? "";

        if ($db_dsn) {
            // error_log("xpa_model::connect() db_dsn: $db_dsn");

            $pdo ??= new PDO($db_dsn, $db_user, $db_password);
        }
        else {
            error_log("xpa_model::connect() no db_dsn");  
        }

        return $pdo;
    }

    static function read($table = null, $limit = 100, $offset = 0, $order_by = "ORDER BY id DESC")
    {
        $table ??= "users";

        // limit and offset must be positive
        $limit = max(0, $limit);
        $offset = max(0, $offset);

        $sql = "SELECT * FROM `$table` $order_by LIMIT $limit OFFSET $offset";
        xpa_model::send_sql($sql);

        return response::$rows;
    }

    static function send_sql ($sql)
    {
        try {
            error_log($sql);
            // store requests
            xpa_model::$requests[] = $sql;
            // connect
            $pdo = xpa_model::connect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            response::$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            error_log("xpa_model::send_sql() PDOException: " . $e->getMessage());
        }
        return $stmt ?? null;
    }
    //#class_end
}

//#file_end