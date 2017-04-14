<?php
ob_start();
session_start();
if( isset($_SESSION['id']) && $_SESSION['id']!="" ){
    header("Location: home.php");
}
include_once 'dbconnect.php';
        
        $error=false;
        $email=$password="";
        $emailError=$errMSG="";
        
        if(!empty($_POST)){
            $email = test_input($_POST['email']);
            $password = test_input($_POST["password"]);
            
            
            if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
                $error = true;
                $emailError = "Please enter valid email address.";
            } 
            
            if(!$error){
                $query="select id,email,password from myTable where email='$email' and password='$password'";
                $result=$conn->query($query);
                if(mysqli_num_rows($result)==1){
                    $row= mysqli_fetch_assoc($result);
                    $_SESSION['id']=$row['id'];
                    header("Location: home.php");
                }
                else{
                    $errMSG = "Incorrect email id or password.";
                }
            }
        }
        
        
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sign In</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
<style>
            body{
                background-image: url("bg19.jpg");
                background-repeat: no-repeat;
            }
            form
            {
                display: inline-block;
                margin-left:400px;
                text-align: center;
            }
</style>
</head>


<body>

    <div class="container" id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
     <div class="col-md-6">
        
         <div class="form-group">
             <h2 class="">Sign In.</h2>
            </div>
        
         <div class="form-group">
             <hr />
            </div>
            
            <?php
   if ( !empty($errMSG) ) {
    
    ?>
    <div class="form-group">
        <div class="alert alert-danger">
        <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
        </div>
    </div>
    <?php
   }
   ?>
            
            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>" maxlength="40" required="true"/>
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
             <hr />
            </div>
            
            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-login">Sign In</button>
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