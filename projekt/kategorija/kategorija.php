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
                    <div class="posebni" >
                        <a href="../index/index.php"><img  alt="logo"  src="logo.png"></a>
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

                    $kategorija = $_GET['kategorija'];
                    include 'connect2.php';
                    define('UPLPATH', '../unos/img/');

                    if($kategorija == "sport"){
                        $kat = "SPORT";
                    }
                    else{
                        $kat = "GLAZBA";
                    }
                ?>
                <section class="sport">
                    <h1><?php echo $kat; ?></h1>
                    <?php
                        $query = "SELECT * FROM clanci WHERE arhiva=0 AND kategorija='$kategorija'";
                        $result = mysqli_query($dbc, $query);
                        while($row = mysqli_fetch_array($result)) {
                            echo '<article>';
                            echo'<div class="article">';
                            echo '<div class="sport_img">';
                            echo '<img src="' . UPLPATH . $row['slika'] . '"';
                            echo '</div>';
                            echo '<div class="media_body">';
                            echo '<h4 class="title">';
                            echo '<a href="../clanak/clanak.php?id='.$row['id'].'">';
                            echo $row['naslov'];
                            echo '</a></h4>';
                            echo '</div></div>';
                            echo '</article>';
                        }
                    ?>
                </section>
                <?php  mysqli_close($dbc); ?>
            </div>
        </main>
        <footer>
            <div class="container">
                <p>Copyright 2019 Mortenpost Velag Grolh</p>
            </div>
        </footer>
    </body>
</html>