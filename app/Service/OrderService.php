<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CodeDelivery\Service;

use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\ProductRepository;
use CodeDelivery\Repositories\CupomRepository;
use Illuminate\Support\Facades\DB;

/**
 * Description of ClientService
 *
 * @author Willian
 */
class OrderService
{

    private $clientRepository;
    private $userRepository;

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository, CupomRepository $cupomRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->cupomRepository = $cupomRepository;
    }

    public function update(array $data, $id)
    {
        $this->clientRepository->update($data, $id);
        $userId = $this->clientRepository->find($id, ['user_id'])->user_id;
        $this->userRepository->update($data['user'], $userId);
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try
        {
            $data['status'] = 0;
            if (isset($data['cupom_code']))
            {
                $cupom = $this->cupomRepository->findByField('code', $data['cupom_code'])->first();
                $data['cupom_id'] = $cupom->id;
                $cupom->used = 1;
                $cupom->save();
                unset($data['cupom_code']);
            }
            $items = $data['items'];
            unset($data['items']);
            $order = $this->orderRepository->create($data);
            $total = 0;
            foreach ($items as $item)
            {
                $item['price'] = $this->productRepository->find($item['product_id'])->price;
                $order->items()->create($item);
                $total += $item['price'] * $item['qtd'];
            }

            $order->total = $total;
            if (isset($cupom))
            {
                $order->total = $total - $cupom->value;
            }
            $order->save();
            DB::commit();
            return $order;
        } catch (Exception $e)
        {
            \DB::rollback();
            throw $e;
        }
    }

    public function updateStatus($id, $idDeliveryman, $status)
    {
        $order = $this->orderRepository->getByIdAndDeliveryman($id, $idDeliveryman);
        if ($order instanceof \CodeDelivery\Models\Order)
        {
            $order->status = $status;
            $order->save();
            return $order;
        }
        return false;
    }

}
