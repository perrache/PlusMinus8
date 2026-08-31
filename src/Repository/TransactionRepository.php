<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class TransactionRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->findColumns = $this->sql->columnList('transaction');
        $this->findQuery = 'select ' . $this->findColumns . 'from transaction ';
    }
}
