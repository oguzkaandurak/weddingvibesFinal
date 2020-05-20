<?php

include "master/dbMaster.php";
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();

    if (isset($_SESSION['user-mail'])) {
        $userMail = $_SESSION['user-mail'];
        $userName = $_SESSION['user-name'];
        $id = $_SESSION['id'];
        $userType = $_SESSION['user-type'];
        $userAddress = $_SESSION['user-address'];

        $userPhone = $_SESSION['user-phone'];
        $query = $db->prepare('SELECT * FROM users WHERE id = ?');
        $query->execute(array($id));
        $results = $query->fetch();
    } else {
        header('Location: login.php');
    }

}


if (isset($_POST["updateProfile"])) {


    //File Upload Empty
    if ($_FILES["profilePhoto"]["error"] == 4) {


        $userNameUpd = $_POST["userName"];
        $userMailUpd = $_POST["userMail"];

        $adress = "";
        if (isset($_POST["userAdress"]))
            $adress = $_POST["userAdress"];


        $query = $db->prepare("UPDATE users SET name_surname = :sliderHeader, address = :sliderButtonHeader, phone = :sliderButtonUrl WHERE id = :id");
        $update = $query->execute(array(
            "sliderHeader" => $userNameUpd,
            "sliderButtonHeader" => $adress,
            "sliderButtonUrl" => $userMailUpd,
            "id" => $id
        ));
        if ($update) {
            //print "güncelleme başarılı!";

            $userName = $userNameUpd;
            $userAddress = $adress;
            $userPhone = $userMailUpd;
            header('Location: profile.php');
        }


    } else { //File Have Uploaded

        //echo "1";
        $directory = 'img_data/';
        $fileToUpload = $directory . generateRandomString() . basename($_FILES['profilePhoto']['name']);

        //echo '<pre>';
        if (move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $fileToUpload)) {
            $userNameUpd = $_POST["userName"];
            $userMailUpd = $_POST["userMail"];

            $adress = "";
            if (isset($_POST["userAdress"]))
                $adress = $_POST["userAdress"];


            $query = $db->prepare("UPDATE users SET name_surname = :sliderHeader, address = :sliderButtonHeader, phone = :sliderButtonUrl, photoUrl = :photoUrl WHERE id = :id");

            $update = $query->execute(array(

                "sliderHeader" => $userNameUpd,
                "sliderButtonHeader" => $adress,
                "sliderButtonUrl" => $userMailUpd,
                "photoUrl" => $fileToUpload,
                "id" => $id
            ));
            if ($update) {
                //print "güncelleme başarılı!";

                $userName = $userNameUpd;
                $userAddress = $adress;
                $userPhone = $userMailUpd;
                header('Location: profile.php');
            }

        } else {
            //echo "Olası dosya yükleme saldırısı!\n";
        }
        //echo 'Diğer hata ayıklama bilgileri:';
        //print_r($_FILES);
        //print "</pre>";


    }

}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.png"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/themify/themify-icons.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/elegant-font/html-css/style.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/lightbox2/css/lightbox.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->

    <style>
        .inputStyleCorrect {
            border: 1px solid rgba(0, 0, 0, .15) !important;
        }

        .custom-control-inputCorrect {
            border: 1px solid rgba(0, 0, 0, .15) !important;
        }

        .btn-bgCorrect {
            background-color: #e65540 !important;
            border-color: #e65540 !important;
        }
    </style>
</head>
<body class="animsition">

<?php

include("includes/header.php")

?>


