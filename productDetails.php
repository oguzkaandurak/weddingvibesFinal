<?php


include "master/dbMaster.php";


if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $query = $db->prepare('SELECT * FROM serviceproviders WHERE id = ?');
    $query->execute(array($id));
    $results = $query->fetch();

    $datesResult = "";

    $queryForDates = $db->query("SELECT * FROM spdates WHERE sp_id = '{$id}'", PDO::FETCH_ASSOC);
    if ( $queryForDates->rowCount() ){
        foreach( $queryForDates as $row ){
            $datesResult .= "\"".$row["dateData"]."\",";
        }
    }
    $datesResult = substr($datesResult, 0, -1);

    //echo "selam ".$results["user_id"];


    $query23 = $db->prepare('SELECT * FROM users WHERE id = ?');
    $query23->execute(array($results["user_id"]));
    $resultsForUser = $query23->fetch();

    //print_r($resultsForUser["firebaseUID"]);

    $firebase=$resultsForUser["firebaseUID"];
    $queryForImages = $db->query("SELECT * FROM spimages WHERE sp_id = " . $id, PDO::FETCH_ASSOC);

}


$count = 0;
$serviceId = $_GET["id"];
$boolCheck = false;
$userId = null;
if (session_status() == PHP_SESSION_NONE) {
    session_start();

    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];
        $boolCheck = true;
    } else {
        $boolCheck = false;

    }

}


$q2 = $db->prepare("SELECT * FROM servicefav where serviceProviderId=:spID and userId=:uID");
$q2->execute(array(
    'spID' => $serviceId,
    'uID' => $userId
));
$count = $q2->rowCount();
if (isset($_GET["id"]) && isset($_GET["addFav"])) {


    $userId = $_SESSION['id'];
    $serviceId = $_GET["id"];

    $q2 = $db->prepare("SELECT * FROM servicefav where serviceProviderId=:spID and userId=:uID");
    $q2->execute(array(
        'spID' => $serviceId,
        'uID' => $userId
    ));

    $count = $q2->rowCount();

    if ($count == 1) {
        $silData = $db->prepare("DELETE from servicefav where serviceProviderId=:spID AND userId=:uID");
        $update = $silData->execute(array(
            'spID' => $serviceId,
            'uID' => $userId

        ));

        header("Refresh:0; url=productDetails.php?id=" . $serviceId);
    } else {
        $queryInsert = $db->prepare("INSERT INTO servicefav SET serviceProviderId = ?, userId = ?");
        $insert = $queryInsert->execute(array($serviceId, $userId));
        if ($insert) {

            header("Refresh:0; url=productDetails.php?id=" . $serviceId);
        }
    }
}


$queryForComments = $db->query("SELECT * FROM comments WHERE serviceProviderId = " . $serviceId, PDO::FETCH_ASSOC);


if(isset($_POST["askForDate"])){


    $userId = $_SESSION['id'];
    $serviceId = $_GET["id"];
    $productDates = $_POST["productDates"];



    $queryInsert = $db->prepare("INSERT INTO userappointments SET dateData = ?, sp_id = ?, user_id = ?, is_active = ?");
    $insert = $queryInsert->execute(array($productDates, $serviceId, $userId, 0));
    if ($insert) {

        //header("Refresh:0; url=productDetails.php?id=" . $serviceId);
    }


}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ürün Detayı</title>
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
    <link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="includes/jquery-ui.multidatespicker.css">


    <style>
        .inputStyleCorrect{border: 1px solid rgba(0,0,0,.15) !important;}
        .custom-control-inputCorrect{border: 1px solid rgba(0,0,0,.15) !important;}
        .btn-bgCorrect{
            background-color: #e65540 !important;
            border-color: #e65540 !important;
        }
    </style>
    <style type="text/css">
        #map{ width:100%; height: 300px; }
    </style>
</head>
<body class="animsition">

<?php

include("includes/header.php");

?>

<!-- breadcrumb -->


