<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CodeDelivery\Http\Controllers;

use CodeDelivery\Repositories\OrderRepository;
/**
 * Description of OrdersController
 *
 * @author Willian
 */
class OrdersController extends Controller {

    public function __construct(OrderRepository $repository) {
        $this->repository = $repository;
    }

    public function index() {
        $orders = $this->repository->paginate();
        return view('admin.orders.index', compact('orders'));
    }

}
