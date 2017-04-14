<?php
ob_start();
session_start();
if( isset($_SESSION['id']) && $_SESSION['id']!="" ){
    header("Location: home.php");
}
include_once 'dbconnect.php';
        
        $error=false;
        $errTyp="failed";
        $errMsg="";
        $fname=$lname=$password=$dob=$city=$country=$pincode=$email=$mobile="";
        $nameError=$emailError=$passError="";
        
        if(!empty($_POST)){
            $fname = test_input($_POST["fname"]);
            $lname = test_input($_POST["lname"]);
            $email = test_input($_POST['email']);
            $password = test_input($_POST["password"]);
            
            $dob = test_input($_POST["dob"]);
            $city = test_input($_POST["city"]);
            $country = test_input($_POST["country"]);
            $pincode = test_input($_POST["pincode"]);
            $mobile = test_input($_POST["mobile"]);
            
            if(empty($fname) or empty($lname)){
                $error=true;
                $nameError="Name is required.";
            }
            
            if(empty($password)){
                $error=true;
                $passError="Password is required.";
            }
            
            
            if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
                $error = true;
                $emailError = "Please enter valid email address.";
            } 
            else {
            // check email exist or not
                $query = "SELECT id FROM myTable WHERE email='$email'";
                $result = $conn->query($query);
                if(mysqli_num_rows($result)!=0){
                    $error = true;
                    $emailError = "Email already exists.";
                }
            }
            
            if(!$error){
                save($fname,$lname,$email,$password,$dob,$city,$country,$pincode,$mobile);
            }
        }
        
        
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function save($fname,$lname,$email,$password,$dob,$city,$country,$pincode,$mobile){
            $sql = "INSERT INTO myTable (fname,lname,email,password,dob,city,country,pincode,mobile)"
                    . "VALUES ('$fname','$lname','$email','$password','$dob','$city','$country','$pincode','$mobile')";
            global $conn;
            $res = $conn->query($sql);
            if($res){
                $errTyp="success";
                $errMsg="Done";
                $conn->close();
                header("Location: home.php");
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
        <title>Sign Up</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
        <link rel="stylesheet" href="style.css" type="text/css" />
        <style>
            body{
                background-image: url("bg25.jpg");
                background-repeat: no-repeat;
                opacity: 30;
            }
            
            form
            {
                display: inline-block;
                margin-left: 420px;
                text-align: center;
            }
        </style>
    </head>
    
    <body>

        <div class="form" id="login-form">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
            <div class="col-md-6">
        
                <div class="form-group">
                    <h2 class="">Sign Up.</h2>
                </div>
        
                <div class="form-group">
                    <hr />
                </div>
            
            <?php if ( isset($errMsg) ) {
                if($errTyp=="danger"){ ?>
                <div class="form-group">
                    <div class="alert alert-danger">
                    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                    </div>
                </div>
            <?php }
            }
            ?>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <input type="text" name="fname" class="form-control" placeholder="First Name" maxlength="50" value="<?php echo $fname ?>" required="true"/>
                </div>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <input type="text" name="lname" class="form-control" placeholder="Last Name" maxlength="50" value="<?php echo $lname ?>" required="true"/>
                </div>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                <input type="email" name="email" class="form-control" placeholder="Email" maxlength="40" value="<?php echo $email ?>" required="true"/>
                </div>
                <?php if( isset($emailError) ) ?>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input type="password" name="password" class="form-control" placeholder="Password" maxlength="15" required="true"/>
                </div>
            </div>
                
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
                <input type="text" name="dob" class="form-control" placeholder="Date of Birth (dd/mm/yyyy)" maxlength="15" />
                </div>
            </div>
                
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
             <input type="text" name="city" class="form-control" placeholder="City" maxlength="15" />
                </div>
            </div>
                
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
             <input type="text" name="country" class="form-control" placeholder="Country" maxlength="15" />
                </div>
            </div>
                
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
             <input type="text" name="pincode" class="form-control" placeholder="Pincode" maxlength="15" />
                </div>
            </div>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
             <input type="text" name="mobile" class="form-control" placeholder="Contact No." maxlength="15" />
                </div>
            </div>    
            
            <div class="form-group">
             <hr />
            </div>
            
            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
            </div>
            
            <div class="form-group">
             <hr />
            </div>
            
        
        </div>
   
    </form>
    </div> 

    </body>
</html>

<?php ob_end_flush(); ?>