<?php 
//le nb de fois qu'on veut boucler
//à essayer 8.39*pow(10,17) mon ordi n'est pas assez puissant pour le faire
//va prendre bcp de temps des heures et des heures c'est normal ca représente le nb de mdp possible
  $nbIterations=4;
/**
 * function de reduction du hash
 * en respectant le fait que le mdp
 * et composé de 6lettre et 4chiffre
 * @param hash l'empreinte à reduire
 */
 function reduce($hash)
 {
   //etape 1 transformer la string hash en tableau de caractère
    $tableauCaractere=str_split($hash);
   //etape 2 parcourir le tableau et prendre 6lettre et 4chiffre
    $nblettre=0;
    $nbchiffre=0;
    $nouveauMdp;
        foreach ($tableauCaractere as $key => $caractere) {
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
   //etape 3 retourner le hash reduit
   return $nouveauMdp;
 }
 /**
  * creer un hash du mdp
  *en md5
  *@param mdp le mdp à hasher
  */
  function hasher($mdp)
 {
    return md5($mdp);
 }
 /**
  * creation de la rainbow table
  *@param mdp le mdp choisie par user
  */
  function creationRainbowTable($mdp)
  {
    global $nbIterations ;
    //stock le mdp entrer par l'utlisateur
    $rainbowTable["mdp"]=$mdp;
    $hash=null;
     for ($i=0; $i <$nbIterations ; $i++) { 
         $hash= hasher($mdp);
         echo "</br> le hash: ".$hash."</br>";
         $mdp=reduce($hash);
         echo "</br> le mdp equivalent: ".$mdp."</br>";
     }
        $rainbowTable["hash"]=$hash;
        var_dump($rainbowTable);
        return $rainbowTable;
  }

  /**
   * Fonction de génération de mots de passe
   * pour peupler la rainbow table et intégrer
   * ces empreintes dans un fichier
   */
  function populationDeLaTable($array_mdp) {
    global $nbIterations ;
    for($j=0; $j < count($array_mdp); $j++) {
      for ($i=0; $i <$nbIterations ; $i++) {
         $mdp= $array_mdp[$j];
         $hash= hasher($mdp);
         echo "</br> le hash: ".$hash."</br>";
         $mdp=reduce($hash);
         echo "</br> le mdp equivalent: ".$mdp."</br>";
      }
      $string_file = $array_mdp[$j]." ".$hash."\r\n";
      file_put_contents("test_100.txt",$string_file, FILE_APPEND);
    }
  
  }
 

  /**
   * si l'empreinte est dans rainbowtable
   * on recommence les memes etapes que lors 
   * de la création de la rainbow table 
   * jusqu'a trouver la valeur à laquelle
   * et associer l'empreinte
   */
   function empreinteDansTable($rainbowTable)
  {
        global $nbIterations ;

        //récupère le mot de passe stocker dans la rainbow table
        $mdp= $rainbowTable["mdp"];
        //recupère le hash rechercher
        $hashRechercher= $rainbowTable["hash"];
        $hash=null;
     for ($i=0; $i <$nbIterations ; $i++) { 
         $hash= hasher($mdp);
         if(strcmp($hashRechercher,$hash)==0){
            return $mdp;
         }
         $mdp=reduce($hash);
         
     }
  }
  /**
   * si l'empreinte est pas dans rainbowtable
   * on recommence les memes etapes que lors 
   * de la création de la rainbow table 
   * jusqu'a trouver la valeur à laquelle
   * et associer l'empreinte
   */
  function empreintePasDansTable($hashRechercher,$rainbowTable)
  {
      
        do {
            //etape1 reduire l'empreinte
            $mdp=reduce($hashRechercher);
            //etape2 hasher 
            $hash=hasher($mdp);
        } while (!in_array($hash, $rainbowTable));
        //récupère le mot de passe stocker dans la rainbow table
        $mdp= $rainbowTable["mdp"];
        $hash=null;
        global $nbIterations ;
        var_dump($nbIterations);
        for ($i=0; $i <$nbIterations ; $i++) { 
            $hash= hasher($mdp);
            //vérifie si le hashrechercher et égal avec le hash
            if(strcmp($hashRechercher,$hash)==0){
                return $mdp;
            }
            $mdp=reduce($hash);

        }
  }

   /**
   * function pour retrouver le mdp à partir de l'empreinte md5
   * @param hash l'empreinte qu'on cherche à décoder
   * @param rainbiowTable la rainbow table créer
   */
  function dechiffrer($hash,$rainbowTable){
    if(in_array($hash, $rainbowTable)){
            //le mdp equivalent à l'empreinte rechercher
            $mdpEnclair=empreinteDansTable($rainbowTable);
            echo "</br>le mot de passe est ".$mdpEnclair." pour l'empreinte rechercher ".$hash."</br>";
    } else{
        $mdpEnclair=empreintePasDansTable($hash,$rainbowTable);
        echo "</br>le mot de passe est ".$mdpEnclair." pour l'empreinte rechercher ".$hash."</br>";

    }
  }

  /*$rainbowTable=creationRainbowTable("bb8327ceab");
    //emprunte pas dans la table mais on ces qu'elle existe dans la table
    dechiffrer("4b3b8e4668ddaac4ed693ae5171721e2",$rainbowTable);
    //emprunte dans la table 
    //dechiffrer("15ca5a6ddf070d84d9c3b6fced8885b2",$rainbowTable);*/

    populationDeLaTable(array("bb8327ceab",
                              "aaaaaa1111",
                              "reza8083zr",
                              "yjt1698qad",
                              "a2633zetqs",
                              "654azdsqf2",
                              "dfg48az56r",
                              "g5f6s3t4gv",
                              "bf896dez5z",
                              "xvdq5ze874"));

?>