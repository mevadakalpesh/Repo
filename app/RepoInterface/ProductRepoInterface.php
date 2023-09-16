<?php

namespace App\RepoInterface;

interface ProductRepoInterface
{
  public function createProduct($request = []);
  public function updateProduct($where = [],$request = []);
  public function deleteProduct($where = []);
  
}
