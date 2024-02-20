<?php
require_once "Database/config.php";

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home_page.php");
    exit;
}
if(isset($curr_num)){
    echo "it's not set";
}
$curr_num = rand(0,2);

$index = -1;

$user_id = $_SESSION['user_id'];

$security_question = $security_answer = $security_answer_err = $msg_sender = $msg_type = $email = $content = "";

$query = "SELECT security_question1,security_question2,security_question3,security_answer1,security_answer2,security_answer3 FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($con, $query);

if(is_object($result)){
    if($result->num_rows > 1){
    	echo "Multiple accounts exist. Fix this.";
    }
    else{
	$question_array = array();
	$answer_array = array();
	$row = mysqli_fetch_assoc($result);
	$question_array[0] = $row['security_question1'];
	$question_array[1] = $row['security_question2'];
	$question_array[2] = $row['security_question3'];
	$answer_array[0] = $row['security_answer1'];
	$answer_array[1] = $row['security_answer2'];
	$answer_array[2] = $row['security_answer3'];
    }
} else{
    echo "Oops! Something went wrong. Please try again later.";
}

 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $temp_index = $_POST["index"];

    if(strcmp($answer_array[$temp_index], $_POST["security_answer"]) == 0){
	$new_email = $_SESSION['reset_email'];
	$new_password = $_SESSION['new_password_hash'];
	$query = "UPDATE users SET password = '$new_password', locked = 0 WHERE email = '$new_email'";
    mysqli_query($con, "CALL manual_audit('Password Change', 'USER: {$new_email} changed their password')");
        if(mysqli_query($con, $query)){
            $msg_sender = "System";
            $msg_type = "Password Changed";
            $content = " ";
            $query = "CALL messaged('$msg_sender', '$new_email', '$msg_type', '$content')";
            $result = mysqli_query($con, $query);
	    header("location: login.php");
	} else{
	    echo "Error in database request";
	}
	
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

    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            
	    <div class="options">
		<input type="hidden" id="index" name="index" value=<?php echo $curr_num; ?>>
                <?php echo $question_array[$curr_num]; ?>
            </div>

            <div class="form-item">
                <span class="pwd-format">
                    8-15 AlphaNumeric Characters
                </span>
                <input type="password" name="security_answer" id="security_answer" placeholder="Answer here" <?php echo (!empty($security_answer_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $security_answer; ?>">
                <span class="invalid-feedback"><?php echo $security_answer_err; ?></span>
            </div>

            <div class="form-btns">
                <button class="signup" type="submit">Reset Password</button>
                <div class="options">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>
        </form>
    </div>





</body>

</html>