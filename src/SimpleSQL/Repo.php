<?php

namespace App\SimpleSQL;

use Psr\Log\LoggerInterface;

class Repo
{
    protected Sql $sql;

    protected string $columns;

    protected string $query;

    public function findAll(LoggerInterface $logger,
                            array           $order = []): array
    {
        $count = 0;
        foreach ($order as $column => $direction) {
            if (++$count === 1) $this->query .= 'order by ';
            if ($count > 1) $this->query .= ', ';
            $this->query .= $column . ' ' . $direction;
        }
        $logger->info('###findAll### ' . $this->query);
        return $this->sql->dml($this->query);
    }

    public function findBy(LoggerInterface $logger,
                           array           $where = [],
                           array           $order = []): array
    {
        $count = 0;
        foreach ($where as $column => $condition) {
            if (++$count === 1) $this->query .= 'where ';
            $this->query .= $column . ' ' . $condition . ' ';
        }
        $count = 0;
        foreach ($order as $column => $direction) {
            if (++$count === 1) $this->query .= 'order by ';
            if ($count > 1) $this->query .= ', ';
            $this->query .= $column . ' ' . $direction;
        }
        $logger->info('###findBy### ' . $this->query);
        return $this->sql->dml($this->query);
    }
}
