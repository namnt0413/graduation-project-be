<?php

namespace app\Repositories;

interface BaseRepositoryInterface{

    public function list($limit = 0, array $filter = []);

    public function find($id);

}
