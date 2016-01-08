<?php

namespace CodeDelivery\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CumpomRepository
 * @package namespace CodeDelivery\Repositories;
 */
interface CupomRepository extends RepositoryInterface
{
    public function findByCode($code);
}
