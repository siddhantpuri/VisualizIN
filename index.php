<html>

<?php

echo "VisializIN <br />";
require_once("facebook.php");
$facebook = new Facebook(array(
  'appId'  => getenv('api_key'),
  'secret' => getenv('api_secret'),
));

$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
    $likes = $facebook->api('/me?fields=likes');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}

// This call will always work since we are fetching public data.
//$naitik = $facebook->api('/me');

?>

<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>VisualizIN</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <h1>VisualizIN</h1>

    <?php if ($user): ?>
      <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <!--<h3>PHP Session</h3>
    <pre><?php print_r($_SESSION); ?></pre>
    -->


    <?php if ($user): ?>
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
      <pre><?php // print_r($likes); ?></pre>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>


    <?php
    $like_query="select page_id,name,page_url from page where page_id in(select page_id from page_fan WHERE uid=me())";

    $response= $facebook ->api(array(
      'method' => 'fql.query',
      'query' => $like_query,));



// print_r($response);
 echo "<br /><br /><br />";
echo  $response[0]; 
$len=count($response);
$pages=array();
$name=array();
$page_url=array();

 foreach ($response as $page_detail) {
      $pages[]=$page_detail['page_id'];
      $name[]=$page_detail['name'];
      $page_url=$page_detail['page_url'];

  } 

  $rand_keys=array_rand($pages,30);

  foreach ($rand_keys as $key) {
    # code...

    echo $response[$key]['page_id'];
    echo $response[$key]['name'];
    echo $response[$key]['page_url'];
    echo "<br/>";

  }

 
 //echo $decoded;
 
?>


<script type="script/javascript">

 var jArray= <?php echo json_encode($pages ); ?>;

    for(var i=0;i<6;i++){
        alert(pages[i]);
    }

</script>


  


  </body>
</html>