<?php

namespace App\RepoClass;
use App\RepoInterface\ProductRepoInterface;
use Illuminate\Support\Facades\Storage;
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
    if($request->has('image')){
      $data['image'] = saveFile($request->file('image'),$this->model::$mediapath);
    }
    
    return self::create($data);
  }
  
  public function updateProduct($where = [],$request = []){
    $data = [
      "name" => $request->name,
      "price" => $request->price,
      "description" => $request->description,
      "sku" => $request->sku,
    ];
    
    if($request->has('image')){
      $data['image'] = saveFile($request->file('image'),$this->model::$mediapath);
      $this->deleteMedia($where);
    }
    $result = self::update($where,$data);
    
  }
  
  public function deleteProduct($where = []){
    $this->deleteMedia($where);
    $result = self::delete($where);
    return $result;
  }
  
  public function deleteMedia($where){
    $products = self::get($where);
    if(!blank($products)){
      foreach ($products as $product){
        $filePath = $this->model::$mediapath.'/'.$product->image;
        deleteFile($filePath);
      }
    }
  }
}