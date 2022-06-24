<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <meta charset="UTF-8">
    </head>
    <body>
        <header>
            <div class="container">
                <nav>
                    <div class="posebni">
                        <a href="../index/index.php"><img alt="logo"  src="logo.png"></a>
                    </div>
                    <ul>
                        <li><a href="../index/index.php">HOME</a></li>
                        <li><a href="../kategorija/kategorija.php?kategorija=sport">SPORT</a></li>
                        <li><a href="../kategorija/kategorija.php?kategorija=glazba">GLAZBA</a></li>
                        <li><a href="../administracija/administracija.php">ADMINISTRACIJA</a></li>
                        <li><a href="../unos/unos.html">UNOS</a></li>
                        <li><a href="../registracija/registracija.php">REGISTRACIJA</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <div class="container">
                <?php

                    $id = $_GET['id'];
                    include 'connect2.php';
                    define('UPLPATH', '../unos/img/');

                    $query = "SELECT * FROM clanci WHERE arhiva= 0 AND id = '$id'";
                    $result = mysqli_query($dbc, $query);
                    $i=0;
                    while($row = mysqli_fetch_array($result)) {
                        $kategorija = $row['kategorija'];
                        $naslov = $row['naslov'];
                        $datum = $row['datum'];
                        $slika = $row['slika'];
                        $sazetak = $row['sazetak'];
                        $tekst = $row['tekst'];
                    }

                ?>

                <section role="main">
                    <div class="row">
                        <h2 class="category"><?php echo "<span>".$kategorija."</span>";?></h2>
                        <h1 class="title"><?php echo $naslov;?></h1>
                        <p>AUTOR:</p>
                        <p>OBJAVLJENO: <?php echo "<span>".$datum."</span>";?></p>
                    </div>
                    <section class="slika">
                        <?php echo '<img src="' . UPLPATH . $slika . '">';?>
                    </section>
                    <section class="about">
                        <p><?php echo "<i>".$sazetak."</i>";?></p>
                    </section>
                    <section class="sadrzaj">
                        <p><?php echo $tekst;?></p>
                    </section>
                </section>
                <?php mysqli_close($dbc); ?>
            </div>
        </main>
        <footer>
            <div class="container">
                <p>Copyright 2019 Mortenpost Velag Grolh</p>
            </div>
        </footer>
    </body>
</html>