<section class="section-content" style="padding-top:30px; background-color: #fbf4f9;min-height: 600px;">
    <div class="container">

        <div class="row">
            <aside class="col-md-3">
                <ul class="list-group">
                    <a class="list-group-item active" href="#"> Hesap Bilgileri </a>
                    <?php
                    if ($userType == 1)
                        print "<a class=\"list-group-item\" href=\"userFav.php\"> Favoriler </a>";
                    if ($userType == 1)
                        print "<a class=\"list-group-item\" href=\"userAppointments.php\"> Randevularım </a>";
                    if ($userType == 2)
                        print "<a class=\"list-group-item\" href=\"addService.php\"> Servis Ekle </a>";
                    if ($userType == 2)
                        print "<a class=\"list-group-item\" href=\"myServices.php\"> Servislerim </a>";
                    ?>
                    <a class="list-group-item" href="login.php"> Çıkış Yap </a>
                </ul>
            </aside> <!-- col.// -->
            <main class="col-md-9">

                <article class="card mb-3" id="profileShowArticle">
                    <div class="card-body" style="min-height: 400px">

                        <figure class="icontext">
                            <div class="icon">
                                <img class="rounded-circle img-sm border" style="max-width: 100px;"
                                     src="<?php echo $results["photoUrl"] ?>">
                            </div>
                            <div class="text">
                                <strong> <?php echo $results["name_surname"] ?> </strong> <br>
                                <?php echo $results["email"] ?> <br>
                            </div>
                        </figure>
                        <hr>
                        <p>
                            <i class="fa fa-map-marker text-muted"></i> &nbsp; Telefon:
                            <br>
                            <?php echo $results["phone"] ?> &nbsp;
                        </p>

                        <?php

                        if ($userType == 2) {
                            print "<p> <i class=\"fa fa-map text-muted\"></i> &nbsp; Address:<br>";
                            echo $results["address"];
                            print "</p>";
                        }
                        ?>
                        <a href="#" class="btn btn-info" id="editProfile"> Düzenle</a>
                    </div> <!-- card-body .// -->
                </article> <!-- card.// -->

                <article class="card mb-3" id="profileEditArticle" style="display:none;">
                    <div class="card-body" style="min-height: 400px">


                        <form action="" method="post" enctype='multipart/form-data'>


                            <div class="form-group">
                                <?php

                                if ($userType == 1)
                                    print "<label for=\"inputAddress\">Ad Soyad</label>";
                                else if ($userType == 2)
                                    print "<label for=\"inputAddress\">Firma Adı</label>";
                                ?>

                                <input type="text" name="userName" class="form-control inputStyleCorrect"
                                       id="inputAddress"
                                       value="<?php echo $results["name_surname"]  ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="inputAddress">Telefon</label>
                                <input type="text" name="userMail" class="form-control inputStyleCorrect"
                                       id="inputAddress"
                                       value="<?php echo $results["phone"]; ?>" required>
                            </div>


                            <?php

                            if ($userType == 2){

                                print "<div class=\"form-group\">";
                                print "<label for=\"exampleFormControlTextarea1\">Adres</label>";
                                print "<textarea class=\"form-control inputStyleCorrect\" name=\"userAdress\"
                                          id=\"exampleFormControlTextarea1\" rows=\"3\" required>".$results["address"]."</textarea>";
                                print "</div>";

                            }


                            ?>





                            <div class="form-group">
                                <label for="exampleFormControlFile1">Fotoğraf Seçiniz</label>
                                <input type="file" name="profilePhoto" class="form-control-file"
                                       id="exampleFormControlFile1">
                            </div>


                            <input type="submit" name="updateProfile" class="btn btn-primary btn-bgCorrect"
                                   value="Profili Güncelle">
                        </form>

                    </div> <!-- card-body .// -->
                </article> <!-- card.// -->

            </main> <!-- col.// -->
        </div>

    </div> <!-- container .//  -->
</section>

<?php
include("includes/footer.php")

?>


<!-- Back to top -->
<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
</div>

<!-- Container Selection1 -->
<div id="dropDownSelect1"></div>


<!--===============================================================================================-->
<script type="text/javascript" src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/bootstrap/js/popper.js"></script>
<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/select2/select2.min.js"></script>
<script type="text/javascript">
    $(".selection-1").select2({
        minimumResultsForSearch: 20,
        dropdownParent: $('#dropDownSelect1')
    });
</script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/slick/slick.min.js"></script>
<script type="text/javascript" src="js/slick-custom.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/lightbox2/js/lightbox.min.js"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript">
    $('.block2-btn-addcart').each(function () {
        var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
        $(this).on('click', function () {
            swal(nameProduct, "is added to cart !", "success");
        });
    });

    $('.block2-btn-addwishlist').each(function () {
        var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
        $(this).on('click', function () {
            swal(nameProduct, "is added to wishlist !", "success");
        });
    });
</script>

<!--===============================================================================================-->
<script src="js/main.js"></script>


<script>

    $("#editProfile").click(function () {


        $("#profileShowArticle").css("display", "none");
        $('#profileEditArticle').css("display", "block");


    });

</script>

</body>
</html>
