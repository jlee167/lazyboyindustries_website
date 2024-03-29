<?php

namespace App\Repositories\Support;

use App\Models\SupportRequest;
use App\Repositories\Base\SingleModelRepository;


class SupportRepository extends SingleModelRepository
{
    protected $model;


    public function __construct(SupportRequest $model)
    {
        parent::__construct($model);
    }
}
