<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\RepoInterface\ProductRepoInterface;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

  public function __construct(
    public ProductRepoInterface $productRepoInterface
  ) {}

  /**
  * Display a listing of the resource.
  */
  public function index(Request $request) {


    if ($request->ajax()) {
      $data = $this->productRepoInterface->get();
      return Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('action', function($row) {
        $actionBtn = '<button data-editurl="'.route('product.edit', $row->id).'" type="button" class="edit-product btn btn-primary" data-bs-toggle="modal" data-bs-target="#editproduct">Edit</button>
                       <a href="javascript:void(0)" data-deleteurl="'.route('product.destroy', $row->id).'" class="delete-product btn btn-danger btn-sm">Delete</a>';
        return $actionBtn;
      })
      ->editColumn('price', function($row) {
        return config('constant.default_currency_symbol').$row->price;
      })
      ->editColumn('fullimage', function($row) {
        return '<img src="'.$row->fullimage.'" width="100px" height="100px" class="table-row-image" />';
      })
      ->rawColumns(['fullimage', 'action'])
      ->make(true);
    }
    return view('product.product-list');
  }

  /**
  * Show the form for creating a new resource.
  */
  public function create() {
    //
  }

  /**
  * Store a newly created resource in storage.
  */
  public function store(ProductStoreRequest $request) {

    DB::beginTransaction();
    try {
      $data = $this->productRepoInterface->createProduct($request);
      DB::commit();
      return responsef(message:"Product Create Successfully");
    } catch (\Exception $e) {
      DB::rollback();
      return responsef(error:true, message:$e->getMessage());
    }
  }

  /**
  * Display the specified resource.
  */
  public function show(string $id) {}

  /**
  * Show the form for editing the specified resource.
  */
  public function edit(string $id) {
    return responsef($this->productRepoInterface->first([['id', $id]]));
  }

  /**
  * Update the specified resource in storage.
  */
  public function update(Request $request, string $id) {

    DB::beginTransaction();
    try {
      $data = $this->productRepoInterface->updateProduct([['id', $id]], $request);
      DB::commit();
      return responsef(message:"Product Update Successfully");
    } catch (\Exception $e) {
      DB::rollback();
      return responsef(error:true, message:$e->getMessage());
    }
  }

  /**
  * Remove the specified resource from storage.
  */
  public function destroy(string $id) {

    DB::beginTransaction();
    try {
      $result = $this->productRepoInterface->deleteProduct([['id', $id]]);
      DB::commit();
      if ($result) {
        return responsef(message:"product Delete Successfully");
      } else {
        return responsef(error:true, message:"something went wrong");
      }
    } catch (\Exception $e) {
      DB::rollback();
      return responsef(error:true, message:$e->getMessage());
    }
  }
}