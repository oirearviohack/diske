<?php
include('diske_hfir_class.php');
include_once('../oirearvio_api.php');
@session_start();

if (!isset($_SESSION['oire'])) {
  $_SESSION['oire'] = new oa();
}
if ($_POST['state'] == 's_item') {
  if (isset($_POST['helper'])) {
    if ($_POST['helper'] == 'init_yes') {

      echo 'Alright. Please, start typing your symptom.';
      echo '<span id="introtext"><p>&nbsp;</p></span>';
      echo '<div id="inputwrap"><input id="inputbox" class="inputbox" type="text"/></div>';
    } else if ($_POST['helper'] == 'init_no') {
      echo 'Well, allrighty then. Behave!';
      echo '<span id="introtext"><p>&nbsp;</p></span>';
      echo '<div data-answer="start_over" class="s_item">Start over</div>';
    } else if ($_POST['helper'] == 'repo_no') {
      echo 'Oh great! Do you have any other symptoms now?';
      echo '<span id="introtext"><p>&nbsp;</p></span>';
      echo '<div data-answer="init_yes" class="s_item yes">Yes</div>';
      echo '<div data-answer="init_no" class="s_item no">No</div>';
    } else if ($_POST['helper'] == 'repo_yes' || $_POST['helper'] == 'addition_yes') {
      $symptom = $_POST['symptom'];
      $id = $_POST['id'];
      if (!empty($_POST['symptom'])) {
        $id = '137';
      }
      $_SESSION['oire']->registerOire($id);

      echo 'Alright. Do you have any of these symptoms?';
      echo '<span id="introtext"><p>&nbsp;</p></span>';
      echo '<div data-answer="addition_no" class="s_item">No</div>';
      if (count($_SESSION['oire']->diseases) > 3) {
        foreach ($_SESSION['oire']->symptomsuggestions as $k => $v) {
          echo '<div class="s_item" data-answer="addition_yes" data-id="'.$v['id'].'">'.$v['name'].'</div>';
        }

      } else {
        foreach ($_SESSION['oire']->diseases as $k => $v) {

          foreach ($v as $k2 => $v2) {
            if ($k2 == 'name') {
              $_d .= $v2.', ';
            }
          }
          echo 'Possible diagnoses(s): '; echo substr($_d, 0, -2);
          echo '<span id="introtext"><p>&nbsp;</p></span>';
          echo '<div data-answer="addition_no" class="s_item">Create careplan</div>';
        }
      }


    } else if ($_POST['helper'] == 'addition_no') {

      foreach ($_SESSION['oire']->diseases as $k => $v) {

        foreach ($v as $k2 => $v2) {
          if ($k2 == 'name') {
            $_d .= $v2.', ';
          }
        }

      }
      echo 'Possible diagnoses(s): '; echo substr($_d, 0, -2);
      fb_hfir::createCareplan(2521, 'Possible diagnose(s) are '.$_d.'. Contact your local healthcare station for scheduling an appoitment. ');
      echo '<br /><br />Careplan created.';
      echo '<span id="introtext"><p>&nbsp;</p></span>';
      echo '<div data-answer="start_over" class="s_item">Start over</div>';
    }
  } else {
    echo 'Huh, I am not sure what happened, but something went bad.';
    echo '<span id="introtext"><p>&nbsp;</p></span>';
    echo '<div data-answer="start_over" class="s_item">Start over</div>';
  }

}

?>

<script src="js/typeahead.bundle.js"></script>
<script src="js/handlebars.js"></script>
<script>

var myAccentMap = { "ä": "a", "ö": "o", "ß": "s", "ü": "u" };

var symptoms = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: 'symptom_search.php?key=%QUERY',
      wildcard: '%QUERY'
    }
});

$('#inputwrap .inputbox').typeahead(null, {
    name: 'symptomsearch',
    display: 'name',
    hint: true,
    highlight: true,
    source: symptoms,
    limit: 200,
    accentmap: myAccentMap,
    templates: {
      empty: [
          '<div class="empty-message">',
          'No matching symptoms.',
          '</div>'
      ].join('\n'),
    suggestion: Handlebars.compile('<div class="queryfield intsmp" data-symptom="{{value}}" data-id="{{id}}"> {{name}}</div>')
    }
});


<?php if ($_POST['helper'] == 'init_yes') { ?>

$(function(){
    document.getElementById('inputbox').focus();
});

<?php } ?>

</script>
