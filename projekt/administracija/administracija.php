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
                        <a href="../index/index.php"><img alt="logo" src="logo.png"></a>
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

                    $uspjesnaPrijava = false;
                    session_start();
                    $dbc = mysqli_connect('localhost', 'root', '' ,'projekt') or die('error connecting'.mysqli_connect_error());
                    // Putanja do direktorija sa slikama
                    define('UPLPATH', '../unos/img/');

                    // Provjera da li je korisnik došao s login forme
                    if (isset($_POST['prijava'])) {
                        // Provjera da li korisnik postoji u bazi uz zaštitu od SQL injectiona
                        $prijavaImeKorisnika = $_POST['username'];
                        $prijavaLozinkaKorisnika = $_POST['pass'];
                        $sql = "SELECT korisnicko, sifra, level FROM korisnik WHERE korisnicko = ?";
                        $stmt = mysqli_stmt_init($dbc);
                        if (mysqli_stmt_prepare($stmt, $sql)) {
                            mysqli_stmt_bind_param($stmt, 's', $prijavaImeKorisnika);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_store_result($stmt);
                        }

                        mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
                        mysqli_stmt_fetch($stmt);

                        //Provjera lozinke
                        if (password_verify($_POST['pass'], $lozinkaKorisnika) && mysqli_stmt_num_rows($stmt) > 0) {
                            $uspjesnaPrijava = true;

                            // Provjera da li je admin
                            if($levelKorisnika == 1) {
                                $admin = true;
                            }
                            else {
                                $admin = false;
                            }
                            
                            //postavljanje session varijabli
                            $_SESSION['username'] = $imeKorisnika;
                            $_SESSION['level'] = $levelKorisnika;
                        }
                        else {
                            $uspjesnaPrijava = false;
                        }
                    }
                    
                    // Pokaži stranicu ukoliko je korisnik uspješno prijavljen i administrator je
                    if (($uspjesnaPrijava == true && $admin == true) || (isset($_SESSION['username'])) && $_SESSION['level'] == 1) {
                        
                        $query = "SELECT * FROM clanci";
                        $result = mysqli_query($dbc, $query);
                        while($row = mysqli_fetch_array($result)) {
                            echo '<form enctype="multipart/form-data" action="" method="POST">
                            <div class="form-item">
                            <label for="title">Naslov clanka:</label>
                            <div class="form-field">
                            <input type="text" name="title" class="form-field-textual"
                            value="'.$row['naslov'].'">
                            </div>
                            </div>
                            <div class="form-item">
                            <label for="about">Kratki sadržaj clanka (do 50
                            znakova):</label>
                            <div class="form-field">
                            <textarea name="about" id="" cols="30" rows="10" class="form-
                            field-textual">'.$row['sazetak'].'</textarea>
                            </div>
                            </div>
                            <div class="form-item">
                            <label for="content">Sadržaj clanka:</label>
                            <div class="form-field">
                            <textarea name="content" id="" cols="30" rows="10" class="form-
                            field-textual">'.$row['tekst'].'</textarea>
                            </div>
                            </div>
                            <div class="form-item">
                            <label for="image">Slika:</label>
                            <div class="form-field">
                            <input type="file" class="input-text" id="image"
                            value="'.$row['slika'].'" name="image"/> <br><img src="' . UPLPATH .
                            $row['slika'] . '" width=100px>
                            </div>
                            </div>
                            <div class="form-item">
                            <label for="category">Kategorija clanka:</label>
                            <div class="form-field">
                            <select name="category" id="" class="form-field-textual"
                            value="'.$row['kategorija'].'">
                            <option value="sport">Sport</option>
                            <option value="glazba">Glazba</option>
                            </select>
                            </div>
                            </div>
                            <div class="form-item">
                            <label>Spremiti u arhivu:
                            <div class="form-field">';
                            if($row['arhiva'] == 0) {
                            echo '<input type="checkbox" name="archive" id="archive"/>
                            Arhiviraj?';
                            } else {
                            echo '<input type="checkbox" name="archive" id="archive"
                            checked/> Arhiviraj?';
                            }
                            echo '</div>
                            </label>
                            </div>
                            <!-- </div> -->
                            <div class="form-item">
                            <input type="hidden" name="id" class="form-field-textual"
                            value="'.$row['id'].'">
                            <button type="reset" value="Poništi">Poništi</button>
                            <button type="submit" name="update" value="Prihvati">
                            Izmjeni</button>
                            <button type="submit" name="delete" value="Izbriši">
                            Izbriši</button>
                            </div>
                            </form>';

                            
                        }
                        
                        if(isset($_POST['delete'])){
                            $id=$_POST['id'];
                            $query = "DELETE FROM clanci WHERE id=$id ";
                            $result = mysqli_query($dbc, $query);

                        }

                        if(isset($_POST['update'])){
                            $picture = $_FILES['image']['name'];
                            $title=$_POST['title'];
                            $about=$_POST['about'];
                            $content=$_POST['content'];
                            $category=$_POST['category'];
                            if(isset($_POST['archive'])){
                            $archive=1;
                            }else{
                            $archive=0;
                            }
                            $target_dir = 'img/'.$picture;
                            move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir);
                            $id=$_POST['id'];
                            $query = "UPDATE clanci SET naslov='$title', sazetak='$about', tekst='$content',
                            slika='$picture', kategorija='$category', arhiva='$archive' WHERE id=$id ";
                            $result = mysqli_query($dbc, $query);
                            
                        }
                        mysqli_close($dbc);

                        // Pokaži poruku da je korisnik uspješno prijavljen, ali nije administrator
                        }
                        else if ($uspjesnaPrijava == true && $admin == false) {
                            echo '<p>Bok ' . $imeKorisnika . '! Uspješno ste prijavljeni, ali niste administrator.</p>';
                        }
                        else if (isset($_SESSION['$username']) && $_SESSION['$level'] == 0) {
                            echo '<p>Bok ' . $_SESSION['$username'] . '! Uspješno ste prijavljeni, ali niste administrator.</p>';
                        }
                        else if ($uspjesnaPrijava == false) {
                            echo '
                                <section role="main">
                                    <form method = "post" action = "">
                                        <label for="content">Korisničko ime:</label><br>
                                        <input type="text" name="username" id="username" class="form-field-textual"><br>
                                        <span id="porukaUsername" class="bojaPoruke"></span><br>
                                        <label for="pass">Lozinka: </label><br>
                                        <input type="password" name="pass" id="pass" class="form-field-textual"><br>
                                        <span id="porukaPass" class="bojaPoruke"></span><br>
                                        <button type="submit" value="Prijava" name="prijava"  id="slanje">Prijava</button>
                                    </form>
                                </section>

                                <script type="text/javascript">
                                    document.getElementById("slanje").onclick = function(event) {
                                        var slanjeForme = true;

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
                                        if (pass.length == 0) {
                                            slanjeForme = false;
                                            poljePass.style.border="1px dashed red";
                                            document.getElementById("porukaPass").innerHTML="<br>Lozinka mora biti unesena!<br>";
                                        }
                                        else {
                                            poljePass.style.border="1px solid green";
                                            document.getElementById("porukaPass").innerHTML="";
                                        }
                                        if (slanjeForme != true) {
                                            event.preventDefault();
                                        }
                                    };
                                </script>
                            ';
                            if(isset($_POST['prijava'])){
                                echo "Korinsik ne postoji";
                                echo "<br>";
                                echo '
                                    <div style = "width: 100%;">

                                        <a href = "../registracija/registracija.php">Registriraj se!</a>

                                    </div>
                                    
                                ';
                            }
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

