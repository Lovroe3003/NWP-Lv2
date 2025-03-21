<?php
//ucitavanje i provjera xml datoteke
$xml = simplexml_load_file('LV2.xml');

if($xml === false){
    die('Greska prilikom ucitavanja XML datoteke');
}

//profil svake osobe
$profiles = [];
foreach($xml->record as $person){
    $id = (string) $person->id;
    $ime = (string) $person->ime;
    $prezime = (string) $person->prezime;
    $email = (string) $person->email;
    $slika = (string) $person->slika;
    $zivotopis = (string) $person->zivotopis;

    $profiles[]= [
        'ime' => $ime,
        'prezime' => $prezime,
        'email' => $email,
        'slika' => $slika,
        'zivotopis' => $zivotopis
    ];
}
?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Zadatak 3 - Osobe</title>
        <style>
            .profile {
                margin-bottom: 15px;
                background-color:#c9c9c9;
                padding: 15px;
                border-radius: 10px;
            }
            .profile img {
                width: 50px;
                height: 50px;
                border-radius: 5px;
                margin-right: 15px;
            }
        </style>
    </head>
    <body>
        <?php foreach($profiles as $profile): ?>
            <div class="profile">
                <img src="<?php echo $profile['slika']; ?>">
                <div>
                    <p><strong>Ime:</strong> <?php echo $profile['ime']; ?></p>
                    <p><strong>Prezime:</strong> <?php echo $profile['prezime']; ?></p>
                    <p><strong>Email:</strong> <?php echo $profile['email']; ?></p>
                    <p><strong>Životopis:</strong> <?php echo $profile['zivotopis']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </body>
</html>