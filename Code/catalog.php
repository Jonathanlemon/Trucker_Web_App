<?php
require_once "Database/config.php";

if(!isset($_SESSION['role'])){
    header("location: login.php");
    if($_SESSION['role'] == "driver"){
        header("location: home_page.php");
    }
}

$query_response = array();

$conversionRate = 100;

$query = "SELECT * FROM organizations WHERE org_id = {$_SESSION['org_id']}";
		  $result = mysqli_query($con, $query);
		  $conversionRate = 100.00;
		  if(is_object($result)){
		     $row = $result->fetch_assoc();
		     $conversionRate = $row['pointToDollar'];
}


function grab_auth_token(){
    exec('python3 python/new_auth_token.py 2>&1', $res);
    $_SESSION['token_acquired'] = 1;
    return $res;
}


function grab_lookup_token(){
    exec('python3 python/new_lookup_token.py 2>&1', $res);
    $_SESSION['token_acquired'] = 1;
    return $res;
}

$result = grab_lookup_token();
$lookup_token = $result[0];


$result = grab_auth_token();
$auth_token = $result[0];


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query'])){

    //echo $auth_token;
    $query = $_POST['query'];
    $request_url = "https://api.sandbox.ebay.com/buy/browse/v1/item_summary/search?limit=10&q={$query}";

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.sandbox.ebay.com/buy/browse/v1/item_summary/search?limit=10&q=$query",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	CURLOPT_HTTPHEADER => array(
	    'Authorization: Bearer '.$auth_token ,
    	),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($response);
    $items = array();
    if (! empty($res->itemSummaries)) {
        foreach ($res->itemSummaries as $item) {
	    array_push($query_response, $item);
        }
    }
}


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['current_popularity'])){
	$pop = $_POST['current_popularity'];

	$product_id = $_POST['itemId'];
	$new_setting = 0;
	if($pop == 0){
		$new_setting = true;
	} else {
		$new_setting = false;
	}
	
	$query = "UPDATE products SET popular = '$new_setting' WHERE product_id = '$product_id'";
	if(mysqli_query($con, $query)){
	} else {
		echo "Failure";
	}

}

?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>home</title>
      <link rel="stylesheet" href="css/catalog.css">
   </head>
   <body>
      <nav class="nav_default">
         <ul class="nav_listing">
            <div class="navbox">
            <img src="../resources/Logo.png">
            <li><a href="home_page.php">Home</a></li>
            <li><a href="profile_page.php">Profile</a></li>
			<li><a href="faq.html">FAQ</a></li>
            <li><a href="catalog.php">Catalog</a></li>

            <li><a href="logout.php">Logout</a></li>
            <li style="margin-left:auto;"><button onclick="myFunction()">Toggle dark mode</button><script>
               function myFunction() {
                  var element = document.body;
                  element.classList.toggle("dark-mode");
               }
            </script>
            </li>
            </div>
         </ul>
      </nav>
      <div class="content">
         <div class="base">
            <label class="indent">Add Items</label>
	    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	      </br>
              <div class="form-item">
                <input type="text" name="query" id="query" placeholder="Search items">
              </div>
	      </br>
              <div class="form-btns">
                <button class="signup" type="submit">Search</button>          
              </div>
            </form>
            <div id="drivers" class="scroll_box">
               <ul id="drivers_list" class="scroll_list">
		  <?php
		  foreach($query_response as $res){
		    if($res->image->imageUrl){
		      $img = $res->image->imageUrl;
		      $price = $res->price->value;
		      
		      $points = $res->price->value * $conversionRate;
		      $title = $res->title;
		      echo "
			<li class='driver_entry'><img src=$img width='60px' height='60px'><p>$title</br>Points: $points</p><form action='add_item.php' method='post'><input type='submit' name='item_add' value='Add' /><input type='hidden' id='itemId' name='itemId' value=$res->itemId><input type='hidden' id='itemTitle' name='itemTitle' value='$title'><input type='hidden' id='itemPoints' name='itemPoints' value=$price><input type='hidden' id='img_src' name='img_src' value=$img></form></li>
			";
		    } else {
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
            <button onclick>Popularity</button>
         <div id="catalog" class="scroll_box">
            <ul id="catalog_listing" class="scroll_list">
               

  	      <?php
                 require_once "Database/config.php";

		$catalog_id = 0;

		$query = "SELECT catalog_id FROM catalog WHERE org_id = {$_SESSION['org_id']}";
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
    		    echo "Oops! Something went wrong. Please try again later.";
  		}


        $query = "SELECT * FROM products WHERE catalog_id = '$catalog_id'";
        $result = mysqli_query($con, $query);
		$curl = curl_init();
		$formatted_product_ids = "";
	    $flag = 0;

        for($x = 0; $x < ($result->num_rows); $x++){
			$row = $result->fetch_assoc();
			$img = $row['image_src'];
			$pts = $row['points'] * $conversionRate;
			$title = $row['title'];
		    $id = $row['product_id'];
		    $product_id = $row['product_id'];
			$is_popular = $row['popular'];
			$hidden = $row['hidden'];
		    $formatted_product_id = str_replace('|', '%7c', $product_id);
			if($hidden == 0){
				if($is_popular == 0){
					echo "
					<li class='driver_entry'><img src=$img width='60px' height='60px'><p>$title</br>Points: $pts</p><form action='remove_item.php' method='post'><input type='submit' name='item_remove' value='Remove' /><input type='hidden' id='itemId' name='itemId' value=$id></form>  <form action='catalog.php' method='post'><input type='submit' name='change_popularity' value='Set Popular' /><input type='hidden' id='current_popularity' name='current_popularity' value=$is_popular><input type='hidden' id='itemId' name='itemId' value=$id></form></li>
					";
				} else {
					echo "
					<li class='driver_entry'><img src=$img width='60px' height='60px'><p style='font-weight:bold;'>★ $title</br>Points: $pts</p><form action='remove_item.php' method='post'><input type='submit' name='item_remove' value='Remove' /><input type='hidden' id='itemId' name='itemId' value=$id></form>  <form action='catalog.php' method='post'><input type='submit' name='change_popularity' value='Set Popular' /><input type='hidden' id='current_popularity' name='current_popularity' value=$is_popular><input type='hidden' id='itemId' name='itemId' value=$id></form></li>
					";
				}
			}
			
		    if($flag == 0){
  		        $formatted_product_ids .= $formatted_product_id;
		    }else {
		        $formatted_product_ids .= ",";
			$formatted_product_ids .= $formatted_product_id;
		    }

		    $flag = 1;
		}
/*
		 $url = "https://api.sandbox.ebay.com/buy/browse/v1/item/?item_ids=$formatted_product_ids";
                    
	 	 curl_setopt_array($curl, array(
  		     CURLOPT_URL => $url,
  		     CURLOPT_RETURNTRANSFER => true,
  		     CURLOPT_ENCODING => '',
		     CURLOPT_MAXREDIRS => 10,
  		     CURLOPT_TIMEOUT => 0,
  		     CURLOPT_FOLLOWLOCATION => true,
  		     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  		     CURLOPT_CUSTOMREQUEST => 'GET',
  		     CURLOPT_HTTPHEADER => array(
    			'Authorization: Bearer '.$lookup_token ,
  		     ),
		 ));


		$response = curl_exec($curl);
		$res = json_decode($response);

		foreach($res->items as $item){
		      $img = $item->image->imageUrl;
		      $points = $item->price->value * 100;
		      
		}
                                        
                curl_close($curl);*/
              ?>





            </ul>
         </div>
         </div>
      </div>           
   </body>
</html>