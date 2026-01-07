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


Route::get('/{id}', function ($id) {
    try {
        Log::info("Anfrage für Zertifikat ID: {$id}");
        // Versuche das Zertifikat anhand der ID zu finden
        $zertifikat = Zeugnis::where('id', $id)->firstOrFail();

        // Dateiname aus dem Datensatz lesen
        $documentName = $zertifikat->filename;

        // Benutzerfreundlicher Name
        $name = verketten($zertifikat->materialnummer, $zertifikat->ident, $zertifikat->teilenr);

        // Pfad zur Datei
        $attachment_location = rtrim(config('custom.files_route'), '/') . '/' . $documentName;

        // Prüfe, ob Datei existiert
        if (!empty($documentName) && file_exists($attachment_location)) {
            // Sende PDF an Browser
            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public");
            header("Content-Type: application/pdf");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length: " . filesize($attachment_location));
            header("Content-Disposition: attachment; filename=\"{$name}.pdf\"");
            readfile($attachment_location);
            Log::warning("Datei gefunden: {$attachment_location} (ID: {$id})");
            die();
        } else {
            // Datei nicht gefunden – logge und gib View aus
            Log::warning("Datei nicht gefunden: {$attachment_location} (ID: {$id})");
            return view('notfound', ['id' => $id]);
        }
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Kein Datensatz mit dieser ID
        Log::error("Zertifikat mit ID {$id} nicht gefunden.");
        return view('notfound', ['id' => $id]);
    } catch (\Throwable $e) {
        // Allgemeiner Fehler (z. B. Pfadproblem, Berechtigung, etc.)
        Log::error("Fehler beim Abrufen von Zertifikat ID {$id}: " . $e->getMessage());
        return view('notfound', ['id' => $id]);
    }
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

