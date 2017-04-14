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
 
 $due=$pay=$numdays=0;
 $error=false;
 $datetemp=$dateend=$datefrom=$dateto="";
 if(!empty($_POST)){
            $datefrom = test_input($_POST["datefrom"]);
            $dateto = test_input($_POST["dateto"]);
            $roomtype = test_input($_POST["roomtype"]);
            $rooms = test_input($_POST["rooms"]);
            change_format();
            check_availability($datefrom,$dateto,$roomtype,$rooms);
            if(!$error){
                update_allotment($datefrom,$dateto,$roomtype,$rooms);
                get_due();
                compute_payment($roomtype, $rooms);
                save($datefrom,$dateto,$roomtype,$rooms);
            }
            else{
                echo '<script>';
                echo 'alert("Selected Room Not Available.\n Please select other room type.")';
                echo '</script>';
            }
        }
        
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        function change_format(){
            global $datefrom, $dateto;
            $mm=substr($datefrom,0,2);
            $dd=substr($datefrom,3,2);
            $yy=substr($datefrom,6);
            $datefrom=$yy."-".$mm."-".$dd;
            $mm=substr($dateto,0,2);
            $dd=substr($dateto,3,2);
            $yy=substr($dateto,6);
            $dateto=$yy."-".$mm."-".$dd;
        }
        
        function check_availability($datefrom,$dateto,$roomtype,$rooms){
            global $error, $conn;
            $query2="SELECT * FROM allotment WHERE day BETWEEN '$datefrom' AND '$dateto' ORDER BY day ASC";
            $result2=mysqli_query($conn,$query2);
            if(mysqli_num_rows($result2)>0){
                while($row2= mysqli_fetch_assoc($result2)){
                    if($roomtype=="single"){
                        if($row2['single']+$rooms>10){
                            $error=true;
                            break;
                        }
                    }
                    else if($roomtype=="royal"){
                        if($row2['royal']+$rooms>5){
                            $error=true;
                            break;
                        }
                    }
                    else if($roomtype=="deluxe"){
                        if($row2['deluxe']+$rooms>5){
                            $error=true;
                            break;
                        }
                    }
                    else if($roomtype=="doubleb"){
                        if($row2['doubleb']+$rooms>7){
                            $error=true;
                            break;
                        }
                    }
                }
                mysqli_free_result($result2);
            }
        }
        
        function get_due(){
            global $conn, $due;
            $query3="select * from bookings where id=".$_SESSION['id']." order by payment_due DESC LIMIT 1";
            $result3=mysqli_query($conn,$query3);
            if(mysqli_num_rows($result3)==1){
                $row3= mysqli_fetch_assoc($result3);
                $due=$row3['payment_due'];
            }
        }
        
        function compute_payment($roomtype,$rooms){
            global $pay,$numdays;
            if($roomtype=="royal")
                $pay=10000*$rooms;
            else if($roomtype=="deluxe")
                $pay=7000*$rooms;
            else if($roomtype=="doubleb")
                $pay=4000*$rooms;
            else if($roomtype=="single")
                $pay=2000*$rooms;
            $pay=$pay*$numdays;
        }
        
        function update_allotment($datefrom,$dateto,$roomtype,$rooms){
            global $datetemp,$dateend,$numdays, $conn;
            $datetemp=$datefrom;
            increment_date($dateto);
            while($datetemp!=$dateend){
                $query4="SELECT * FROM allotment WHERE day = '$datetemp'";
                $result4=mysqli_query($conn,$query4);
                if(mysqli_num_rows($result4)==1){
                    $query5="UPDATE allotment SET $roomtype=$roomtype+$rooms WHERE day='$datetemp'";
                    $result5=mysqli_query($conn,$query5);
                }
                else {
                    $query6="INSERT INTO allotment(day,royal,deluxe,doubleb,single) VALUES('$datetemp',0,0,0,0)";
                    $result6=mysqli_query($conn,$query6);
                    $query5="UPDATE allotment SET $roomtype=$roomtype+$rooms WHERE day='$datetemp'";
                    $result5=mysqli_query($conn,$query5);
                }
                $numdays=$numdays+1;
                mysqli_free_result($result4);
                increment_date($datetemp);
            }
        }
        
        function increment_date($datet){
            global $datetemp,$dateend,$numdays;
            $yy=substr($datet,0,4);
            $mm=substr($datet,5,2);
            $dd=substr($datet,8,2);
            if($dd==31){
                $dd=1;
                if($mm==12){
                    $mm=1; $yy=$yy+1;
                }
                else{
                    $mm=$mm+1;
                    if($mm<10)  $mm="0".$mm;
                }
            }
            else if($dd==30){
                if($mm==4 || $mm==6 || $mm==9 || $mm==11){
                    $dd=1; $mm=$mm+1;
                    if($mm<10)  $mm="0".$mm;
                }
            }
            else if(($dd==28 || $dd==29) && $mm==2){
                if($yy%4==0 && $dd==28) $dd=29;
                else if($dd==28 || $dd==29){    
                    $dd=1; $mm=$mm+1;
                    if($mm<10)  $mm="0".$mm;
                }
                else    $dd=$dd+1;
            }
            else{
                $dd=$dd+1;
            }
            if($dd<10)  $dd="0".$dd;
            if($numdays==0)    $dateend=$yy."-".$mm."-".$dd;
            else               $datetemp=$yy."-".$mm."-".$dd;
        }
        
        function save($datefrom,$dateto,$roomtype,$rooms){
            global $due,$pay,$conn;
            $sql = "INSERT INTO bookings (id,date_from,date_to,room_type,rooms,amount,payment_done,payment_due)"
                    . "VALUES (".$_SESSION['id'].",'$datefrom','$dateto','$roomtype',$rooms,$pay,0,$due)";
            $res = mysqli_query($conn,$sql);
            if($res){
                $errTyp="success";
                $errMsg="Done";
                $conn->close();
                header("Location: booking_summary.php");
                //exit;
            }
            else{
                $errTyp="danger";
                $errMsg="Something's wrong... Please try again.";
            }
        }
 
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
            
            form
            {
                display: block;
                width: 25%;
                margin-left: auto;
                margin-right: auto;
                background-color: lightsteelblue;
                opacity: 0.8;
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
        
    <div class="form" id="book-form">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
            <h2 style="text-align:center;">Book</h2>
            <div style="text-align:center;">
                <label for="date_from" style="font-size:130%;color:black;">Date From:</label><br>
                <input type="text" id="datepicker_from" name="datefrom"><br /><br>
            </div>
            <div style="text-align:center;">
                <label for="date_to" style="font-size:130%;color:black;">Date To:</label><br>
                <input type="text" id="datepicker_to" name="dateto"><br /><br>
            </div>
            <div style="text-align:center;">
                <label for="room_type" style="font-size:130%;color:black;">Room Type:</label><br>
                <select id="roomtype" class="input" name="roomtype" required="true">
                    <option value="royal">Royal</option>
                    <option value="deluxe">Deluxe</option>
                    <option value="doubleb">Double Bed</option>
                    <option value="single">Single Bed</option>
                </select><br /><br>
            </div>
            <div style="text-align:center;">
                <label for="rooms" style="font-size:130%;color:black;">Number of Rooms:</label><br>
                <input id="rooms" class="input" name="rooms" type="text" value="" required="true"/><br /><br>
            </div>
            <div style="text-align:center;padding-bottom: 5px;">
            <input id="submit_button" type="submit" style="background-color: bisque" value="Book"/>
            </div>
           
        </form>
        </div>
    </body>
</html>
<?php ob_end_flush(); ?>