<?php

include('../oirearvio_api.php');

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
    } else if ($_POST['helper'] == 'repo_yes') {
      echo 'Alright. Do you have any of these symptoms?';
      echo '<span id="introtext"><p>&nbsp;</p></span>';
      echo '<div class="s_item">ad</div>';
      echo '<div class="s_item">ad</div>';
      echo '<div class="s_item">ad</div>';
      echo '<div class="s_item">ad</div>';
      echo '<div class="s_item">ad</div>';
      echo '<div class="s_item">ad</div>';
      echo '<div class="s_item">ad</div>';
      echo '<div class="s_item">ad</div>';
      echo '<div data-anwer="addition_no" class="s_item">No</div>';
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
    display: 'value',
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
    suggestion: Handlebars.compile('<div class="queryfield" data-id="{{id}}"> {{value}}</div>')
    }
});


<?php if ($_POST['helper'] == 'init_yes') { ?>

$(function(){
    document.getElementById('inputbox').focus();
});

<?php } ?>

</script>
