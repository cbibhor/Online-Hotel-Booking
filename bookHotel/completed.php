<?php
 ob_start();
 session_start();

 if( !isset($_SESSION['id']) ) {
  header("Location: index.php");
  exit;
 }
 include_once 'dbconnect.php';
 $query1="select * from myTable where id=".$_SESSION['id'];
 $result1= mysqli_query($conn,$query1);
 $row1= mysqli_fetch_assoc($result1);
 $email=$row1['email'];
 $query2="SELECT * FROM bookings ORDER BY idx DESC LIMIT 1";
 $result2= mysqli_query($conn,$query2);
 $row2= mysqli_fetch_assoc($result2);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Book Now</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
        <link rel="stylesheet" href="style.css" type="text/css" />
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            body{
                background-image: url("bg14.jpg");
                background-repeat: no-repeat;
                opacity: 30;
            }
            .navbar-default {
                opacity: 0.8;
                background-image: none;
                background-repeat: no-repeat;
                border: none;
            }
  
           .li-custom {
                background-color: black;
                background-image: none;
                background-repeat: no-repeat;
            }
            table.center
            {
                margin-left: auto;
                margin-right: auto;
            }
            th,td{
                padding-bottom: 3px;
                text-align: left;
            }
            .container1{
                width: 60px;
                margin: auto;
                color: black;
                font-size: 50%;
            }
        </style>
        
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $(document).ready(function() {
            $("#datepicker_from").datepicker();
            });
            $(document).ready(function() {
            $("#datepicker_to").datepicker();
            });
        </script>
    </head>

    <body>
        
         <nav class="navbar navbar-inverse navbar-fixed-top navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" style="color:lightseagreen">Hotel Management</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
              <li class="li-custom"><a href="home.php">Home</a></li>
              <li class="li-custom"><a href="booknow.php">Book Now</a></li>
            <li class="li-custom"><a href="bookings.php">All Bookings</a></li>
            <li class="li-custom"><a href="profile.php">User Profile</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right li-custom">
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
     <span class="glyphicon glyphicon-user"></span>&nbsp;Hi' <?php echo $email; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
        
    <br>
    <br><br><br>
    <h1 style="color:white;margin-left:80px;">Transaction Completed!</h1>
    <h4 style="color:white;margin-left:80px;">
        Payment Done: <?php echo $row2['payment_done']; ?>
    </h4>
    <h4 style="color:white;margin-left:80px;">
        Payment Due: <?php echo $row2['payment_due']; ?>
    </h4>
    <br>
    <p style="font-size:200%;color:white;margin-left:80px;">
        Thank You for booking.
    </p>
    </body>
</html>
<?php $conn->close(); ?>
<?php ob_end_flush(); ?>