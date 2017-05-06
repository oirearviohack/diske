<?php

class oa {

	public static $conn = array('server' => "", 'login' => "", 'pass' => "", 'db' => "");

	public $oireet;
	public $diseases;
	public $symptomsuggestions;
	public $similars;

	public function __construct($oire = false) {
		if ($oire) {
			$this->similars = self::findOire($oire);
		}
	}

	public static function findOire($oire) {

		foreach (explode(" ",$oire) as $o) {
			$oireet[] = "soundex(name) like concat('%',soundex('$o'),'%') or name like '%$o%'";
		}

		$symptoms = "select id, name from symptom_table where ".implode(" or ", $oireet)." or name like '%$oire%'";

		$retVal['symptoms'] = self::sqlDo($symptoms);

		return $retVal;
	}

	public  function registerOire($oire) {
		$this->oireet[] = $oire;
		$sql = "select a.id, a.name from disease_table a left join symptom_map b on b.disease = a.id where b.symptom = $oire";
		$retVal['diseases'] = self::sqlDo($sql);
		foreach ($retVal['diseases'] as $disease) {
			if (!array_key_exists($disease['id'], $this->diseases)) {
				$this->diseases[$disease['id']] = $disease;
				$this->diseases[$disease['id']]['score'] = 1;
			} else {
				$this->diseases[$disease['id']]['score']++;
			}
		}
		usort($this->diseases, function ($a, $b) {
    		return $a['score'] - $b['score'];
		});
		$highest = 0;
		foreach ($this->diseases as $disease) {
			if ($disease['score'] > $highest) {
				$highest = $disease['score'];
			}
		}
		foreach ($this->diseases as $idx => $disease) {
			if ($disease['score'] < $highest) {
				unset($this->diseases[$idx]);
			} else {
				$ehdot[] = "b.disease = $idx";
			}
		}
		$sql = "select distinct a.id, a.name from symptom_table a left join symptom_map b on b.symptom = a.id where ".implode(" or ", $ehdot);
		$this->symptomsuggestions = self::sqlDo($sql);
		foreach ($this->symptomsuggestions as $idx => $smptm) {
			if (in_array($smptm['id'], $this->oireet)) {
				unset($this->symptomsuggestions[$idx]);
			}
		}
	}



	public static function sqlDo($sql) {
		foreach (self::$conn as $idx => $val) {
			$$idx = $val;
		}
		$sr = new mysqli($server, $login, $pass, $db);
		$res = $sr->query($sql);
		if ($res) {
			while ($row = $res->fetch_assoc()) {
				$retval[] = $row;
			}
		} else {
			$retval = $res;
		}
		return $retval;
	}


}
/**
$oire = new oa("Eye manifestations");
//
$oire->registerOire(30);
$oire->registerOire(25);
echo "<pre>";
print_r($oire);
echo "</pre>";
**/

?>
