<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class CurrencyRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->columns = $this->sql->columnList('currency');
        $this->query = 'select ' . $this->columns . 'from currency ';
    }
}
