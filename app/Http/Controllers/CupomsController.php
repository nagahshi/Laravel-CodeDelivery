<?php

namespace CodeDelivery\Http\Controllers;

use Illuminate\Http\Request;
use CodeDelivery\Http\Requests;
use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Repositories\CupomRepository;
use CodeDelivery\Http\Requests\AdminCupomRequest;

class CupomsController extends Controller {

    public function __construct(CupomRepository $repository) {
        $this->repository = $repository;
    }

    public function index(CupomRepository $repository) {
        $cupoms = $repository->paginate();

        return view('admin.cupoms.index', compact('cupoms'));
    }

    public function create() {
        return view('admin.cupoms.create');
    }

    public function store(AdminCupomRequest $request) {
        $data = $request->all();
        $this->repository->create($data);
        return redirect()->route('admin.cupoms.index');
    }


    public function update(AdminCupomRequest $request, $id) {
        $data = $request->all();
        $this->repository->update($data,$id);
        return redirect()->route('admin.cupoms.index');
    }

}
