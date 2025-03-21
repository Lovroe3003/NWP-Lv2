<?php

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])){
    $file = $_FILES["file"];

    //provjera je li uploadan prihvatljiv tip datoteke i dohvacanje ekstenzije
    $accepted_extensions = array("pdf", "jpeg", "jpg", "png");
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    //ako je ekstenzija dozvoljena nastavlja s radom
    if(in_array($file_extension, $accepted_extensions)){
        //stvaranje kljuca za enkripciju
        $encryption_key = md5('jed4n j4k0 v3l1k1 kljuc');
        $cipher = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($cipher);

        $encryption_iv = random_bytes($iv_length); //inicijalizacijski vektor

        $_SESSION['iv'] = $encryption_iv;

        //kriptiranje sadrzaja datoteke i spremanje
        $encrypted_data = openssl_encrypt(file_get_contents($file["tmp_name"]), $cipher, $encryption_key, 0, $encryption_iv);
        $file_name = uniqid() . "_" . $file["name"];
        $file_path = "files/" . $file_name;
        file_put_contents($file_path, $encrypted_data);

        echo "Datoteka je uploadana i enkriptirana uspješno.";
    }
    else{
        echo "Neprihvatljiv format. Prihvaćeni su samo PDF, JPG, JPEG, PNG";
    }
}

?>

<form method = "post" enctype="multipart/form-data">
    Odaberite datoteku za upload: <input type="file" name="file">
    <input type="submit" value="Upload file">
</form>