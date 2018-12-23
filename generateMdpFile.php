<?php
function randomString($length = 10) {
    $str = "";
    $characters = array_merge(range('a','z'), range('0','9'));
    $max = count($characters) - 1;
    $maxChar = count($characters) - 11; //Valeur max du random sans les chiffres
    $limitNumbers = 0;
    $limitChar = 0;

    for ($i = 0; $i < $length; $i++) {
      if($limitNumbers >= 4) {
        $rand = mt_rand(0, $maxChar);
        $limitChar++;
        $str .= $characters[$rand];
      }
      else if($limitChar >= 6) {
        $rand = random_int(0, 9);
        $limitNumbers++;
        $str .= $rand;
      }
      else if(($limitChar < 6) && ($limitNumbers < 4)) {
        $rand = mt_rand(0, $max);
        if(is_numeric($characters[$rand])) {
          $limitNumbers++;
        }
        else {
          $limitChar++;
        }
        $str .= $characters[$rand];
      }
    }
    return $str;
  }

  function generateMdp($nb_mdp) {
    for($i=0; $i <= $nb_mdp; $i++) {
      file_put_contents("generate_mdp_100.txt",randomString()."\r\n", FILE_APPEND);
    }
  }

  generateMdp(100);
?>