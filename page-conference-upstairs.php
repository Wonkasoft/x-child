<?php
/**
 * Template Name: Full Page Conference Upstairs
 *
 * @package x-child
 * @subpackage x_child
 * @since 1.0.0
 */
?>
<meta http-equiv="X-Frame-Options" content="deny">
<div class="topbar">
  <a class="active" href="https://rockstarconnect.yourlive.site/rsc-vroom/"><i class="arrow left"></i><i class="arrow left"></i><span>EXIT TO LOBBY</span></a>
</div>
<iframe height="100%" width="100%"  src="https://rockstarconference.name/b/lou-yd6-xvr" frameborder="0" allowfullscreen></iframe>

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