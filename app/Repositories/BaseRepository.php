<?php

namespace App\Repositories;

class BaseRepository extends Repository implements BaseRepositoryInterface
{
    static public function queryFilter($query, $filter)
    {
        if (isset($filter['province_ids'])) {
            $query = $query->whereIn('province_id', $filter['province_ids']);
        }

        return $query;
    }

    public function list($limit = 0, array $filter = [])
    {
        // TODO: Implement list() method.
        $this->resetCriteria();

        $this->scopeQuery(function ($query) use ($filter) {
            $query = self::queryFilter($query, $filter);
            return $query;
        });

        if ($limit) {
            return $this->paginate($limit);
        }

        return $this->get();
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }
}
