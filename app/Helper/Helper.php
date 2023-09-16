<?php

use Illuminate\Support\Facades\Storage;

function responsef($data = [], $error=false , $message = "Success"){
  return response()->json([
    "error" =>$error,
    "message" => $message,
    "data" => (Object) $data
  ]);
}

function saveFile($file,$folder,$disk = null){
  $disk = !blank($disk)  ? $disk : config('constant.storage_type');
  $fileName = $file->hashName();
  $file->store($folder,$disk);
  return $fileName;
}

function deleteFile($filePath,$disk = null){
  $disk = !blank($disk)  ? $disk : config('constant.storage_type');
  if( Storage::disk($disk)->exists($filePath) ){
     Storage::disk($disk)->delete($filePath);
  }
}