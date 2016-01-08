<?php

namespace CodeDelivery\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use CodeDelivery\Http\Requests;
use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\UserRepository;
use CodeDelivery\Repositories\ProductRepository;
use CodeDelivery\Service\OrderService;
use LucaDegasperi\OAuth2Server\Authorizer;

class ClientCheckoutController extends Controller
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
     * @var ProductRepository 
     */
    private $productRepository;

    /**
     *
     * @var type 
     */
    private $with = ['client', 'items', 'cupom'];

    public function __construct(OrderRepository $repository, UserRepository $userRepository, ProductRepository $productRepository, OrderService $orderService)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->service = $orderService;
    }

    public function index()
    {
        $id = \Authorizer::getResourceOwnerId();
        $clientId = $this->userRepository->find($id)->client->id;
        $orders = $this->repository
                        ->skipPresenter(false)
                        ->with($this->with)
                        ->scopeQuery(function($query) use($clientId) {
                            return $query->where('client_id', '=', $clientId);
                        })->paginate();

        return $orders;
    }

    public function store(Requests\CheckoutRequest $request)
    {

        $data = $request->all();
        $id = \Authorizer::getResourceOwnerId();
        $clientId = $this->userRepository->find($id)->client->id;
        $data['client_id'] = $clientId;
        $order = $this->service->create($data);

        return $this->repository->with($this->with)->find($order->id);
    }

    public function show($id)
    {

        return $this->repository
                        ->skipPresenter(false)
                        ->with($this->with)
                        ->find($id);
    }

}
