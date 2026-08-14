<?php

namespace App\SimpleSQL;

use PgSql\Connection;

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

    public function ddl(string $sql)
    {
        return pg_query($this->conn, $sql);
    }

    public function dml(string $sql, array $params = [])
    {
        return pg_fetch_all(pg_query_params($this->conn, $sql, $params));
    }
}
