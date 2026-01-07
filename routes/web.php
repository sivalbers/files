<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Models\Zeugnis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

/*

function convertToValidWindowsFilename($filename) {
    $invalidChars = array('<', '>', ':', '"', '/', '\\', '|', '?', '*');
    $validFilename = str_replace($invalidChars, '_', $filename);
    return $validFilename;
}

*/


Route::get('/{id}', function ($id){
	//return "Function ist : ".$id;

	$documentName = "test.pdf";
	$documentName = $id;


	$zertifikat = Zeugnis::where('id', $id)->firstOrFail();
	try{
		$documentName = $zertifikat->filename;
	}
	catch(\Exception $e){
		request()->session()->flash('error', "Fehler: ".$e->getMessage());
		dump("Fehler:".$e->getMessage());
	}
	$name = verketten( $zertifikat->materialnummer, $zertifikat->ident, $zertifikat->teilenr);
	//$name = convertToValidWindowsFilename($name);

	$attachment_location = rtrim(config('custom.files_route'), '/') . '/' . $documentName;

    if (!is_null($documentName) && ($documentName != "") && file_exists($attachment_location)) {


        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public"); // needed for internet explorer
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($attachment_location));
        header("Content-Disposition: attachment; filename=".$name.".pdf");
        readfile($attachment_location);
        return response()->status(200);
    } else {
    //	dump($zertifikat);
        return view('notfound', ['id' => $id]);
    }
});


