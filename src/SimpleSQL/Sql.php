<?php

namespace App\SimpleSQL;

use PgSql\Connection;
use PgSql\Result;

class Sql
{
    private Connection $conn;

    public function __construct()
    {
        $conn_string = 'host = ' . $_ENV['HOST'];
        $conn_string .= ' port = ' . $_ENV['PORT'];
        $conn_string .= ' dbname = ' . $_ENV['DBNAME'];
        $conn_string .= ' user = ' . $_ENV['USER'];
        $conn_string .= ' password = ' . $_ENV['PASSWORD'];
        $this->conn = pg_connect($conn_string);
    }

    public function dml(string $sql, array $params = []): Result
    {
        return pg_query_params($this->conn, $sql, $params);
    }

    public function dmlFetch(string $sql, array $params = []): array
    {
        return pg_fetch_all(pg_query_params($this->conn, $sql, $params));
    }

    public function dmlDelete(string $tbl = '', array $conditions = []): bool|string
    {
        return pg_delete($this->conn, $tbl, $conditions);
    }

    public function columnList(string $tableName): string
    {
        $work = pg_meta_data($this->conn, $tableName);
        $count = 0;
        $list = '';
        foreach ($work as $key => $column) {
            if (++$count > 1) $list .= ', ';
            $list .= $key;
        }
        return $list;
    }

    public function columnListNotID(string $tableName): string
    {
        $work = pg_meta_data($this->conn, $tableName);
        $count = 0;
        $list = '';
        foreach ($work as $key => $column) {
            if ($key != 'id') {
                if (++$count > 1) $list .= ', ';
                $list .= $key;
            }
        }
        return $list;
    }

    public function columnArray(string $tableName): array
    {
        $work = pg_meta_data($this->conn, $tableName);
        $extra = [];
        foreach ($work as $key => $column) {
            $extra[] = $key;
        }
        return $extra;
    }

    public function columnArrayNotID(string $tableName): array
    {
        $work = pg_meta_data($this->conn, $tableName);
        $extra = [];
        foreach ($work as $key => $column) {
            if ($key != 'id') $extra[] = $key;
        }
        return $extra;
    }
}
