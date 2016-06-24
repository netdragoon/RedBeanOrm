<?php

namespace Repositories;

use R;
use RedBeanPHP\OODBBean;

abstract class Repository
{
    private $name;

    /**
     * Repository constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return OODBBean
     */
    public function create()
    {
        return R::dispense($this->name);
    }

    /**
     * @param array $values
     * @return OODBBean
     * @throws \Exception
     */
    public function insert(array $values)
    {
        if (is_null($values) || is_array($values) === false)
        {
            throw new \Exception("Array of values no valid");
        }

        $model = $this->create();

        if (is_null($model))
        {
            throw new \Exception("Model no find");
        }

        $model->import($values);
        R::store($model);
        return $model;
    }

    /**
     * @param array $values
     * @param $id
     * @return OODBBean
     * @throws \Exception
     */
    public function edit(array $values, $id)
    {
        if (is_null($values) || is_array($values) === false)
        {
            throw new \Exception("Array of values no valid");
        }

        $model = $this->find($id);

        if (is_null($model))
        {
            throw new \Exception("Model no find");
        }

        $model->import($values);
        R::store($model);
        return $model;
    }

    /**
     * @param $id
     * @return OODBBean
     */
    public function find($id)
    {
        return R::load($this->name, $id);
    }

    /**
     * @param int|OODBBean $item
     * @return bool
     */
    public function delete($item)
    {
        if (is_null($item)) return false;
        if ($item instanceof OODBBean)
        {
            R::trash($item);
            return true;
        }
        else if (is_int($item))
        {
            $model = $this->find(((int)$item));
            R::trash($model);
            return true;
        }
        return false;
    }

    /**
     * @param null $sql
     * @param array|null $bindings
     * @return array
     */
    public function toAll($sql = null,array $bindings = array())
    {
        return R::findAll($this->name, $sql, $bindings);
    }

    /**
     * @param $sql
     * @param array $bindings
     * @return OODBBean List
     */
    public function toList($sql, array $bindings = array())
    {
        return R::convertToBean($this->name, $this->toArray($sql,$bindings));
    }

    /**
     * @param $sql
     * @param array $bindings
     * @return array
     */
    public function toDictionary($sql, array $bindings = array())
    {
        return R::getAssoc($sql,$bindings);
    }

    /**
     * @param $sql
     * @param array $bindings
     * @return array
     */
    public function toArray($sql, array $bindings = array())
    {
        return R::getAll($sql,$bindings);
    }

    /**
     * @param $sql
     * @param array $bindings
     * @return int
     */
    public function execute($sql, array $bindings = array())
    {
        return R::exec($sql, $bindings);
    }

    public function page($orderBy, $page = 1, $total = 10, $where = null, array $bindings = array())
    {
        $pg = 0;
        if ($page > 0)
        {
            $pg = $page - 1;
            $pg *= $total;
        }
        $count = $this->count($where, $bindings);
        $sql = sprintf("%s %s LIMIT %s, %s", !is_null($where) && is_string($where)?$where:'', $orderBy, $pg, $total);

        $class = new \stdClass();
        $class->items = $this->toAll($sql,$bindings);
        $class->page->current = ($page);
        $class->page->limit = $total;
        $class->records->count = $count;
        $class->page->previous = ($pg > 0);
        $class->page->next = (($pg+$total)<$count);
        $class->page->total = ceil($count / $total);
        return $class;
    }

    public function count($sql,array $bindings = array())
    {
        return R::count($this->name,$sql, $bindings);
    }
}

//http://www.redbeanphp.com/index.php?p=/counting