<?php

namespace App\SimpleSQL;

use PgSql\Result;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function sqlInsert(Request         $request,
                              LoggerInterface $logger): Result
    {
        $count = 0;
        foreach ($this->sql->columnArrayNotID($this->tableName) as $column) {
            if (++$count === 1) $this->insertQuery .= 'values (';
            if ($count > 1) $this->insertQuery .= ', ';
            $fieldName = $this->tableName . '_' . $column;
            $this->insertQuery .= "'" . $request->getPayload()->get($fieldName, 'default') . "'";
        }
        $this->insertQuery .= ')';
        $logger->info('###sqlInsert### ' . $this->insertQuery);
        return $this->sql->dml($this->insertQuery);
    }
}
