<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class MinusRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->tableName = 'minus';
        $this->findColumns = $this->sql->columnList($this->tableName);
    }
}
