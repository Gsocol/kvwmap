<?

	$GUI = $this;	
	
	$this->bauauskunftSuche = function() use ($GUI){
    $GUI->bau = new Bauauskunft($GUI->baudatabase);
    $GUI->bau->readvorhaben();
    $GUI->bau->readverfahrensart();
    $GUI->bau->readaktualitaet();

    # Abfragen f�r welche Gemeinden die Stelle Zugriffsrechte hat
    # GemeindenStelle wird eine Liste mit ID�s der Gemeinden zugewiesen, die zur Stelle geh�ren
    $GemeindenStelle=$GUI->Stelle->getGemeindeIDs();
    $Gemarkung=new gemarkung('',$GUI->pgdatabase);
		if($GemeindenStelle == NULL){
			$GUI->GemkgListe=$Gemarkung->getGemarkungListe(NULL, NULL);
		}
		else{
			$GUI->GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
    $GUI->main = PLUGINS.'probaug/view/bauauskunftsuche.php';
    $GUI->titel='Bauauskunftsuche';
  };

	$this->bauauskunftSucheSenden = function($flurstkennz) use ($GUI){
    $GUI->bau = new Bauauskunft($GUI->baudatabase);
    if($GUI->formvars['flurstkennz'] != ''){
      $GUI->formvars['flurstkennz'] = $flurstkennz;
    }
    if($GUI->bau->checkformdaten($GUI->formvars)){
      if(!$GUI->formvars['anzahl']){
        $GUI->formvars['anzahl'] = $GUI->bau->countbaudaten($GUI->formvars);
      }
      $searchvars  = $GUI->bau->getbaudaten($GUI->formvars);
      $GUI->formvars['gemarkung'] = $searchvars['gemarkung'];
      $GUI->formvars['flur'] = $searchvars['flur'];
      $GUI->formvars['flurstueck'] = $searchvars['flurstueck'];

      for($i = 0; $i < count($GUI->bau->baudata); $i++){
        $gemarkungs_searchvars['jahr'] = $GUI->bau->baudata[$i]['feld1'];
        $gemarkungs_searchvars['obergruppe'] = $GUI->bau->baudata[$i]['feld2'];
        $gemarkungs_searchvars['nummer'] = $GUI->bau->baudata[$i]['feld3'];
        $baudata = $GUI->bau->getbaudaten2($gemarkungs_searchvars);
        $Gemarkung=new gemarkung('13'.$baudata[0]['feld12'],$GUI->pgdatabase);
        $GUI->bau->baudata[$i]['bauort'] = $Gemarkung->getGemkgName();
      }
      $GUI->main = PLUGINS.'probaug/view/bauauskunftsuchergebnis.php';
      $GUI->titel='Suchergebnis';
    }
    else{
      $GUI->main = PLUGINS.'probaug/view/bauauskunftsuche.php';
      $GUI->titel='Bauauskunftsuche';
    }
  };

	$this->bauauskunftanzeige = function() use ($GUI){
    $GUI->bau = new Bauauskunft($GUI->baudatabase);
    $GUI->bau->getbaudaten($GUI->formvars);
    for($i = 0; $i < count($GUI->bau->baudata); $i++){
			$flst = explode(', ', $GUI->bau->baudata[$i]['feld14']);
			for($j = 0; $j < count($flst); $j++){
				$GUI->bau->grundstueck[] = '13'.$GUI->bau->baudata[$i]['feld12'].'-'.$GUI->bau->baudata[$i]['feld13'].'-'.$flst[$j];
			}
    }
    $Gemarkung=new gemarkung($GUI->bau->baudata[0]['feld12'],$GUI->pgdatabase);
    $GUI->bau->baudata[0]['bauort'] = $Gemarkung->getGemkgName();
    $GUI->main = PLUGINS.'probaug/view/bauauskunftanzeige.php';
    $GUI->titel='Baudatenanzeige';
  };
	
?>