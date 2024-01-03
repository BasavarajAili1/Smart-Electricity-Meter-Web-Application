<?php
session_start();
include('dbconnect.php');

if (isset($_POST['LOGIN'])) {
  
    $sql = mysqli_query($conn, "select * from web_smart_meter_account  where meter_id='" . $_POST["Username"] . "' and password='" . $_POST["Password"] . "'");

    if (mysqli_num_rows($sql) > 0) {
        while ($results = mysqli_fetch_assoc($sql)) 
        {
            
            $_SESSION['shop_id']=$results['meter_id'];
            $_SESSION['password']=$results['password'];
			
            // echo "You are logged in sucessfully!!";

            //header("Location:home.php");
			?>
			<script> alert('You are logged in successfully')
		               window.location.href='main_home.php';
		</script>
		?>
		<?php
        }
    } else {
        echo "<script> alert('Invalid username or password!!'); </script>";
    }
}
?>
<html>
    <head>
        <title>Smart Energy Meter App</title>
        <!-- for-mobile-apps -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="Grocery Store Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
              Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
            function hideURLbar(){ window.scrollTo(0,1); } </script>
        <!-- //for-mobile-apps -->
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
        <!-- font-awesome icons -->
        <link href="css/font-awesome.css" rel="stylesheet" type="text/css" media="all" /> 
        <!-- //font-awesome icons -->
        <!-- js -->
        <script src="js/jquery-1.11.1.min.js"></script>
        <!-- //js -->
        <link href='//fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image/png" href="asset/power.png">
        <!-- start-smoth-scrolling -->
        <script type="text/javascript" src="js/move-top.js"></script>
        <script type="text/javascript" src="js/easing.js"></script>
        <!----<link href="formvalid.css" rel="stylesheet">
        <script src="formvalid.js"></script>----->
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $(".scroll").click(function (event) {
                    event.preventDefault();
                    $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
                });
            });


            function getOneTimePass() {

                //Code for getting Div id to set product dropdown
                var mobile_no = document.getElementById("phone").value;
                $.ajax({
                    type: 'post',
                    url: 'get_one_pass.php',
                    data: {
                        mobile_no: mobile_no
                    },
                    success: function (data) {
                        console.log(data);
                        var json_obj = JSON.parse(data);
                        document.getElementById('session').value = json_obj.Details;
                    }
                });
            }
        </script>

        <!-- start-smoth-scrolling -->
        <style>
            h2{
                text-align:center;
            }
        </style>
    </head>

    <body>
        <!-- header -->

<?php //include('header_part.php'); ?>
        <!-- //header -->
        <!-- products-breadcrumb -->
        <div class="products-breadcrumb">
            <div class="container">
                <ul>
                    <li>SMART ENERGY METER WEB APPLICATION</li>
                </ul>
            </div>
        </div>
        <!-- //products-breadcrumb -->
        <!-- banner -->
        <div class="banner">
<?php //include('navigation_bar.php');   ?>
            <div class="">
                <!-- login -->

                <div class="w3_login">
                    <h3>WELCOME TO SMART ENERGY METER APPLICATION</h3>
                    <div class="w3_login_module">
                        <div class="module form-module">
                            <!---<div class="toggle"><i class="fa fa-times fa-pencil"></i>
                                <div class="tooltip">Click Me</div>
                            </div>---->
                            <a href="signup.php" style="float:right;text-decoration:underline;"></a>
                            <div class="form">
                                <h2><strong>Login Here</strong></h2>
                                <form action="#" method="post" name="form1">
                                    <input class="form-control" type="text" name="Username" placeholder="Enter Meter Id" required=" ">
                                    <input class="form-control" type="password" name="Password" placeholder="Password" required=" ">

                                    <!--<input type="hidden" name="redirurl" value="<?php echo $_SERVER['HTTP_REFERER']; ?>"   />--->
                                    <input type="submit" name="LOGIN" value="LOGIN">
                                </form>
                                
                                
                                <form action="createnewaccount.php">
                                  <input type="submit" name="Login" value="CREATE NEW ACCOUNT">
                                  </form>
							</div>

                           <!--- <div class="form">
                                <h2>Create an account</h2>
                                <form action="#" method="post">
                                    <input class="form-control" type="text" name="Username" pattern="[a-zA-Z]{6,20}$" placeholder="Username(6-20 characters)" required=" ">
                                    <input class="form-control" type="password" name="Password" pattern="[a-zA-Z]{6,20}$" placeholder="Password(6-20 characters)" required=" ">
                                    <input class="form-control" type="email" name="Email" placeholder="Email Address" required=" ">
                                    <input class="form-control" type="text" id="phone" name="Phone" pattern="(7|8|9)\d{9}" maxlength="10" placeholder="Enter 10 digit valid mobile no." required=" ">

                                    <input class="form-control" type="text" name="city" pattern="[a-zA-Z]+$" placeholder="City" required=" ">
                                    <input class="form-control" type="text" name="pcode" pattern="[0-9]{6}$" placeholder="Pincode" required=" ">
                                    <input class="form-control" type="text" name="state" pattern="[a-zA-Z]+$" placeholder="State" required=" ">

                                    <textarea class="form-control" type="text" name="addr" placeholder="Address" required=" "></textarea>                                         
                                    <input type="button" id="getotp" value="GET OTP" onclick="getOneTimePass()">
                                    <input class="form-control" id="session" type="hidden" name="session">
                                    <input class="form-control" type="text" name="otp" pattern="[0-9]{6}$" placeholder="One Time Password" required=" ">

                                    <input type="submit" name="Register" value="Register">
                                </form>
                            </div>---->


                           <!--- <div class="cta"><a href="forget.php">Forgot your password?</a>--->

                            </div>
                        </div>
                    </div>
                    <script>
                        $('.toggle').click(function () {
                            // Switches the Icon
                            $(this).children('i').toggleClass('fa-pencil');
                            // Switches the forms  
                            $('.form').animate({
                                height: "toggle",
                                'padding-top': 'toggle',
                                'padding-bottom': 'toggle',
                                opacity: "toggle"
                            }, "slow");
                        });
                    </script>
                </div>
                <!-- //login -->
            </div>
            <div class="clearfix"></div>
        </div>
       
<?php //include('footer.php'); ?>
        <!-- //footer -->
        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>
        <script>
                                                    $(document).ready(function () {
                                                        $(".dropdown").hover(
                                                                function () {
                                                                    $('.dropdown-menu', this).stop(true, true).slideDown("fast");
                                                                    $(this).toggleClass('open');
                                                                },
                                                                function () {
                                                                    $('.dropdown-menu', this).stop(true, true).slideUp("fast");
                                                                    $(this).toggleClass('open');
                                                                }
                                                        );
                                                    });
        </script>
        <!-- here stars scrolling icon -->
        <script type="text/javascript">
            $(document).ready(function () {
               

                $().UItoTop({easingType: 'easeOutQuart'});

            });
        </script>
        <!-- //here ends scrolling icon -->
        <script src="js/minicart.min.js"></script>
        <script>
                    // Mini Cart
                    paypal.minicart.render({
                        action: '#'
                    });

                    if (~window.location.search.indexOf('reset=true')) {
                        paypal.minicart.reset();
                    }
        </script>
    </body>
</html>