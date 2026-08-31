<?php

namespace App\SimpleSQL;

use PgSql\Result;
use Psr\Log\LoggerInterface;

class Repo
{
    protected Sql $sql;

    protected string $tableName;

    protected string $findColumns;

    protected string $findQuery;

    protected string $insertQuery;

    public function findAll(LoggerInterface $logger,
                            array           $order = []): array
    {
        $count = 0;
        foreach ($order as $column => $direction) {
            if (++$count === 1) $this->findQuery .= 'order by ';
            if ($count > 1) $this->findQuery .= ', ';
            $this->findQuery .= $column . ' ' . $direction;
        }
        $logger->info('###findAll### ' . $this->findQuery);
        return $this->sql->dmlFetch($this->findQuery);
    }

    public function findBy(LoggerInterface $logger,
                           array           $where = [],
                           array           $order = []): array
    {
        $count = 0;
        foreach ($where as $column => $condition) {
            if (++$count === 1) $this->findQuery .= 'where ';
            $this->findQuery .= $column . ' ' . $condition . ' ';
        }
        $count = 0;
        foreach ($order as $column => $direction) {
            if (++$count === 1) $this->findQuery .= 'order by ';
            if ($count > 1) $this->findQuery .= ', ';
            $this->findQuery .= $column . ' ' . $direction;
        }
        $logger->info('###findBy### ' . $this->findQuery);
        return $this->sql->dmlFetch($this->findQuery);
    }

    public function sqlInsert(LoggerInterface $logger): Result
    {
        $count = 0;
        $work = $this->sql->columnArrayNotID($this->tableName);
        foreach ($work as $column) {
            if (++$count === 1) $this->insertQuery .= 'values (';
            if ($count > 1) $this->insertQuery .= ', ';
            $this->insertQuery .= '$' . $count;
        }//##########$sql->dml('insert into kind (name) values ($1)', [$request->getPayload()->get('kind_name', 'default')]);
        $this->insertQuery .= ')';
        $logger->info('###sqlInsert### ' . $this->insertQuery);
        return $this->sql->dml('insert into');
//        return $this->sql->dml($this->insertQuery);
    }
}
