<?php

if (!isset($_SESSION)) {
    ob_start();
    session_start();
}

include "master/dbMaster.php";
include "secret/Friend.php";
include "secret/User.php";
include "secret/Message.php";
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

$friend = new Friends();
if (isset($_GET["spfbUID"])) {


    $newFriend = $_GET["spfbUID"];
    $friendData = array(
        $_SESSION["user-firebaseUID"] =>
            array(
                '-Lww6X' . generateRandomString(14) => $newFriend,
            ),
    );

    $friend->insert($friendData);


    $friendData = array(
        $newFriend =>
            array(
                '-Lww6X' . generateRandomString(14) => $_SESSION["user-firebaseUID"],
            ),
    );


    $friend->insert($friendData);


}


$myID = $_SESSION["user-firebaseUID"];

$currentUserList = $friend->get($myID);




if(isset($_GET["myID"])&&isset($_GET["receiverID"])){

    $dataArr = array($_GET["receiverID"],$_GET["myID"]);


    

    $messageController = new Messages();



    $messages = $messageController->getAll($dataArr);


    print_r($messages);

}


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

    <style>

        .container {
            max-width: 1170px;
            margin: auto;
        }

        img {
            max-width: 100%;
        }

        .inbox_people {
            background: #f8f8f8 none repeat scroll 0 0;
            float: left;
            overflow: hidden;
            width: 40%;
            border-right: 1px solid #c4c4c4;
        }

        .inbox_msg {
            border: 1px solid #c4c4c4;
            clear: both;
            overflow: hidden;
        }

        .top_spac {
            margin: 20px 0 0;
        }


        .recent_heading {
            float: left;
            width: 40%;
        }

        .srch_bar {
            display: inline-block;
            text-align: right;
            width: 60%;
            padding:
        }

        .headind_srch {
            padding: 10px 29px 10px 20px;
            overflow: hidden;
            border-bottom: 1px solid #c4c4c4;
        }

        .recent_heading h4 {
            color: #05728f;
            font-size: 21px;
            margin: auto;
        }

        .srch_bar input {
            border: 1px solid #cdcdcd;
            border-width: 0 0 1px 0;
            width: 80%;
            padding: 2px 0 4px 6px;
            background: none;
        }

        .srch_bar .input-group-addon button {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: medium none;
            padding: 0;
            color: #707070;
            font-size: 18px;
        }

        .srch_bar .input-group-addon {
            margin: 0 0 0 -27px;
        }

        .chat_ib h5 {
            font-size: 15px;
            color: #464646;
            margin: 0 0 8px 0;
        }

        .chat_ib h5 span {
            font-size: 13px;
            float: right;
        }

        .chat_ib p {
            font-size: 14px;
            color: #989898;
            margin: auto
        }

        .chat_img {
            float: left;
            width: 11%;
        }

        .chat_ib {
            float: left;
            padding: 0 0 0 15px;
            width: 88%;
        }

        .chat_people {
            overflow: hidden;
            clear: both;
        }

        .chat_list {
            border-bottom: 1px solid #c4c4c4;
            margin: 0;
            padding: 18px 16px 10px;
        }

        .inbox_chat {
            height: 550px;
            overflow-y: scroll;
        }

        .active_chat {
            background: #ebebeb;
        }

        .incoming_msg_img {
            display: inline-block;
            width: 6%;
        }

        .received_msg {
            display: inline-block;
            padding: 0 0 0 10px;
            vertical-align: top;
            width: 92%;
        }

        .received_withd_msg p {
            background: #ebebeb none repeat scroll 0 0;
            border-radius: 3px;
            color: #646464;
            font-size: 14px;
            margin: 0;
            padding: 5px 10px 5px 12px;
            width: 100%;
        }

        .time_date {
            color: #747474;
            display: block;
            font-size: 12px;
            margin: 8px 0 0;
        }

        .received_withd_msg {
            width: 57%;
        }

        .mesgs {
            float: left;
            padding: 30px 15px 0 25px;
            width: 60%;
        }

        .sent_msg p {
            background: #05728f none repeat scroll 0 0;
            border-radius: 3px;
            font-size: 14px;
            margin: 0;
            color: #fff;
            padding: 5px 10px 5px 12px;
            width: 100%;
        }

        .outgoing_msg {
            overflow: hidden;
            margin: 26px 0 26px;
        }

        .sent_msg {
            float: right;
            width: 46%;
        }

        .input_msg_write input {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: medium none;
            color: #4c4c4c;
            font-size: 15px;
            min-height: 48px;
            width: 100%;
        }

        .type_msg {
            border-top: 1px solid #c4c4c4;
            position: relative;
        }

        .msg_send_btn {
            background: #05728f none repeat scroll 0 0;
            border: medium none;
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            font-size: 17px;
            height: 33px;
            position: absolute;
            right: 0;
            top: 11px;
            width: 33px;
        }

        .messaging {
            padding: 0 0 50px 0;
        }

        .msg_history {
            height: 516px;
            overflow-y: auto;
        }


    </style>

