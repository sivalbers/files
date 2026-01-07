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


Route::get('/{id}', function (int $id) {
    ob_clean(); // Buffer leeren
    Log::info("Anfrage für Zertifikat ID: {$id}");

    $zertifikat = Zeugnis::find($id);

    if (!$zertifikat) {
        Log::warning("Kein Zertifikat gefunden (ID: {$id})");
        return response()->view('notfound', ['id' => $id], 404);
    }

    $attachment_location = rtrim(config('custom.files_route'), '/') . '/' . $zertifikat->filename;

    $filePath = $attachment_location;
    if (!is_file($filePath)) {
        Log::warning("Datei fehlt: {$filePath} (ID: {$id})");
        return response()->view('notfound', ['id' => $id], 404);
    }

    $name = verketten(
        $zertifikat->materialnummer,
        $zertifikat->ident,
        $zertifikat->teilenr
    ) . '.pdf';

    $headers = [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => "attachment; filename=".$name.".pdf",
	    'Content-Transfer-Encoding' => 'Binary',
	    'Content-Length' => filesize($attachment_location),
        'Cache-Control' => 'public', // needed for internet explorer
    ];

//     return response()->download($filePath, $name, $headers)->setStatusCode(200);

    $response = response()
        ->download($filePath, $name, $headers)
        ->setStatusCode(200);

    Log::info("Response Status: " . $response->status());

    return $response;


	header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
	header("Cache-Control: public"); // needed for internet explorer
	header("Content-Type: application/pdf");
	header("Content-Transfer-Encoding: Binary");
	header("Content-Length:".filesize($attachment_location));
	header("Content-Disposition: attachment; filename=".$name.".pdf");
	readfile($attachment_location);
	die();

})->whereNumber('id');

Route::get('/favicon.ico', function () {
    abort(404); // oder return response()->noContent();
});


/*
Route::get('/{id}', function ($id){
	//return "Function ist : ".$id;

	$documentName = "test.pdf";
	$documentName = $id;

	// $attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/file.zip";
	// $attachment_location = "https://files.netzmaterialonline.de/file_".$id.".pdf";
	// $attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/".$documentName;

	// $attachment_location = "../resources/files/". $documentName;


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
        die();
    } else {
    //	dump($zertifikat);
        return view('notfound', ['id' => $id]);
    }
});
*/

