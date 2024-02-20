<?php

require_once "Database/config.php";

$_SESSION['token_acquired'] = 0;

function grab_token(){
    exec('python3 python/new_token.py 2>&1', $res);
    $_SESSION['token_acquired'] = 1;
    return $res;
}

$result = grab_token();
$auth_token = $result[0];
$time_left = $result[1];

echo $auth_token;

if($_SERVER["REQUEST_METHOD"] == "POST"){

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
	    'Authorization: Bearer v^1.1#i^1#p^1#I^3#r^0#f^0#t^H4sIAAAAAAAAAOVYbWwURRjuXT9MhWIMCiKNNAtREXZvP27vY8NdvLZgr6EfeEcpTRBmd+fKtne7y85ce0dCUlppUAGxiSERA8Q0EEVU4AepqCCiSNQfKiRG+YdpUMFY0FABo7PXUq6VQEMvpon7ZzPvvPPO8zzzvjOzy3YUFT/VXdV9tcRxn3NPB9vhdDi4KWxxUeGCafnORwvz2CwHx56OeR0FnfkXFiGQiJvSsxCZho5gWSoR15GUMQaopKVLBkAaknSQgEjCihQJ1SyVeIaVTMvAhmLEqbJwZYDyAQWqglvmWR4IPsVNrPrNmFEjQIluwPncsugBIuBFUSb9CCVhWEcY6DhAkXE8zbE0L0ZZThK8ktvD+PzeJqqsAVpIM3TiwrBUMANXyoy1srDeGSpACFqYBKGC4dCSSF0oXLm4NrrIlRUrOKxDBAOcRKNbFYYKyxpAPAnvPA3KeEuRpKJAhChXcGiG0UGl0E0w9wA/I7Xsl/1+3u8HMKZAIntOpFxiWAmA74zDtmgqHcu4SlDHGk7fTVGihtwCFTzcqiUhwpVl9mtZEsS1mAatALW4PLQyVF9PBauBYsgroEwjQEfKG2mg8j7V7fN4aB8ve7wxHzs8xVCcYYHHzFFh6Kpmy4XKag1cDgleOFYVPksV4lSn11mhGLaxZPsJI+pxTfZyDq1fEq/V7RWFCSJBWaZ5d+1HRmNsaXISw5EIYzsy4gQoYJqaSo3tzGThcOKkUIBai7EpuVzt7e1Mu8AYVrOLZ1nO1VizNKKshQlAjfjata7dfQCtZajYWZVCmoTTJsGSIllKAOjNVFDwu72iMKz7aFjBsdZ/GbI4u0bXQq5qA4oeQfBCLxsTBFnxsbmojeBwerpsHFAGaToBrFaIzTgpPloheZZMQEtTJUGM8YIvBmnV44/Rbn8sRsui6qG5GIQshLKs+H3/jxIZb5JHoGJBnMMsz0GGpwW8bHm8LdnYwtasj7aBGhdW5fAyrXVdjV5V3epPNMZEb9RsERNGYLx1cFvyFXGNKBMl8+daALvWJyZClYEwVCdEL6IYJqw34pqSnlwLLFhqPbBwujyZJu0IjMfJa0JUQ6YZzuVenQOS494m7o1xrk+n//xkui0rZKfs5GJlj0ckADA1xj57GMVIuAxALh22aTVS7FonqCfEWyO31UnFmpAcYqupQ9dMJkOZQW0KY0FkJC1yw2bq7LtX1GiFOjnPsGXE49Bq4CZcyYlEEgM5DidbSecgwTUwyQ5bzmM/HNmXJsRLyRylqyfblpS7TXjIUCCO6yrtGv1JH8zLPFyn40O209HndDhYL0tzC9j5RfnLC/KnUkjDkEFAV2UjxWggxiCtWSdfrBZkWmHaBJrlLHJo359RBrN+JuxZxT4y8juhOJ+bkvVvgS291VPIPTCzhOc5lhdZTvC6PU3s3Fu9BdyMgof2vVnyUW3HwCfXDu+79PyV1PbX0lMjbMmIk8NRmFfQ6ciDK7cVX1t64kjfqcjB2aXFBx7kmn/pPbGh4eOv1m3+ZsO8i7NmDn765/3nPQc2vgMum2dTW+ZfbtjK9l/pyHty3ouRnf3Hfi8/MvDbwBf+8MIG+oeD/ez+3orSi5u/nV38eO/pDeE1x7bOqa6advSPwZ37rx4vd8x0Djy3iUlO/+toWr826+fyh6eWXtp28vT5s0VidefL677uWtHvfqDm+NO/HlrTrRRc33T4/ZJd+qsLzry36InPem58vqrnpd0/vv7Mwr63Bq9Mb+ne0XXd8cLJC84ZqX1zdlWs/2AH3Psl/UrPlBtzYfvFht7523uYtlNdux8709e1Rnjb76vceOiNvw9815Tf8+5P5/ZuSRX7zg0t4z8mJMXn5hEAAA==',
    	),
    ));

$response = curl_exec($curl);
curl_close($curl);
$res = json_decode($response);
$items = array();
if (! empty($res->itemSummaries)) {
    foreach ($res->itemSummaries as $item) {
	echo json_encode($item);
    }
    
}



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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

	    </br></br></br>
            <div class="form-item">
                <input type="text" name="query" id="query" placeholder="Search items">
            </div>
	    </br>
            <div class="form-btns">
                <button class="signup" type="submit">Search</button>          
            </div>
        </form>
    </body>
</html>
