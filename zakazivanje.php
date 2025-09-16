<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zakazivanje";
try{
$conn = mysqli_connect($servername, $username, $password, $dbname);
}
catch(mysqli_sql_exception){
echo "neuspesna konekcija";
}
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
} catch (PDOException $e) {
   echo "neuspesna konekcija";
}
$sql = "SELECT datum,vreme FROM zakazivanja ";
$rezultat = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZTVW0GPFMR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZTVW0GPFMR');
</script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js"></script>
    <link rel="icon" href="slike/favicon.ico">
    <link rel="stylesheet" href="stil/style.css">
    
    <title>Zakazivanje padela-Padel centar</title>
    <meta name="description" content="zakazivanje padela, sta je padel,igranje padela">
    <meta name="keywords" content="padel,padel novi sad,padel srbija,padel centar">
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
    
<div class="tabela_zakazanih" >
    <table>
        <tr><th colspan="2" style="background-color: greenyellow;">zakazani termini</th></tr>
        <tr><th style="background-color: greenyellow;">datum</th><th style="background-color: greenyellow;">vreme</th></tr>
        <?php
        if($rezultat->num_rows>0){
            while($row=$rezultat->fetch_assoc()){
                echo "<tr> <td style='background-color:green;'>". $row['datum'] . "</td>" . "<td style='background-color:green;'>" . $row['vreme']. "</td></tr>";
            }
        }
         ?>
    </table>
</div>
<p id="text-iznad-forme">Zakaži kod nas pomocu ove forme </p>
<div class="forma">
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="polja"><p>korisnicko_ime</p>
            <input type="text" id="korisnicko_ime" name="korisnicko_ime"></div>
        <div class="polja"><p>Broj telefona</p>
            <input type="text" id="broj_telefona" name="broj_telefona"></div>
        <div class="polja"><p>Email</p>
            <input type="text" id="email" name="email"></div>
        <div class="polja" ><p>Datum</p>
            <input type="date" id="datum" name="datum" ></div>
        <div class="polja"><p>Vreme</p>
            <input type="time" id="vreme" name="vreme"  ></div>
      <div class=""> <input type="submit" class="prijava" value="Zakaži" id="zakazivanje"></div>
        
    </form>
</div>
<?php
 $ispis = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $korisnicko_ime = $_POST['korisnicko_ime'] ?? null;
    $broj_telefona = $_POST['broj_telefona'] ?? null;
    $email = $_POST['email'] ?? null;
    $datum = $_POST['datum'] ?? null;
    $vreme = $_POST['vreme'] ?? null;

    if(empty($korisnicko_ime)||empty($broj_telefona)||empty($email)||empty($datum)||empty($vreme)){
        $ispis = "Sva polja moraju biti popunjena";

    }
    elseif(ctype_alnum($korisnicko_ime)===false){
        $ispis = "korisnicko ime mora biti spojeno i moze da se sastoji samo od slova i brojeva";
    }
    elseif(ctype_digit($broj_telefona)===false){
        $ispis = "broj telefona moze da se sastoji samo od brojeva";
    }
    elseif(filter_var($email,FILTER_VALIDATE_EMAIL)===FALSE){
        $ispis = "email nije validan";
    }
    else {
        try {
            
            $stmt = $conn->prepare(
                "INSERT INTO zakazivanja (korisnicko_ime, broj_telefona, email, datum, vreme) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            
           
            $stmt->bind_param("sssss", $korisnicko_ime, $broj_telefona, $email, $datum, $vreme);
            
           
            if ($stmt->execute()) {
                $ispis = "Uspešno zakazivanje!";
            } else {
                $ispis = "Neuspešno zakazivanje.";
            }
            
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $ispis = "Greška pri zakazivanju: " . $e->getMessage();
        }
    }
}


mysqli_close($conn);
?>

<div class="ispis-forme">
    
<p><?= htmlspecialchars($ispis); ?></p>
</div>
<h2 id="text-inzad-formi">Ako zelite da  otkazete termin popunite ovu formu</h2>

<div class="d-forma" >
<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <p>Za otkazivanje termina</p>
    <p>korisnicko ime</p>
<input type="text" id="d-korisnicko_ime" name="d-korisnicko_ime">
<p>datum kad ste zakazali</p>
<input type="date" id="d_datum" name="d_datum">
<p>vreme kad ste zakazali</p>
<input type="time" id="d_vreme" name="d_vreme"> <br> <br>
<input type="submit" class="promena" value="otkazi" id="otkazi" name="otkazi">
</form>
</div>
</div>
<?php
 $dispis = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['otkazi'])) {
  
    $dkorisnicko_ime = $_POST['d-korisnicko_ime'] ?? null;
    $d_datum = $_POST['d_datum'] ?? null;
    $d_vreme = $_POST['d_vreme'] ?? null;

   
    

    
    if ($dkorisnicko_ime && $d_datum && $d_vreme) {
        if(ctype_alnum($korisnicko_ime)===false){
            $dispis="korisnicko ime je spojeno i sastoji se samo od slova i brojeva";
        }
        else{
        try {
            
            $pdo = new PDO("mysql:host=localhost;dbname=zakazivanje", "root", "");
            $pdo->beginTransaction();
            
            $sql = "DELETE FROM zakazivanja 
                    WHERE datum = :datum 
                    AND korisnicko_ime = :korisnicko_ime 
                    AND vreme = :vreme";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":datum", $d_datum);
            $stmt->bindParam(":korisnicko_ime", $dkorisnicko_ime);
            $stmt->bindParam(":vreme", $d_vreme);

           
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $dispis = "Termin je uspešno otkazan.";

            } else {
                $dispis = "Nije pronađen termin za otkazivanje.";
            }
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $dispis = "Greška prilikom otkazivanja termina: " . $e->getMessage();
        }
    }
    } else {
        $dispis = "Sva polja su obavezna!";
    }
}
?>


<div class="uispis"><?= htmlspecialchars($dispis); ?></div>

</body>
</html>