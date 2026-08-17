<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class SaldoRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->columns = $this->sql->columnList('saldo');
        $this->query = 'select ' . $this->columns . 'from saldo ';
    }
}