<!-- Product Detail -->
<div class="container bgwhite p-t-35 p-b-80">
    <div class="flex-w flex-sb">
        <div class="w-size13 p-t-30 respon5">
            <div class="wrap-slick3 flex-sb flex-w">
                <div class="wrap-slick3-dots"></div>

                <div class="slick3">

                    <?php
                    if ($queryForImages->rowCount()) {
                        foreach ($queryForImages as $row) {

                            print "<div class=\"item-slick3\" data-thumb=\"".$row["photoUrl"]."\">";
                            print "<div class=\"wrap-pic-w\">";
                            print "<img src=\"" . $row["photoUrl"] . "\" alt=\"IMG-PRODUCT\">";
                            print "</div>";
                            print "</div>";
                        }
                    }

                    ?>

                </div>
            </div>
        </div>

        <div class="w-size14 p-t-30 respon5">
            <h4 class="product-detail-name m-text16 p-b-13">
                <?php echo $results["vendor_name"]; ?>
            </h4>

           

            <p class="s-text8 p-t-10">
                <?php echo $resultsForUser["name_surname"]; ?>
            </p>


            <span class="m-text17">
                <a href="messages.php?spfbUID=<?php print "".$firebase.""; ?>">Fiyat için iletişime geçiniz.</a>
            </span>
            <!--  -->
            <div class="p-t-33 p-b-60">


                <div class="flex-r-m flex-w p-t-10" style="width: 50%;">
                    <?php
                    if (isset($_SESSION['id'])) {

                        if ($_SESSION["user-type"] == 1)
                        {

                            print "<form method='POST' action='' style='width: 100%'>";
                            print "
                                
                                <input type=\"text\" name=\"productDates\" style='margin-bottom: 10px;' class=\"form-control inputStyleCorrect\" id=\"productDates\"
                                       placeholder=\"Tarih Seçiniz\" value=\"\">
                            ";
                            print "<input type=\"submit\" name=\"askForDate\" style='margin-bottom: 10px;' class=\"flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4\" value=\"Randevu Al\">";
                            print "</form>";
                        }




                        if ($count == 1 && $_SESSION["user-type"] == 1)
                            echo "<a style=\"background:black;\" class=\"flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4\" href=\"productDetails.php?id=" . $id . "&addFav\">Favoriden Çıkar</a>";
                        else if ($count == 0 && $_SESSION["user-type"] == 1)
                            echo "<a class=\"flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4\"  href=\"productDetails.php?id=" . $id . "&addFav\">Favoriye Ekle</a>";




                    }
                    ?>


                    

                    <a class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4"></a>
                    <div class="w-size16 flex-m flex-w">

                        <div class="btn-addcart-product-detail size9 trans-0-4 m-t-10 m-b-10">
                            <!-- Button -->

                        </div>
                    </div>
                </div>
            </div>


            <!--  -->
            <div class="wrap-dropdown-content bo6 p-t-15 p-b-14 active-dropdown-content">
                <h5 class="js-toggle-dropdown-content flex-sb-m cs-pointer m-text19 color0-hov trans-0-4">
                    Açıklama
                    <i class="down-mark fs-12 color1 fa fa-minus dis-none" aria-hidden="true"></i>
                    <i class="up-mark fs-12 color1 fa fa-plus" aria-hidden="true"></i>
                </h5>

                <div class="dropdown-content dis-none p-t-15 p-b-23">
                    <p class="s-text8">
                        <?php echo $results["description"] ?>
                    </p>
                </div>
            </div>


            <div class="wrap-dropdown-content bo7 p-t-15 p-b-14">
                <h5 class="js-toggle-dropdown-content flex-sb-m cs-pointer m-text19 color0-hov trans-0-4">
                    Yorumlar
                    <i class="down-mark fs-12 color1 fa fa-minus dis-none" aria-hidden="true"></i>
                    <i class="up-mark fs-12 color1 fa fa-plus" aria-hidden="true"></i>
                </h5>

                <div class="dropdown-content dis-none p-t-15 p-b-23">
                    <div class="row" id="result">
                        <?php

                        if ($queryForComments->rowCount()) {
                            foreach ($queryForComments as $row) {

                                $q2 = $db->prepare('SELECT * FROM users WHERE id = ?');
                                $q2->execute(array($row["userId"]));
                                $resultsForCommentUser = $q2->fetch();


                                ?>

                                <div class="col-md-12">
                                    <hr>
                                    <h1 style="font-size: 15px; padding-bottom: 15px;"><?php echo $resultsForCommentUser["name_surname"]; ?></h1>
                                    <span style="margin: 10px; color: #888888;"><?php echo $row["comment"]; ?></span>

                                </div>

                                <?php
                            }

                        }
                        ?>


                    </div>

                </div>
            </div>
        </div>


    </div>
    <?php if ($boolCheck && $_SESSION["user-type"] == 1) { ?>
        <div class="flex-w flex-sb">
            <div class="w-size13 p-t-30 respon5">

            </div>
            <div class="w-size14 p-t-30 respon5">
                <div class="form-group">
                    <textarea id="comment" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    <button id="sendComment" class="form-control btn-primary"
                            style="background-color: #e65540;border-color: #e65540; margin-top: 15px">Yorum Yap
                    </button>
                </div>
            </div>
        </div>
    <?php } ?>

