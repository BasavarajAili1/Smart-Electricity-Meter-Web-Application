<?php 
include('dbconnect.php');

$exists = false;
if(isset($_POST['add'])) {
	$meterId=$_POST['meterId'];
	$username=$_POST['username'];
	$address=$_POST['address'];
	$mobile=$_POST['mobile'];
	$password=$_POST['password'];
    
    $existsSql = "SELECT * FROM web_smart_meter_account WHERE meter_id = '$meterId'";
    $result = mysqli_query($conn,$existsSql);
    $existNumRows = mysqli_num_rows($result);
    
    // If meter already exists ask user to enter different ID 
    if($existNumRows > 0){
        $exists = true;
        echo "<script> alert('Meter ID already exists, Please try to give different ID.')
		               window.location.href='createnewaccount.php';
		        </script>";
        exit; 
    }
   else{
	  $sql="insert into web_smart_meter_account values('$meterId','$username','$address','$mobile','$password')";
	  mysqli_query($conn,$sql);
    }
?>
    <script> alert('Account created succesfully')
	    window.location.href='index.php';
	</script>
	
<?php	
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
                justify-content: center;
                text-align: center;
            }
            
            label{
                font-size: 12px;
                text-decoration: none;
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
                    <h3>WELCOME TO SMART METER APPLICATION</h3>
                    <div class="w3_login_module">
                        <div class="module form-module">
                            <!---<div class="toggle"><i class="fa fa-times fa-pencil"></i>
                                <div class="tooltip">Click Me</div>
                            </div>---->
                            <a href="signup.php" style="float:right;text-decoration:underline;"></a>
                            <div class="form">
                                <h2><strong>Create Account</strong></h2>
                                <hr>
                                <form action="#" method="post" name="form1">
                                    <label for="meterId">Enter Meter ID</label>
                                    <input class="form-control" type="text" id="meterId" name="meterId" placeholder="Example: 002" required=" ">
                                    <label for="username">User Name</label>
                                    <input class="form-control" type="text" id="username" name="username" placeholder="Alice" required=" ">
 <label for="address">Address</label>
  <input class="form-control" type="text" id="address" name="address" placeholder="Address" required=" ">

 <label for="mobile">Mobile Number</label>
  <input class="form-control" type="text" id="mobile" name="mobile" placeholder="Enter 10 digit valid Mobile no." required=" ">

 <label for="password">Password</label>
  <input class="form-control" type="password" id="password" name="password" placeholder="Password" required=" ">
  
  <!--<input type="hidden" name="redirurl" value="<?php echo $_SERVER['HTTP_REFERER']; ?>"   />--->
  <input type="submit" name="add" value="Add">
                                </form>
                                
                                <form action="index.php">
                                  <input type="submit" name="Login" value="Login">
                                  </form>
							</div>
                        
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