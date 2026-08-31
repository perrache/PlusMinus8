<?php

namespace App\Repository;

use App\SimpleSQL\Repo;
use App\SimpleSQL\Sql;

class AccountRepository extends Repo
{
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
        $this->findColumns = $this->sql->columnList('account');
        $this->findQuery = 'select ' . $this->findColumns . 'from account ';
    }
}
