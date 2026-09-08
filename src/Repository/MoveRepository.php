<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class MoveRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->tableName = 'move';
        $this->findColumns = $this->sql->columnList($this->tableName);
    }
}
