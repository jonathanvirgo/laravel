<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use VIPSoft\Unzip\Unzip;

class uploadFile extends Controller
{
    public function index(){
        return view('welcome',compact('abc'));
    }

    /**
     * @param $request
     */
    public function upload(Request $request){
        if($request->hasFile('font')) {
            $file = $request->font;
            $name = $file->getClientOriginalName();
            $nameFolder = str_replace(".zip", '', $name);
            $unzipper  = new Unzip();
            $file = $file->store('font'); //store file in storage/app/zip
            $fileNames = $unzipper->extract(storage_path('app/'.$file),storage_path('app/font/'.$nameFolder));
            Storage::delete($file);
            $this->moveFile($fileNames, $nameFolder);
            Storage::deleteDirectory("font/".$nameFolder."/static");

            $this->index();
        }else {
            echo "Chưa upload";
        }
    }

    public function moveFile($fileNames, $nameFolder){
        for($i = 0; $i < count($fileNames); $i++){
            echo $fileNames;
            if($fileNames[$i] == 'OFL.txt') {
                Storage::delete("font/".$nameFolder."/".$fileNames[$i]);
            }elseif (strpos($fileNames[$i], $nameFolder) == 7) {
                 if(Storage::exists("font/". $nameFolder. "/" . substr($fileNames[$i],7)) != 1) {
                     Storage::move("font/" . $nameFolder . "/" . $fileNames[$i], "font/" . $nameFolder . "/" . substr($fileNames[$i], 7));
                 }
            }
        }

    }


}
