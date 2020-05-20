<?php

include "master/dbMaster.php";


if(isset($_GET["delId"])){

    $query = $db->prepare("DELETE FROM servicefav WHERE id = :id");
    $delete = $query->execute(array(
        'id' => $_GET["delId"]
    ));


}


if (session_status() == PHP_SESSION_NONE) {
    session_start();

    if (isset($_SESSION['user-mail'])) {
        $userMail = $_SESSION['user-mail'];
        $userName = $_SESSION['user-name'];
        $id = $_SESSION['id'];


        $queryForFav = $db->query("SELECT * FROM servicefav WHERE userId = " . $id, PDO::FETCH_ASSOC);



    } else {
        header('Location: login.php');
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
</head>
<body class="animsition">

<?php

include("includes/header.php")

?>


<section class="section-content" style="padding-top:30px;background-color: #fbf4f9;min-height: 600px;">
    <div class="container">

        <div class="row">
            <aside class="col-md-3">
                <ul class="list-group">
                    <a class="list-group-item" href="profile.php"> Hesap Bilgileri </a>
                    <a class="list-group-item active" href="#"> Favoriler </a>
                    <a class="list-group-item" href="userAppointments.php"> Randevularım </a>
                    <a class="list-group-item" href="login.php"> Çıkış Yap </a>
                </ul>
            </aside> <!-- col.// -->
            <main class="col-md-9">

                <article class="card  mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Favoriler </h5>

                        <div class="row">


                            <?php

                            if ($queryForFav->rowCount()) {
                                foreach ($queryForFav as $row) {

                                    $q2 = $db->prepare('SELECT * FROM serviceproviders WHERE id = ?');
                                    $q2->execute(array($row["serviceProviderId"]));
                                    $results = $q2->fetch();


                                    $queryforspimages = $db->query("SELECT * FROM spimages WHERE sp_id = '{$row["serviceProviderId"]}'")->fetch(PDO::FETCH_ASSOC);

                                    print "<div class=\"col-md-4\">";
                                    print "<figure class=\"itemside  mb-4\">";
                                    print "<div class=\"aside\"><a href='productDetails.php?id=".$results["id"]."'><img style='width:100%' src=\"".$queryforspimages["photoUrl"]."\" class=\"border img-sm imageCorrect\"></a>";

                                    print "</div>";
                                    print "<figcaption class=\"info\">";
                                    print "<time class=\"text-muted\">".$results["vendor_name"]."</time>";
                                    print "</figcaption>";
                                    print "</figure>";
                                    print "<a class='btn btn-danger' style='width: 100%' href='userFav.php?delId=".$row["id"]."'> Favoriden Sil</a>";
                                    print "</div>";
                                }
                            }

                            ?>

                        </div> <!-- row.// -->


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

</body>
</html>
