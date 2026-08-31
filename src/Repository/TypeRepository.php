<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class TypeRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->findColumns = $this->sql->columnList('type');
        $this->findQuery = 'select ' . $this->findColumns . 'from type ';
    }
}
