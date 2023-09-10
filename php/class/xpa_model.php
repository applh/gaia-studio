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

    static function connect()
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
        } else {
            error_log("xpa_model::connect() no db_dsn");
        }

        return $pdo;
    }

    static function read($table = null, $limit = 100, $offset = 0, $order_by = "ORDER BY id DESC", $where = "")
    {
        $table ??= "users";

        // limit and offset must be positive
        $limit = max(0, $limit);
        $offset = max(0, $offset);
        // trim where
        $where = trim($where);
        // if where is not empty, add WHERE
        if ($where) {
            $where = "WHERE $where";
        }

        $sql = "SELECT * FROM `$table` $where $order_by LIMIT $limit OFFSET $offset";

        error_log($sql);

        xpa_model::send_sql($sql);

        return xpa_response::$rows;
    }

    static function read1 ($table, $col="id", $value="")
    {
        $table ??= "users";

        $sql = "SELECT * FROM `$table` WHERE `$col` = :$col LIMIT 1";

        xpa_model::send_sql($sql, ["$col" => $value]);
        $founds = xpa_response::$rows;
        return $founds[0] ?? null;
    }

    static function send_sql($sql, $data = [])
    {
        try {
            // error_log($sql);
            // store requests
            xpa_model::$requests[] = $sql;
            // connect
            $pdo = xpa_model::connect();
            if ($pdo != null) {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($data);
                xpa_response::$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);    
            }
        } catch (PDOException $e) {
            error_log("xpa_model::send_sql() PDOException: " . $e->getMessage());
        }
        return $stmt ?? null;
    }

    static function insert($table = null, $data = [])
    {
        $table ??= "users";

        $sql = "INSERT INTO `$table` ( `";
        $sql .= implode("`, `", array_keys($data));
        $sql .= "` ) VALUES ( :";
        $sql .= implode(", :", array_keys($data));
        $sql .= " )";

        xpa_model::send_sql($sql, $data);
        // return last insert id
        return xpa_model::connect()->lastInsertId();
    }

    static function delete($table = null, $id = null)
    {
        $table ??= "users";

        $sql = "DELETE FROM `$table` WHERE id = :id";

        xpa_model::send_sql($sql, ["id" => $id]);
    }

    static function update($table = null, $id = null, $data = [])
    {
        $table ??= "users";

        $sql = "UPDATE `$table` SET ";

        // Prepare the SQL statement
        $set_values = '';
        foreach ($data as $key => $value) {
            $set_values .= "$key = :$key, ";
        }
        $set_values = rtrim($set_values, ', ');
        $sql = "UPDATE $table SET $set_values WHERE id = :id";

        xpa_model::send_sql($sql, ["id" => $id] + $data);
    }

    //#class_end
}

//#file_end