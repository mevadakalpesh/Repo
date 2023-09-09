<?php

namespace App\RepoClass;
use App\RepoInterface\ProductRepoInterface;
use App\Traits\CrudTrait;
use App\Models\Product;

class ProductRepoClass implements ProductRepoInterface
{
  use CrudTrait;

  public function __construct(
    public Product $model
  ) {}

  public function createProduct($request = []) {
    $data = [
      "name" => $request->name,
      "price" => $request->price,
      "description" => $request->description,
      "sku" => $request->sku,
    ];
    return self::create($data);
  }
  
  public function updateProduct($where = [],$request = []){
    $data = [
      "name" => $request->name,
      "price" => $request->price,
      "description" => $request->description,
      "sku" => $request->sku,
    ];
    
    return self::update($where,$data);
  }
  
}