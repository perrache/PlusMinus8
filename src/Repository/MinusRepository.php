<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class MinusRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->columns = 'id, name ';
        $this->query = 'select ' . $this->columns . 'from kind ';
    }
}
