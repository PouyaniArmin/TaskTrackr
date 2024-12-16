<?php
namespace App\Core;

class Application{
    public function run(){
        $request=new Request;
        echo $request->path();
    }
}