</head>
<body class="animsition">

<!-- Header -->
<?php

include_once("includes/header.php");

?>


<section class="bgwhite p-t-55 p-b-65">

    <div class="messaging">
        <div class="inbox_msg">
            <div class="inbox_people">
                <div class="headind_srch">
                    <div class="recent_heading">
                        <h4>Mesajlar</h4>
                    </div>

                </div>
                <div class="inbox_chat">




                    <?php

                    $UserController = new Users();
                    foreach ($currentUserList as $key => $value){
                        //echo $key ." ". $value;


                        $user = $UserController->get($value);


                        print "<div class=\"chat_list\">";
                        print "<div class=\"chat_people\">";
                        print "<div class=\"chat_img\"><img src=\"https://ptetutorials.com/images/user-profile.png\"
                                                       alt=\"sunil\"></div>";
                        print "<div class=\"chat_ib\">";
                        print "<a href=\"messages.php?receiverID=".$value."&myID=".$_SESSION["user-firebaseUID"]."\">";
                        print "<h5>".$user["name"]."</h5>";

                        print "<p>".$user["email"]."</p>";
                        print "</a>";
                        print "</div>";
                        print "</div>";
                        print "</div>";

                    }





                    ?>



                </div>
            </div>
            <div class="mesgs">
                <div class="msg_history">




                    <?php

                    if(isset($_GET["receiverID"]) && isset($_GET["myID"]) && $messages != null)
                    {
                        foreach ($messages as $row){


                            if ($row["idSender"] == $_GET["receiverID"]){

                                print "<div class=\"incoming_msg\">";
                                print "<div class=\"incoming_msg_img\"><img src=\"https://ptetutorials.com/images/user-profile.png\"alt=\"sunil\"></div>";
                                print "<div class=\"received_msg\">";
                                print "<div class=\"received_withd_msg\">";

                                print "<p>".$row["text"]."</p>";
                                print " <span class=\"time_date\"></span></div>";
                                print "</div>";
                                print "</div>";

                            }

                            if ($row["idSender"] == $_GET["myID"]){

                                print " <div class=\"outgoing_msg\">
                                <div class=\"sent_msg\">";
                                print "<p>".$row["text"]."</p>";
                                print "</div></div>";

                            }


                        }
                    }else{
                        print "Mobil uygulamamızdan mesajlaşmaya devam edebilirsiniz";
                    }

                    ?>


                </div>

                <!--
                <div class="type_msg">
                    <div class="input_msg_write">
                        <form action="" method="post">
                            <input type="text" class="write_msg" placeholder="Mesaj Yaz"/>
                            <submit class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o"
                                                                          aria-hidden="true"></i>
                        </form>

                    </div>
                </div>-->
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
        start: [50, 200],
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

    filterBar.noUiSlider.on('update', function (values, handle) {
        skipValues[handle].innerHTML = Math.round(values[handle]);
    });
</script>
<!--===============================================================================================-->
<script src="js/main.js"></script>


<script>


    $("#searchBox").on("propertychange change keyup paste input", function () {
        var value = $("#searchBox").val();

        $.ajax({
            url: "searchVendor.php?searchkey=" + value, success: function (result) {
                var resulText = result.split("@");

                $("#result").html(resulText[0]);
                $("#countResult").html(resulText[1] + " sonuç gösteriliyor.");
            }
        });
    });


    $.urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    };

    if (typeof $.urlParam('categoryId') !== 'undefined') {
        $.ajax({
            url: "searchVendorCat.php?searchCat=" + $.urlParam('categoryId'), success: function (result) {
                var resulText = result.split("@");

                $("#result").html(resulText[0]);
                $("#countResult").html(resulText[1] + " sonuç gösteriliyor.");
            }
        });
    }


</script>

</body>
</html>
