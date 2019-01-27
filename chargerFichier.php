<?php
function chargerFichier() {
  $array_mdp = array();
    $handle = fopen("generate_mdp_100.txt", "r");
    if ($handle) {
      $i = 0;
      while (($buffer = fgets($handle, 4096)) !== false) {
          $array_mdp[$i] = $buffer;
          $i++;
      }
      if (!feof($handle)) {
          echo "Erreur: fgets() a échoué\n";
      }
      fclose($handle);
    }
    return $array_mdp;
  }

  function hasher($mdp)
 {
    return md5($mdp);
 }

 function reduce($hash)
 {
   //etape 1 transformer la string hash en tableau de caractère
    $tableauCaractere=str_split($hash);
   //etape 2 parcourir le tableau et prendre 6lettre et 4chiffre
    $nblettre=0;
    $nbchiffre=0;
    $nouveauMdp;
        foreach ($tableauCaractere as $key => $caractere) {
            if($nbchiffre<4||$nblettre<6){
                if (is_numeric($caractere)) {
                    if($nbchiffre<4){
                        $nbchiffre++;
                        if(empty($nouveauMdp)){
                            $nouveauMdp=$caractere;
                        }else {
                            $nouveauMdp.=$caractere;
                        }
                    }
                }else {
                    if($nblettre<6){
                        $nblettre++;
                        if(empty($nouveauMdp)){
                            $nouveauMdp=$caractere;
                        }else {
                            $nouveauMdp.=$caractere;
                        }
                    }
                }
            }
        }
   //etape 3 retourner le hash reduit
   return $nouveauMdp;
 }

  function creationRainbowTable($mdp)
  {
    $nbIterations = 4;
    $base = $mdp;
    //stock le mdp entrer par l'utlisateur
     for ($i=0; $i <$nbIterations ; $i++) { 
         $hash= hasher($mdp);
         $mdp=reduce($hash);
       }
        $string_file = $base." ".$hash."\r\n";
        file_put_contents("file_rt.txt",$string_file, FILE_APPEND);
  }

  function truc($array_mdp) {
    for($i = 0; $i < count($array_mdp); $i++) {
      creationRainbowTable($array_mdp[$i]);
    }
  }

  $array_mdp = chargerFichier();
  truc($array_mdp);


?>