<?php
namespace App\Traits;

trait CrudTrait {
  
  public function first($where = [],$with = []){
    return $this->model->with($with)->where($where)->first();
  }
  
  public function get($where = [],$with = []){
    return $this->model->with($with)->where($where)->get();
  }
  
  public function create($data = []){
    return $this->model->create($data);
  }
  
  public function update($where = [],$data = []){
    return $this->model->where($where)->update($data);
  }
  
  public function delete($where = []){
    return $this->model->where($where)->delete();
  }
  
}