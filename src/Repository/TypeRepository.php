<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class TypeRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;

        $this->tableName = 'type';

        $this->findColumns = $this->sql->columnList($this->tableName);

        $this->findQuery = 'select ';
        $this->findQuery .= $this->findColumns;
        $this->findQuery .= ' from ';
        $this->findQuery .= $this->tableName;
        $this->findQuery .= ' ';

        $this->insertQuery = 'insert into';
        $this->insertQuery .= ' ';
        $this->insertQuery .= $this->tableName;
        $this->insertQuery .= ' (';
        $this->insertQuery .= $this->sql->columnListNotID($this->tableName);
        $this->insertQuery .= ') ';
    }
}
