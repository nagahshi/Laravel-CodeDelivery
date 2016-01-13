<?php

namespace CodeDelivery\Http\Controllers\Api\Admin;

use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Repositories\ProductRepository;
use CodeDelivery\Http\Requests\AdminProductRequest;
use CodeDelivery\Repositories\CategoryRepository;

class AdminProductController extends Controller
{

    /**
     * @var ProductRepository 
     */
    private $repository;

    /**
     * @var CategoryRepository 
     */
    private $categoryRepository;

    public function __construct(ProductRepository $repository, CategoryRepository $categoryRepository)
    {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        try
        {
            return $this->repository->with('category')->all();
        } catch (\Exception $e)
        {
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    public function store(AdminProductRequest $request)
    {
        try
        {
            $data = $request->all();
            return $this->repository->create($data);
        } catch (ModelNotFoundException $e)
        {
            return [
                'error' => true,
                'message' => 'Error on save!'
            ];
        } catch (\Exception $e)
        {
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    public function show($id)
    {
        try
        {
            return $this->repository->find($id);
        } catch (ModelNotFoundException $e)
        {
            return [
                'error' => true,
                'message' => 'Register not found!'
            ];
        } catch (\Exception $e)
        {
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    public function update(AdminProductRequest $request, $id)
    {
        try
        {
            $data = $request->all();
            return $this->repository->update($data, $id);
        } catch (ModelNotFoundException $e)
        {
            return [
                'error' => true,
                'message' => 'Not updated!'
            ];
        } catch (\Exception $e)
        {
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    public function destroy($id)
    {

        try
        {
            $this->repository->delete($id);
            return 'Has been deleted!';
        } catch (ModelNotFoundException $e)
        {
            return [
                'error' => true,
                'message' => 'Not deleted!'
            ];
        } catch (\Exception $e)
        {
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

}