<div class="flex-w flex-sb">
            <div class="w-size13 p-t-30 respon5">

            </div>
            <div class="w-size14 p-t-30 respon5">
                 <div id="map"></div>
            </div>
        </div>

</div>


<?php

include("includes/footer.php");


?>


<!-- Back to top -->
<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
</div>

<!-- Container Selection -->
<div id="dropDownSelect1"></div>
<div id="dropDownSelect2"></div>


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

    $(".selection-2").select2({
        minimumResultsForSearch: 20,
        dropdownParent: $('#dropDownSelect2')
    });
</script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/slick/slick.min.js"></script>
<script type="text/javascript" src="js/slick-custom.js"></script>
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

    $('.btn-addcart-product-detail').each(function () {
        var nameProduct = $('.product-detail-name').html();
        $(this).on('click', function () {
            swal(nameProduct, "is added to wishlist !", "success");
        });
    });


    $(document).ready(function () {
        $("#sendComment").click(function () {
            var value = $("#comment").val();


            $.ajax({
                url: "addComment.php?message=" + value + "&spID=" + <?php echo $serviceId;?>  +"&uID=" + <?php echo $userId;?>,
                success: function (result) {

                    $("#result").html(result);

                    swal("", "Yorumunuz Alındı", "success");
                    $("#comment").val("");
               
                }
            });
        });
    });
</script>

<!--===============================================================================================-->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script src="includes/jquery-ui.multidatespicker.js"></script>

<script type="text/javascript">
    $( function() {


        var arrayOfResult = [<?php echo $datesResult; ?>]

        //arrayOfResult.forEach(myFunction);

        var dates = [];

        for (x in arrayOfResult) {
            var trimmed = arrayOfResult[x].trim();

            //alert(trimmed);

            var result = trimmed.split("-");
            //alert(result);
            var d = new Date(result[2],result[1]-1,result[0]);
            dates.push(d);
        }



        //alert(dates);
        $( "#productDates" ).multiDatesPicker({
            maxPicks: 1,
            dateFormat: "dd-mm-yy",
            minDate:0,
            monthNames: [ "Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık" ],
            dayNamesMin: [ "Pa", "Pt", "Sl", "Ça", "Pe", "Cu", "Ct" ],
            firstDay:1,
            addDisabledDates: dates
        });


    } );
</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<script>

    //map.js

    //Set up some of our variables.
    var map; //Will contain map object.
    var marker = true; ////Has the user plotted their location marker?

    //Function called to initialize / create the map.
    //This is called when the page has loaded.
    function initMap() {

        //The center location of our map.
        var centerOfMap = new google.maps.LatLng(<?php echo $results["latlng"]; ?>);

        //Map options.
        var options = {
            center: centerOfMap, //Set center.
            zoom: 17 //The zoom value.
        };

        //Create the map object.
        map = new google.maps.Map(document.getElementById('map'), options);



        marker = new google.maps.Marker({
                    position: centerOfMap,
                    map: map,
                    draggable: true //make it draggable
                });


        //Listen for any clicks on the map.
        google.maps.event.addListener(map, 'click', function(event) {
            //Get the location that the user clicked.

            /*

            var clickedLocation = event.latLng;
            //If the marker hasn't been added.
            if(marker === false){
                //Create the marker.
                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map,
                    draggable: true //make it draggable
                });
                //Listen for drag events!
                google.maps.event.addListener(marker, 'dragend', function(event){
                    markerLocation();
                });
            } else{
                //Marker has already been added, so just change its location.
                marker.setPosition(clickedLocation);
            }
            //Get the marker's location.
            markerLocation();
            */

            return false;
        });
    }

    //This function will get the marker's current location and then add the lat/long
    //values to our textfields so that we can save the location.
    function markerLocation(){
        //Get location.
        var currentLocation = marker.getPosition();
        //Add lat and lng values to a field that we can save.
        document.getElementById('lat').value = currentLocation.lat(); //latitude
        document.getElementById('lng').value = currentLocation.lng(); //longitude
    }


    //Load the map when the page has finished loading.
    google.maps.event.addDomListener(window, 'load', initMap);

</script>

</body>
</html>
