<?php

namespace CodeDelivery\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Criteria\RequestCriteria;
use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Models\Order;

/**
 * Class OrderRepositoryEloquent
 * @package namespace CodeDelivery\Repositories;
 */
class OrderRepositoryEloquent extends BaseRepository implements OrderRepository
{

    protected $skipPresenter = true;

    public function getByIdAndDeliveryman($id, $idDeliveryman)
    {
        $result = $this->with(['items', 'client', 'cupom'])->findWhere([
            'id' => $id,
            'user_deliveryman_id' => $idDeliveryman
        ]);
        if ($result instanceof Collection)
        {
            $result = $result->first();
            if ($result)
            {
                $result->items->each(function($item) {
                    $item->product;
                });
            }
        } else
        {
            if (isset($result['data']) && count($result['data']) == 1)
            {
                $result = ['data' => $result['data'][0]];
            } else
            {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Order not found!');
            }
        }
        return $result;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return \CodeDelivery\Presenters\OrderPresenter::class;
    }

}
