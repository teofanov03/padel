<?php
class Takmicenje {
    public $mesto;
    public $ime;
    public $datum;

    public function __construct($mesto, $ime, $datum) {
        $this->mesto = $mesto;
        $this->ime = $ime;
        $this->datum = $datum;
    }

    public function mesto() {
        return $this->mesto;
    }

    public function ime() {
        return $this->ime;
    }

    public function datum() {
        return $this->datum;
    }
}

$takmicenja = [
    new Takmicenje("Mexico city", "Cdmx open 2024", "12-14 april"),
    new Takmicenje("Buenos aires", "Buenor aires padel master", "12-14 november"),
    new Takmicenje("London", "London open 2025", "8-12 january"),
    new Takmicenje("Madrid", "Madrista 2025", "25-28 march")
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="slike/favicon.ico">
    <title>Takmicenja</title>
    <link rel="stylesheet" href="stil/style.css">
</head>
<body>
<div class="navigacioni-meni">
    <ul class="navigacija">
        <img src="slike/logo.png" id="logo" alt="padel-central">
        <li class="link"><a href="takmicenja.php">Takmicenja</a></li>
        <li class="link"><a href="onama.php">O nama</a></li>
        <li class="link"><a href="index.html">Sta je padel?</a></li>
        <li class="link"><a href="zakazivanje.php">Zakazivanje</a></li>
    </ul>
</div>
<div class="tabela">
    <table>
        <tr style="background-color: greenyellow;">
            <th>Mesto</th>
            <th>Ime</th>
            <th>Datum</th>
        </tr>
        <?php foreach ($takmicenja as $takmicenje): ?>
        <tr>
            <td style="background-color:green;"><?= htmlspecialchars($takmicenje->mesto()) ?></td>
            <td style="background-color:green;"><?= htmlspecialchars($takmicenje->ime()) ?></td>
            <td style="background-color:green;"><?= htmlspecialchars($takmicenje->datum()) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="u-forma">
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <p>Ako ste vec zakazali neki termin kod nas mozete ovde to promeniti</p>
        <p>Korisnicko ime</p>
        <input type="text" id="u-korisnicko_ime" name="u-korisnicko_ime">
        <p>Stari datum</p>
        <input type="date" id="stari_datum" name="stari_datum">
        <p>Staro vreme</p>
        <input type="time" id="staro_vreme" name="staro_vreme">
        <p>Novi datum</p>
        <input type="date" id="novi_datum" name="novi_datum">
        <p>Novo vreme</p>
        <input type="time" id="novo_vreme" name="novo_vreme">
        <br><br>
        <input type="submit" class="promena" value="Promeni" id="promena" name="promena">
    </form>
</div>
<?php
$uispis = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $promena = $_POST['promena'] ?? null;
    $ukorisnicko_ime = $_POST['u-korisnicko_ime'] ?? null;
    $staro_vreme = $_POST['staro_vreme'] ?? null;
    $stari_datum = $_POST['stari_datum'] ?? null;
    $novi_datum = $_POST['novi_datum'] ?? null;
    $novo_vreme = $_POST['novo_vreme'] ?? null;

    if ($promena) {
        if ($ukorisnicko_ime && $staro_vreme && $stari_datum && $novi_datum && $novo_vreme) {
            $mysqli = new mysqli("localhost", "root", "", "zakazivanje");

            if ($mysqli->connect_error) {
                die("Greska pri povezivanju: " . $mysqli->connect_error);
            }

            $mysqli->begin_transaction();

            try {
                $stmt = $mysqli->prepare(
                    "SELECT * FROM zakazivanja WHERE korisnicko_ime = ? AND datum = ? AND vreme = ?"
                );
                $stmt->bind_param("sss", $ukorisnicko_ime, $stari_datum, $staro_vreme);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $update_stmt = $mysqli->prepare(
                        "UPDATE zakazivanja SET datum = ?, vreme = ? WHERE korisnicko_ime = ? AND datum = ? AND vreme = ?"
                    );
                    $update_stmt->bind_param(
                        "sssss",
                        $novi_datum,
                        $novo_vreme,
                        $ukorisnicko_ime,
                        $stari_datum,
                        $staro_vreme
                    );
                    $update_stmt->execute();

                    $uispis = "Uspešna promena!";
                } else {
                    $uispis = "Termin nije pronađen.";
                }

                $mysqli->commit();
            } catch (Exception $e) {
                $mysqli->rollback();
                $uispis = "Greska pri promeni termina: " . $e->getMessage();
            } finally {
                $stmt->close();
                $mysqli->close();
            }
        } else {
            $uispis = "Sva polja su obavezna!";
        }
    }
}
?>

<div class="uispis"><?= htmlspecialchars($uispis) ?></div>
</body>
</html>
