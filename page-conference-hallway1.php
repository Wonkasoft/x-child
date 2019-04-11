<?php
/**
 * Template Name: Full Page Conference Hallway 1
 *
 * @package x-child
 * @subpackage x_child
 * @since 1.0.0
 */
  header('Access-Control-Allow-Origin: https://rockstarconference.name');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Rockstar | Hallway 1</title>
  <style type="text/css">
    iframe {
    height:calc(100vh - 4px);
    width:calc(100vw - 4px);
    box-sizing: border-box;
  }

  .topbar {
    background-color: hsl(0,0%,92%);
    width: 100%;
    height: 24px;
  }

  .topbar a {
    background-color: hsl(6,100%,37%);
    text-decoration: none;
    display: inline-block;
    padding: 3px 20px;
    color: #fff;
    transition: all .3s ease-in-out;
    -webkit-transition: all .3s ease-in-out;
  }

  .topbar a:hover {
    background-color: hsl(6,100%,42%);
    padding: 3px 35px;
  }

  i {
    border: solid #fff;
    border-width: 0 3px 3px 0;
    display: inline-block;
    padding: 3px;
  }

  .left {
    transform: rotate(135deg);
    -webkit-transform: rotate(135deg);
  }

  .topbar span {
    padding-left: 5px; 
    transition: all .3s ease-in-out;
    -webkit-transition: all .3s ease-in-out;
  }

  .topbar span:hover {
    padding-left: 12px;  
  }

  body {
    margin:0;
  }

  </style>
</head>
<body>

  <div class="topbar">
    <a class="active" href="https://rockstarconnect.yourlive.site/rsc-vroom/"><i class="arrow left"></i><i class="arrow left"></i><span>EXIT TO LOBBY</span></a>
  </div>
  <?php 
  
  function dlPage($href) {

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_URL, $href);
  curl_setopt($curl, CURLOPT_REFERER, $href);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
  $str = curl_exec($curl);
  curl_close($curl);

  // Create a DOM object
  $dom = new simple_html_dom();
  // Load HTML from a string
  $dom->load($str);

  return $dom;
  }

  $url = 'https://rockstarconference.name/b/lou-yd6-xvr/';
  $data = dlPage($url);
  print_r($data);

 ?>
</body>
</html>
