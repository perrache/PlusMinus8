<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class MinusRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->findColumns = $this->sql->columnList('minus');
        $this->findQuery = 'select ' . $this->findColumns . ' from minus ';
    }
}
