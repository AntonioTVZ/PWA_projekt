<!DOCTYPE html>

<?php

    include 'connect2.php';

    $picture = $_FILES['image']['name'];
    
    if(isset($_POST['title'])){
        $title = $_POST['title'];
    }

    if(isset($_POST['about'])){
        $about = $_POST['about'];
    }

    if(isset($_POST['content'])){
        $content = $_POST['content'];
    }

    if(isset($_POST['category'])){
        $category = $_POST['category'];
    }

    $date=date('d.m.Y.');

    if(isset($_POST['archive'])){
        $archive=1;
    }
    else{
        $archive=0;
    }

    $target_dir = 'img/'.$picture;
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir);

    $query = "INSERT INTO clanci (datum, naslov, sazetak, tekst, slika, kategorija,
    arhiva ) VALUES ('$date', '$title', '$about', '$content', '$picture',
    '$category', '$archive')";

    $result = mysqli_query($dbc, $query) or die('Error querying databese.');
    mysqli_close($dbc);

?>


<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <meta charset="UTF-8">
    </head>
    <body>
        <header>
            <div class="container">
                <nav>
                    <div class="posebni" style="height: 80px; width: 140px;" >
                        <a href="../index/index.php"><img style="width:100%; height: 100%;"  alt="logo"  src="logo.png"></a>
                    </div>
                    <ul>
                        <li><a href="../index/index.php">HOME</a></li>
                        <li><a href="../kategorija/kategorija.php?kategorija=sport">SPORT</a></li>
                        <li><a href="../kategorija/kategorija.php?kategorija=glazba">GLAZBA</a></li>
                        <li><a href="../administracija/administracija.php">ADMINISTRACIJA</a></li>
                        <li><a href="#">UNOS</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <div class="container">
                <section role="main">
                    <div class="row">
                        <p class="category"><?php echo $category;?></p>
                        <h1 class="title"><?php echo $title;?></h1>
                        <p>AUTOR:</p>
                        <p>OBJAVLJENO:</p>
                    </div>
                    <section class="slika">
                        <?php echo "<img style = 'width: 100%;' src='img/$picture'";?>
                    </section>
                    <section class="about">
                        <p><?php echo $about;?></p>
                    </section>
                    <section class="sadrzaj">
                        <p><?php echo $content;?></p>
                    </section>
                </section>
            </div>
        </main>
        <footer>
            <div class="container">
                <p>Copyright 2019 Mortenpost Velag Grolh</p>
            </div>
        </footer>
    </body>
</html>
