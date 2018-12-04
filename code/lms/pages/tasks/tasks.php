<?php session_start();include "../../secure/talk2db.php";include "../../../functions/processClassTask.php";?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../../css/style.css">
<link href="../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery.js"></script><script src="../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../css/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="../../js/jsDatePick.min.1.3.js"></script>
<script type="text/javascript" src="../../js/jquery.js"></script>
<!--Fancybox-->
<!-- Add jQuery library -->
<script type="text/javascript" src="../../js/fancybox/lib/jquery-1.10.1.min.js"></script>
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="../../js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="../../js/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="../../js/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="../../js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="../../js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="../../js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="../../js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

  
<title>Open Learning Exchange - Ghana</title>
</head>
<script type="text/javascript">
$(document).ready(function(){
	/*$('#Vbook').click(function () {
    	$("#videoBooks").slideToggle("slow");
	});
	$('#AStory').click(function () {
    	$("#audioStory").slideToggle("slow");
	});
	$('#Phons').click(function () {
    	$("#phonomics").slideToggle("slow");
	});
	$('#WordP').click(function () {
    	$("#wordPower").slideToggle("slow");
	});
	*/
});
</script>
<script type="text/javascript">
$(".fancybox").fancybox({
    	maxWidth	: '100%',
		fitToView	: false,
		width		: '100%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
});
<!--<a class="fancybox" data-fancybox-type="iframe" href="http://samplepdf.com/sample.pdf">Test pdf</a>-->
</script>
<body>
<form name="form1" method="post" action="">
  <div id="wrapper" style="background-color:#FFF; width:600px; height:700px;">
  <div id="rightContent" style="text-align:center; margin-left:auto; margin-right:auto; width:580px;">
       <div id="taskIcon"><img src="../../images/tasks/btn_mylibrary.png" alt="" name="" width="150" height="150" usemap="#Map" border="0">
         <map name="Map">
           <area shape="rect" coords="3,3,151,156" href="pages/Stories4week.php?memberId=<?php echo $_GET['memberId']?>" data-fancybox-type="iframe"  class="fancybox">
         </map>
       </div>
       <div id="taskIcon"><img src="../../images/tasks/btn_audiobooks.png" alt="" name="" width="150" height="150" usemap="#Map2" border="0">
         <map name="Map2">
           <area shape="rect" coords="4,3,165,156" href="pages/assign_audio.php?memberId=<?php echo $_GET['memberId']?>" data-fancybox-type="iframe"  class="fancybox">
         </map>
       </div>
       <div id="taskIcon"><img src="../../images/tasks/btn_videobook.png" alt="" name="" width="150" height="150" usemap="#Map3" border="0">
         <map name="Map3">
           <area shape="rect" coords="3,3,148,148" href="pages/assign_videoBook.php?memberId=<?php echo $_GET['memberId']?>" data-fancybox-type="iframe"  class="fancybox">
         </map>
       </div>
       <div id="taskIcon"><img src="../../images/tasks/btn_phonics.png" alt="" name="" width="150" height="150" usemap="#Map4" border="0">
         <map name="Map4">
           <area shape="rect" coords="3,4,147,150" href="#" data-fancybox-type="iframe"  class="fancybox">
         </map>
       </div>
       <div id="taskIcon"><img src="../../images/tasks/btn_listen_find.png" alt="" name="" width="150" height="150" usemap="#Map5" border="0">
         <map name="Map5">
           <area shape="rect" coords="3,3,165,169" href="pages/listen_and_find.php?memberId=<?php echo $_GET['memberId']?>" data-fancybox-type="iframe"  class="fancybox">
         </map>
       </div>
       <div id="taskIcon"><img src="../../images/tasks/btn_filltheblanks.png" alt="" name="" width="150" height="150" usemap="#Map6" border="0">
         <map name="Map6">
           <area shape="rect" coords="0,2,162,155" href="pages/fill_in_blanks.php?memberId=<?php echo $_GET['memberId']?>" data-fancybox-type="iframe"  class="fancybox">
         </map>
       </div>
       <div id="taskIcon"><img src="../../images/tasks/btn_wordformation.png" alt="" name="" width="150" height="150" usemap="#Map7" border="0">
         <map name="Map7">
           <area shape="rect" coords="4,3,149,154" href="pages/word_formation.php?memberId=<?php echo $_GET['memberId']?>" data-fancybox-type="iframe"  class="fancybox">
         </map>
       </div>
       <div id="taskIcon"><img src="../../images/tasks/btn_unjumble.png" alt="" name="" width="150" height="150" usemap="#Map8" border="0">
         <map name="Map8">
           <area shape="rect" coords="4,3,154,164" href="pages/unjumble_words.php?memberId=<?php echo $_GET['memberId']?>" data-fancybox-type="iframe"  class="fancybox">
         </map>
       </div>
       <div id="taskIcon"><img src="../../images/tasks/btn_wordpower.png" alt="" name="" width="150" height="150" usemap="#Map9" border="0">
         <map name="Map9">
           <area shape="rect" coords="4,3,154,164" href="pages/word_power.php?memberId=<?php echo $_GET['memberId']?>" data-fancybox-type="iframe"  class="fancybox">
         </map>
      </div>
   </div>
</div>
</form>

<script type="text/javascript">

</script>
</body>
</html>
