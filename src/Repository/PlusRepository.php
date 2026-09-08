<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class PlusRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->tableName = 'plus';
        $this->findColumns = $this->sql->columnList($this->tableName);
    }
}
