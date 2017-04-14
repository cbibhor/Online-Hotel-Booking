<?php
 ob_start();
 session_start();

 if( !isset($_SESSION['id']) ) {
  header("Location: index.php");
  exit;
 }
 require_once 'dbconnect.php';
 $query="select * from myTable where id=".$_SESSION['id'];
 $result=$conn->query($query);
 $row= mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
            body{
                background-image: url("bg13.jpg");
                background-repeat: no-repeat;
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
    </style>
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
     <span class="glyphicon glyphicon-user"></span>&nbsp;Hi' <?php echo $row['email']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav> 

 <div id="wrapper">

 <div class="container">
    <br>
    <div class="page-header">
         <br><br>
         <h3>
             <p style="color:white">
             <?php echo "Welcome! ".$row['fname']." ".$row['lname']; ?>
             </p>
         </h3>
     </div>
        
    
    </div>
    
    </div>
    
</body>
</html>
<?php ob_end_flush(); ?>