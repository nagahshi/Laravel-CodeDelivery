<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CodeDelivery\Http\Controllers;

use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Description of OrdersController
 *
 * @author Willian
 */
class OrdersController extends Controller
{

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $orders = $this->repository->paginate();
        return view('admin.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $data = $request->all();
            $data['customer_id'] = $this->customer_id;

            DB::commit();
            return redirect()->route('admin.order.index');
        } catch (\Prettus\Validator\Exceptions\ValidatorException $e)
        {
            \DB::rollback();
            return ['error' => true, 'message' => $e->getMessageBag()];
        }
    }

    public function edit($id, UserRepository $userRepository)
    {
        $list_status = [0 => 'Pendente', 1 => 'A Caminho', 2 => 'Entregue'];
        $order = $this->repository->find($id);
        $deliveryman = $userRepository->getDeliverymen();
        return view('admin.orders.edit', compact('order', 'list_status', 'deliveryman'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->repository->update($data, $id);
        return redirect()->route('admin.orders.index');
    }

}
