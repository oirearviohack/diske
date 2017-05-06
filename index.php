<?php
include('diske_hfir_class.php');
echo '<html>';
  echo '<body>';
?>

<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<style>

body {
  background-color: #2980b9;
  background-image: url('img/od.png');
  background-repeat: repeat;
}

.shadow {
  -webkit-box-shadow: 3px 3px 5px 0px #333;  /* Safari 3-4, iOS 4.0.2 - 4.2, Android 2.3+ */
  -moz-box-shadow:    3px 3px 5px 0px #333;  /* Firefox 3.5 - 3.6 */
  box-shadow:         3px 3px 5px 0px #333;  /* Opera 10.5, IE 9, Firefox 4+, Chrome 6+, iOS 5 */
}

.talk-bubble {
  position: relative;
  top:-30px;
  left:-85px;
  z-index: 0;
	margin: 40px;
  display: inline-block;
  position: relative;
	width: 300px;
	height: 500px;
	background-color: #ecf0f1;
  border-radius: 2px;
}

.debug-bubble {
  position: absolute;
  top:158px;
  left:605px;
  z-index: 0;
	margin: 40px;
  display: inline-block;
	width: 400px;
	max-height: 300px;
  min-height: 300px;
  background-color: #ecf0f1;
  border-radius: 2px;
  padding:10px;
  font-family: 'Roboto', sans-serif;

}

.tri-right.left-in:after{
	content: ' ';
	position: absolute;
	width: 0;
	height: 0;
  left: -20px;
	right: auto;
  top: auto;
	bottom: 20px;
	border: 12px solid;
	border-color: #ecf0f1 #ecf0f1 transparent transparent;
}

.talktext {
  font-family: 'Roboto', sans-serif;
  padding: 1em;
	text-align: left;
  line-height: 1.5em;
}

.talktext p {
  /* remove webkit p margins */
  -webkit-margin-before: 0em;
  -webkit-margin-after: 0em;
}

#ukko {
  float: left;
  width:300px;
  overflow: hidden;
  height: 100%;
}

.ukko {
  position: relative;
  top:200px;
  z-index: 1;
  width:300px;
}

#inputwrap {
  position: absolute;
  bottom:5px;
  width:290px;
  left:5px;
  z-index: 3;
}

.inputbox {
  padding: 5px;
  width:100%;
  height:30px;
  outline: none;
  border: none;
  font-family: 'Roboto', sans-serif;
  background-color: #bdc3c7;
  position: relative;
  z-index: 3;
}

.yes:hover {
  color: #ffffff !important;
  background-color: #27ae60 !important;
  border: 1px solid #27ae60 !important;
  z-index: 4;
  position: relative;
}

.no:hover {
  color: #ffffff !important;
  background-color: #c0392b !important;
  border: 1px solid #c0392b !important;
  z-index: 4;
  position: relative;
}

.yes {
  color: #27ae60;
}

.no {
  color: #c0392b;
}

.yesno {
  position: absolute;
  margin-left: 90px;
  top:200px;
  font-size: 22px !important;
}

.s_item {
  border: 1px solid #bdc3c7;
  padding: 5px;
  width:280px;
  position: relative;
  left:-12px;
  margin-bottom: -1px;
  text-align: center;
  -webkit-transition: all 200ms ease-in;
    -webkit-transform: scale(1);
    -ms-transition: all 200ms ease-in;
    -ms-transform: scale(1);
    -moz-transition: all 200ms ease-in;
    -moz-transform: scale(1);
    transition: all 200ms ease-in;
    transform: scale(1);
}

.s_item:hover {
  cursor: pointer;
  background-color: #bdc3c7;
  -webkit-transition: all 200ms ease-in;
    -webkit-transform: scale(1.1);
    -ms-transition: all 200ms ease-in;
    -ms-transform: scale(1.1);
    -moz-transition: all 200ms ease-in;
    -moz-transform: scale(1.1);
    transition: all 200ms ease-in;
    transform: scale(1.1);
}

#inputwrap .tt-dropdown-menu {
  max-height: 150px;
  overflow-y: auto;
}

</style>

<?php

$uid = '2521';

echo '<div id="ukko">';
  echo '<img class="ukko" src="img/ukko.png" />';
echo '</div>';
echo '<div class="talk-bubble tri-right left-in shadow">';
  echo '<div class="talktext">';
    if (!isset($_GET['unlink'])) {
      $p = fb_hfir::getPatient($uid); // potilaan tiedot id:llä
      $greeting = '<b>Hi, '.$p['name'][0]['given'][0].' '.$p['name'][0]['family'].'!</b> I found that you had xx . Does this symptom still occure?';
      echo '<span id="greet">'.$greeting.'</span>';
      echo '<span id="introtext"><p>&nbsp;</p></span>';
      echo '<div data-answer="repo_yes" class="s_item yes">Yes</div>';
      echo '<div data-answer="repo_no" class="s_item no">No</div>';
      //echo '<div class="yesno"><span data-answer="yes" class="yes">YES</span> | <span data-answer="no" class="no">NO</span></div>';
    } else {
      $greeting = '<b>Hi, stranger!</b> Do you have any symptoms?';
      echo '<span id="greet">'.$greeting.'</span>';
      echo '<span id="introtext"><p>&nbsp;</p></span>';
      echo '<div data-answer="init_yes" class="s_item yes">Yes</div>';
      echo '<div data-answer="init_no" class="s_item no">No</div>';
      //echo '<div class="yesno"><span data-answer="yes" class="yes">YES</span> | <span data-answer="no" class="no">NO</span></div>';
    }



  echo '</div>';
  echo '</div>';
echo '<div class="debug-bubble shadow">';
  echo 'DEBUG DATA';
  echo '<div id="debuggi" style="font-size:10px; position: relative; top: 10px;"></div>';
echo '</div>';





/*echo '<pre>';
echo '<h2>Potilaan data</h2>';

echo '<h2>Päivämäärät</h2>';
$_oArr = fb_hfir::getObservations('PATIENT1', '8310-5'); // kaikki potilaan 'observationit' | jos tossa on toisena argumenttina joku loinc-koodi niin palauttaa viimeisimmän arvon | 8665-2 = vikat kuukautiset, 29463-7 = paino
print_r($_oArr);
echo '</pre>';*/

//fb_hfir::createCareplan(932, 'Pälä pälä pälä lässyn lää 2.');

  echo '</body>';
echo '</html>';

$tstamp = date('G.i', time());

?>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script>

<?php if (!isset($_GET['unlink'])) { ?>

  $('#debuggi').html('<?php echo '['.$tstamp.']'; ?> https://oda.medidemo.fi/phr/ with <b>uid <?php echo $uid; ?></b> initialized.');

<?php } else { ?>

  $('#debuggi').html('<?php echo '['.$tstamp.']'; ?> No external data repositories available.');

<?php } ?>

$(document).on('click', '.s_item', function() {
  var helper = $(this).data('answer');

  if (helper == 'start_over') {
    $(location).attr('href','http://www.fudbot.com/oda/?unlink');
  } else {
    $.ajax({
    type: 'POST',
      url: 'oirearvio_ajax.php',
      cache: false,
      data: { 'state' : 's_item', 'helper' : helper },
      success: function(html) {
        $('.talktext').empty();
        $('.talktext').prepend(html);
      }
    })
  }
});

</script>
