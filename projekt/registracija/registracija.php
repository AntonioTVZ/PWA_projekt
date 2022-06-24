<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <meta charset="UTF-8">
        <style>
            .bojaPoruke{
                color: red;
            }
        </style>
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

                <section role="main">
                    <form method = "post" action = "">
                        <label for="ime">Ime: </label><br>
                        <input type="text" name="ime" id="ime" class="form-field-textual"><br>
                        <span id="porukaIme" class="bojaPoruke"></span><br>

                        <label for="prezime">Prezime: </label><br>
                        <input type="text" name="prezime" id="prezime" class="form-field-textual"><br>
                        <span id="porukaPrezime" class="bojaPoruke"></span><br>

                        <label for="username">Korisničko ime:</label><br>
                        <input type="text" name="username" id="username" class="form-field-textual"><br>
                        <span id="porukaUsername" class="bojaPoruke"></span><br>

                        <label for="pass">Lozinka: </label><br>
                        <input type="password" name="pass" id="pass" class="form-field-textual"><br>
                        <span id="porukaPass" class="bojaPoruke"></span><br>

                        <label for="passRep">Ponovite lozinku: </label><br>
                        <input type="password" name="passRep" id="passRep" class="form-field-textual"><br>
                        <span id="porukaPassRep" class="bojaPoruke"></span><br>

                        <button type="submit" value="Registriraj se" id="slanje" name="slanje">Registriraj se</button>
                    </form>
                </section>

                <script type="text/javascript">
                    document.getElementById("slanje").onclick = function(event) {
                        var slanjeForme = true;

                        // Ime korisnika mora biti uneseno
                        var poljeIme = document.getElementById("ime");
                        var ime = document.getElementById("ime").value;
                        if (ime.length == 0) {
                            slanjeForme = false;
                            poljeIme.style.border="1px dashed red";
                            document.getElementById("porukaIme").innerHTML="<br>Unesite ime!<br>";
                        }
                        else {
                            poljeIme.style.border="1px solid green";
                            document.getElementById("porukaIme").innerHTML="";
                        }


                        // Prezime korisnika mora biti uneseno
                        var poljePrezime = document.getElementById("prezime");
                        var prezime = document.getElementById("prezime").value;
                        if (prezime.length == 0) {
                            slanjeForme = false;
                            poljePrezime.style.border="1px dashed red";
                            document.getElementById("porukaPrezime").innerHTML="<br>Unesite Prezime!<br>";
                        }
                        else {
                            poljePrezime.style.border="1px solid green";
                            document.getElementById("porukaPrezime").innerHTML="";
                        }


                        // Korisničko ime mora biti uneseno
                        var poljeUsername = document.getElementById("username");
                        var username = document.getElementById("username").value;
                        if (username.length == 0) {
                            slanjeForme = false;
                            poljeUsername.style.border="1px dashed red";
                            document.getElementById("porukaUsername").innerHTML="<br>Unesite korisničko ime!<br>";
                        }
                        else {
                            poljeUsername.style.border="1px solid green";
                            document.getElementById("porukaUsername").innerHTML="";
                        }


                        // Provjera podudaranja lozinki
                        var poljePass = document.getElementById("pass");
                        var pass = document.getElementById("pass").value;
                        var poljePassRep = document.getElementById("passRep");
                        var passRep = document.getElementById("passRep").value;
                        if (pass.length == 0 || passRep.length == 0 || pass != passRep) {
                            slanjeForme = false;
                            poljePass.style.border="1px dashed red";
                            poljePassRep.style.border="1px dashed red";
                            document.getElementById("porukaPass").innerHTML="<br>Lozinke nisu iste!<br>";
                            document.getElementById("porukaPassRep").innerHTML="<br>Lozinke nisu iste!<br>";
                        }
                        else {
                            poljePass.style.border="1px solid green";
                            poljePassRep.style.border="1px solid green";
                            document.getElementById("porukaPass").innerHTML="";
                            document.getElementById("porukaPassRep").innerHTML="";
                        }
                        if (slanjeForme != true) {
                            event.preventDefault();
                        }
                    };
                </script>


                <?php

                if (isset($_POST['ime'])){
                    $ime = $_POST['ime'];
                }
                if (isset($_POST['prezime'])){
                    $prezime = $_POST['prezime'];
                }
                if (isset($_POST['username'])){
                    $korisnicko = $_POST['username'];
                }
                if (isset($_POST['pass'])){
                    $sifra = $_POST['pass'];
                    $hashed_sifra = password_hash($sifra, CRYPT_BLOWFISH);
                }
                if (isset($_POST['passRep'])){
                    $sifra2 = $_POST['passRep'];
                    $hashed_sifra2 = password_hash($sifra2, CRYPT_BLOWFISH);
                }
                
                $level = 0;

                if(isset($_POST['slanje'])){
                    $dbc = mysqli_connect('localhost', 'root', '' ,'projekt') or die('error connecting'.mysqli_connect_error());
                    //Provjera postoji li u bazi već korisnik s tim korisničkim imenom
                    $sql = "SELECT korisnicko FROM korisnik WHERE korisnicko = ?";
                    $stmt = mysqli_stmt_init($dbc);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, 's', $korisnicko);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                    }

                    if(mysqli_stmt_num_rows($stmt) > 0){
                        echo 'Korisničko ime već postoji!';
                        echo "<br>";
                    }
                    else{
                        // Ako ne postoji korisnik s tim korisničkim imenom - Registracija korisnika u bazi pazeći na SQL injection
                        $sql = "INSERT INTO korisnik (ime, prezime, korisnicko, sifra, level)VALUES (?, ?, ?, ?, ?)";
                        $stmt = mysqli_stmt_init($dbc);
                        if (mysqli_stmt_prepare($stmt, $sql)) {
                            mysqli_stmt_bind_param($stmt, 'ssssi', $ime, $prezime, $korisnicko, $hashed_sifra, $level);
                            mysqli_stmt_execute($stmt);
                            $registriranKorisnik = true;
                        }
                        if($registriranKorisnik == true) {
                            echo '
                            <div style= "width: 100%;">
                                <p>Korisnik je uspješno registriran!</p>
                            </div>
                            ';
                            echo "<br>";
                            }
                    }
                    mysqli_close($dbc);
                }
                ?>
            </div>
        </main>
        <footer>
            <div class="container">
                <p>Copyright 2019 Mortenpost Velag Grolh</p>
            </div>
        </footer>
    </body>
</html>
        