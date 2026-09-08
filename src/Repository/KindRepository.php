<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class KindRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->tableName = 'kind';
        $this->findColumns = $this->sql->columnList($this->tableName);
    }
}
