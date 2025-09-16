<!DOCTYPE html>
<?php
 class ispisi {
private $naslov1;
private $naslov2;

public function __construct($naslov1,$naslov2)
{
    $this->naslov1=$naslov1;
    $this->naslov2=$naslov2;
}

    

public function naslov1()  {
    return $this->naslov1;
}
public function naslov2()  {
    return $this->naslov2;
}


 }
$naslovi = new ispisi("Ko smo mi?","O nama")

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="slike/favicon.ico">
    <title>o nama</title>
    <link rel="stylesheet" href="stil/style.css">
</head>
<body>
<div class="navigacioni-meni"><ul class="navigacija">
        <img src="slike/logo.png" id="logo" alt="padel-central">
        <li class="link"><a href="takmicenja.php">Takmicenja</a></li>
        <li class="link"><a href="onama.php">O nama</a></li>
        <li class="link"><a href="index.php" >Sta je padel?</a></li>
        <li class="link"><a href="zakazivanje.php">Zakazivanje</a></li>
    </ul>
</div>
<div class="ko-smo-mi">
    <h2><?= $naslovi->naslov1()   ?></h2>
    <p id="text-ko-smo-mi">“Padel centar“ je osnovan 2022.godine od strane diplomiranih profesora sporta i fizičkog vaspitanja sa ciljem promovisanja ove dinamične igre sa višestrukim benefitima po Srbiji.</p>
</div>
<div class="O-nama"><h2><?= $naslovi->naslov2()?></h2>
    <p>
Naša vizija podrazumeva podizanje padela na viši nivo kroz registraciju sportske grane, stvaranje kompetitivnih turnira, formiranje padel klubova i započinjanje škola padela po Srbiji. Želimo da okupimo sve entuzijaste i zaljubljenike u padel pod jedan krov i zajedno radimo na implementaciji strategije za dalji razvoj ovog budućeg olimpijskog sporta.

</p></div>

</body>
</html>