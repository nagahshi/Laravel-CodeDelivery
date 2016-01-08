<?php

namespace CodeDelivery\Http\Controllers\Api\Deliveryman;

use Illuminate\Http\Request;
use CodeDelivery\Http\Requests;
use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\UserRepository;
use CodeDelivery\Service\OrderService;
use LucaDegasperi\OAuth2Server\Authorizer;

class DeliverymanCheckoutController extends Controller
{

    /**
     * @var OrderRepository
     */
    private $repository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     *
     * @var type 
     */
    private $with = ['client','cupom','items'];
    
    public function __construct(OrderRepository $repository, UserRepository $userRepository, OrderService $orderService)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->service = $orderService;
    }

    public function index()
    {
        $idDeliveryman = \Authorizer::getResourceOwnerId();
        $orders = $this->repository
                ->skipPresenter(false)
                ->with($this->with)
                ->scopeQuery(function($query) use($idDeliveryman) {
                    return $query->where('user_deliveryman_id', '=', $idDeliveryman);
                })->paginate();

        return $orders;
    }

    public function show($id)
    {
        $idDeliveryman = \Authorizer::getResourceOwnerId();
        return $this->repository
                ->skipPresenter(false)
                ->getByIdAndDeliveryman($id, $idDeliveryman);
    }

    public function updateStatus(Request $request,$id)
    {
        $idDeliveryman = \Authorizer::getResourceOwnerId();
        $order = $this->service->updateStatus($id,$idDeliveryman,$request->get('status'));
        
        return (($order)?$this->repository->find($order->id):  abort(400,'Order not found!'));
    }

}
