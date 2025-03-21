<?php
session_start();

//Funkcija za dekriptiranje
function decryptData($data, $key)
{
    $iv = $_SESSION['iv'];
    return openssl_decrypt($data, 'AES-128-CTR', $key, 0, $iv);
}

//provjera postoji li direktorij "files"
$upload_directory = "files";
if (!is_dir($upload_directory)) {
    echo "Direktorij 'files' ne postoji.";
    exit;
}

//dohvacanje kriptiranih datoteka
$uploaded_files = glob($upload_directory . '/*.{pdf,jpeg,jpg,png}', GLOB_BRACE);

if (empty($uploaded_files)) {
    echo "Nema kriptiranih datoteka.";
    exit;
}

foreach ($uploaded_files as $uploaded_file) {

    //citanje sadrzaja datoteke
    $encrypted_data = file_get_contents($uploaded_file);

    //dohvacanje dijela naziva datoteke koji predstavlja originalni naziv
    $file_name_parts = explode('_', basename($uploaded_file));
    if (count($file_name_parts) !== 2) {
        echo "Neispravan format naziva datoteke: {$uploaded_file}.";
        continue;
    }

    $encryption_key = md5('jed4n j4k0 v3l1k1 kljuc'); 

    //dekriptiranje sadrzaja datoteke
    $decrypted_data = decryptData($encrypted_data, $encryption_key);

    //dohvacanje originalnog naziva datoteke
    $decrypted_file_name = "decrypted_" . $file_name_parts[1];

    //Spremanje dekriptirane datoteke u direktorij files
    $downloaded_file_path = $upload_directory . "/" . $decrypted_file_name;
    if (file_put_contents($downloaded_file_path, $decrypted_data) !== false) {
        //Link za preuzimanje dekriptiranog dokumenta
        echo "<a href='{$downloaded_file_path}'>Preuzmi dekriptiranu datoteku ({$decrypted_file_name})</a><br>";
    } else {
        echo "Greška prilikom spremanja dekriptirane datoteke {$decrypted_file_name}.<br>";
    }
}

?>