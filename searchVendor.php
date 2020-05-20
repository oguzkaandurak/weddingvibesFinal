<?php


include "master/dbMaster.php";
$resultText = "";
$counter = 0;
if (isset($_GET["searchkey"])) {

    $searchKey = $_GET["searchkey"];

    $query = $db->prepare('SELECT * FROM serviceproviders WHERE vendor_name LIKE ? AND is_activated=1');
    $query->execute(array($searchKey . "%"));

    $counter = 0;
    while ($results = $query->fetch()) {
$query2 = $db->query("SELECT * FROM spimages WHERE sp_id = '{$results["id"]}'")->fetch(PDO::FETCH_ASSOC);


        $counter++;
        $resultText .= "<div class=\"col-sm-12 col-md-6 col-lg-4 p-b-50\">
                                    <!-- Block2 -->
                                    <div class=\"block2\">
                                        <div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
                                            <img src=\"" . $query2["photoUrl"] . "\" alt=\"IMG-PRODUCT\" style=\"min-height: 400px; object-fit: cover;\">

                                            <div class=\"block2-overlay trans-0-4\">
                                                <a href=\"productDetails.php?id=" . $results["id"] . "\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">

                                                </a>

                                                <div class=\"block2-btn-addcart w-size1 trans-0-4\">
                                                    <!-- Button -->
                                                    <a href=\"productDetails.php?id=" . $results["id"] . "\" class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
                                                        Ürün Sayfası
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=\"block2-txt p-t-20\">
                                            <a href=\"productDetails.php?id=" . $results["id"] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
                                                " . $results["vendor_name"] . "
                                            </a>

                                            <span class=\"block2-price m-text6 p-r-5\">
										
									</span>
                                        </div>
                                    </div>
                                </div>";
    }


    echo $resultText."@".$counter;
}

if (isset($_GET["searchCat"])) {

    $searchCat = $_GET["searchCat"];

    $query = $db->prepare('SELECT * FROM serviceproviders WHERE category_id=?');
    $query->execute(array($searchCat));
    $counter = 0;
    while ($results = $query->fetch()) {
        $counter++;
        $resultText .= "<div class=\"col-sm-12 col-md-6 col-lg-4 p-b-50\">
                                    <!-- Block2 -->
                                    <div class=\"block2\">
                                        <div class=\"block2-img wrap-pic-w of-hidden pos-relative block2-labelnew\">
                                            <img src=\"" . $results["photoUrl"] . "\" alt=\"IMG-PRODUCT\">

                                            <div class=\"block2-overlay trans-0-4\">
                                                <a href=\"productDetails.php?id=" . $results["id"] . "\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">

                                                </a>

                                                <div class=\"block2-btn-addcart w-size1 trans-0-4\">
                                                    <!-- Button -->
                                                    <a href=\"productDetails.php?id=" . $results["id"] . "\" class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
                                                        Ürün Sayfası
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class=\"block2-txt p-t-20\">
                                            <a href=\"productDetails.php?id=" . $results["id"] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
                                                " . $results["vendor_name"] . "
                                            </a>

                                            <span class=\"block2-price m-text6 p-r-5\">
										
									</span>
                                        </div>
                                    </div>
                                </div>";
    }


    echo $resultText."@".$counter;
}