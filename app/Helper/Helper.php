<?php

function responsef($data = [], $error=false , $message = "Success"){
  return response()->json([
    "error" =>$error,
    "message" => $message,
    "data" => (Object) $data
  ]);
}