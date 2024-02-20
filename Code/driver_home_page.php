<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
   header("location: login.php");
}

if($_SESSION["role"] != "driver"){
   $_SESSION["remote_view"] = 1;
}

$org_name = "No Organization";
$org_desc = "";
$points = 0;
$catalog_search = "";
$duplicate_error = "";
$catalog_err = "";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['org_id'])){
   $_SESSION['org_id'] = $_POST['org_id'];
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query'])){
   $catalog_search = $_POST['query'];
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['placing_order'])){
   $query_org_id = $_SESSION['org_id'];
   $query_user_id = $_SESSION['id'];
   $cart_id = 0;
   $query = "SELECT * FROM user_to_cart WHERE user_id = '$query_user_id' and org_id = '$query_org_id'";
   $result = mysqli_query($con, $query);
   if(is_object($result)){
      if($result->num_rows === 1){
         $row = $result->fetch_assoc();
         $cart_id = $row['cart_id'];
      }
   }

   //1. grab conversion rate from organization

   $conversionRate = 0;

   if(isset($_SESSION['org_id'])){
      $query = "SELECT * FROM organizations WHERE org_id = {$_SESSION['org_id']}";
      $result = mysqli_query($con, $query);
      $conversionRate = 100.00;
      if(is_object($result)){
         $row = $result->fetch_assoc();
         $conversionRate = $row['pointToDollar'];
      }
   }

   //2. grab all items in cart
   $product_ids = array();
   $query = "SELECT * FROM cart WHERE cart_id = '$cart_id'";
   $result = mysqli_query($con, $query);
   $price = 0;
   if(is_object($result)){
      for($x = 0; $x < $result->num_rows; $x++){
         //3. for each item in cart, grab the price
         $row = $result->fetch_assoc();
         $prod_id = $row['product_id'];
         array_push($product_ids, $prod_id);
         //$price += $row['product_id'];

         $query = "SELECT * FROM products WHERE product_id = '$prod_id'";
         $result2 = mysqli_query($con, $query);
         if(is_object($result2)){
            if($result2->num_rows !== 1){
               echo "Error doesn't appear once";
            }
            else{
               $row2 = $result2->fetch_assoc();
               $points = $row2['points'];
               //4. add up the total cost
               $price += $points * $conversionRate;
            }
         }
      }
   }
      

   //5. check that the driver has enough points
   $current_balance = 0;
   $query = "SELECT * FROM driver_to_org WHERE org_id = '$query_org_id' and driver_id = '$query_user_id'";
   $result = mysqli_query($con, $query);
   if(is_object($result)){
      if($result->num_rows !== 1){
         echo "Error invalid number of rows in database";
      }else{
         $row = $result->fetch_assoc();
         $current_balance = $row['curr_points'];
      }
   }

   if($price > $current_balance){
      echo "<h1>You do not have enough points to complete the transaction!</h1>";
   }else{
      //6. subtract cost
      $query = "UPDATE driver_to_org SET curr_points = curr_points - '$price' WHERE org_id = '$query_org_id' and driver_id = '$query_user_id'";
      if(mysqli_query($con, $query)){
      }else{
         echo "Error occured during price change";
      }
      
      foreach($product_ids as $curr_prod_id){

         //7. add to orders
         $query = "SELECT * FROM products WHERE product_id = '$curr_prod_id'";
         $result = mysqli_query($con, $query);
         $individual_points_cost = 0;
         if(is_object($result)){
            if($result->num_rows == 1){
               $row = $result->fetch_assoc();
               $individual_points_cost = $row['points'] * $conversionRate;
            }
         }
         $query = "call order_item({$_SESSION['id']}, {$_SESSION['org_id']}, '{$curr_prod_id}', '{$individual_points_cost}')";
         //echo $query;
         if(mysqli_query($con, $query)){
         }else{
            echo mysqli_error($con);
         }

         //8. hide from catalog
         $query = "UPDATE products SET hidden = 1 WHERE product_id = '$curr_prod_id'";
         if(mysqli_query($con, $query)){
         }else{
            echo $curr_prod_id;
            echo "Error hiding item from catalog";
         }

         //9. remove items from cart
         $query = "DELETE FROM cart WHERE product_id = '$curr_prod_id'";
         if(mysqli_query($con, $query)){
         }else{
            echo "Error removing item from cart";
         }

         //10. remove items from wishlist
         $query = "DELETE FROM wishlist WHERE product_id = '$curr_prod_id' and user_id = '$query_user_id'";
         if(mysqli_query($con, $query)){
         }else{
            echo "Error removing item from wishlist";
         }
      }  
   }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])){
   //look up cart_id based on the user id and the org id
   $query_org_id = $_SESSION['org_id'];
   $query_user_id = $_SESSION['id'];
   $cart_id = 0;
   $query = "SELECT * FROM user_to_cart WHERE user_id = '$query_user_id' and org_id = '$query_org_id'";
   $result = mysqli_query($con, $query);
   if(is_object($result)){
      if($result->num_rows === 1){
         $row = $result->fetch_assoc();
         $cart_id = $row['cart_id'];
      }
   }

   $query_product_id = $_POST['id'];
   $duplicate_flag = 0;
   $query = "SELECT * FROM cart WHERE cart_id = '$cart_id'";
   $result = mysqli_query($con, $query);
   if(is_object($result)){
      for($x = 0; $x < ($result->num_rows); $x++){
         $row = $result->fetch_assoc();
         if(strcmp($row['product_id'], $query_product_id) === 0){
            //add item selected to cart
            $duplicate_flag = 1;
         }
      }
      if($result->num_rows === 0|| $duplicate_flag === 0){
         $query = "INSERT INTO cart (cart_id, product_id) VALUES ('$cart_id', '$query_product_id')";
         if(mysqli_query($con, $query)){
            //echo "success";
         } else {
            echo mysqli_error($con);
         }
      } else {
         echo "Error item already in cart!";
      }
   }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_wishlist'])){
   $query_user_id = $_SESSION['id'];
   $prod_id = $_POST['id'];
   $query = "SELECT * FROM wishlist WHERE user_id = '$query_user_id'";
   $result = mysqli_query($con, $query);
   $already_exists_flag = 0;
   if(is_object($result)){
      for($x = 0; $x < $result->num_rows; $x++){
         $row = $result->fetch_assoc();
         if($row['product_id'] == $prod_id){
            $already_exists_flag = 1;
         }
      }
   }

   if($already_exists_flag == 1){
      $catalog_err = "Item is already in your wishlist! You can remove it in on the wishlist page.";
   }else{
      //add to wishlist
      $query = "INSERT INTO wishlist (product_id, user_id) VALUES ('$prod_id', '$query_user_id')";
      if(mysqli_query($con, $query)){

      }else{
         echo mysqli_error($con);
      }
   }

}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_id'])){
   $query_cart_id = $_POST['cart_id'];
   $query_remove_id = $_POST['remove_id']; 
   $query = "DELETE FROM cart WHERE cart_id = '$query_cart_id' and product_id = '$query_remove_id'";
   if(mysqli_query($con, $query)){
   } else {
      echo mysqli_error($con);
   }
}


