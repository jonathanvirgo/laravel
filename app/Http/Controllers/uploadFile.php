<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use VIPSoft\Unzip\Unzip;

class uploadFile extends Controller
{
    public function index(){
        return view('welcome');
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
            $file = $file->store('public/font');
            $fileNames = $unzipper->extract(storage_path('app/'.$file),storage_path('app/public/font/'.$nameFolder));
            Storage::delete($file);
            $this->moveFile($fileNames, $nameFolder);
            Storage::deleteDirectory("public/font/".$nameFolder."/static");
            $fileInFolder = Storage::disk('local')->files("public/font/".$nameFolder);
            session()->flash('listFile', $fileInFolder);
        }else {
            sleep(500/1000);
        }
        return redirect('/');
    }

    public function moveFile($fileNames, $nameFolder){
        for($i = 0; $i < count($fileNames); $i++) {
            if ($fileNames[$i] == 'OFL.txt') {
                Storage::delete("public/font/" . $nameFolder . "/" . $fileNames[$i]);
            } elseif (substr($fileNames[$i],0,7) === 'static/' && substr($fileNames[$i],-4) === '.ttf') {
                if (Storage::exists("public/font/" . $nameFolder . "/" . substr($fileNames[$i], 7)) != 1) {
                    Storage::move("public/font/" . $nameFolder . "/" . $fileNames[$i], "public/font/" . $nameFolder . "/" . substr($fileNames[$i], 7));
                }
            }
        }
    }
    public function saveData(Request $request){
        $results = DB::select('select * from fonts where name = ?', [$request->name]);
        if($request->listFont !== null){
            $listFontJson = json_encode($request->listFont);
            if(count($results) == 0){
                DB::insert('insert into fonts ( name, path, list_font) values (?, ?, ?)', [$request->name, $request->pathRegular, $listFontJson]);
            }else{
                DB::update('update fonts set path = ?,list_font = ?  where name = ?', [$request->pathRegular,$listFontJson,$request->name]);
            }
        }
    }
    public function showData(){
        $results = DB::select('select * from fonts');
        return view('showList',compact('results'));
    }
    public function updateName(Request $request){
        $results = DB::select('select * from fonts where id = ?', [$request->id]);
        if(count($results) > 0){
            DB::update('update fonts set name = ?  where id = ?', [$request->name,$request->id]);
        }
    }

    public function deleteFont($id){
        $results = DB::select('select * from fonts where id = ?', [$id]);
        if(count($results) > 0){
            DB::table('fonts')->delete($id);
            return redirect('/showData');
        }
    }
}
