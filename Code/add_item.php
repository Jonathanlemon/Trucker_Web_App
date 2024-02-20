<?php

require_once "Database/config.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_add'])){
    
  //get sponsor email
  $sponsor_email = $_SESSION['email'];

  //find the organization id that sponsor is in based on his email
  $query = "SELECT org_id FROM sponsors WHERE email = '$sponsor_email'";
  $result = mysqli_query($con, $query);

  //snatch that org_id if it exists...
  if(is_object($result)){
    if($result->num_rows === 1){
      $row = $result->fetch_assoc();
      $org_id = $row['org_id'];
    } else{
      echo "WARNING: Invalid database entry! User does not exist!";
    }
  } else{
    echo "Oops! Something went wrong. Please try again later.";
  } 

  //find catalog id from org_id
  $query = "SELECT catalog_id FROM catalog WHERE org_id = '$org_id'";
  $result = mysqli_query($con, $query);

  //grab that CATALOG_id if it exists... (hope it does)
  if(is_object($result)){
    if($result->num_rows === 1){
      $row = $result->fetch_assoc();
      $catalog_id = $row['catalog_id'];
    } else{
      echo "WARNING: Invalid database entry! Catalog does not exist!";
    }
  } else{
    echo "Oops! Something went wrong. Please try again laterr.";
  }

  //add product id to catalog id
  $itemId = $_POST['itemId'];
  $itemTitle = $_POST['itemTitle'];
  $itemPoints = $_POST['itemPoints'];
  $img_src = $_POST['img_src'];
  $query = "INSERT INTO products (product_id, catalog_id, image_src, points, title) VALUES ('$itemId', '$catalog_id', '$img_src', '$itemPoints', '$itemTitle')";


  //insert that DATA if you can???... (hope it works)
  if(mysqli_query($con, $query)){
    header("location: catalog.php");
  } else{
    echo "Oops! Something went wrong. Please try again late.";
    echo mysqli_error($con);
  }



}
else {
header("location: home_page.php");
}


?>