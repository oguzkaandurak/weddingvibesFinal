<?php

if(!isset($_SESSION)) {
    ob_start();
    session_start();
}

include "master/dbMaster.php";



$query = $db->query("SELECT * FROM categories", PDO::FETCH_ASSOC);
$querySP = $db->query("SELECT * FROM serviceproviders WHERE is_activated=1", PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Anasayfa</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php

    include_once("includes/linkrel.php");

	?>

</head>
<body class="animsition">

	<!-- Header -->
    <?php

    include_once("includes/header.php");

    ?>


	<!-- Title Page -->
	<section class="bg-title-page p-t-50 p-b-40 flex-col-c-m" style="background-image: url(images/heading-pages-02.jpg);">
		<h2 class="l-text2 t-center">
			Anasayfa
		</h2>
		<p class="m-text13 t-center">

		</p>
	</section>


	<!-- Content page -->
	<section class="bgwhite" style="background-color: #fbf4f9;">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-3 p-b-50" style="background-color: #f6e5f5;
    padding-top: 40px;
    padding-left: 20px;">
					<div class="leftbar p-r-20 p-r-0-sm">

                        <div class="search-product pos-relative bo4 of-hidden" style="margin-bottom: 20px;">
                            <input class="s-text7 size6 p-l-23 p-r-50" type="text" id="searchBox" name="search-product" placeholder="Ara...">

                            <button class="flex-c-m size5 ab-r-m color2 color0-hov trans-0-4">
                                <i class="fs-12 fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>

                        <!--  -->
						<h4 class="m-text14 p-b-7">
							Kategoriler
						</h4>

						<ul class="p-b-54">

                            <?php
                            if ($query->rowCount()) {
                                foreach ($query as $row) {


                                    if (isset($_GET["categoryId"])){

                                        if ($_GET["categoryId"] == $row["id"]){

                                            print "<li class=\"p-t-4\"><a class=\"s-text13\" style='color: #111111' href=\"index.php?categoryId=". $row["id"]."\">". $row["category"]."<span></span></a></li>";


                                        }
                                        else{

                                            print "<li class=\"p-t-4\"><a class=\"s-text13\" href=\"index.php?categoryId=". $row["id"]."\">". $row["category"]."<span></span></a></li>";


                                        }

                                    }else{
                                        print "<li class=\"p-t-4\"><a class=\"s-text13\" href=\"index.php?categoryId=". $row["id"]."\">". $row["category"]."<span></span></a></li>";


                                    }


                                }
                            }
                            ?>
						</ul>

						<!--
						<h4 class="m-text14 p-b-32">
							Fltreler
						</h4>

						<div class="filter-price p-t-22 p-b-50 bo3">
							<div class="m-text15 p-b-17">
								Fiyat
							</div>

							<div class="wra-filter-bar">
								<div id="filter-bar"></div>
							</div>

							<div class="flex-sb-m flex-w p-t-16">
								<div class="w-size11">

									<button class="flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4">
										Filtrele
									</button>
								</div>

								<div class="s-text3 p-t-10 p-b-10">
									Aralık: $<span id="value-lower">610</span> - $<span id="value-upper">980</span>
								</div>
							</div>
						</div> -->

					</div>
				</div>

				<div class="col-sm-6 col-md-8 col-lg-9 p-b-50">
					<!--  -->
					<div class="flex-sb-m flex-w p-b-35">
						<div class="flex-w">
                            <!--
							<div class="rs2-select2 bo4 of-hidden w-size12 m-t-5 m-b-5 m-r-10">
								<select class="selection-2" name="sorting">
									<option>Sıralama Seçenekler</option>
									<option>Popülerlik</option>
									<option>Fiyat: azdan çoğa</option>
									<option>Price: çoktan aza</option>
								</select>
							</div>

							<div class="rs2-select2 bo4 of-hidden w-size12 m-t-5 m-b-5 m-r-10">
								<select class="selection-2" name="sorting">
									<option>Fiyat</option>
									<option>$0.00 - $50.00</option>
									<option>$50.00 - $100.00</option>
									<option>$100.00 - $150.00</option>
									<option>$150.00 - $200.00</option>
									<option>$200.00+</option>

								</select>
							</div>
							-->
						</div>

						<span class="s-text8 p-t-5 p-b-5" id="countResult">
                            <?php echo $querySP->rowCount() ?> sonuç gösteriliyor.
						</span>
					</div>

					<!-- Product -->
					<div id="result" class="row">



                        <?php
                        if ($querySP->rowCount()) {
                            foreach ($querySP as $row) {

                                $query = $db->query("SELECT * FROM spimages WHERE sp_id = '{$row["id"]}'")->fetch(PDO::FETCH_ASSOC);


                                ?>
                                <div class="col-sm-12 col-md-6 col-lg-4 p-b-50">
                                    <!-- Block2 -->
                                    <div class="block2">
                                        <div class="block2-img wrap-pic-w of-hidden pos-relative ">
                                            <img src="<?php echo $query["photoUrl"]?>" alt="IMG-PRODUCT" style="min-height: 400px; object-fit: cover;">

                                            <div class="block2-overlay trans-0-4">
                                                <a href="productDetails.php?id=<?php echo $row["id"]?>" class="block2-btn-addwishlist hov-pointer trans-0-4">

                                                </a>

                                                <div class="block2-btn-addcart w-size1 trans-0-4">
                                                    <!-- Button -->
                                                    <a href="productDetails.php?id=<?php echo $row["id"]?>" class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4">
                                                        Ürün Sayfası
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="block2-txt p-t-20">
                                            <a href="productDetails.php?id=<?php echo $row["id"]?>" class="block2-name dis-block s-text3 p-b-5">
                                                <?php echo $row["vendor_name"]?>
                                            </a>

                                            <span class="block2-price m-text6 p-r-5">
										<?php //echo $row["description"]?>
									</span>
                                        </div>
                                    </div>
                                </div>
                                <?php

                            }
                        }
                        ?>
					</div>


				</div>
			</div>
		</div>
	</section>


    <?php
     include_once("includes/footer.php")
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
	<script type="text/javascript" src="vendor/daterangepicker/moment.min.js"></script>
	<script type="text/javascript" src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/slick/slick.min.js"></script>
	<script type="text/javascript" src="js/slick-custom.js"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/sweetalert/sweetalert.min.js"></script>


<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/noui/nouislider.min.js"></script>
	<script type="text/javascript">
		/*[ No ui ]
	    ===========================================================*/
	    var filterBar = document.getElementById('filter-bar');

	    noUiSlider.create(filterBar, {
	        start: [ 50, 200 ],
	        connect: true,
	        range: {
	            'min': 50,
	            'max': 200
	        }
	    });

	    var skipValues = [
	    document.getElementById('value-lower'),
	    document.getElementById('value-upper')
	    ];

	    filterBar.noUiSlider.on('update', function( values, handle ) {
	        skipValues[handle].innerHTML = Math.round(values[handle]) ;
	    });
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>


    <script>


        $("#searchBox").on("propertychange change keyup paste input", function(){
            var value = $("#searchBox").val();

            $.ajax({url: "searchVendor.php?searchkey="+value, success: function(result){
                    var resulText = result.split("@");

                    $("#result").html(resulText[0]);
                    $("#countResult").html(resulText[1]+" sonuç gösteriliyor.");
                }});
        });


        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            return results[1] || 0;
        };

        if (typeof $.urlParam('categoryId') !== 'undefined') {
            $.ajax({url: "searchVendorCat.php?searchCat="+$.urlParam('categoryId'), success: function(result){
                    var resulText = result.split("@");

                    $("#result").html(resulText[0]);
                    $("#countResult").html(resulText[1]+" sonuç gösteriliyor.");
                }});
        }





    </script>

</body>
</html>
