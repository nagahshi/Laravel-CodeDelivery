<?php

namespace CodeDelivery\Http\Controllers;

use Illuminate\Http\Request;
use CodeDelivery\Http\Requests;
use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Repositories\ClientRepository;
use CodeDelivery\Http\Requests\AdminClientRequest;
use CodeDelivery\Service\ClientService;

class ClientController extends Controller {

    public function __construct(ClientRepository $repository, ClientService $clientService) {
        $this->repository = $repository;
        $this->clientService = $clientService;
    }

    public function index(ClientRepository $repository) {
        $clients = $repository->paginate();

        return view('admin.clients.index', compact('clients'));
    }

    public function create() {

        return view('admin.clients.create');
    }

    public function store(AdminClientRequest $request) {
        $data = $request->all();
        $this->clientService->create($data);
        return redirect()->route('admin.clients.index');
    }

    public function edit($id) {
        $client = $this->repository->find($id);

        return view('admin.clients.edit', compact('client'));
    }

    public function update(AdminClientRequest $request, $id) {
        $data = $request->all();
        $this->clientService->update($data,$id);

        return redirect()->route('admin.clients.index');
    }

}
