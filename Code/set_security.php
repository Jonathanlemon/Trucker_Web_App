<?php
// Initialize the session

// Include config file
require_once "Database/config.php";

$q1 = $q2 = $q3 = "";
$a1 = $a2 = $a3 = "";
$err1 = $err2 = $err3 = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["answer1"]))){
        $err1 = "Please enter your first security question answer";
    }
    else{
        $a1 = trim($_POST["answer1"]);
    }
    if(empty(trim($_POST["answer2"]))){
        $err2 = "Please enter your second security question answer";
    }
    else{
        $a2 = trim($_POST["answer2"]);
    }
    if(empty(trim($_POST["answer3"]))){
        $err2 = "Please enter your third security question answer";
    }
    else{
        $a3 = trim($_POST["answer3"]);
    }
    if (trim($_POST['question1']) != trim($_POST['question2']) && trim($_POST['question1']) != trim($_POST['question3']) && trim($_POST['question2']) != trim($_POST['question3']))
    {
        $_SESSION["security_question1"] = trim($_POST['question1']);
        $q1 = $_SESSION["security_question1"];
        $_SESSION["security_answer1"] = trim($_POST['answer1']);
        $a1 = $_SESSION["security_answer1"];
        $_SESSION["security_question2"] = trim($_POST['question2']);
        $q2 = $_SESSION["security_question2"];
        $_SESSION["security_answer2"] = trim($_POST['answer2']);
        $a2 = $_SESSION["security_answer2"];
        $_SESSION["security_question3"] = trim($_POST['question3']);
        $q3 = $_SESSION["security_question3"];
        $_SESSION["security_answer3"] = trim($_POST['answer3']);
        $a3 = $_SESSION["security_answer3"];
        
        $callCreateUser = 'CALL create_user("'
            .$_SESSION["sec_email"].'","'
            .$_SESSION["new_user_org"].'","' 
            .$_SESSION["sec_password"].'","' 
            .$_SESSION["sec_role"].'",' 
            .'1,"' 
            .$_SESSION["security_question1"].'","' 
            .$_SESSION["security_answer1"].'","' 
            .$_SESSION["security_question2"].'","' 
            .$_SESSION["security_answer2"].'","'  
            .$_SESSION["security_question3"].'","'  
            .$_SESSION["security_answer3"].'","' 
            .$_SESSION["firstname"].'","' 
            .$_SESSION["lastname"].'",' 
            .'0)';
    
        $queryStatus = mysqli_query($con, $callCreateUser);
        if($queryStatus){
            header('location: login.php');
        } 
        } else {
            echo "Your security questions cannot be the same.";
        }
    
        mysqli_close($con);
    
    }
    
?>
 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page </title>
    <link rel="stylesheet" href="css/signup.css" />
</head>

<body>
    <?php echo $callCreateUser; ?>
   
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

	    <select name="question1" id="question1">
  	        <option value="What is your mothers maiden name?">What is your mother's maiden name?</option>
  	        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
  	        <option value="What city were you born in?">What city were you born in?</option>
  	        <option value="What is your fathers middle name?">What is your father's middle name?</option>
	    </select>

            <div class="form-item">
                <input type="text" name="answer1" id="answer1" placeholder="Enter answer" <?php echo (!empty($err1)) ? 'is-invalid' : ''; ?>" value="<?php echo $a1; ?>">
                <span class="invalid-feedback"><?php echo $err1; ?></span>
            </div>

            <select name="question2" id="question2">
		<option value="What is your mothers maiden name?">What is your mother's maiden name?</option>
  	        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
  	        <option value="What city were you born in?">What city were you born in?</option>
  	        <option value="What is your fathers middle name?">What is your father's middle name?</option>
	    </select>

            <div class="form-item">
                <input type="text" name="answer2" id="answer2" placeholder="Enter answer" <?php echo (!empty($err2)) ? 'is-invalid' : ''; ?>" value="<?php echo $a2; ?>">
                <span class="invalid-feedback"><?php echo $err2; ?></span>
            </div>

	    <select name="question3" id="question3">
		<option value="What is your mothers maiden name?">What is your mother's maiden name?</option>
  	        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
  	        <option value="What city were you born in?">What city were you born in?</option>
  	        <option value="What is your fathers middle name?">What is your father's middle name?</option>
	    </select>

            <div class="form-item">
                <input type="text" name="answer3" id="answer3" placeholder="Enter answer" <?php echo (!empty($err3)) ? 'is-invalid' : ''; ?>" value="<?php echo $a3; ?>">
                <span class="invalid-feedback"><?php echo $err3; ?></span>
            </div>

	    <div class="form-btns">
                <span class="invalid-feedback"><?php echo $login_err; ?></span>
                <button class="signup" type="submit">Finish</button>
                <div class="options">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>

        </form>
    





</body>

</html>


