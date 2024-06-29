<?php


namespace App\Repositories;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Contracts\PresenterInterface;

/**
 * Class Repository
 * @package App\Repositories
 */
abstract class Repository extends \Prettus\Repository\Eloquent\BaseRepository
{
    public static function whereIn($query, $field, $values)
    {
        $query = $query->where(function ($q) use ($field, $values) {
            $q = $q->orWhereIn($field, $values);
            if (in_array(null, $values) ||
                in_array('null', $values)) {
                $q = $q->orWhereNull($field);
            }


            return $q;
        });


        return $query;
    }

    /**
     * @param null $limit
     * @param string[] $columns
     * @param string $method
     * @return LengthAwarePaginator|Collection|mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns);
//        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * @param mixed $result
     * @param string $include
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function parserResult($result, $include = '')
    {
        $this->presenter->parseIncludes($include);
        if ($this->presenter instanceof PresenterInterface) {
            if ($result instanceof Collection || $result instanceof LengthAwarePaginator) {
                $result->each(function ($model) {
                    if ($model instanceof Presentable) {
                        $model->setPresenter($this->presenter);
                    }

                    return $model;
                });
            } elseif ($result instanceof Presentable) {
                $result = $result->setPresenter($this->presenter);
            }

            if (!$this->skipPresenter) {
                return $this->presenter->present($result);
            }
        }

        return $result;
    }

    /**
     * @param array $where
     * @param string $columns
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function sum(array $where = [], $columns = '*')
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($where) {
            $this->applyConditions($where);
        }

        $result = $this->model->sum($columns);

        $this->resetModel();
        $this->resetScope();

        return $result;
    }

    public function parseIncludes($includes = '')
    {
        if ($this->presenter instanceof PresenterInterface) {
            $this->presenter->parseIncludes($includes);
        }
    }

    public function getDateRange($date_start, $date_end){
        $date = DB::query()->select('t_range.date')->fromRaw(DB::raw("(
            select '$date_end' - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as date
            from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
            ) as t_range ")
        )->whereBetween('date', [$date_start, $date_end])->orderBy('date');
        return $date;
    }
}
