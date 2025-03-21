<?php
//Podaci za spajanje na bazu radovi, stvaranje veze i provjera povezanosti
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "radovi";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Greska u povezivanju: " . $conn->connect_error);
}

//odredjivanje naziva backup datoteke, imat ce timestamp u nazivu 
$backup_file = 'radovi_backup_' . date("Y-m-d-H-i-s") . '.txt';

//Sql upit
$query = "SELECT * FROM diplomski_radovi";
$result = $conn->query($query);

//provjera rezultata sql upita
if($result->num_rows > 0){
    //otvaranje backup datoteke u nacinu za pisanje
    $backup_handle = fopen($backup_file, 'w');

        // dohvacanje naziva stupaca
        $columns = array_keys($result->fetch_assoc());
        $column_list = implode(", ", $columns);
    
        // vracanje pokazivaca na pocetak rezultata
        $result->data_seek(0);
    
        while ($row = $result->fetch_assoc()) {
            //formatiranje
            $values = array_map(function($value) use ($conn) {
                return "'" . $conn->real_escape_string($value) . "'";
            }, array_values($row));
    
            $values_list = implode(", ", $values);
    
            // upisivanje odgovarajuceg oblika u datoteku
            $sql_insert = "INSERT INTO diplomski_radovi ($column_list) VALUES ($values_list);\n";
            fwrite($backup_handle, $sql_insert);
    }

    fclose($backup_handle);

    //sazimanje datoteke
    $compressed_backup_file = $backup_file . '.gz';
    $compressed_content = gzencode(file_get_contents($backup_file), 9);
    file_put_contents($compressed_backup_file, $compressed_content);

    unlink($backup_file);

    echo "Backup baze podataka uspješno napravljen i sažet";
}else{
    echo "Nema podataka za backup";
}
$conn->close();

?>