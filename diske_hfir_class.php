<?php

class fb_hfir {

  private function fetchData($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'HAPI-FHIR/2.4 (FHIR Client; FHIR 3.0.1/DSTU3; apache)');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if(!$resp = curl_exec($curl)){
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    } else {
      $_vArr = json_decode($resp, true);
      return $_vArr;
    }
    curl_close($curl);
  }

  public static function getPatient($id) {
     $url = "https://oda.medidemo.fi/phr/baseDstu3/Patient/$id?_pretty=true";
    return fb_hfir::fetchData($url);
  }

  public static function createCareplan($pId, $description) {

    $data = array(
      "resourceType" => "CarePlan",
      "id" => "111234",
      "meta" => array("versionId" => "1", "lastUpdated" => "2017-05-05T22:00:05.922+03:00"),
      "subject" => array("reference" => "Patient/$pId"),
      "status" => "active",
      "description" => "'.$description.'",
    );

    $data_string = json_encode($data);

    $ch = curl_init('https://oda.medidemo.fi/phr/baseDstu3/CarePlan?_format=json&_pretty=true');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/fhir+json; charset=UTF-8',
      'Content-Length: ' . strlen($data_string),
      'Accept-Encoding: gzip',
      'Accept-Charset: utf-8',
      'User-Agent: HAPI-FHIR/2.4 (FHIR Client; FHIR 3.0.1/DSTU3; apache)',
      'Accept: application/fhir+json;q=1.0, application/json+fhir;q=0.9',
    ));

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }

  public static function getObservations($id, $loinc = null) {
    $url = "https://oda.medidemo.fi/phr/baseDstu3/Observation?patient=$id&_pretty=true";
    if ($loinc != null) {
      $_vArr = fb_hfir::fetchData($url);
      foreach ($_vArr as $key => $value) {
        foreach ($value as $key => $value2) {
          foreach ($value2 as $key => $value3) {
            foreach ($value3 as $key => $value4) {
              foreach ($value4 as $key => $value5) {
                foreach ($value5 as $key => $value6) {
                  foreach ($value6 as $key => $value7) {
                    if ($value7 === $loinc) {
                      $_arr = array('value' => $value3['valueQuantity']['value'], 'unit' => $value3['valueQuantity']['unit']);
                    }
                  }
                }
              }
            }
          }
        }
      }
      foreach ($_arr as $key => $value) {
        if ($key === 'value') {
          $_v = $value;
        } else if ($key === 'unit') {
          $_u = $value;
        }
      }
      return 'Last observation for loinc '.$loinc.' is '.$_v.' '.$_u.'.';
    } else {
      $_vArr = fb_hfir::fetchData($url);
      foreach ($_vArr as $key => $value) {
        foreach ($value as $key => $value2) {
          foreach ($value2 as $key => $value3) {
            foreach ($value3 as $key => $value4) {
              foreach ($value4 as $key => $value5) {
                foreach ($value5 as $key => $value6) {
                  foreach ($value6 as $key => $value7) {

                      $_k .= $value3['valueQuantity']['value'].' '.$value3['valueQuantity']['unit'].' ';
                  }
                }
              }
            }
          }
        }
      }
    }
    return $_k;
  }

  public static function getDiagnostics($id, $text = false) {
    $url = "https://oda.medidemo.fi/phr/baseDstu3/DiagnosticReport?patient=$id&_format=json&_pretty=true";
    if ($text == true) {
      $_vArr = fb_hfir::fetchData($url);
      foreach ($_vArr as $key => $value) {
        foreach ($value as $key => $value2) {
          foreach ($value2 as $key => $value3) {
            foreach ($value3 as $key => $value4) {
              foreach ($value4 as $key => $value5) {
                if ($key === 'div') {
                    return $value5;
                }

              }
            }
          }
        }
      }
    } else {
      return fb_hfir::fetchData($url);
    }
  }

}

?>