$conversionRate = 100;

if(isset($_SESSION['org_id'])){
   $query = "SELECT * FROM organizations WHERE org_id = {$_SESSION['org_id']}";
         $result = mysqli_query($con, $query);
         $conversionRate = 100.00;
         if(is_object($result)){
            $row = $result->fetch_assoc();
            $conversionRate = $row['pointToDollar'];
   }
}



if(isset($_SESSION['org_id'])){
   $result = mysqli_query($con, "SELECT org_name, org_desc from organizations where org_id = ${_SESSION['org_id']}")->fetch_assoc();
   $org_name = $result['org_name'];
   $org_desc = $result['org_desc'];
   $points = mysqli_query($con, "SELECT curr_points from driver_to_org where driver_id = ${_SESSION['id']} and org_id = ${_SESSION['org_id']}")->fetch_assoc()['curr_points'];
}

?>
<!DOCTYPE html>
<html>
   <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Home</title>
   <link rel="stylesheet" href="css/driver_home_style.css">
   <script src="js/driver_home_page.js"></script>
</head>
<body>
   <nav class="nav_default">
      <ul class="nav_listing">
         <div class="navbox">
            <img src="resources/Logo.png">
            <li><a href="home_page.php">Home</a></li>
            <li><a href="profile_page.php">Profile</a></li>
            <li><a href="html/faq.html">FAQ</a></li>
            <?php
            if($_SESSION['remote_view'] != 1){
               echo("<li><a href=\"driver_applications_page.php\">Apply</a></li>");
            }
            ?>
            <li><a href="wishlist.php">Wishlist</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li style="margin-left:auto;">
               <form action="driver_home_page.php" method="POST">
                  <select name="org_id" onchange="this.form.submit()">
                     <option value="none" selected disabled hidden>Change Organization</option>
                     <?php
                        $result = mysqli_query($con, "SELECT org_id from driver_to_org where driver_id = {$_SESSION['id']}");
                        for($i = 0; $i<$result->num_rows;$i++){
                           $id = $result->fetch_assoc()['org_id'];
                           $name = mysqli_query($con, "SELECT org_name from organizations where org_id = {$id}")->fetch_assoc()['org_name'];
                           echo("<option value=\"{$id}\">$name</option>");
                        }
                     ?>
                  </select>
               </form>
            </li>
            <li><button onclick="myFunction()">Toggle dark mode</button><script>
               function myFunction() {
                  var element = document.body;
                  element.classList.toggle("dark-mode");
               }
            </script>
            </li>
         </div>
      </ul>
   </nav>

   <h1>My Organization: <?php echo("$org_name");?></h1>
   <h2><?php echo("$org_desc");?></h2>



   <div class="content">

      <div class="base">
         <label class="indent" id="user_points">My Points: <?php echo $points;?></label>
         <div id="point_history" class="scroll_box">
            <ul id="point_history_listing" class="scroll_list">
               <?php
               if(isset($_SESSION['id']) and isset($_SESSION['org_id'])){
                  $point_result = mysqli_query($con, "SELECT * from points where driver_id = ${_SESSION['id']} and org_id = ${_SESSION['org_id']}");
                  for($i = 0; $i < $point_result->num_rows; $i++){
                     $row = $point_result->fetch_assoc();
                     echo("<li class=\"point_history_entry\"><p>Points: {$row['amt_change']}</p><button>Details</button><br><p>Timestamp: {$row['time_stamp']}</p><p style=\"display:none;\"><br><br>{$row['description']}</p></li>");
                  }
               }
               ?>
            </ul>
         </div>
      </div>







      <div class="base">
         <label class="indent">My Catalog: </label>
            <button onclick="filterLow()">Price: Low to High</button>
            <button onclick="filterHigh()">Price: High to Low</button>
            <button onclick="filterPopular()">Popularity</button>
	    
	    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	      <div style="display:flex;margin-top:1%;"> 
	        <div class="form-btns">
                  <button class="signup" type="submit">Search</button>          
                </div>
                <div style="margin-left:10px;" class="form-item">
                  <input type="text" name="query" id="query" placeholder="Search items">
                </div> 
     	      </div>
	    </form>
	   
            

         <div id="catalog" class="scroll_box">
            <ul id="catalog_listing" class="scroll_list">
               
               
	      <?php
            echo $catalog_err;
            if(isset($_SESSION['org_id'])){
            
               $org_id = $_SESSION['org_id'];
               

               $query = "SELECT catalog_id FROM catalog WHERE org_id = '$org_id'";
               $result = mysqli_query($con, $query);

               //snatch that org_id if it exists...
               if(is_object($result)){
                  if($result->num_rows === 1){
                        $row = $result->fetch_assoc();
                     $catalog_id = $row['catalog_id'];	
                  }
               }

               $query = "SELECT pointToDollar FROM organizations WHERE org_id = '$temp_org_id'";
               $result = mysqli_query($con, $query);
               $pointRate = 0;
               if(is_object($result)){
                  if($result->num_rows === 1){
                  $row = $result->fetch_assoc();
                  $pointRate = $row['pointToDollar'];

                  }
               }


               //get list of products from corresponding catalog_id
               $query = "SELECT * FROM products WHERE catalog_id = '$catalog_id'";
               $result = mysqli_query($con, $query);

               //snatch that org_id if it exists...
               if(is_object($result)){
                  //echo $result->num_rows;
                  for($x = 0; $x < ($result->num_rows); $x++){
                     $res = $result->fetch_assoc();
                     //echo "test";
                     $img_src = $res['image_src'];
                     $title = $res['title'];
                     $id = $res['product_id'];
                     $item_points = $res['points'] * $conversionRate;
                     $popularity = $res['popular'];
                     $hidden = $res['hidden'];
                     //echo $popularity;
                     if($hidden == 0){
                        if(($catalog_search === "" || strpos(strtolower($title), strtolower($catalog_search)) !== false)){
                           if($popularity == 0){
                              echo "
                              <li class='catalog_entry' price='$item_points' product_id='$id' popularity='$popularity'>
                                 <div style='display:flex;flex-direction:row;'>
                                    <img src='$img_src' width='50px' height='50px'>
                                    <p>$item_points Points </p>
                                    <p>$title</p>
                                    <form action='driver_home_page.php' method='post'>
                                       <input type='hidden' id='add_to_cart' name='add_to_cart' value='0'>
                                       <input type='hidden' id='img_src' name='img_src' value='$img_src'>
                                       <input type='hidden' id='title' name='title' value='$title'>
                                       <input type='hidden' id='id' name='id' value='$id'>
                                       <input type='hidden' id='item_points' name='item_points' value='$item_points'>
                                       <button type='submit' style='margin-top:25%;'>Add to Cart</button>
                                    </form>
                                    <form action='driver_home_page.php' method='post'>
                                       <input type='hidden' id='add_to_wishlist' name='add_to_wishlist' value='0'>
                                       <input type='hidden' id='img_src' name='img_src' value='$img_src'>
                                       <input type='hidden' id='title' name='title' value='$title'>
                                       <input type='hidden' id='id' name='id' value='$id'>
                                       <input type='hidden' id='item_points' name='item_points' value='$item_points'>
                                       <button type='submit' style='margin-top:20%;'>Add to Wishlist</button>
                                    </form>
                                 </div>
                              </li>
                              ";
                           }else{
                              echo "
                              <li class='catalog_entry' price='$item_points' product_id='$id' popularity='$popularity'>
                                 <div style='display:flex;flex-direction:row;'>
                                    <img src='$img_src' width='50px' height='50px'>
                                    <p style='font-weight:bold;'>$item_points Points </p>
                                    <p style='font-weight:bold;'>★ $title</p>
                                    <form action='driver_home_page.php' method='post'>
                                       <input type='hidden' id='add_to_cart' name='add_to_cart' value='0'>
                                       <input type='hidden' id='img_src' name='img_src' value='$img_src'>
                                       <input type='hidden' id='title' name='title' value='$title'>
                                       <input type='hidden' id='id' name='id' value='$id'>
                                       <input type='hidden' id='item_points' name='item_points' value='$item_points'>
                                       <button type='submit' style='margin-top:25%;'>Add to Cart</button>
                                    </form>
                                    <form action='driver_home_page.php' method='post'>
                                       <input type='hidden' id='add_to_wishlist' name='add_to_wishlist' value='0'>
                                       <input type='hidden' id='img_src' name='img_src' value='$img_src'>
                                       <input type='hidden' id='title' name='title' value='$title'>
                                       <input type='hidden' id='id' name='id' value='$id'>
                                       <input type='hidden' id='item_points' name='item_points' value='$item_points'>
                                       <button type='submit' style='margin-top:20%;'>Add to Wishlist</button>
                                    </form>
                                 </div>
                              </li>
                              ";
                           }
                        }
                     }
                  }
               }
            }
	      ?>
	      


            </ul>
         </div>
      </div>

      <div class="base" id="cartBox">
         <label class="indent">My Cart: </label>
         <form action='driver_home_page.php' method='POST'>
            <input type='hidden' id='placing_order' name='placing_order' value='1'>
            <button type='submit'>Place Order</button>
         </form>
         <button onclick="clearCart()">Clear Cart</button>
	      <label id="cart_price">Current Total: </label>
         <div id="cart_content" class="scroll_box">
            <ul id="cart_listing" class="scroll_list">
               

	       <?php
                  $query_org_id = $_SESSION['org_id'];
   		  $query_user_id = $_SESSION['id'];
   		  $cart_id = 0;
   		  $query = "SELECT * FROM user_to_cart WHERE user_id = '$query_user_id' and org_id = '$query_org_id'";
   		  $result = mysqli_query($con, $query);
   		  if(is_object($result)){
      		   if($result->num_rows === 1){
         	      $row = $result->fetch_assoc();
         	      $cart_id = $row['cart_id'];
	               if($cart_id !== 0){
		               $query = "SELECT * FROM cart WHERE cart_id = '$cart_id'";
		               $result = mysqli_query($con, $query);
		               if(is_object($result)){
      		            for($z = 0; $z < ($result->num_rows); $z++){
         	               $row = $result->fetch_assoc();
         	               $product_id = $row['product_id'];
			                  $query2 = "SELECT * FROM products WHERE product_id = '$product_id'";
			                  $result2 = mysqli_query($con, $query2);
			                  if(is_object($result2)){
			                     for($x = 0; $x < ($result2->num_rows); $x++){
			                        $row2 = $result2->fetch_assoc();
                                 $img = $row2['image_src'];
                                 $pts = $row2['points'] * $conversionRate;
                                 $title = $row2['title'];
                                 $id = $row2['product_id'];
                                 $pop = $row2['popular'];
                                 echo "
                                 <li class='catalog_entry' price='$pts' product_id='$id' popularity='$pop'>
                                    <img src='$img' width='50px' height='50px'>
                                    <p>$pts Points </p>
                                    <p>$title</p>
                                    <form action='driver_home_page.php' method='post'>
                                       <input type='hidden' id='remove_id' name='remove_id' value='$id'>
                                       <input type='hidden' id='cart_id' name='cart_id' value='$cart_id'>
                                       <button type='submit'>Remove</button>
                                    </form>
                                 </li>
                                 ";
                                 //$formatted_product_id = str_replace('|', '%7c', $product_id);
                                 
			                     }
			                  }
      		            }
   		            }
                  }
      		   }
   		  }

               ?>



            </ul>
         </div>
      </div>

      <div class="base" id="orderBox">
         <label class="indent">My Orders: </label>
         <div id="order_content" class="scroll_box">
            <ul id="order_listing" class="scroll_list">
              




            </ul>
         </div>
      </div>



   </div>
</body>
</html>