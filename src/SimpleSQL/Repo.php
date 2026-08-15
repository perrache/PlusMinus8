<?php

namespace App\SimpleSQL;

class Repo
{
    protected Sql $sql;

    protected string $columns;

    protected string $query;

    public function findAll(array $order = []): array
    {
        $count = 0;
        foreach ($order as $column => $direction) {
            if (++$count === 1) $this->query .= 'order by ';
            if ($count > 1) $this->query .= ', ';
            $this->query .= $column . ' ' . $direction;
        }
        return $this->sql->dml($this->query);
    }
}
