<?php
#--------------------------------------------------------------------------------------------------------------
##############
# Klasse ALB #
##############

class ALB {
  # Mitteilungen zum Einlesen einer WLDGE-Datei
  var $Protokoll_Einlesen;
  # Mitteilungen zum Aktualisieren vorhandener ALB-Tabellen
  var $Protokoll_Aktualisieren;
  var $debug;
  # $WLDGE_Datei - array f�r die WLDGE-Datei ['tmp_name']...Dateiname mit Pfadangabe zur Datei auf Server
  # ['name'] Dateiname auf dem Client Rechner, von dem die Datei hochgeladen wurde (mu� nicht)
  var $WLDGE_Datei;
  # Datei enth�lt nach dem Einlesen der WLDGE-Datei alle ausgef�hrten SQL-Statements
  # kann zur Import/Export, zur Kontrolle oder zum Loggen verwendet werden.
  var $WLDGE_Dump_Datei;
  # Datei enth�lt nach dem Fortf�hren der ALB-Tabellen alle ausgef�hrten SQL-Statements
  # kann zum Fortf�hren, zur Kontrolle und zum Loggen verwendet werden.
  var $WLDGE_updateDump_Datei;
  # Datenbankobjekt in der die ALB Daten vorgehalten werden
  var $database;
  # Variable, die auf 1 gesetzt wird, wenn die WLDGE-Datei auf Grund eines bei der Pr�fung
  # festgestellten Fehlers nicht eingelesen werden kann.
  var $WLDGE_Datei_fehlerhaft;

  ###################### Liste der Funktionen ####################################
  #
  # function ALB($database) - Construktor
  # function ALBAuszug($FlurstKennz,$formnummer)
  # function HausNrTextKorrektur()
  # function GrundausstattungAnlegen()
  # function WLDGE_Datei_Pruefen()
  # function WLDGE_Datei_einlesen()
  # function Fortfuehren()

  function ALB($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $database->setDebugLevel=1;
  }

  function export_klassifizierung_csv($flurstuecke, $formvars){
  if($formvars['flurstkennz']){ $csv .= 'FlstKZ;';}
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ_kurz;';}
    if($formvars['gemkgname']){ $csv .= 'Gemkg-Name;';}
    if($formvars['gemkgschl']){ $csv .= 'Gemkg-Schl.;';}
    if($formvars['flurnr']){ $csv .= 'Flur;';}
    if($formvars['gemeindename']){ $csv .= 'Gem-Name;';}
    if($formvars['gemeinde']){ $csv .= 'Gemeinde;';}
    if($formvars['kreisname']){ $csv .= 'Kreisname;';}
    if($formvars['kreisid']){ $csv .= utf8_encode('Kreisschl�ssel;');}
    if($formvars['finanzamtname']){ $csv .= 'Finanzamtname;';}
    if($formvars['finanzamt']){ $csv .= utf8_encode('Finanzamtschl�ssel;');}
    if($formvars['forstname']){ $csv .= 'Forstamtname;';}
    if($formvars['forstschluessel']){ $csv .= utf8_encode('Forstamtschl�ssel;');}
    if($formvars['flaeche']){ $csv .= utf8_encode('Flst-Fl�che ALB;');}
    if($formvars['amtsgerichtnr']){ $csv .= 'Amtsgericht;';}
    if($formvars['amtsgerichtname']){ $csv .= 'Amtsgerichtname;';}
    if($formvars['grundbuchbezirkschl']){ $csv .= utf8_encode('GBBschl�ssel;');}
    if($formvars['grundbuchbezirkname']){ $csv .= 'GBBname;';}
    if($formvars['lagebezeichnung']){ $csv .= 'Lage;';}
    if($formvars['entsteh']){ $csv .= 'Entstehung;';}
    if($formvars['letzff']){ $csv .= utf8_encode('Fortf�hrung;');}
    if($formvars['karte']){ $csv .= 'Flurkarte;';}
    if($formvars['status']){ $csv .= 'Status;';}
    if($formvars['vorgaenger']){ $csv .= 'Vorgaenger;';}
    if($formvars['nachfolger']){ $csv .= 'Nachfolger;';}
    $csv .= utf8_encode('Klassifizierung-ALB;Klass-Fl�che-ALB;EMZ-ALB;Klassifizierung-ALK;Klass-Fl�che-ALK;EMZ-ALK;gesamt;');
    if($formvars['freitext']){ $csv .= 'Freitext;';}
    if($formvars['hinweis']){ $csv .= 'Hinweis;';}
    if($formvars['baulasten']){ $csv .= 'Baulasten;';}
    if($formvars['ausfstelle']){ $csv .= utf8_encode('ausf�hrende Stelle;');}
    if($formvars['verfahren']){ $csv .= 'Verfahren;';}
    if($formvars['nutzung']){ $csv .= 'Nutzung;';}
    if($formvars['blattnr']){ $csv .= 'Blattnummer;';}
    if($formvars['pruefzeichen']){ $csv .= 'P Buchung;';}
  	if($formvars['pruefzeichen_f']){ $csv .= utf8_encode('P Flurst�ck;');}
    if($formvars['bvnr']){ $csv .= 'BVNR;';}
    if($formvars['buchungsart']){ $csv .= 'Buchungsart;';}
    if($formvars['eigentuemer']){ $csv .= utf8_encode('Eigent�mer;');}
    
    $csv .= chr(10);
    for($i = 0; $i < count($flurstuecke); $i++) {
      $flurstkennz = $flurstuecke[$i];
      $flst = new flurstueck($flurstkennz,$this->database);
      $flst->readALB_Data($flurstkennz);

      for($kl = 0; $kl < count($flst->Klassifizierung)-1; $kl++){		    		           
	      if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
	      if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
	      if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
	      if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
	      if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
	      if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
	      if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
	      if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
	      if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
	      if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
	      if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
	      if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
	      if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
	      if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
	      if($formvars['amtsgerichtnr']){ $csv .= $flst->Amtsgericht['schluessel'].';';}
	      if($formvars['amtsgerichtname']){ $csv .= $flst->Amtsgericht['name'].';';}
	      if($formvars['grundbuchbezirkschl']){ $csv .= $flst->Grundbuchbezirk['schluessel'].';';}
	      if($formvars['grundbuchbezirkname']){ $csv .= $flst->Grundbuchbezirk['name'].';';}
	      if($formvars['lagebezeichnung']){
	        $anzStrassen=count($flst->Adresse);
	        for ($s=0;$s<$anzStrassen;$s++) {
	          $csv .= $flst->Adresse[$s]["gemeindename"].' ';
	          $csv .= $flst->Adresse[$s]["strassenname"].' ';
	          $csv .= $flst->Adresse[$s]["hausnr"].' ';
	        }
	        $anzLage=count($flst->Lage);
	        $Lage='';
	        for ($j=0;$j<$anzLage;$j++) {
	          $Lage.=' '.$flst->Lage[$j];
	        }
	        if ($Lage!='') {
	          $csv .= TRIM($Lage);
	        }
	        $csv .= ';';
	      }
	      if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
	      if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
	      if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
	      if($formvars['status']){ $csv .= $flst->Status.';';}
	      if($formvars['vorgaenger']){
	        for($v = 0; $v < count($flst->Vorgaenger); $v++){
	          $csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
	        }
	        $csv .= ';';
	      }
	      if($formvars['nachfolger']){
	        for($v = 0; $v < count($flst->Nachfolger); $v++){
	          $csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
	        }
	        $csv .= ';';
	      }
      
      	// Klassifizierung
      	$csv .= '"'; 
        $csv .= $flst->Klassifizierung[$kl]['tabkenn'].'-'.$flst->Klassifizierung[$kl]['klass'].' '.$flst->Klassifizierung[$kl]['bezeichnung'].' ';
        $wert=substr($flst->Klassifizierung[$kl]['angaben'],strrpos($flst->Klassifizierung[$kl]['angaben'],'/')+1);
        $emz = round($flst->Klassifizierung[$kl]['flaeche'] * $wert / 100);
        if($flst->Klassifizierung[$kl]['tabkenn'] =='32' AND $flst->Klassifizierung[$kl]['angaben'] !='') {
          $csv .= "'".$flst->Klassifizierung[$kl]['angaben']."'";
        } else {
          $csv .= $flst->Klassifizierung[$kl]['angaben'];
        }
        $csv .= '";';
        $csv .= $flst->Klassifizierung[$kl]['flaeche'].';';
        if ($flst->Klassifizierung[$kl]['tabkenn'] == '32' AND $flst->Klassifizierung[$kl]['angaben'] !='') {
        	$csv .= $emz;
        	$flst->emz = true;
        }
        $csv .= ';;;;';
        //////////////////////EMZ aus ALK////////////////////////
        //////////////////////
        	      
	      if($formvars['freitext']) {
	        for($j = 0; $j < count($flst->FreiText); $j++){
	        	if($j > 0)$csv .= ' | ';
	          $csv .= $flst->FreiText[$j]['text'];
	        }
	        $csv .= ';';
	      }
	      if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
	      if ($formvars['baulasten']){
	        for($b=0; $b < count($flst->Baulasten); $b++) {
	          $csv .= " ".$flst->Baulasten[$b]['blattnr'];
	        }
	        $csv .= ';';
	      }
	      if ($formvars['ausfstelle']){ 
	      	for($v = 0; $v < count($flst->Verfahren); $v++){
	      		if($v > 0)$csv .= ' | ';
	      		$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
	      	}
	      	$csv .= ';';
	      }
	      if ($formvars['verfahren']){
	      	for($v = 0; $v < count($flst->Verfahren); $v++){
	      		if($v > 0)$csv .= ' | ';
	      		$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
	      	}
	      	$csv .= ';';
	      }
	      if ($formvars['nutzung']){
	        $anzNutzung=count($flst->Nutzung);
	        for ($j = 0; $j < $anzNutzung; $j++){
	        	if($j > 0)$csv .= ' | ';
	          $csv .= $flst->Nutzung[$j][flaeche].' m2 ';
	          $csv .= $flst->Nutzung[$j][nutzungskennz].' ';
	          if($flst->Nutzung[$j][abkuerzung]!='') {
	            $csv .= $flst->Nutzung[$j][abkuerzung].'-';
	          }
	          $csv .= $flst->Nutzung[$j][bezeichnung];
	        }
	        $csv .= ';';
	      }
	      
	      if($formvars['blattnr']){
		        for($g = 0; $g < count($flst->Grundbuecher); $g++){
		          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
		          for($b = 0; $b < count($flst->Buchungen); $b++){
		          	if($b > 0)$csv .= ' | ';
		            $csv .= intval($flst->Buchungen[$b]['blatt']).'|';
		          }
		        }
		        $csv .= ';';
			    }
			    
			    if($formvars['pruefzeichen']){
		        for($g = 0; $g < count($flst->Grundbuecher); $g++){
		          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
		          for($b = 0; $b < count($flst->Buchungen); $b++){
		          	if($b > 0)$csv .= ' | ';
		            $csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
		          }
		        }
		        $csv .= ';';
			    }
			    
	    		if($formvars['pruefzeichen_f']){
		      	$csv .= $flst->Pruefzeichen;
		      	$csv .= ';';
		      }
			    
			    if($formvars['bvnr']){
		        for($g = 0; $g < count($flst->Grundbuecher); $g++){
		          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
		          for($b = 0; $b < count($flst->Buchungen); $b++){
		          	if($b > 0)$csv .= ' | ';
		            $csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
		          }
		        }
		        $csv .= ';';
			    }
			    
			    if($formvars['buchungsart']){
		        for($g = 0; $g < count($flst->Grundbuecher); $g++){
		          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
		          for($b = 0; $b < count($flst->Buchungen); $b++){
		          	if($b > 0)$csv .= ' | ';
		            $csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
		            $csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
		          }
		        }
		        $csv .= ';';
		      }
	      
	      if($formvars['eigentuemer']){
	      	$csv .= '"';
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	        	if($g > 0)$csv .= "\n";
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= "\n";
	            $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
	            $anzEigentuemer=count($Eigentuemerliste);
	            for($e=0;$e<$anzEigentuemer;$e++){
	            	if($e > 0)$csv .= "\n";
	              $csv .= $Eigentuemerliste[$e]->Nr.' ';
	              $anzNamenszeilen = count($Eigentuemerliste[$e]->Name);
	              for($n=0;$n<$anzNamenszeilen;$n++) {
	                $csv .= $Eigentuemerliste[$e]->Name[$n].' ';
	              }
	            }
	          }
	        }
	        $csv .= '";';
	      }
	      $csv .= chr(10);
	    }
	    if(!$flst->emz){
        $alkemz = $flst->getEMZfromALK();
        $ratio = $flst->ALB_Flaeche/$alkemz[0]['flstflaeche'];
				$emzges_222 = 0; $emzges_223 = 0;
        $flaeche_222 = 0; $flaeche_223 = 0;

	      for($kl = 0; $kl < count($alkemz)+2; $kl++){
	      	if($kl == count($alkemz)){              
		      	$nichtgeschaetzt=round($flst->ALB_Flaeche-$flaeche_222-$flaeche_223);
						if($nichtgeschaetzt <= 0)continue;
	      	}
		      if($nichtgeschaetzt > 0 OR $kl < count($alkemz)+2){
		      	if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
			      if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
			      if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
			      if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
			      if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
			      if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
			      if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
			      if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
			      if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
			      if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
			      if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
			      if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
			      if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
			      if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
			      if($formvars['amtsgerichtnr']){ $csv .= $flst->Amtsgericht['schluessel'].';';}
			      if($formvars['amtsgerichtname']){ $csv .= $flst->Amtsgericht['name'].';';}
			      if($formvars['grundbuchbezirkschl']){ $csv .= $flst->Grundbuchbezirk['schluessel'].';';}
			      if($formvars['grundbuchbezirkname']){ $csv .= $flst->Grundbuchbezirk['name'].';';}
			      if($formvars['lagebezeichnung']){
			        $anzStrassen=count($flst->Adresse);
			        for ($s=0;$s<$anzStrassen;$s++) {
			          $csv .= $flst->Adresse[$s]["gemeindename"].' ';
			          $csv .= $flst->Adresse[$s]["strassenname"].' ';
			          $csv .= $flst->Adresse[$s]["hausnr"].' ';
			        }
			        $anzLage=count($flst->Lage);
			        $Lage='';
			        for ($j=0;$j<$anzLage;$j++) {
			          $Lage.=' '.$flst->Lage[$j];
			        }
			        if ($Lage!='') {
			          $csv .= TRIM($Lage);
			        }
			        $csv .= ';';
			      }
			      if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
			      if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
			      if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
			      if($formvars['status']){ $csv .= $flst->Status.';';}
			      if($formvars['vorgaenger']){
			        for($v = 0; $v < count($flst->Vorgaenger); $v++){
			          $csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
			        }
			        $csv .= ';';
			      }
			      if($formvars['nachfolger']){
			        for($v = 0; $v < count($flst->Nachfolger); $v++){
			          $csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
			        }
			        $csv .= ';';
			      }
		      
		      	// Klassifizierung
		      	$csv .= ';;;';
		        //////////////////////EMZ aus ALK////////////////////////
		      	if(!$flst->emz){
		      		if($alkemz[$kl]['wert'] != ''){
			        	$wert=$alkemz[$kl]['wert'];
			          $alkemz[$kl]['flaeche'] = $alkemz[$kl]['flaeche']*$ratio;
			          $emz = round($alkemz[$kl]['flaeche'] * $wert / 100);
			          if($alkemz[$kl]['objart'] == '222'){
			          	$emzges_222 = $emzges_222 + $emz;
			          	$flaeche_222 = $flaeche_222 + $alkemz[$kl]['flaeche'];
			          	$objart = 'Ackerland ';
			          }
			          if($alkemz[$kl]['objart'] == '223'){
			          	$emzges_223 = $emzges_223 + $emz;
			          	$flaeche_223 = $flaeche_223 + $alkemz[$kl]['flaeche'];
			          	$objart = 'Gr�nland ';
			          }
			          if(strlen($alkemz[$kl]['label'])==20) {
			            $label1=substr(substr($alkemz[$kl]['label'],4),0,-6);
			            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-6,6)),0,3),"0");
			            $label3=ltrim(substr($alkemz[$kl]['label'],-3,3),"0");
			          } elseif (strlen($alkemz[$kl]['label'])==23) {
			            $label1=substr(substr($alkemz[$kl]['label'],4),0,-9);
			            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-9,6)),0,3),"0");
			            $label3=ltrim(substr($alkemz[$kl]['label'],-6,3),"0");
			            $label4=substr($alkemz[$kl]['label'],-3,3);
			          } elseif (strlen($alkemz[$kl]['label'])==29) {									
									$label1='('.rtrim(substr(substr($alkemz[$kl]['label'],4),0,-15)).')';
				          $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-15,6)),0,3),"0");
				          $label3=ltrim(substr($alkemz[$kl]['label'],-12,3),"0");
				          $label4='';
			          }
			          $csv .= utf8_encode($objart.$label1.' '.$label2.'/'.$label3.' '.$label4.';');
			          $csv .= round($alkemz[$kl]['flaeche']).';';
			          $csv .= $emz;
		      		}
			        if($kl == count($alkemz)){              
			          if($nichtgeschaetzt>0){
			          	$csv .= utf8_encode('nicht gesch�tzt: ;'.$nichtgeschaetzt.';');
			          }
								else{
									$csv .= ';;';
								}
			        }
							$csv .= ';';
							if($kl == count($alkemz)+1){              
								$csv .= ';;';
			          if($emzges_222 > 0){
									$BWZ_222 = round($emzges_222/$flaeche_222*100);
									$csv .= ' Ackerland gesamt: EMZ '.$emzges_222.' , BWZ '.$BWZ_222." ";
								}
								if($emzges_223 > 0){
									$BWZ_223 = round($emzges_223/$flaeche_223*100);
									$csv .= utf8_encode(' Gr�nland gesamt: EMZ '.$emzges_223.' , BWZ '.$BWZ_223);
								}
			        }
		        }
		        //////////////////////
		        $csv .= ';';       
		      
			      
			      if($formvars['freitext']) {
			        for($j = 0; $j < count($flst->FreiText); $j++){
			        	if($j > 0)$csv .= ' | ';
			          $csv .= $flst->FreiText[$j]['text'];
			        }
			        $csv .= ';';
			      }
			      if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
			      if ($formvars['baulasten']){
			        for($b=0; $b < count($flst->Baulasten); $b++) {
			          $csv .= " ".$flst->Baulasten[$b]['blattnr'];
			        }
			        $csv .= ';';
			      }
			      if ($formvars['ausfstelle']){ 
			      	for($v = 0; $v < count($flst->Verfahren); $v++){
			      		if($v > 0)$csv .= ' | ';
			      		$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
			      	}
			      	$csv .= ';';
			      }
			      if ($formvars['verfahren']){
			      	for($v = 0; $v < count($flst->Verfahren); $v++){
			      		if($v > 0)$csv .= ' | ';
			      		$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
			      	}
			      	$csv .= ';';
			      }
			      if ($formvars['nutzung']){
			        $anzNutzung=count($flst->Nutzung);
			        for ($j = 0; $j < $anzNutzung; $j++){
			        	if($j > 0)$csv .= ' | ';
			          $csv .= $flst->Nutzung[$j][flaeche].' m2 ';
			          $csv .= $flst->Nutzung[$j][nutzungskennz].' ';
			          if($flst->Nutzung[$j][abkuerzung]!='') {
			            $csv .= $flst->Nutzung[$j][abkuerzung].'-';
			          }
			          $csv .= $flst->Nutzung[$j][bezeichnung];
			        }
			        $csv .= ';';
			      }
			      
			      if($formvars['blattnr']){
				        for($g = 0; $g < count($flst->Grundbuecher); $g++){
				          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
				          for($b = 0; $b < count($flst->Buchungen); $b++){
				          	if($b > 0)$csv .= ' | ';
				            $csv .= intval($flst->Buchungen[$b]['blatt']).'|';
				          }
				        }
				        $csv .= ';';
					    }
					    
					    if($formvars['pruefzeichen']){
				        for($g = 0; $g < count($flst->Grundbuecher); $g++){
				          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
				          for($b = 0; $b < count($flst->Buchungen); $b++){
				          	if($b > 0)$csv .= ' | ';
				            $csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
				          }
				        }
				        $csv .= ';';
					    }
					    
			    		if($formvars['pruefzeichen_f']){
				      	$csv .= $flst->Pruefzeichen;
				      	$csv .= ';';
				      }
					    
					    if($formvars['bvnr']){
				        for($g = 0; $g < count($flst->Grundbuecher); $g++){
				          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
				          for($b = 0; $b < count($flst->Buchungen); $b++){
				          	if($b > 0)$csv .= ' | ';
				            $csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
				          }
				        }
				        $csv .= ';';
					    }
					    
					    if($formvars['buchungsart']){
				        for($g = 0; $g < count($flst->Grundbuecher); $g++){
				          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
				          for($b = 0; $b < count($flst->Buchungen); $b++){
				          	if($b > 0)$csv .= ' | ';
				            $csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
				            $csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
				          }
				        }
				        $csv .= ';';
				      }
			      
			      if($formvars['eigentuemer']){
			      	$csv .= '"';
			        for($g = 0; $g < count($flst->Grundbuecher); $g++){
			        	if($g > 0)$csv .= "\n";
			          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
			          for($b = 0; $b < count($flst->Buchungen); $b++){
			          	if($b > 0)$csv .= "\n";
			            $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
			            $anzEigentuemer=count($Eigentuemerliste);
			            for($e=0;$e<$anzEigentuemer;$e++){
			            	if($e > 0)$csv .= "\n";
			              $csv .= $Eigentuemerliste[$e]->Nr.' ';
			              $anzNamenszeilen = count($Eigentuemerliste[$e]->Name);
			              for($n=0;$n<$anzNamenszeilen;$n++) {
			                $csv .= $Eigentuemerliste[$e]->Name[$n].' ';
			              }
			            }
			          }
			        }
			        $csv .= '";';
			      }
			      $csv .= chr(10);
		      }
	      }
	    }
	    $csv .= chr(10);
    }

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
  
  function export_eigentuemer_csv($flurstuecke, $formvars){
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ;';}
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ_kurz;';}
    if($formvars['gemkgname']){ $csv .= 'Gemkg-Name;';}
    if($formvars['gemkgschl']){ $csv .= 'Gemkg-Schl.;';}
    if($formvars['flurnr']){ $csv .= 'Flur;';}
    if($formvars['gemeindename']){ $csv .= 'Gem-Name;';}
    if($formvars['gemeinde']){ $csv .= 'Gemeinde;';}
    if($formvars['kreisname']){ $csv .= 'Kreisname;';}
    if($formvars['kreisid']){ $csv .= utf8_encode('Kreisschl�ssel;');}
    if($formvars['finanzamtname']){ $csv .= 'Finanzamtname;';}
    if($formvars['finanzamt']){ $csv .= utf8_encode('Finanzamtschl�ssel;');}
    if($formvars['forstname']){ $csv .= 'Forstamtname;';}
    if($formvars['forstschluessel']){ $csv .= utf8_encode('Forstamtschl�ssel;');}
    if($formvars['flaeche']){ $csv .= utf8_encode('Flst-Fl�che ALB;');}
    if($formvars['amtsgerichtnr']){ $csv .= 'Amtsgericht;';}
    if($formvars['amtsgerichtname']){ $csv .= 'Amtsgerichtname;';}
    if($formvars['grundbuchbezirkschl']){ $csv .= utf8_encode('GBBschl�ssel;');}
    if($formvars['grundbuchbezirkname']){ $csv .= 'GBBname;';}
    if($formvars['lagebezeichnung']){ $csv .= 'Lage;';}
    if($formvars['entsteh']){ $csv .= 'Entstehung;';}
    if($formvars['letzff']){ $csv .= utf8_encode('Fortf�hrung;');}
    if($formvars['karte']){ $csv .= 'Flurkarte;';}
    if($formvars['status']){ $csv .= 'Status;';}
    if($formvars['vorgaenger']){ $csv .= 'Vorgaenger;';}
    if($formvars['nachfolger']){ $csv .= 'Nachfolger;';}
  	if($formvars['klassifizierung']){ $csv .= 'Klassifizierung-ALB;Klassifizierung-ALK;';}
    if($formvars['freitext']){ $csv .= 'Freitext;';}
    if($formvars['hinweis']){ $csv .= 'Hinweis;';}
    if($formvars['baulasten']){ $csv .= 'Baulasten;';}
    if($formvars['ausfstelle']){ $csv .= utf8_encode('ausf�hrende Stelle;');}
    if($formvars['verfahren']){ $csv .= 'Verfahren;';}
    if($formvars['nutzung']){ $csv .= 'Nutzung;';}
    if($formvars['blattnr']){ $csv .= 'Blattnummer;';}
    if($formvars['pruefzeichen']){ $csv .= 'P Buchung;';}
  	if($formvars['pruefzeichen_f']){ $csv .= utf8_encode('P Flurst�ck;');}
    if($formvars['bvnr']){ $csv .= 'BVNR;';}
    if($formvars['buchungsart']){ $csv .= 'Buchungsart;';}
    $csv .= 'Namensnummer;'; 
    $csv .= utf8_encode('Eigent�mer;Zusatz;Adresse;Ort;');
    
    $csv .= chr(10);
    for($i = 0; $i < count($flurstuecke); $i++) {
      $flurstkennz = $flurstuecke[$i];
      $flst = new flurstueck($flurstkennz,$this->database);
      $flst->readALB_Data($flurstkennz);
      
      for($g = 0; $g < count($flst->Grundbuecher); $g++){
          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
          for($b = 0; $b < count($flst->Buchungen); $b++){
            $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
            $anzEigentuemer=count($Eigentuemerliste);
            for($e=0;$e<$anzEigentuemer;$e++){
      
				      if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
				      if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
				      if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
				      if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
				      if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
				      if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
				      if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
				      if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
				      if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
				      if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
				      if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
				      if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
				      if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
				      if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
				      if($formvars['amtsgerichtnr']){ $csv .= $flst->Amtsgericht['schluessel'].';';}
				      if($formvars['amtsgerichtname']){ $csv .= $flst->Amtsgericht['name'].';';}
				      if($formvars['grundbuchbezirkschl']){ $csv .= $flst->Grundbuchbezirk['schluessel'].';';}
				      if($formvars['grundbuchbezirkname']){ $csv .= $flst->Grundbuchbezirk['name'].';';}
				      if($formvars['lagebezeichnung']){
				        $anzStrassen=count($flst->Adresse);
				        for ($s=0;$s<$anzStrassen;$s++) {
				          $csv .= $flst->Adresse[$s]["gemeindename"].' ';
				          $csv .= $flst->Adresse[$s]["strassenname"].' ';
				          $csv .= $flst->Adresse[$s]["hausnr"].' ';
				        }
				        $anzLage=count($flst->Lage);
				        $Lage='';
				        for ($j=0;$j<$anzLage;$j++) {
				          $Lage.=' '.$flst->Lage[$j];
				        }
				        if ($Lage!='') {
				          $csv .= TRIM($Lage);
				        }
				        $csv .= ';';
				      }
				      if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
				      if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
				      if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
				      if($formvars['status']){ $csv .= $flst->Status.';';}
				      if($formvars['vorgaenger']){
				        for($v = 0; $v < count($flst->Vorgaenger); $v++){
				          $csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
				        }
				        $csv .= ';';
				      }
				      if($formvars['nachfolger']){
				        for($v = 0; $v < count($flst->Nachfolger); $v++){
				          $csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
				        }
				        $csv .= ';';
				      }
            if($formvars['klassifizierung']){
            	$emzges = 0;
			      	$csv .= '"';
			        for($j = 0; $j < count($flst->Klassifizierung)-1; $j++){
			          if($j > 0)$csv .= " \n ";
			          $csv .= $flst->Klassifizierung[$j]['flaeche'].'m� '.$flst->Klassifizierung[$j]['tabkenn'].'-'.$flst->Klassifizierung[$j]['klass'].' '.$flst->Klassifizierung[$j]['bezeichnung'].' ';
			          $wert=mb_substr($flst->Klassifizierung[$j]['angaben'],mb_strrpos($flst->Klassifizierung[$j]['angaben'],'/','utf8')+1, 999,'utf8');
			          $emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
			          if($flst->Klassifizierung[$j]['tabkenn'] =='32' AND $flst->Klassifizierung[$j]['angaben'] !='') {
			            $csv .= "'".$flst->Klassifizierung[$j]['angaben']."'";
			          } else {
			            $csv .= $flst->Klassifizierung[$j]['angaben'];
			          }
			          if ($flst->Klassifizierung[$j]['tabkenn'] == '32' AND $flst->Klassifizierung[$j]['angaben'] !='') {
			            $csv .= ' EMZ: '.$emz;
			            $emzges=$emzges+$emz;
			            $flst->emz = true;
			          }
			        }
			        if ($emzges > 0) {
			          $csv .= "\n EMZ gesamt: ".$emzges;
			        }
			        $csv .= '";"';
			        //////////////////////EMZ aus ALK////////////////////////
			      		if(!$flst->emz){
				        	$alkemz = $flst->getEMZfromALK();
				        	if($alkemz[0]['wert'] != ''){
				        		$ratio = $flst->ALB_Flaeche/$alkemz[0]['flstflaeche'];
										$emzges_222 = 0; $emzges_223 = 0;
					          $flaeche_222 = 0; $flaeche_223 = 0;
					          for($j = 0; $j < count($alkemz); $j++){
				            	$wert=$alkemz[$j]['wert'];
				            	$alkemz[$j]['flaeche'] = $alkemz[$j]['flaeche']*$ratio;
						          $emz = round($alkemz[$j]['flaeche'] * $wert / 100);
						          if($alkemz[$j]['objart'] == '222'){
						          	$emzges_222 = $emzges_222 + $emz;
						          	$flaeche_222 = $flaeche_222 + $alkemz[$j]['flaeche'];
						          }
						          if($alkemz[$j]['objart'] == '223'){
						          	$emzges_223 = $emzges_223 + $emz;
						          	$flaeche_223 = $flaeche_223 + $alkemz[$j]['flaeche'];
						          }
						          if(strlen($alkemz[$kl]['label'])==20) {
						            $label1=substr(substr($alkemz[$kl]['label'],4),0,-6);
						            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-6,6)),0,3),"0");
						            $label3=ltrim(substr($alkemz[$kl]['label'],-3,3),"0");
						          } elseif (strlen($alkemz[$kl]['label'])==23) {
						            $label1=substr(substr($alkemz[$kl]['label'],4),0,-9);
						            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-9,6)),0,3),"0");
						            $label3=ltrim(substr($alkemz[$kl]['label'],-6,3),"0");
						            $label4=substr($alkemz[$kl]['label'],-3,3);
						          } elseif (strlen($alkemz[$kl]['label'])==29) {
						            $label1=substr(substr($alkemz[$kl]['label'],4),0,-15);
						            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-15,6)),0,3),"0");
						            $label3=ltrim(substr($alkemz[$kl]['label'],-12,3),"0");
						            $label4='W';
						          }
					            $csv .= round($alkemz[$j]['flaeche']).' m� '; 
					            $csv .= $label1.' '.$label2.'/'.$label3.' '.$label4;
					            $csv .= ' EMZ: '.$emz." \n ";
					          }
				            $nichtgeschaetzt=round($flst->ALB_Flaeche-$flaeche_222-$flaeche_223);
				            if($nichtgeschaetzt>0){
			          			$csv .=  utf8_encode('nicht gesch�tzt: '.$nichtgeschaetzt." m� \n");
			          		}
			        			if($emzges_222 > 0){
			        				$BWZ_222 = round($emzges_222/$flaeche_222*100);
			          			$csv .= ' Ackerland gesamt: EMZ '.$emzges_222.' , BWZ '.$BWZ_222." \n";
			          		}
			        			if($emzges_223 > 0){
			        				$BWZ_223 = round($emzges_223/$flaeche_223*100);
			        				$csv .= utf8_encode(' Gr�nland gesamt: EMZ '.$emzges_223.' , BWZ '.$BWZ_223);
										}
				        	}
				        }
			        //////////////////////
			        
			        $csv .= '";';
			      }      
				      if($formvars['freitext']) {
				        for($j = 0; $j < count($flst->FreiText); $j++){
				        	if($j > 0)$csv .= ' | ';
				          $csv .= $flst->FreiText[$j]['text'];
				        }
				        $csv .= ';';
				      }
				      if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
				      if ($formvars['baulasten']){
				        for($bl=0; $bl < count($flst->Baulasten); $bl++) {
				          $csv .= " ".$flst->Baulasten[$bl]['blattnr'];
				        }
				        $csv .= ';';
				      }
				      if ($formvars['ausfstelle']){ 
				      	for($v = 0; $v < count($flst->Verfahren); $v++){
				      		if($v > 0)$csv .= ' | ';
				      		$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
				      	}
				      	$csv .= ';';
				      }
				      if ($formvars['verfahren']){
				      	for($v = 0; $v < count($flst->Verfahren); $v++){
				      		if($v > 0)$csv .= ' | ';
				      		$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
				      	}
				      	$csv .= ';';
				      }
				      if ($formvars['nutzung']){
				        $anzNutzung=count($flst->Nutzung);
				        for ($j = 0; $j < $anzNutzung; $j++){
				        	if($j > 0)$csv .= ' | ';
				          $csv .= $flst->Nutzung[$j][flaeche].'m2 ';
          				$csv .= $flst->Nutzung[$j][nutzungskennz].' ';
          				if($flst->Nutzung[$j][abkuerzung]!='') {
						      	$csv .= $flst->Nutzung[$j][abkuerzung].'-';
						      }
						      $csv .= $flst->Nutzung[$j][bezeichnung];
				        }
				        $csv .= ';';
				      }
				      
				 if($formvars['blattnr']){
	        $csv .= intval($flst->Buchungen[$b]['blatt']);
	        $csv .= ';';
		    }
		    
		    if($formvars['pruefzeichen']){
	        $csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
	        $csv .= ';';
		    }
		    
        if($formvars['pruefzeichen_f']){
	      	$csv .= $flst->Pruefzeichen;
	      	$csv .= ';';
	      }
		    
		    if($formvars['bvnr']){
	        $csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
	        $csv .= ';';
		    }
		    
		    if($formvars['buchungsart']){
          $csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
          $csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
	        $csv .= ';';
	      }
				      
	        $csv .= '\''.$Eigentuemerliste[$e]->Nr.'\'';
          if($Eigentuemerliste[$e]->Anteil !=''){$csv .= '  zu '.$Eigentuemerliste[$e]->Anteil;}
          $csv .= ';';
	        $anzNamenszeilen = count($Eigentuemerliste[$e]->Name);
	        for($n=0;$n<$anzNamenszeilen;$n++) {
	        	$csv .= $Eigentuemerliste[$e]->Name[$n].';';
	        }
	        $csv .= chr(10);
        }
       }
      }
      $csv .= chr(10);
    }

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
  
  function export_nutzungsarten_csv($flurstuecke, $formvars){
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ;';}
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ_kurz;';}
    if($formvars['gemkgname']){ $csv .= 'Gemkg-Name;';}
    if($formvars['gemkgschl']){ $csv .= 'Gemkg-Schl.;';}
    if($formvars['flurnr']){ $csv .= 'Flur;';}
    if($formvars['gemeindename']){ $csv .= 'Gem-Name;';}
    if($formvars['gemeinde']){ $csv .= 'Gemeinde;';}
    if($formvars['kreisname']){ $csv .= 'Kreisname;';}
    if($formvars['kreisid']){ $csv .= utf8_encode('Kreisschl�ssel;');}
    if($formvars['finanzamtname']){ $csv .= 'Finanzamtname;';}
    if($formvars['finanzamt']){ $csv .= utf8_encode('Finanzamtschl�ssel;');}
    if($formvars['forstname']){ $csv .= 'Forstamtname;';}
    if($formvars['forstschluessel']){ $csv .= utf8_encode('Forstamtschl�ssel;');}
    if($formvars['flaeche']){ $csv .= utf8_encode('Flst-Fl�che ALB;');}
    if($formvars['amtsgerichtnr']){ $csv .= 'Amtsgericht;';}
    if($formvars['amtsgerichtname']){ $csv .= 'Amtsgerichtname;';}
    if($formvars['grundbuchbezirkschl']){ $csv .= utf8_encode('GBBschl�ssel;');}
    if($formvars['grundbuchbezirkname']){ $csv .= 'GBBname;';}
    if($formvars['lagebezeichnung']){ $csv .= 'Lage;';}
    if($formvars['entsteh']){ $csv .= 'Entstehung;';}
    if($formvars['letzff']){ $csv .= utf8_encode('Fortf�hrung;');}
    if($formvars['karte']){ $csv .= 'Flurkarte;';}
    if($formvars['status']){ $csv .= 'Status;';}
    if($formvars['vorgaenger']){ $csv .= 'Vorgaenger;';}
    if($formvars['nachfolger']){ $csv .= 'Nachfolger;';}
  	if($formvars['klassifizierung']){ $csv .= 'Klassifizierung-ALB;Klassifizierung-ALK;';}
    if($formvars['freitext']){ $csv .= 'Freitext;';}
    if($formvars['hinweis']){ $csv .= 'Hinweis;';}
    if($formvars['baulasten']){ $csv .= 'Baulasten;';}
    if($formvars['ausfstelle']){ $csv .= utf8_encode('ausf�hrende Stelle;');}
    if($formvars['verfahren']){ $csv .= 'Verfahren;';}
   	if($formvars['blattnr']){ $csv .= 'Blattnummer;';}
    if($formvars['pruefzeichen']){ $csv .= 'P Buchung;';}
  	if($formvars['pruefzeichen_f']){ $csv .= utf8_encode('P Flurst�ck;');}
    if($formvars['bvnr']){ $csv .= 'BVNR;';}
    if($formvars['buchungsart']){ $csv .= 'Buchungsart;';}
    if($formvars['eigentuemer']){ $csv .= utf8_encode('Eigent�mer;');}
    $csv .= utf8_encode('Nutzung - Fl�che;');
    $csv .= 'Nutzung - Kennzeichen;';
    $csv .= 'Nutzung - Bezeichnung;';
    
    $csv .= chr(10);
    for($i = 0; $i < count($flurstuecke); $i++) {
      $flurstkennz = $flurstuecke[$i];
      $flst = new flurstueck($flurstkennz,$this->database);
      $flst->readALB_Data($flurstkennz);
      
      $anzNutzung=count($flst->Nutzung);
			for ($n = 0; $n < $anzNutzung; $n++){
      
	      if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
	      if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
	      if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
	      if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
	      if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
	      if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
	      if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
	      if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
	      if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
	      if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
	      if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
	      if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
	      if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
	      if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
	      if($formvars['amtsgerichtnr']){ $csv .= $flst->Amtsgericht['schluessel'].';';}
	      if($formvars['amtsgerichtname']){ $csv .= $flst->Amtsgericht['name'].';';}
	      if($formvars['grundbuchbezirkschl']){ $csv .= $flst->Grundbuchbezirk['schluessel'].';';}
	      if($formvars['grundbuchbezirkname']){ $csv .= $flst->Grundbuchbezirk['name'].';';}
	      if($formvars['lagebezeichnung']){
	        $anzStrassen=count($flst->Adresse);
	        for ($s=0;$s<$anzStrassen;$s++) {
	          $csv .= $flst->Adresse[$s]["gemeindename"].' ';
	          $csv .= $flst->Adresse[$s]["strassenname"].' ';
	          $csv .= $flst->Adresse[$s]["hausnr"].' ';
	        }
	        $anzLage=count($flst->Lage);
	        $Lage='';
	        for ($j=0;$j<$anzLage;$j++) {
	          $Lage.=' '.$flst->Lage[$j];
	        }
	        if ($Lage!='') {
	          $csv .= TRIM($Lage);
	        }
	        $csv .= ';';
	      }
	      if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
	      if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
	      if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
	      if($formvars['status']){ $csv .= $flst->Status.';';}
	      if($formvars['vorgaenger']){
	        for($v = 0; $v < count($flst->Vorgaenger); $v++){
	          $csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
	        }
	        $csv .= ';';
	      }
	      if($formvars['nachfolger']){
	        for($v = 0; $v < count($flst->Nachfolger); $v++){
	          $csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
	        }
	        $csv .= ';';
	      }
			if($formvars['klassifizierung']){
				$emzges = 0;
      	$csv .= '"';
        for($j = 0; $j < count($flst->Klassifizierung)-1; $j++){
          if($j > 0)$csv .= " \n ";
          $csv .= $flst->Klassifizierung[$j]['flaeche'].'m� '.$flst->Klassifizierung[$j]['tabkenn'].'-'.$flst->Klassifizierung[$j]['klass'].' '.$flst->Klassifizierung[$j]['bezeichnung'].' ';
          $wert=mb_substr($flst->Klassifizierung[$j]['angaben'],mb_strrpos($flst->Klassifizierung[$j]['angaben'],'/','utf8')+1, 999, 'utf8');
          $emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
          if($flst->Klassifizierung[$j]['tabkenn'] =='32' AND $flst->Klassifizierung[$j]['angaben'] !='') {
            $csv .= "'".$flst->Klassifizierung[$j]['angaben']."'";
          } else {
            $csv .= $flst->Klassifizierung[$j]['angaben'];
          }
          if ($flst->Klassifizierung[$j]['tabkenn'] == '32' AND $flst->Klassifizierung[$j]['angaben'] !='') {
            $csv .= ' EMZ: '.$emz;
            $emzges=$emzges+$emz;
            $flst->emz = true;
          }
        }
        if ($emzges > 0) {
          $csv .= "\n EMZ gesamt: ".$emzges;
        }
        $csv .= '";"';
        //////////////////////EMZ aus ALK////////////////////////
      		if(!$flst->emz){
	        	$alkemz = $flst->getEMZfromALK();
	        	if($alkemz[0]['wert'] != ''){
	        		$ratio = $flst->ALB_Flaeche/$alkemz[0]['flstflaeche'];
							$emzges_222 = 0; $emzges_223 = 0;
		          $flaeche_222 = 0; $flaeche_223 = 0;
		          for($j = 0; $j < count($alkemz); $j++){
	            	$wert=$alkemz[$j]['wert'];
	            	$alkemz[$j]['flaeche'] = $alkemz[$j]['flaeche']*$ratio;
			          $emz = round($alkemz[$j]['flaeche'] * $wert / 100);
			          if($alkemz[$j]['objart'] == '222'){
			          	$emzges_222 = $emzges_222 + $emz;
			          	$flaeche_222 = $flaeche_222 + $alkemz[$j]['flaeche'];
			          }
			          if($alkemz[$j]['objart'] == '223'){
			          	$emzges_223 = $emzges_223 + $emz;
			          	$flaeche_223 = $flaeche_223 + $alkemz[$j]['flaeche'];
			          }
		          	if(strlen($alkemz[$kl]['label'])==20) {
			            $label1=substr(substr($alkemz[$kl]['label'],4),0,-6);
			            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-6,6)),0,3),"0");
			            $label3=ltrim(substr($alkemz[$kl]['label'],-3,3),"0");
			          } elseif (strlen($alkemz[$kl]['label'])==23) {
			            $label1=substr(substr($alkemz[$kl]['label'],4),0,-9);
			            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-9,6)),0,3),"0");
			            $label3=ltrim(substr($alkemz[$kl]['label'],-6,3),"0");
			            $label4=substr($alkemz[$kl]['label'],-3,3);
			          } elseif (strlen($alkemz[$kl]['label'])==29) {
			            $label1=substr(substr($alkemz[$kl]['label'],4),0,-15);
			            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-15,6)),0,3),"0");
			            $label3=ltrim(substr($alkemz[$kl]['label'],-12,3),"0");
			            $label4='W';
			          }
		            $csv .= round($alkemz[$j]['flaeche']).' m� '; 
		            $csv .= $label1.' '.$label2.'/'.$label3.' '.$label4;
		            $csv .= ' EMZ: '.$emz." \n ";
		          }
	            $nichtgeschaetzt=round($flst->ALB_Flaeche-$flaeche_222-$flaeche_223);
	            if($nichtgeschaetzt>0){
          			$csv .=  utf8_encode('nicht gesch�tzt: '.$nichtgeschaetzt." m� \n");
          		}
        			if($emzges_222 > 0){
        				$BWZ_222 = round($emzges_222/$flaeche_222*100);
          			$csv .= ' Ackerland gesamt: EMZ '.$emzges_222.' , BWZ '.$BWZ_222." \n";
          		}
        			if($emzges_223 > 0){
        				$BWZ_223 = round($emzges_223/$flaeche_223*100);
        				$csv .= utf8_encode(' Gr�nland gesamt: EMZ '.$emzges_223.' , BWZ '.$BWZ_223);
							}
	        	}
	        }
        //////////////////////
        
        $csv .= '";';
      }      
	      if($formvars['freitext']) {
	        for($j = 0; $j < count($flst->FreiText); $j++){
	        	if($j > 0)$csv .= ' | ';
	          $csv .= $flst->FreiText[$j]['text'];
	        }
	        $csv .= ';';
	      }
	      if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
	      if ($formvars['baulasten']){
	        for($b=0; $b < count($flst->Baulasten); $b++) {
	          $csv .= " ".$flst->Baulasten[$b]['blattnr'];
	        }
	        $csv .= ';';
	      }
	      if ($formvars['ausfstelle']){ 
	      	for($v = 0; $v < count($flst->Verfahren); $v++){
	      		if($v > 0)$csv .= ' | ';
	      		$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
	      	}
	      	$csv .= ';';
	      }
	      if ($formvars['verfahren']){
	      	for($v = 0; $v < count($flst->Verfahren); $v++){
	      		if($v > 0)$csv .= ' | ';
	      		$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
	      	}
	      	$csv .= ';';
	      }
		      
		    if($formvars['blattnr']){
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= ' | ';
	            $csv .= intval($flst->Buchungen[$b]['blatt']);
	          }
	        }
	        $csv .= ';';
		    }
		    
		    if($formvars['pruefzeichen']){
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= ' | ';
	            $csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
	          }
	        }
	        $csv .= ';';
		    }
		    
				if($formvars['pruefzeichen_f']){
	      	$csv .= $flst->Pruefzeichen;
	      	$csv .= ';';
	      }
		    
		    if($formvars['bvnr']){
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= ' | ';
	            $csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
	          }
	        }
	        $csv .= ';';
		    }
		    
		    if($formvars['buchungsart']){
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= ' | ';
	            $csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
	            $csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
	          }
	        }
	        $csv .= ';';
	      }
	      
  			if($formvars['eigentuemer']){
        for($g = 0; $g < count($flst->Grundbuecher); $g++){
          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
          for($b = 0; $b < count($flst->Buchungen); $b++){
            $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
            $anzEigentuemer=count($Eigentuemerliste);
            for($e=0;$e<$anzEigentuemer;$e++){
            	if($e > 0)$csv .= ' | ';
              $csv .= $Eigentuemerliste[$e]->Nr.' ';
              $anzNamenszeilen = count($Eigentuemerliste[$e]->Name);
              for($nz=0;$nz<$anzNamenszeilen;$nz++) {
                $csv .= $Eigentuemerliste[$e]->Name[$nz].' ';
              }
            }
          }
        }
        $csv .= ';';
      }

        
        $csv .= $flst->Nutzung[$n][flaeche].';';
        $csv .= $flst->Nutzung[$n][nutzungskennz].';';
        if($flst->Nutzung[$n][abkuerzung]!='') {
          $csv .= $flst->Nutzung[$n][abkuerzung].'-';
        }
        $csv .= $flst->Nutzung[$n][bezeichnung].';';             
       
      	$csv .= ';';


        $csv .= chr(10);
      }
      $csv .= chr(10);
    }

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }

  function export_flurst_csv($flurstuecke, $formvars){
    if($formvars['flurstkennz']){ $csv .= 'FlstKZ;';}
  	if($formvars['flurstkennz']){ $csv .= 'FlstKZ_kurz;';}
    if($formvars['gemkgname']){ $csv .= 'Gemkg-Name;';}
    if($formvars['gemkgschl']){ $csv .= 'Gemkg-Schl.;';}
    if($formvars['flurnr']){ $csv .= 'Flur;';}
    if($formvars['gemeindename']){ $csv .= 'Gem-Name;';}
    if($formvars['gemeinde']){ $csv .= 'Gemeinde;';}
    if($formvars['kreisname']){ $csv .= 'Kreisname;';}
    if($formvars['kreisid']){ $csv .= utf8_encode('Kreisschl�ssel;');}
    if($formvars['finanzamtname']){ $csv .= 'Finanzamtname;';}
    if($formvars['finanzamt']){ $csv .= utf8_encode('Finanzamtschl�ssel;');}
    if($formvars['forstname']){ $csv .= 'Forstamtname;';}
    if($formvars['forstschluessel']){ $csv .= utf8_encode('Forstamtschl�ssel;');}
    if($formvars['flaeche']){ $csv .= utf8_encode('Flst-Fl�che ALB;');}
    if($formvars['amtsgerichtnr']){ $csv .= 'Amtsgericht;';}
    if($formvars['amtsgerichtname']){ $csv .= 'Amtsgerichtname;';}
    if($formvars['grundbuchbezirkschl']){ $csv .= utf8_encode('GBBschl�ssel;');}
    if($formvars['grundbuchbezirkname']){ $csv .= 'GBBname;';}
    if($formvars['lagebezeichnung']){ $csv .= 'Lage;';}
    if($formvars['entsteh']){ $csv .= 'Entstehung;';}
    if($formvars['letzff']){ $csv .= utf8_encode('Fortf�hrung;');}
    if($formvars['karte']){ $csv .= 'Flurkarte;';}
    if($formvars['status']){ $csv .= 'Status;';}
    if($formvars['vorgaenger']){ $csv .= 'Vorgaenger;';}
    if($formvars['nachfolger']){ $csv .= 'Nachfolger;';}
    if($formvars['klassifizierung']){ $csv .= 'Klassifizierung-ALB;Klassifizierung-ALK;';}
    if($formvars['freitext']){ $csv .= 'Freitext;';}
    if($formvars['hinweis']){ $csv .= 'Hinweis;';}
    if($formvars['baulasten']){ $csv .= 'Baulasten;';}
    if($formvars['ausfstelle']){ $csv .= utf8_encode('ausf�hrende Stelle;');}
    if($formvars['verfahren']){ $csv .= 'Verfahren;';}
    if($formvars['nutzung']){ $csv .= 'Nutzung;';}
    if($formvars['blattnr']){ $csv .= 'Blattnummer;';}
    if($formvars['pruefzeichen']){ $csv .= 'P Buchung;';}
  	if($formvars['pruefzeichen_f']){ $csv .= utf8_encode('P Flurst�ck;');}
    if($formvars['bvnr']){ $csv .= 'BVNR;';}
    if($formvars['buchungsart']){ $csv .= 'Buchungsart;';}
    if($formvars['eigentuemer']){ $csv .= utf8_encode('Eigent�mer;');}
    
    $csv .= chr(10);
    for($i = 0; $i < count($flurstuecke); $i++) {
      $flurstkennz = $flurstuecke[$i];
      $flst = new flurstueck($flurstkennz,$this->database);
      $flst->readALB_Data($flurstkennz);
      if($formvars['flurstkennz']){ $csv .= $flst->FlurstKennz.';';}
      if($formvars['flurstkennz']){ $csv .= "'".$flst->FlurstNr."';";}
      if($formvars['gemkgname']){ $csv .= $flst->GemkgName.';';}
      if($formvars['gemkgschl']){ $csv .= $flst->GemkgSchl.';';}
      if($formvars['flurnr']){ $csv .= $flst->FlurNr.';';}
      if($formvars['gemeindename']){ $csv .= $flst->GemeindeName.';';}
      if($formvars['gemeinde']){ $csv .= $flst->GemeindeID.';';}
      if($formvars['kreisname']){ $csv .= $flst->KreisName.';';}
      if($formvars['kreisid']){ $csv .= $flst->KreisID.';';}
      if($formvars['finanzamtname']){ $csv .= $flst->FinanzamtName.';';}
      if($formvars['finanzamt']){ $csv .= $flst->FinanzamtSchl.';';}
      if($formvars['forstname']){ $csv .= $flst->Forstamt['name'].';';}
      if($formvars['forstschluessel']){ $csv .= '00'.$flst->Forstamt['schluessel'].';';}
      if($formvars['flaeche']){ $csv .= $flst->ALB_Flaeche.';';}
      if($formvars['amtsgerichtnr']){ $csv .= $flst->Amtsgericht['schluessel'].';';}
      if($formvars['amtsgerichtname']){ $csv .= $flst->Amtsgericht['name'].';';}
      if($formvars['grundbuchbezirkschl']){ $csv .= $flst->Grundbuchbezirk['schluessel'].';';}
      if($formvars['grundbuchbezirkname']){ $csv .= $flst->Grundbuchbezirk['name'].';';}
      if($formvars['lagebezeichnung']){
        $anzStrassen=count($flst->Adresse);
        for ($s=0;$s<$anzStrassen;$s++) {
          $csv .= $flst->Adresse[$s]["gemeindename"].' ';
          $csv .= $flst->Adresse[$s]["strassenname"].' ';
          $csv .= $flst->Adresse[$s]["hausnr"].' ';
        }
        $anzLage=count($flst->Lage);
        $Lage='';
        for ($j=0;$j<$anzLage;$j++) {
          $Lage.=' '.$flst->Lage[$j];
        }
        if ($Lage!='') {
          $csv .= TRIM($Lage);
        }
        $csv .= ';';
      }
      if($formvars['entsteh']){ $csv .= $flst->Entstehung.';';}
      if($formvars['letzff']){ $csv .= $flst->LetzteFF.';';}
      if($formvars['karte']){ $csv .= $flst->Flurkarte.';';}
      if($formvars['status']){ $csv .= $flst->Status.';';}
      if($formvars['vorgaenger']){
        for($v = 0; $v < count($flst->Vorgaenger); $v++){
          $csv .= $flst->Vorgaenger[$v]['vorgaenger'].' ';
        }
        $csv .= ';';
      }
      if($formvars['nachfolger']){
        for($v = 0; $v < count($flst->Nachfolger); $v++){
          $csv .= $flst->Nachfolger[$v]['nachfolger'].' ';
        }
        $csv .= ';';
      }
      if($formvars['klassifizierung']){
      	$csv .= '"';
      	$emzges = 0;
        for($j = 0; $j < count($flst->Klassifizierung)-1; $j++){
          if($j > 0)$csv .= "\n";
          $csv .= $flst->Klassifizierung[$j]['flaeche'].'m� '.$flst->Klassifizierung[$j]['tabkenn'].'-'.$flst->Klassifizierung[$j]['klass'].' '.$flst->Klassifizierung[$j]['bezeichnung'].' ';
          $wert=mb_substr($flst->Klassifizierung[$j]['angaben'],mb_strrpos($flst->Klassifizierung[$j]['angaben'],'/','utf8')+1, 999, 'utf8');
          $emz = round($flst->Klassifizierung[$j]['flaeche'] * $wert / 100);
          if($flst->Klassifizierung[$j]['tabkenn'] =='32' AND $flst->Klassifizierung[$j]['angaben'] !='') {
            $csv .= "'".$flst->Klassifizierung[$j]['angaben']."'";
          } else {
            $csv .= $flst->Klassifizierung[$j]['angaben'];
          }
          if ($flst->Klassifizierung[$j]['tabkenn'] == '32' AND $flst->Klassifizierung[$j]['angaben'] !='') {
            $csv .= ' EMZ: '.$emz;
            $emzges=$emzges+$emz;
            $flst->emz = true;
          }
        }
        if ($emzges > 0) {
          $csv .= "\n EMZ gesamt: ".$emzges;
        }
        $csv .= '";"';
        //////////////////////EMZ aus ALK////////////////////////
      		if(!$flst->emz){
	        	$alkemz = $flst->getEMZfromALK();
	        	if($alkemz[0]['wert'] != ''){
	        		$ratio = $flst->ALB_Flaeche/$alkemz[0]['flstflaeche'];
							$emzges_222 = 0; $emzges_223 = 0;
		          $flaeche_222 = 0; $flaeche_223 = 0;
		          for($j = 0; $j < count($alkemz); $j++){
	            	$wert=$alkemz[$j]['wert'];
	            	$alkemz[$j]['flaeche'] = $alkemz[$j]['flaeche']*$ratio;
			          $emz = round($alkemz[$j]['flaeche'] * $wert / 100);
			          if($alkemz[$j]['objart'] == '222'){
			          	$emzges_222 = $emzges_222 + $emz;
			          	$flaeche_222 = $flaeche_222 + $alkemz[$j]['flaeche'];
			          }
			          if($alkemz[$j]['objart'] == '223'){
			          	$emzges_223 = $emzges_223 + $emz;
			          	$flaeche_223 = $flaeche_223 + $alkemz[$j]['flaeche'];
			          }
		          	if(strlen($alkemz[$kl]['label'])==20) {
			            $label1=substr(substr($alkemz[$kl]['label'],4),0,-6);
			            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-6,6)),0,3),"0");
			            $label3=ltrim(substr($alkemz[$kl]['label'],-3,3),"0");
			          } elseif (strlen($alkemz[$kl]['label'])==23) {
			            $label1=substr(substr($alkemz[$kl]['label'],4),0,-9);
			            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-9,6)),0,3),"0");
			            $label3=ltrim(substr($alkemz[$kl]['label'],-6,3),"0");
			            $label4=substr($alkemz[$kl]['label'],-3,3);
			          } elseif (strlen($alkemz[$kl]['label'])==29) {
			            $label1=substr(substr($alkemz[$kl]['label'],4),0,-15);
			            $label2=ltrim(substr(trim(substr($alkemz[$kl]['label'],-15,6)),0,3),"0");
			            $label3=ltrim(substr($alkemz[$kl]['label'],-12,3),"0");
			            $label4='W';
			          }
		            $csv .= round($alkemz[$j]['flaeche']).' m� '; 
		            $csv .= $label1.' '.$label2.'/'.$label3.' '.$label4;
		            $csv .= ' EMZ: '.$emz." \n ";
		          }
	            $nichtgeschaetzt=round($flst->ALB_Flaeche-$flaeche_222-$flaeche_223);
	            if($nichtgeschaetzt>0){
          			$csv .=  utf8_encode('nicht gesch�tzt: '.$nichtgeschaetzt." m� \n");
          		}
        			if($emzges_222 > 0){
        				$BWZ_222 = round($emzges_222/$flaeche_222*100);
          			$csv .= ' Ackerland gesamt: EMZ '.$emzges_222.' , BWZ '.$BWZ_222." \n";
          		}
        			if($emzges_223 > 0){
        				$BWZ_223 = round($emzges_223/$flaeche_223*100);
        				$csv .= utf8_encode(' Gr�nland gesamt: EMZ '.$emzges_223.' , BWZ '.$BWZ_223);
							}
	        	}
	        }
        //////////////////////
        
        $csv .= '";';
      }      
      if($formvars['freitext']) {
        for($j = 0; $j < count($flst->FreiText); $j++){
        	if($j > 0)$csv .= ' | ';
          $csv .= $flst->FreiText[$j]['text'];
        }
        $csv .= ';';
      }
      if ($formvars['hinweis']){ $csv .= $flst->Hinweis['bezeichnung'].';';}
      if ($formvars['baulasten']){
        for($b=0; $b < count($flst->Baulasten); $b++) {
          $csv .= " ".$flst->Baulasten[$b]['blattnr'];
        }
        $csv .= ';';
      }
      if ($formvars['ausfstelle']){ 
      	for($v = 0; $v < count($flst->Verfahren); $v++){
      		if($v > 0)$csv .= ' | ';
      		$csv .= $flst->Verfahren[$v]['ausfstelleid'].' '.$flst->Verfahren[$v]['ausfstellename'];
      	}
      	$csv .= ';';
      }
      if ($formvars['verfahren']){
      	for($v = 0; $v < count($flst->Verfahren); $v++){
      		if($v > 0)$csv .= ' | ';
      		$csv .= $flst->Verfahren[$v]['verfnr'].' '.$flst->Verfahren[$v]['verfbemerkung'];
      	}
      	$csv .= ';';
      }
      if ($formvars['nutzung']){
        $anzNutzung=count($flst->Nutzung);
        for ($j = 0; $j < $anzNutzung; $j++){
        	if($j > 0)$csv .= ' | ';
          $csv .= $flst->Nutzung[$j][flaeche].' m2 ';
          $csv .= $flst->Nutzung[$j][nutzungskennz].' ';
          if($flst->Nutzung[$j][abkuerzung]!='') {
            $csv .= $flst->Nutzung[$j][abkuerzung].'-';
          }
          $csv .= $flst->Nutzung[$j][bezeichnung];
        }
        $csv .= ';';
      }
      
      if($formvars['blattnr']){
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= ' | ';
	            $csv .= intval($flst->Buchungen[$b]['blatt']).'|';
	          }
	        }
	        $csv .= ';';
		    }
		    
		    if($formvars['pruefzeichen']){
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= ' | ';
	            $csv .= str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
	          }
	        }
	        $csv .= ';';
		    }
		    
    		if($formvars['pruefzeichen_f']){
	      	$csv .= $flst->Pruefzeichen;
	      	$csv .= ';';
	      }
		    
		    if($formvars['bvnr']){
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= ' | ';
	            $csv .= ' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
	          }
	        }
	        $csv .= ';';
		    }
		    
		    if($formvars['buchungsart']){
	        for($g = 0; $g < count($flst->Grundbuecher); $g++){
	          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	          for($b = 0; $b < count($flst->Buchungen); $b++){
	          	if($b > 0)$csv .= ' | ';
	            $csv .= ' ('.$flst->Buchungen[$b]['buchungsart'].')';
	            $csv .= ' '.$flst->Buchungen[$b]['bezeichnung'];
	          }
	        }
	        $csv .= ';';
	      }
      
      if($formvars['eigentuemer']){
      	$csv .= '"';
        for($g = 0; $g < count($flst->Grundbuecher); $g++){
        	if($g > 0)$csv .= "\n";
          $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
          for($b = 0; $b < count($flst->Buchungen); $b++){
          	if($b > 0)$csv .= "\n";
            $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
            $anzEigentuemer=count($Eigentuemerliste);
            for($e=0;$e<$anzEigentuemer;$e++){
            	if($e > 0)$csv .= "\n";
              $csv .= $Eigentuemerliste[$e]->Nr.' ';
              $anzNamenszeilen = count($Eigentuemerliste[$e]->Name);
              for($n=0;$n<$anzNamenszeilen;$n++) {
                $csv .= $Eigentuemerliste[$e]->Name[$n].' ';
              }
            }
          }
        }
        $csv .= '";';
      }
      $csv .= chr(10);
    }

    ob_end_clean();
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition:  inline; filename=Flurstuecke.csv");
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    print utf8_decode($csv);
  }
  

  function getFlurstKennzByRaumbezug($FlurstKennz,$Raumbezug,$Wert) {
    $ret = $this->database->getFlurstuecksKennzByRaumbezug($FlurstKennz,$Raumbezug,$Wert);
    return $ret;
  }

  function getFlurstKennzByGemeindeIDs($Gemeinde_IDs, $FlurstKennz) {
    $ret = $this->database->getFlurstuecksKennzByGemeindeIDs($Gemeinde_IDs, $FlurstKennz);
    return $ret;
  }

  function ALBAuszug_SeitenKopf(&$pdf,$flst,$Ueberschrift,$art,$seite,&$row,$fontSize,$BestandStr,$AktualitaetsNr) {
    # 2006-11-23 Holger Riedel Formatierungs�nderung
    $col0=50; # 28 -> 50 2007-04-02 Schmidt
    $col1=$col0+7.23;
    $col27=$col0+195.17;
    $col37=$col0+267.45;
    $col42=$col0+303.59;
    $col48=$col0+346.96;
    $col58=$col0+419.24;
    $col59=$col0+426.47;
    $col64=$col0+462.61;
    $col70=$col0+505.99;

    $pdf->addText(342,$row-=12,$fontSize,$art); # 2007-04-02 Schmidt
    # $pdf->addText($col37,$row-=12,$fontSize,$art);
    if($art != 'Bestand'){
      $pdf->addText(412,$row,$fontSize,mb_substr($flst->FlurstKennz,0,20,'utf8')); # 2007-04-02 Schmidt
      # $pdf->addText(374,$row,$fontSize,mb_substr($flst->FlurstKennz,0,20));
      $pdf->addText(572,$row,$fontSize,$flst->getPruefKZ()); # 2007-04-02 Schmidt
      #  $pdf->addText(547,$row,$fontSize,$flst->getPruefKZ());
      $pdf->addText(412,$row-=12,$fontSize,str_repeat('=',23)); # 2007-04-02 Schmidt
      # $pdf->addText(374,$row-=12,$fontSize,str_repeat('=',25));
    }
    else{
      $pdf->addText(477,$row,$fontSize,utf8_decode($BestandStr)); # 2007-04-02 Schmidt
      # $pdf->addText($col58,$row,$fontSize,$BestandStr);
      # $pdf->addText(477,$row-=12,$fontSize,str_repeat('=',14));
      $pdf->addText($col58,$row-=12,$fontSize,str_repeat('=',15));
    }
    $pdf->addText(342,$row-=12,$fontSize,'Datum'); # 2007-04-02 Schmidt
    # $pdf->addText($col37,$row-=12,$fontSize,'Datum');
    $pdf->addText(412,$row,$fontSize,date('d.m.Y')); # 2007-04-02 Schmidt
    # $pdf->addText($col48,$row,$fontSize,date('d.m.Y'));
    # 23.11.2006 H.Riedel - Aktualit�tsnr f�r Bestand aus Grundbuchblatt holen
    if($art != 'Bestand'){
      $pdf->addText(490,$row,$fontSize,str_pad($flst->AktualitaetsNr,2,"0",STR_PAD_LEFT)); # 2007-04-02 Schmidt
      # $pdf->addText(453,$row,$fontSize,str_pad($flst->AktualitaetsNr,2,"0",STR_PAD_LEFT));
    }
    else{
      # $pdf->addText($col59,$row,$fontSize,str_pad($flst->AktualitaetsNr,4,"0",STR_PAD_LEFT));
      $pdf->addText(490,$row,$fontSize,str_pad($AktualitaetsNr,4,"0",STR_PAD_LEFT)); # 2007-04-02 Schmidt
      # $pdf->addText($col59,$row,$fontSize,str_pad($AktualitaetsNr,4,"0",STR_PAD_LEFT));
    }
    $pdf->addText($col0,$row-=12,$fontSize,$Ueberschrift);
    $pdf->addText(527,$row,$fontSize,'Seite '.$seite); # 2007-04-02 Schmidt
    # $pdf->addText($col64,$row,$fontSize,'Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
    $row-=12;
  }

  function ALBAuszug_Flurstueck($FlurstKennz,$formnummer,$wasserzeichen) {
  	global $katasterfuehrendestelle;
    $pdf=new Cezpdf();
    $pdf->selectFont(PDFCLASSPATH.'fonts/Courier.afm');
    # Hilfsobjekte erzeugen
    $fontSize=12;
    $col00=28;
    $col0=50; # 35 -> 50 2007-04-02 Schmidt
    $col1=$col0+20;
    $col1a=$col1+16;
    $col1b=$col1a+30;
    $col1_1=115;
    $col2=$col0+100;
    $col2_1=$col2+50;
    $col2_2=$col2_1+20;
    $col2_3=$col2+25;
    $col3=$col0+185;
    $col4=$col0+200;
    $col4a=$col0+228;
    $col5=$col0+248;
    $col6=342;
    $col7=363;
    $col8=$col6+70;
    $col9=527;
    $col9_1=$col9-50;

    for($f = 0; $f < count($FlurstKennz); $f++){
      $pagecount[$f] = $pagecount[$f] + 1;
      if ($wasserzeichen) {
        $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
      }
      $seite=1;
      $row=825; # 812 -> 825  2007-04-02 Schmidt
      $nennerausgabe= '';
      $flst=new flurstueck($FlurstKennz[$f],$this->database);
      $flst->database=$this->database;
      $ret=$flst->readALB_Data($FlurstKennz[$f]);
      if ($ret!='') {
        return $ret;
      }

      if ($flst->Status != 'H' OR $formnummer = '30') {
        switch ($formnummer) {
          case '30' : {
            $Ueberschrift='*Flurst�cksnachweis';
            $art = 'Flurst�ck';
          } break;
          case '35' : {
            $Ueberschrift='*Flurst�cks- und Eigent�mernachweis';
            $art = 'Flurst�ck';
          } break;
          case '40' : {
            $Ueberschrift='******** Eigent�mernachweis *******';
            $art = 'Flurst�ck';
          } break;
        }

        $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,$art,$seite,$row,$fontSize,NULL,$AktualitaetsNr);

        if(AMT != ''){
        	$amt = AMT;
        	if($katasterfuehrendestelle){
	        	foreach ($katasterfuehrendestelle as $key => $value) {
					    if($flst->Grundbuecher[0]['bezirk'] <= $key) {
					      $amt .= $value;
					      break;
					    }
	        	}
        	}
        	$pdf->addText($col0,$row-=12,$fontSize,utf8_decode($amt));
        }
        if(LANDKREIS != '')$pdf->addText($col7,$row-=12,$fontSize,utf8_decode(LANDKREIS));
        if(($formnummer == '30' OR $formnummer == '35') AND BEARBEITER == 'true')$pdf->addText($col0,$row-=12,$fontSize,utf8_decode(BEARBEITER_NAME));
        if(STRASSE != '')$pdf->addText($col7,$row-=12,$fontSize,utf8_decode(STRASSE));
        if(STRASSE2 != '')$pdf->addText($col7,$row-=12,$fontSize,utf8_decode(STRASSE2));
        if(PLZ != '')$pdf->addText($col7,$row-=12,$fontSize,utf8_decode(PLZ.' '.ORT));
      	if(POSTANSCHRIFT != '')$pdf->addText($col7,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT));
        if(POSTANSCHRIFT_STRASSE != '')$pdf->addText($col7,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT_STRASSE));
        if(POSTANSCHRIFT_PLZ != '')$pdf->addText($col7,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT_PLZ.' '.POSTANSCHRIFT_ORT));
        $pdf->addText($col0,$row-=12,$fontSize,'Gemarkung');
        $pdf->addText($col3,$row,$fontSize,$flst->GemkgSchl);
        $pdf->addText($col7,$row,$fontSize,utf8_decode($flst->GemkgName));

        if($formnummer == '30' || $formnummer == '35'){
          $pdf->addText($col0,$row-=12,$fontSize,'Gemeinde');
          $pdf->addText($col3,$row,$fontSize,$flst->GemeindeID);
          $pdf->addText($col7,$row,$fontSize,utf8_decode($flst->GemeindeName));

          $pdf->addText($col0,$row-=12,$fontSize,'Kreis/Stadt');
          $pdf->addText($col3,$row,$fontSize,$flst->KreisID);
          $pdf->addText($col7,$row,$fontSize,utf8_decode($flst->KreisName));

          $pdf->addText($col0,$row-=12,$fontSize,'Finanzamt');
          $pdf->addText($col3,$row,$fontSize,$flst->FinanzamtSchl);
          $pdf->addText($col7,$row,$fontSize,utf8_decode($flst->FinanzamtName));

          $pdf->addText($col0,$row-=12,$fontSize,'Forstamt');
          $pdf->addText($col3,$row,$fontSize,'00'.$flst->Forstamt['schluessel']);
          $pdf->addText($col7,$row,$fontSize,utf8_decode($flst->Forstamt['name']));
        }


        $pdf->addText($col0,$row-=12,$fontSize,str_repeat("-",75)); # Schmidt 2007-04-02

        if($formnummer == '30' || $formnummer == '35'){
          $pdf->addText($col0,$row-=24,$fontSize,'GMKG   FLR FLURST-NR    P');
          if($flst->Status == 'H'){
          	$pdf->addText($col3,$row,$fontSize,'Status');
          	$pdf->addText($col6,$row,$fontSize,utf8_decode('(H) Historisches Flurst�ck'));
          }
          if ($flst->Nenner!=0) { $nennerausgabe="/".$flst->Nenner; }
          $pdf->addText($col0,$row-=12,$fontSize,$flst->GemkgSchl." ".str_pad($flst->FlurNr,3," ",STR_PAD_LEFT)." ".str_pad($flst->Zaehler,5," ",STR_PAD_LEFT).$nennerausgabe);
          $pdf->addText($col0+173,$row,$fontSize,$flst->getPruefKZ());
          $pdf->addText($col0,$row-=12,$fontSize,str_repeat('=',25));

          $pdf->addText($col3,$row,$fontSize,'Entstehung');
          if($flst->Entstehung == '/     -'){
            $flst->Entstehung = 2;
          }
          $pdf->addText($col6,$row,$fontSize,$flst->Entstehung);

          $pdf->addText($col3,$row-=12,$fontSize,'Fortf�hrung');
          $pdf->addText($col6,$row,$fontSize,$flst->LetzteFF);

          $pdf->addText($col3,$row-=12,$fontSize,'Flurkarte Ri�');
          $pdf->addText($col6,$row,$fontSize,$flst->Flurkarte);
          $pdf->addText($col0,$row-=24,$fontSize,'Lage');
          # Ausgabe der Adressangabe zur Lage
          $anzStrassen=count($flst->Adresse);
          for ($s=0;$s<$anzStrassen;$s++) {
            $Adressbezeichnung =$flst->Adresse[$s]["strasse"];
            $Adressbezeichnung.=' '.$flst->Adresse[$s]["strassenname"];
            $Adressbezeichnung.=' '.$flst->Adresse[$s]["hausnr"];
            $ausgabetext=zeilenumbruch($Adressbezeichnung,40);
            $pdf->addText($col3,$row-=12,$fontSize,utf8_decode($ausgabetext[0]));
            for ($j=1;$j<count($ausgabetext);$j++) {
              $pdf->addText($col4a,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
          }
          # Ausgabe Lagebezeichnung falls vorhanden
          $Lagebezeichnung=$flst->Lage;
          for ($i=0;$i<count($Lagebezeichnung);$i++) {
            $pdf->addText($col3,$row-=12,$fontSize,utf8_decode($Lagebezeichnung[$i]));
          }
          $pdf->addText($col0,$row-=24,$fontSize,'Tats�chliche Nutzung');
          for ($i=0;$i<count($flst->Nutzung);$i++) {
          	# Seitenumbruch wenn erforderlich
            if($row<120) {
              # Seitenumbruch
              $seite++;
              # aktuelle Seite abschlie�en
              $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
              # neue Seite beginnen
              $pageid=$pdf->newPage();
              $pagecount[$f] = $pagecount[$f] + 1;
              if ($wasserzeichen) {
                $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
              }
              $row=825; # 812 -> 825 2007-04-02 Schmidt
              $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
            }
            $pdf->addText($col1_1,$row-=12,$fontSize,str_pad (str_space($flst->Nutzung[$i]['flaeche'],3), 11, ' ', STR_PAD_LEFT).' m2');
            $pdf->addText($col4,$row,$fontSize,$flst->Nutzung[$i]['nutzungskennz']);
            $Nutzunglangtext=$flst->Nutzung[$i]['bezeichnung'];
            if ($flst->Nutzung[$i]['abkuerzung']!='') {
              $Nutzunglangtext.=' ('.$flst->Nutzung[$i]['abkuerzung'].')';
            }
            $ausgabetext=zeilenumbruch($Nutzunglangtext,40);
            $pdf->addText($col5,$row,$fontSize,utf8_decode($ausgabetext[0]));
            for ($j=1;$j<count($ausgabetext);$j++) {
              $pdf->addText($col5,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
          }
          $pdf->addText($col0,$row-=12,$fontSize,str_repeat('-',25));
          $pdf->addText($col0,$row-=12,$fontSize,'Fl�che');
          $pdf->addText($col1_1,$row,$fontSize,str_pad (str_space($flst->ALB_Flaeche,3), 11, "*", STR_PAD_LEFT).' m2');
          $pdf->addText($col0,$row-=12,$fontSize,str_repeat('=',25));

          # Gesetzliche Klassifizierung
          $anzKlassifizierungen=count($flst->Klassifizierung)-1;
          if ($anzKlassifizierungen>0) {
            $pdf->addText($col0,$row-=24,$fontSize,'Klassifizierung');
            $pdf->addText($col4,$row,$fontSize,$flst->Klassifizierung[0]['tabkenn']);
            $emz_summe = 0;
            $summe = 0;
            $count = 0;
            for ($i=0;$i<$anzKlassifizierungen;$i++) {
            	$bruch = NULL;            	
              # Seitenumbruch wenn erforderlich
              if($row<120) {
                # Seitenumbruch
                $seite++;
                # aktuelle Seite abschlie�en
                $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
                # neue Seite beginnen
                $pageid=$pdf->newPage();
                $pagecount[$f] = $pagecount[$f] + 1;
                if ($wasserzeichen) {
                  $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
                }
                $row=825; # 812 -> 825 2007-04-02 Schmidt
                $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
              }
              $pdf->addText($col2,$row-=12,$fontSize,str_pad ($flst->Klassifizierung[$i]['flaeche'].' m2', 11, ' ', STR_PAD_LEFT));
              $pdf->addText($col4,$row,$fontSize,utf8_decode($flst->Klassifizierung[$i]['tabkenn'].'-'.$flst->Klassifizierung[$i]['klass']));
              $ausgabetext=zeilenumbruch($flst->Klassifizierung[$i]['bezeichnung'],40);
              $pdf->addText($col5,$row,$fontSize,utf8_decode($ausgabetext[0]));
              for ($j=1;$j<count($ausgabetext);$j++) {
                $pdf->addText($col5,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
              }
              # $pdf->addText($col5,$row-=12,$fontSize,$flst->Klassifizierung[$i]['Abkuerzung']);

              $angaben = explode(' ', $flst->Klassifizierung[$i]['angaben']);
              $z = 0;
              $row = $row-12;
              for($j = 0; $j < count($angaben); $j++){
                if($angaben[$j] != ''){
                  if($z == 0){
                    $ausgabe = 'Bodsch '.$angaben[$j];
                    $abstand = 0;
                  }
                  if($z == 1){
                    if(strlen($angaben[$j]) == 1){
                      $ausgabe = $angaben[$j].$angaben[$j+1];
                      $j++;
                    }
                    else{
                      $ausgabe = $angaben[$j];
                    }
                    $abstand += 100;
                  }
                  if($z == 2){
                    $bruch = explode('/', $angaben[$j]);
                    $ausgabe = 'WZ  '.ltrim($bruch[0], '0').'/'.ltrim($bruch[1], '0');
                    $abstand += 40;
                  }
                  $pdf->addText($col4+$abstand,$row,$fontSize,utf8_decode($ausgabe));
                  $z++;
                }
              }
              if($bruch[1]){
                $abstand += 110;
                $emz = round($flst->Klassifizierung[$i]['flaeche'] * $bruch[1] / 100);
                $pdf->addText($col4+$abstand,$row,$fontSize,'EMZ   '.$emz);
                $emz_summe += $emz;
              }
              $summe += $flst->Klassifizierung[$i]['flaeche'];
              $count++;
							#--- nach den 32er Folien die Summen ausgeben
            	if($flst->Klassifizierung[$i]['tabkenn'] == '32' AND $flst->Klassifizierung[$i+1]['tabkenn'] != '32'){
            		if($count > 1){
		            	$pdf->addText($col0,$row-=12,$fontSize,str_repeat(" ",10).str_repeat("-",65));
			            $pdf->addText($col0,$row-=12,$fontSize,'Summe');
			            //$pdf->addText($col2,$row,$fontSize,str_pad ($flst->Klassifizierung['summe'].' m2',11,' ',STR_PAD_LEFT));
			            $pdf->addText($col2,$row,$fontSize,str_pad ($summe.' m2',11,' ',STR_PAD_LEFT));
			            $pdf->addText($col4+250,$row,$fontSize,'EMZ   '.$emz_summe);
            		}
		            $summe = 0;
		            $count = 0;
		            $row-=24;
            	}
            	if($flst->Klassifizierung[$i]['tabkenn'] == '33' AND $flst->Klassifizierung[$i+1]['tabkenn'] != '33'){
            		if($count > 1){
		            	$pdf->addText($col0,$row-=12,$fontSize,str_repeat(" ",10).str_repeat("-",65));
			            $pdf->addText($col0,$row-=12,$fontSize,'Summe');
			            $pdf->addText($col2,$row,$fontSize,str_pad ($summe.' m2',11,' ',STR_PAD_LEFT));
            		}
		            $summe = 0;
		            $count = 0;
		            $row-=24;
            	}
            	if($flst->Klassifizierung[$i]['tabkenn'] == '34' AND $flst->Klassifizierung[$i+1]['tabkenn'] != '34'){
            		if($count > 1){
		            	$pdf->addText($col0,$row-=12,$fontSize,str_repeat(" ",10).str_repeat("-",65));
			            $pdf->addText($col0,$row-=12,$fontSize,'Summe');
			            $pdf->addText($col2,$row,$fontSize,str_pad ($summe.' m2',11,' ',STR_PAD_LEFT));
            		}
		            $summe = 0;
		            $count = 0;
		            $row-=24;
            	}
            	if($flst->Klassifizierung[$i]['tabkenn'] == '35' AND $flst->Klassifizierung[$i+1]['tabkenn'] != '35'){
            		if($count > 1){
		            	$pdf->addText($col0,$row-=12,$fontSize,str_repeat(" ",10).str_repeat("-",65));
			            $pdf->addText($col0,$row-=12,$fontSize,'Summe');
			            $pdf->addText($col2,$row,$fontSize,str_pad ($summe.' m2',11,' ',STR_PAD_LEFT));
            		}
		            $summe = 0;
		            $count = 0;
		            $row-=24;
            	}
            	if($flst->Klassifizierung[$i]['tabkenn'] == '37' AND $flst->Klassifizierung[$i+1]['tabkenn'] != '37'){
            		if($count > 1){
		            	$pdf->addText($col0,$row-=12,$fontSize,str_repeat(" ",10).str_repeat("-",65));
			            $pdf->addText($col0,$row-=12,$fontSize,'Summe');
			            $pdf->addText($col2,$row,$fontSize,str_pad ($summe.' m2',11,' ',STR_PAD_LEFT));
            		}
		            $summe = 0;
		            $count = 0;
		            $row-=24;
            	}
            }
          }

          /*
          # Gesetzliche Klassifizierung
          $anzKlassifizierungen=count($flst->Klassifizierung)-1;
          if ($anzKlassifizierungen>0) {
            $pdf->addText($col0,$row-=24,$fontSize,'Klassifizierung'.count($flst->Klassifizierung));
            $pdf->addText($col4,$row,$fontSize,$flst->Klassifizierung[0]['tabkenn']);
            for ($i=0;$i<$anzKlassifizierungen;$i++) {
              $pdf->addText($col2,$row-=12,$fontSize,str_pad ($flst->Klassifizierung[$i]['flaeche'].' m2', 11, ' ', STR_PAD_LEFT));
              $pdf->addText($col4,$row,$fontSize,$flst->Klassifizierung[$i]['tabkenn'].'-'.$flst->Klassifizierung[$i]['klass']);
              $ausgabetext=zeilenumbruch($flst->Klassifizierung[$i]['bezeichnung'],40);
              $pdf->addText($col5,$row,$fontSize,$ausgabetext[0]);
              for ($j=1;$j<count($ausgabetext);$j++) {
                $pdf->addText($col5,$row-=12,$fontSize,$ausgabetext[$j]);
              }
              # $pdf->addText($col5,$row-=12,$fontSize,$flst->Klassifizierung[$i]['Abkuerzung']);
              $pdf->addText($col4,$row-=12,$fontSize,$flst->Klassifizierung[$i]['angaben']);
            }
            $pdf->addText($col0,$row-=12,$fontSize,str_repeat(" ",10).str_repeat("-",65));
            $pdf->addText($col0,$row-=12,$fontSize,'Summe');
            $pdf->addText($col2,$row,$fontSize,str_pad ($flst->Klassifizierung['summe'].' m2',11,' ',STR_PAD_LEFT));
          }
          */

        	if($row<120) {
            # Seitenumbruch
            $seite++;
            # aktuelle Seite abschlie�en
            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
            # neue Seite beginnen
            $pageid=$pdf->newPage();
            $pagecount[$f] = $pagecount[$f] + 1;
            if ($wasserzeichen) {
            	$pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
            }
            $row=825; # 812 -> 825 2007-04-02 Schmidt
          	$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
          }
          
          # Freier Text zum Flurst�ck
          if (count($flst->FreiText)>0) {
            $pdf->addText($col0,$row-=24,$fontSize,'Zus�tzliche Angaben');
            for ($z=0;$z<count($flst->FreiText);$z++) {
              if ($z==0) { $row+=12; }
              $ausgabetext=zeilenumbruch($flst->FreiText[$z]['text'],40);
              $pdf->addText($col2_1,$row-=12,$fontSize,utf8_decode($ausgabetext[0]));
              for ($j=1;$j<count($ausgabetext);$j++) {
                $pdf->addText($col2_1,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
              }
            }
          }

          # Hinweise zum Flurst�cke
          if ($flst->Hinweis[0]['hinwzflst']!='') {
            $pdf->addText($col0,$row-=24,$fontSize,'Hinweise');
          }
          for($h = 0; $h < count($flst->Hinweis); $h++){
            $pdf->addText($col2_1,$row,$fontSize,utf8_decode($flst->Hinweis[$h]['hinwzflst']));
            $pdf->addText($col2_2,$row,$fontSize,utf8_decode($flst->Hinweis[$h]['bezeichnung']));
            $row = $row - 12;
          }

          # Baulastenblattnummer
          if (count($flst->Baulasten)>0) {
            $pdf->addText($col0,$row-=24,$fontSize,'Baulastenblatt-Nr');
            $BaulastenStr=$flst->Baulasten[0]['blattnr'];
            for ($k=1;$k<count($flst->Baulasten);$k++) {
              $BaulastenStr.=', '.$flst->Baulasten[$k]['blattnr'];
            }
            $ausgabetext=zeilenumbruch($BaulastenStr,40);
            $pdf->addText($col2_1,$row,$fontSize,utf8_decode($ausgabetext[0]));
            for ($j=1;$j<count($ausgabetext);$j++) {
              $pdf->addText($col2_1,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
            #$pdf->addText($col2_1,$row,$fontSize,$BaulastenStr);
          }

          # Verfahren
          $anzVerfahren=count($flst->Verfahren);
          for ($i=0;$i<$anzVerfahren;$i++){
	          if($row<120) {
	            # Seitenumbruch
	            $seite++;
	            # aktuelle Seite abschlie�en
	            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
	            # neue Seite beginnen
	            $pageid=$pdf->newPage();
	            $pagecount[$f] = $pagecount[$f] + 1;
	            if ($wasserzeichen) {
	            	$pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
	            }
	            $row=825; # 812 -> 825 2007-04-02 Schmidt
	          	$this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
	          }
            $pdf->addText($col0,$row-=24,$fontSize,'Ausf�hrende Stelle');
            $pdf->addText($col2_1,$row,$fontSize,$flst->Verfahren[$i]['ausfstelleid']);
            $AusfStelleName=zeilenumbruch($flst->Verfahren[$i]['ausfstellename'],40);
            $pdf->addText($col4,$row,$fontSize,utf8_decode($AusfStelleName[0]));
            for ($j=1;$j<count($AusfStelleName);$j++) {
              $pdf->addText($col4,$row-=12,$fontSize,utf8_decode($AusfStelleName[$j]));
            }
            if($flst->Verfahren[$i]['verfnr'] != ''){
              $pdf->addText($col0,$row-=12,$fontSize,'Verfahren');
              $pdf->addText($col2_1,$row,$fontSize,$flst->Verfahren[$i]['verfnr']);
              $pdf->addText($col4,$row,$fontSize,'('.$flst->Verfahren[$i]['verfbemid'].')');
              $AusfBemerkung=zeilenumbruch($flst->Verfahren[$i]['verfbemerkung'],40);
              $pdf->addText($col5,$row,$fontSize,utf8_decode($AusfBemerkung[0]));
              for ($j=1;$j<count($AusfBemerkung);$j++) {
                $pdf->addText($col5,$row-=12,$fontSize,utf8_decode($AusfBemerkung[$j]));
              }
            }
          }

          # Vorg�ngerflurst�cke
          if (count($flst->Vorgaenger)>0) {
            $pdf->addText($col0,$row-=24,$fontSize,'Vorg�ngerflurst�ck');
            $pdf->addText($col2_1,$row,$fontSize,mb_substr($flst->Vorgaenger[0]['vorgaenger'],0,20,'utf8'));
            for ($v=1;$v<count($flst->Vorgaenger);$v++) {
              $pdf->addText($col2_1,$row-=12,$fontSize,mb_substr($flst->Vorgaenger[$v]['vorgaenger'],0,20,'utf8'));
            }
          }
          # Nachfolgerflurst�cke
          if (count($flst->Nachfolger)>0) {
            $pdf->addText($col0,$row-=24,$fontSize,'Nachfolgerflurst�ck');
            $pdf->addText($col2_1,$row,$fontSize,mb_substr($flst->Nachfolger[0]['nachfolger'],0,20,'utf8'));
            for ($v=1;$v<count($flst->Nachfolger);$v++) {
              $pdf->addText($col2_1,$row-=12,$fontSize,mb_substr($flst->Nachfolger[$v]['nachfolger'],0,20,'utf8'));
            }
          }
        } # endif 30 oder 35
					
				if($flst->Status != 'H'){
	        # Amtsgericht, Grundbuchbezirk
	        $pdf->addText($col0,$row-=24,$fontSize,'Amtsgericht');
	        $pdf->addText($col2_1,$row,$fontSize,str_pad($flst->Amtsgericht['schluessel'],11," "));
	        $pdf->addText($col4,$row,$fontSize,utf8_decode($flst->Amtsgericht['name']));
	        $pdf->addText($col0,$row-=12,$fontSize,'Grundbuchbezirk');
	        $pdf->addText($col2_1,$row,$fontSize,str_pad($flst->Grundbuchbezirk['schluessel'],11," "));
	        $pdf->addText($col4,$row,$fontSize,utf8_decode($flst->Grundbuchbezirk['name']));
				
	        ################################################################################
	        # Bestandsnachweis #
	        ####################
	        switch ($formnummer) {
	          case 40 : {
	            for ($g=0;$g<count($flst->Grundbuecher);$g++) {
	              # Bestand
	              $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],1);
	              for ($b=0;$b<count($flst->Buchungen);$b++) {
	                # Seitenumbruch wenn erforderlich
	                if($row<120) {
	                  # Seitenumbruch
	                  $seite++;
	                  # aktuelle Seite abschlie�en
	                  $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
	                  # neue Seite beginnen
	                  $pageid=$pdf->newPage();
	                  $pagecount[$f] = $pagecount[$f] + 1;
	                  if ($wasserzeichen) {
	                    $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
	                  }
	                  $row=825; # 812 -> 825 2007-04-02 Schmidt
	                  $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
	                }
	
	                # Ausgabe der Zeile f�r die Bestandbezeichnung
	                $pdf->addText($col0,$row-=12,$fontSize,'Bestand');
	                $BestandStr =$flst->Buchungen[$b]['bezirk'].'-'.intval($flst->Buchungen[$b]['blatt']);
	                $BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
	                $BestandStr.=' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
	                $BestandStr.=' ('.$flst->Buchungen[$b]['buchungsart'].')';
	                $BestandStr.=' '.$flst->Buchungen[$b]['bezeichnung'];
	                $pdf->addText($col2_1,$row,$fontSize,$BestandStr);
	                $pdf->addText($col0,$row-=12,$fontSize,str_repeat("=",7));
	
	                # Abfragen und Ausgeben der Eigent�mer zum Grundbuchblatt
	                $Eigentuemerliste=$flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
	                $anzEigentuemer=count($Eigentuemerliste);
	                for ($i=0;$i<$anzEigentuemer;$i++) {
	                  if($row<120) {
	                    # Seitenumbruch
	                    $seite++;
	                    # aktuelle Seite abschlie�en
	                    $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
	                    # neue Seite beginnen
	                    $pageid=$pdf->newPage();
	                    $pagecount[$f] = $pagecount[$f] + 1;
	                    if ($wasserzeichen) {
	                      $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
	                    }
	                    $row=825; # 812 -> 825 2007-04-02 Schmidt;
	                    $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
	                  }
	                  else {
	                    $row-=12;
	                  }
	                  if ($Eigentuemerliste[$i]->Nr!=0) {
	                    $pdf->addText($col0,$row-=12,$fontSize,$Eigentuemerliste[$i]->Nr);
	                    if($Eigentuemerliste[$i]->Anteil != '')$pdf->addText($col3,$row,$fontSize,'zu '.$Eigentuemerliste[$i]->Anteil);
	                  }
	                  else {
	                    $row-=12;
	                  }
	                  $anzNamenszeilen=count($Eigentuemerliste[$i]->Name);
	                  for ($k=0;$k<$anzNamenszeilen;$k++) {
	                    $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($Eigentuemerliste[$i]->Name[$k]));
	                  }
	                } # ende Schleife Eigent�mer des Grundbuchblattes
	              } # ende Schleife Bestand
	              if ($flst->Grundbuecher[$g]['zusatz_eigentuemer']!='') {
	                $zusatzeigentuemertext=$flst->Grundbuecher[$g]['zusatz_eigentuemer'];
	                while(strlen($zusatzeigentuemertext) > 60){
	                  $positionkomma=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'),",",'utf8');
	                  $positionleerzeichen=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8')," ",'utf8');
	                  if($positionkomma>$positionleerzeichen){
	                    $positiontrenner=$positionkomma;
	                  }
	                  else{
	                    $positiontrenner=$positionleerzeichen;
	                  }
	                  if($row<120) {
	                    # Seitenumbruch
	                    $seite++;
	                    # aktuelle Seite abschlie�en
	                    $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
	                    # neue Seite beginnen
	                    $pageid=$pdf->newPage();
	                    $pagecount[$f] = $pagecount[$f] + 1;
	                    if ($wasserzeichen) {
	                      $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
	                    }
	                    $row=825; # 812 -> 825 2007-04-02 Schmidt;
	                    $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
	                  }
	                  $pdf->addText($col1,$row-=12,$fontSize,utf8_decode(mb_substr($zusatzeigentuemertext,0,$positiontrenner,'utf8')));
	                  $zusatzeigentuemertext=mb_substr($zusatzeigentuemertext,$positiontrenner+1, 999, 'utf8');
	                }
	                $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($zusatzeigentuemertext));
	              }
	            } # ende Schleife Grundbuecher
	          } # ende Ausgabe Formular 40
	          break;
	          case 35 : {
	            for ($g=0;$g<count($flst->Grundbuecher);$g++) {
	              # Bestand
	              $flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],1);
	              for ($b=0;$b<count($flst->Buchungen);$b++) {
	                # Seitenumbruch wenn erforderlich
	                if($row<120) {
	                  # Seitenumbruch
	                  $seite++;
	                  # aktuelle Seite abschlie�en
	                  $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
	                  # neue Seite beginnen
	                  $pageid=$pdf->newPage();
	                  $pagecount[$f] = $pagecount[$f] + 1;
	                  if ($wasserzeichen) {
	                    $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
	                  }
	                  $row=825; # 812 -> 825 2007-04-02 Schmidt;
	                  $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
	                }
	
	                # Ausgabe der Zeile f�r die Bestandbezeichnung
	                $pdf->addText($col0,$row-=24,$fontSize,'Bestand');
	                $BestandStr =$flst->Buchungen[$b]['bezirk'].'-'.intval($flst->Buchungen[$b]['blatt']);
	                $BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
	                $BestandStr.=' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
	                $BestandStr.=' ('.$flst->Buchungen[$b]['buchungsart'].')';
	                $BestandStr.=' '.$flst->Buchungen[$b]['bezeichnung'];
	                $pdf->addText($col2_1,$row,$fontSize,$BestandStr);
	                $pdf->addText($col0,$row-=12,$fontSize,str_repeat("=",7));
	
	                # Abfragen und Ausgeben der Eigent�mer zum Grundbuchblatt
	                $Eigentuemerliste=$flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
	                $anzEigentuemer=count($Eigentuemerliste);
	                for ($i=0;$i<$anzEigentuemer;$i++) {
	                  if($row<120) {
	                    # Seitenumbruch
	                    $seite++;
	                    # aktuelle Seite abschlie�en
	                    $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
	                    # neue Seite beginnen
	                    $pageid=$pdf->newPage();
	                    $pagecount[$f] = $pagecount[$f] + 1;
	                    if ($wasserzeichen) {
	                      $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
	                    }
	                    $row=825; # 812 -> 825 2007-04-02 Schmidt;
	                    $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
	                  }
	                  else {
	                    $row-=12;
	                  }
	                  if ($Eigentuemerliste[$i]->Nr!=0) {
	                    $pdf->addText($col0,$row-=12,$fontSize,$Eigentuemerliste[$i]->Nr);
	                    if($Eigentuemerliste[$i]->Anteil != '')$pdf->addText($col3,$row,$fontSize,'zu '.$Eigentuemerliste[$i]->Anteil);
	                  }
	                  else {
	                    $row-=12;
	                  }
	                  $anzNamenszeilen=count($Eigentuemerliste[$i]->Name);
	                  # --- Kommas rausfiltern ---
	                  $Eigentuemerliste[$i]->Name_bearb = $Eigentuemerliste[$i]->Name;
	                  $Eigentuemerliste[$i]->Name_bearb[0] = str_replace(',,,', '', $Eigentuemerliste[$i]->Name_bearb[0]);
	                  $Eigentuemerliste[$i]->Name_bearb[0] = str_replace(',,', ',', $Eigentuemerliste[$i]->Name_bearb[0]);
	                  if(mb_substr($Eigentuemerliste[$i]->Name_bearb[0], 0, strlen($Eigentuemerliste[$i]->Name_bearb[0])-1,'utf8') == ','){
	                    $Eigentuemerliste[$i]->Name_bearb[0] = mb_substr($Eigentuemerliste[$i]->Name_bearb[0], 0, strlen($Eigentuemerliste[$i]->Name_bearb[0])-1,'utf8');
	                  }
	                  # ---------------------------
	                  for ($k=0;$k<$anzNamenszeilen;$k++) {
	                    $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($Eigentuemerliste[$i]->Name_bearb[$k]));
	                  }
	                } # ende Schleife Eigent�mer des Grundbuchblattes
	              } # ende Schleife Bestand
	                            
	              if ($flst->Grundbuecher[$g]['zusatz_eigentuemer']!='') {
	                $zusatzeigentuemertext=$flst->Grundbuecher[$g]['zusatz_eigentuemer'];
	                while(strlen($zusatzeigentuemertext) > 60){
	                  $positionkomma=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'),",",'utf8');
	                  $positionleerzeichen=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8')," ",'utf8');
	                  if($positionkomma>$positionleerzeichen){
	                    $positiontrenner=$positionkomma;
	                  }
	                  else{
	                    $positiontrenner=$positionleerzeichen;
	                  }
	                  if($row<120) {
	                    # Seitenumbruch
	                    $seite++;
	                    # aktuelle Seite abschlie�en
	                    $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
	                    # neue Seite beginnen
	                    $pageid=$pdf->newPage();
	                    $pagecount[$f] = $pagecount[$f] + 1;
	                    if ($wasserzeichen) {
	                      $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
	                    }
	                    $row=825; # 812 -> 825 2007-04-02 Schmidt;
	                    $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
	                  }
	                  $pdf->addText($col1,$row-=12,$fontSize,utf8_decode(mb_substr($zusatzeigentuemertext,0,$positiontrenner,'utf8')));
	                  $zusatzeigentuemertext=mb_substr($zusatzeigentuemertext,$positiontrenner+1, 999,'utf8');
	                }
	                $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($zusatzeigentuemertext));
	              }
	              
	            } # ende Schleife Grundbuecher
	          } # ende Ausgabe Formular 35
	          break;
	          case 30 : {
	            # Bestand
	            $pdf->addText($col0,$row-=24,$fontSize,'Bestand');
	            $pdf->addText($col0,$row-12,$fontSize,str_repeat("=",7));
	
	            for ($b=0;$b<count($flst->Buchungen);$b++) {
	              # Seitenumbruch wenn erforderlich
	              if($row<60) {
	                # Seitenumbruch
	                $seite++;
	                # aktuelle Seite abschlie�en
	                $pdf->addText($col9_1,$row-=24,$fontSize,'Forts. Seite '.$seite);
	                # neue Seite beginnen
	                $pageid=$pdf->newPage();
	                $pagecount[$f] = $pagecount[$f] + 1;
	                if ($wasserzeichen) {
	                  $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
	                }
	                $row=825; # 812 -> 825 2007-04-02 Schmidt
	                $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Flurst�ck',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
	                $pdf->addText($col0,$row-=24,$fontSize,'Bestand');
	                $pdf->addText($col0,$row-12,$fontSize,str_repeat("=",7));
	              }
	
	              # Ausgabe der Zeile f�r die Bestandbezeichnung
	              $BestandStr =$flst->Buchungen[$b]['bezirk'].'-'.intval($flst->Buchungen[$b]['blatt']);
	              $BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
	              $BestandStr.=' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
	              $BestandStr.=' ('.$flst->Buchungen[$b]['buchungsart'].')';
	              $BestandStr.=' '.$flst->Buchungen[$b]['bezeichnung'];
	              $pdf->addText($col2_1,$row-=12,$fontSize,$BestandStr);
	
	            } # ende Schleife Bestand
	          } # ende Ausgabe Bestandsnachweis Formular 30
	          break;
	        } # end of switch for Bestandsnachweis
				}
        # neue Seite beginnen
        if($f < count($FlurstKennz)-1){
          $pageid=$pdf->newPage();
          //$pagecount[$f] = $pagecount[$f] + 1;
        }
      } # end of flurst�ck is not historisch
    } # end of for all flurst�cke
    $pdf->pagecount = $pagecount;
    return $pdf;
  }

  function ALBAuszug_Bestand($Grundbuchbezirk,$Grundbuchblatt,$formnummer,$wasserzeichen) {
    $pdf=new Cezpdf();
    $pdf->selectFont(PDFCLASSPATH.'fonts/Courier.afm');
    # Hilfsobjekte erzeugen

    $grundbuch=new grundbuch($Grundbuchbezirk,$Grundbuchblatt,$this->database);
    # Abfrage aller Flurst�cke, die auf dem angegebenen Grundbuchblatt liegen.
    $ret=$grundbuch->getBuchungen('','','',1);
    $buchungen=$ret[1];

    # ein Flurst�ck erzeugen
    $flst=new flurstueck($buchungen[0]['flurstkennz'],$this->database);
    $flst->database=$this->database;
    $ret=$flst->readALB_Data($buchungen[0]['flurstkennz']);

    if ($wasserzeichen) {
      $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
    }
    $seite=1;
    $fontSize=12;
    $col0=50; # 28 -> 50 Schmidt 2007-04-02
    $col1=$col0+7.23;
    $col6=$col0+43.37;
    $col10=$col0+72.28;
    $col18=$col0+130.11;
    $col27=$col0+195.17;
    $col34=$col0+245.76;
    $col37=$col0+267.45;
    $col42=$col0+303.59;
    $col44=$col0+318.05;
    $col48=$col0+346.96;
    $col57=$col0+412.02;
    $col58=$col0+419.24;
    $col59=$col0+426.47;
    $col62=$col0+448.16;
    $col64=$col0+462.61;
    $col70=$col0+505.99;
    $col00=28;
#    $col0=35;
#    $col1=$col0+20;
#    $col1=$col0+35;
#    $col1a=$col1+16;
#    $col1b=$col1a+30;
#    $col1_1=115;
#    $col2=$col0+100;
#    $col2_1=$col2+50;
#    $col2_2=$col2_1+20;
#    $col2_3=$col2+25;
#    $col3=$col0+185;
#    $col3=$col0+187;
#    $col4=$col0+200;
#    $col5=$col0+248;
#    $col6=342;
#    $col7=363;
#    $col7=330;
#    $col8=$col6+70;
#    $col9=527;
#    $col9_1=$col9-50;
#23.11.06 H.Riedel, eingefuegt
#    $col9_10=$col9-38;
    $row=825; # 812 -> 825 2007-04-02 Schmidt
    switch ($formnummer) {
      case '20' : {
#        $Ueberschrift='******** Bestandsnachweis *******';
        $Ueberschrift='********* Bestandsnachweis ********';
        $art = 'Bestand';
      } break;
      case '25' : {
#        $Ueberschrift='******** Bestands�bersicht *******';
        $Ueberschrift='******** Bestands�bersicht ********';
        $art = 'Bestand';
      } break;
    }

#    $BestandStr =$buchungen[0]['bezirk'].'-'.intval($buchungen[0]['blatt']);
    $BestandStr =$buchungen[0]['bezirk'].'-'.($buchungen[0]['blatt'].' ');
#    $BestandStr.=' '.str_pad($buchungen[0]['pruefzeichen'],3,' ',STR_PAD_LEFT);
    $BestandStr.=str_pad($buchungen[0]['pruefzeichen'],2,' ',STR_PAD_LEFT);
#28.11.2006 H.Riedel, Aktualitaetsnr uebergeben
    $AktualitaetsNr=$buchungen[0]['aktualitaetsnr'];
    $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,$art,$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
  	if(AMT != ''){
    	$amt = AMT;
    	if($katasterfuehrendestelle){
    		foreach ($katasterfuehrendestelle as $key => $value) {
					if($flst->Grundbuecher[0]['bezirk'] <= $key) {
		      	$amt .= $value;
		      	break;
		    	}
        }
      }
	  $pdf->addText($col0,$row-=12,$fontSize,utf8_decode($amt));
    }
    if(LANDKREIS != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(LANDKREIS));
    if(STRASSE != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(STRASSE));
    if(STRASSE2 != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(STRASSE2));
    if(PLZ != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(PLZ.' '.ORT));
    if(POSTANSCHRIFT != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT));
    if(POSTANSCHRIFT_STRASSE != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT_STRASSE));
    if(POSTANSCHRIFT_PLZ != '')$pdf->addText($col42,$row-=12,$fontSize,utf8_decode(POSTANSCHRIFT_PLZ.' '.POSTANSCHRIFT_ORT));
    # Amtsgericht, Grundbuchbezirk
    $pdf->addText($col1,$row-=12,$fontSize,'Grundbuchbezirk');
    $pdf->addText($col27,$row,$fontSize,str_pad($flst->Grundbuchbezirk['schluessel'],11," "));
    $pdf->addText($col42,$row,$fontSize,utf8_decode($flst->Grundbuchbezirk['name']));
    $pdf->addText($col1,$row-=12,$fontSize,'Amtsgericht');
    $pdf->addText($col27,$row,$fontSize,str_pad($flst->Amtsgericht['schluessel'],11," "));
    $pdf->addText($col42,$row,$fontSize,utf8_decode($flst->Amtsgericht['name']));
#   $pdf->addText($col00,$row-=12,$fontSize,str_repeat("-",75));
    $pdf->addText($col0,$row-=12,$fontSize,str_repeat("-",73));

    ################################################################################
    # Bestandsnachweis #
    ####################
    switch ($formnummer) {
      case 25 : {
        # Bestand

        # Abfragen und Ausgeben der Eigent�mer zum Grundbuchblatt
        $Eigentuemerliste=$flst->getEigentuemerliste($buchungen[0]['bezirk'],$buchungen[0]['blatt'],$buchungen[0]['bvnr']);
        $anzEigentuemer=count($Eigentuemerliste);
        for ($i=0;$i<$anzEigentuemer;$i++) {
          if($row<120) {
            # Seitenumbruch
            $seite++;
            # aktuelle Seite abschlie�en
#            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
            $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
            # neue Seite beginnen
            $pageid=$pdf->newPage();
            if ($wasserzeichen) {
              $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
            }
            $row=825; # 812 -> 825 2007-04-02 Schmidt
 #           $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr);
            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
          }
          else {
            $row-=12;
          }
          if ($Eigentuemerliste[$i]->Nr!=0) {
#            $pdf->addText($col0,$row-=12,$fontSize,$Eigentuemerliste[$i]->Nr);
            $pdf->addText($col1,$row-=12,$fontSize,$Eigentuemerliste[$i]->Nr);
#            if($Eigentuemerliste[$i]->Anteil != '')$pdf->addText($col3,$row,$fontSize,'zu '.$Eigentuemerliste[$i]->Anteil);
            if($Eigentuemerliste[$i]->Anteil != '')$pdf->addText($col27,$row,$fontSize,'zu '.$Eigentuemerliste[$i]->Anteil);
          }
          else {
            $row-=12;
          }
          $anzNamenszeilen=count($Eigentuemerliste[$i]->Name);
          for ($k=0;$k<$anzNamenszeilen;$k++) {
#            $pdf->addText($col1,$row-=12,$fontSize,$Eigentuemerliste[$i]->Name[$k]);
            $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($Eigentuemerliste[$i]->Name[$k]));
#28.11.2006 H.Riedel - Einfuegen der Eigentuemerart
            if ($k == 0) {
              $pdf->addText($col62,$row,$fontSize,utf8_decode($Eigentuemerliste[$i]->Art));
            }
          }
        } # ende Schleife Eigent�mer des Grundbuchblattes


        if ($buchungen[0]['zusatz_eigentuemer'] != '') {
                $zusatzeigentuemertext=$buchungen[0]['zusatz_eigentuemer'];
                while(strlen($zusatzeigentuemertext) > 60){
                  $positionkomma=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'),",",'utf8');
                  $positionleerzeichen=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8')," ",'utf8');
                  if($positionkomma>$positionleerzeichen){
                    $positiontrenner=$positionkomma;
                  }
                  else{
                    $positiontrenner=$positionleerzeichen;
                  }
                  if($row<120) {
                    # Seitenumbruch
                    $seite++;
                    # aktuelle Seite abschlie�en
                    $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.$seite);
                    # neue Seite beginnen
                    $pageid=$pdf->newPage();
                    if ($wasserzeichen) {
                      $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
                    }
                    $row=825; # 812 -> 825 2007-04-02 Schmidt;
                    $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Bestand',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
                  }
                  $pdf->addText($col1,$row-=12,$fontSize,utf8_decode(mb_substr($zusatzeigentuemertext,0,$positiontrenner,'utf8')));
                  $zusatzeigentuemertext=mb_substr($zusatzeigentuemertext,$positiontrenner+1, 999, 'utf8');
                }
                $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($zusatzeigentuemertext));
              }

        $gesamtflaeche = 0;
        for ($b=0;$b < count($buchungen);$b++) {
          # Flurst�ck erzeugen
          $flst=new flurstueck($buchungen[$b]['flurstkennz'],$this->database);
          $flst->database=$this->database;
          $ret=$flst->readALB_Data($buchungen[$b]['flurstkennz']);

          # Seitenumbruch wenn erforderlich
          if($row<120) {
            # Seitenumbruch
            $seite++;
            # aktuelle Seite abschlie�en
#            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
            $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
            # neue Seite beginnen
            $pageid=$pdf->newPage();
            if ($wasserzeichen) {
              $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
            }
            $row=825; # 812 -> 825 2007-04-02 Schmidt
#            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr);
            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
          }
# 23.11.2006 H. Riedel, if-Schleife bzgl. Gemarkungsausgabe anpassen
          if ($buchungen[$b-1]['gemkgname'] != $buchungen[$b]['gemkgname']) {
#            $pdf->addText($col1b,$row-=36,$fontSize,'Gemarkung  '.$flst->GemkgName);
            $pdf->addText($col10,$row-=24,$fontSize,'Gemarkung  '.utf8_decode($flst->GemkgName));
#         $pdf->addText($col0,$row-=24,$fontSize,'BVNR Art GMKG   FLR FLURST-NR    P');
      $pdf->addText($col1,$row-=24,$fontSize,'BVNR Art GMKG   FLR FLURST-NR    P');
#         $pdf->addText($col9-10,$row,$fontSize,'Fl�che');
#29.11.2006 H. Riedel, Flurkarte, Riss hinzugef�gt
      $pdf->addText($col44,$row,$fontSize,'Flurkarte Riss');
      $pdf->addText($col64,$row,$fontSize,'Fl�che');
        }
          if ($flst->Nenner!=0) {
            $nennerausgabe="/".$flst->Nenner;
          }
          else{
            $nennerausgabe= '';
          }
         if($buchungen[$b-1]['bvnr'] != $buchungen[$b]['bvnr']){
            $pdf->addText($col1,$row-=12,$fontSize,str_pad(intval($buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT).' ('.$buchungen[$b]['buchungsart'].') ');
            if($buchungen[$b]['anteil'] != ''){
              if($buchungen[$b]['buchungsart'] == 'N' OR $buchungen[$b]['buchungsart'] == 'W'){
                $pdf->addText($col10,$row,$fontSize, $buchungen[$b]['anteil'].' Miteigentumsanteil an');
              }
              elseif($buchungen[$b]['buchungsart'] == 'H'){
                $pdf->addText($col10,$row,$fontSize, $buchungen[$b]['anteil'].' '.$buchungen[$b]['bezeichnung'].' an');
              }
              $row = $row-12;
            }
            elseif($buchungen[$b]['buchungsart'] != 'N'){
              $pdf->addText($col10,$row,$fontSize, $buchungen[$b]['bezeichnung'].' an');
              $row = $row-12;
            }
          }
          else{
            $row = $row-12;
          }
          $pdf->addText($col10,$row,$fontSize,$flst->GemkgSchl." ".str_pad($flst->FlurNr,3," ",STR_PAD_LEFT)." ".str_pad($flst->Zaehler,5," ",STR_PAD_LEFT).$nennerausgabe);
          $pdf->addText($col34,$row,$fontSize,$flst->getPruefKZ());
          $pdf->addText($col59,$row,$fontSize,str_pad(str_space($flst->ALB_Flaeche,3).' m2',14,' ',STR_PAD_LEFT));
          $gesamtflaeche += $flst->ALB_Flaeche;

#         $pdf->addText($col1b,$row-=12,$fontSize,'Lage');
          $pdf->addText($col10,$row-=12,$fontSize,'Lage');
          # Ausgabe der Adressangabe zur Lage
          $anzStrassen=count($flst->Adresse);
          for ($s=0;$s<$anzStrassen;$s++) {
            $Adressbezeichnung.=$flst->Adresse[$s]["strassenname"];
            $Adressbezeichnung.=' '.$flst->Adresse[$s]["hausnr"];
            $ausgabetext=zeilenumbruch($Adressbezeichnung,40);
            $pdf->addText($col18,$row-=$s*12,$fontSize,utf8_decode($ausgabetext[0]));
            for ($j=1;$j<count($ausgabetext);$j++) {
              $pdf->addText($col18+43,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
          }
          if ($anzStrassen == 0) {
          	$Adressbezeichnung=$flst->Lage[0];
            $pdf->addText($col18,$row,$fontSize,utf8_decode($Adressbezeichnung));
          }
          $Adressbezeichnung = '';

#         $pdf->addText($col1b,$row-=12,$fontSize,'Nutzung');
          $pdf->addText($col10,$row-=12,$fontSize,'Nutzung');
          for ($i=0;$i<count($flst->Nutzung);$i++) {
            # Seitenumbruch wenn erforderlich
            if($row<120) {
              # Seitenumbruch
              $seite++;
              # aktuelle Seite abschlie�en
  #            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
              $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
              # neue Seite beginnen
              $pageid=$pdf->newPage();
              if ($wasserzeichen) {
                $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
              }
              $row=825; # 812 -> 825 2007-04-02 Schmidt
  #            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr);
              $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
            }
#30.11.2006 H.Riedel, Zeilenweiterzaehlen wenn mehr als eine Nutzungsart pro Flst.
            if ($i>=1) {
              $row-=12;
            }
            $Nutzunglangtext=$flst->Nutzung[$i]['bezeichnung'];
            if ($flst->Nutzung[$i]['abkuerzung']!='') {
              $Nutzunglangtext.=' ('.$flst->Nutzung[$i]['abkuerzung'].')';
            }
            $ausgabetext=zeilenumbruch($Nutzunglangtext,40);
#           $pdf->addText($col2_3,$row,$fontSize,$ausgabetext[0]);
            $pdf->addText($col18,$row,$fontSize,utf8_decode($ausgabetext[0]));
            for ($j=1;$j<count($ausgabetext);$j++) {
#             $pdf->addText($col2_3,$row-=12,$fontSize,$ausgabetext[$j]);
              $pdf->addText($col18,$row-=12,$fontSize,utf8_decode($ausgabetext[$j]));
            }
#           $pdf->addText($col9-10,$row,$fontSize,str_pad($flst->Nutzung[$i]['flaeche'],6, ' ', STR_PAD_LEFT).' m2');
            $pdf->addText($col59,$row,$fontSize,str_pad(str_space($flst->Nutzung[$i]['flaeche'],3).' m2',14, ' ', STR_PAD_LEFT));
          }
          if($buchungen[$b+1]['bvnr'] != $buchungen[$b]['bvnr']){
            if($buchungen[$b]['sondereigentum'] != ''){
              $sondereigentum = zeilenumbruch('verbunden mit dem Sondereigentum an '.$buchungen[$b]['sondereigentum'],40);
              $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($sondereigentum[0]));
              for ($j=1;$j<count($sondereigentum);$j++) {
                $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($sondereigentum[$j]));
              }
            }
            if($buchungen[$b]['auftplannr'] != ''){
              $pdf->addText($col6,$row-=12,$fontSize,'Aufteilungsplan-Nr. '.$buchungen[$b]['auftplannr']);
            }
            if($buchungen[$b]['erbbaurechtshinw'] == 'E'){
              $pdf->addText($col6,$row-=12,$fontSize, 'belastet mit Erbbaurecht');
            }
            if($buchungen[$b]['erbbaurechtshinw'] == 'G'){
              $pdf->addText($col6,$row-=12,$fontSize, 'belastet mit Nutzungsrecht');
            }
          }
#30.11.2006 H.Riedel, Zeile zur Trennung zw. den Flst.
          $row-=12;
        } # ende Schleife Bestand
#        $pdf->addText($col7+10,$row-=12,$fontSize,str_repeat("-",29));
        $pdf->addText($col44,$row-=12,$fontSize,str_repeat("-",29));
#       $pdf->addText($col7+10,$row-=12,$fontSize,'Bestandsfl�che '.str_pad($gesamtflaeche,11,'*',STR_PAD_LEFT).' m2');
        $pdf->addText($col44,$row-=12,$fontSize,'Bestandsfl�che '.str_pad(str_space($gesamtflaeche,3).' m2',14,'*',STR_PAD_LEFT));
#       $pdf->addText($col7+10,$row-=12,$fontSize,str_repeat("=",29));
        $pdf->addText($col44,$row-=12,$fontSize,str_repeat("=",29));
      } # ende Ausgabe Formular 25
      break;
      case 20 : {
        # Bestand

        # Abfragen und Ausgeben der Eigent�mer zum Grundbuchblatt
        $Eigentuemerliste=$flst->getEigentuemerliste($buchungen[0]['bezirk'],$buchungen[0]['blatt'],$buchungen[0]['bvnr']);
        $anzEigentuemer=count($Eigentuemerliste);
        for ($i=0;$i<$anzEigentuemer;$i++) {
          if($row<120) {
            # Seitenumbruch
            $seite++;
            # aktuelle Seite abschlie�en
#            $pdf->addText($col9_1,$row-=12,$fontSize,'Forts. Seite '.$seite);
            $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
            # neue Seite beginnen
            $pageid=$pdf->newPage();
            if ($wasserzeichen) {
              $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
            }
            $row=825; # 812 -> 825 2007-04-02 Schmidt
#            $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr);
            $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
          }
          else {
            $row-=12;
          }
          if ($Eigentuemerliste[$i]->Nr!=0) {
#            $pdf->addText($col0,$row-=12,$fontSize,$Eigentuemerliste[$i]->Nr);
            $pdf->addText($col1,$row-=12,$fontSize,$Eigentuemerliste[$i]->Nr);
#            if($Eigentuemerliste[$i]->Anteil != '')$pdf->addText($col3,$row,$fontSize,'zu '.$Eigentuemerliste[$i]->Anteil);
            if($Eigentuemerliste[$i]->Anteil != '')$pdf->addText($col27,$row,$fontSize,'zu '.$Eigentuemerliste[$i]->Anteil);
          }
          else {
            $row-=12;
          }
          $anzNamenszeilen=count($Eigentuemerliste[$i]->Name);
          for ($k=0;$k<$anzNamenszeilen;$k++) {
#            $pdf->addText($col1,$row-=12,$fontSize,$Eigentuemerliste[$i]->Name[$k]);
            $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($Eigentuemerliste[$i]->Name[$k]));
#28.11.2006 H.Riedel - Einfuegen der Eigentuemerart
            if ($k == 0) {
              $pdf->addText($col62,$row,$fontSize,utf8_decode($Eigentuemerliste[$i]->Art));
            }
          }
        } # ende Schleife Eigent�mer des Grundbuchblattes

				if ($buchungen[0]['zusatz_eigentuemer'] != '') {
          $zusatzeigentuemertext=$buchungen[0]['zusatz_eigentuemer'];
          while(strlen($zusatzeigentuemertext) > 60){
            $positionkomma=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8'),",",'utf8');
            $positionleerzeichen=mb_strrpos(mb_substr($zusatzeigentuemertext,0,60,'utf8')," ",'utf8');
            if($positionkomma>$positionleerzeichen){
              $positiontrenner=$positionkomma;
            }
            else{
              $positiontrenner=$positionleerzeichen;
            }
            if($row<120) {
              # Seitenumbruch
              $seite++;
              # aktuelle Seite abschlie�en
              $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.$seite);
              # neue Seite beginnen
              $pageid=$pdf->newPage();
              if ($wasserzeichen) {
                $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
              }
              $row=825; # 812 -> 825 2007-04-02 Schmidt;
              $this->ALBAuszug_SeitenKopf($pdf,$flst,$Ueberschrift,'Bestand',$seite,$row,$fontSize,NULL,$AktualitaetsNr);
            }
            $pdf->addText($col1,$row-=12,$fontSize,utf8_decode(mb_substr($zusatzeigentuemertext,0,$positiontrenner,'utf8')));
            $zusatzeigentuemertext=mb_substr($zusatzeigentuemertext,$positiontrenner+1, 999, 'utf8');
          }
          $pdf->addText($col1,$row-=12,$fontSize,utf8_decode($zusatzeigentuemertext));
        }

        $gesamtflaeche = 0;
        for ($b=0;$b < count($buchungen);$b++) {
          # Flurst�ck erzeugen
          $flst=new flurstueck($buchungen[$b]['flurstkennz'],$this->database);
          $flst->database=$this->database;
          $ret=$flst->readALB_Data($buchungen[$b]['flurstkennz']);

          # Seitenumbruch wenn erforderlich
          if($row<120) {
            # Seitenumbruch
            $seite++;
            # aktuelle Seite abschlie�en
            $pdf->addText($col57,$row-=12,$fontSize,'Forts. Seite '.str_pad($seite,3," ",STR_PAD_LEFT));
            # neue Seite beginnen
            $pageid=$pdf->newPage();
            if ($wasserzeichen) {
              $pdf->addJpegFromFile(WWWROOT.APPLVERSION.$wasserzeichen,0,0,600); # 2007-04-02 Schmidt
            }
            $row=825; # 812 -> 825 2007-04-02 Schmidt
            $this->ALBAuszug_SeitenKopf($pdf,NULL,$Ueberschrift,'Bestand',$seite,$row,$fontSize,$BestandStr,$AktualitaetsNr);
          }
          if($buchungen[$b-1]['gemkgname'] != $buchungen[$b]['gemkgname']) {
            $pdf->addText($col10,$row-=36,$fontSize,'Gemarkung  '.utf8_decode($flst->GemkgName));
            $pdf->addText($col1,$row-=24,$fontSize,'BVNR Art GMKG   FLR FLURST-NR    P');
            $pdf->addText($col64,$row,$fontSize,'Fl�che');
          }
          if ($flst->Nenner!=0) {
            $nennerausgabe="/".$flst->Nenner;
          }
          else{
            $nennerausgabe= '';
          }
          if($buchungen[$b-1]['bvnr'] != $buchungen[$b]['bvnr']){
            $pdf->addText($col1,$row-=12,$fontSize,str_pad(intval($buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT).' ('.$buchungen[$b]['buchungsart'].') ');
            if($buchungen[$b]['anteil'] != ''){
              if($buchungen[$b]['buchungsart'] == 'N' OR $buchungen[$b]['buchungsart'] == 'W'){
                $pdf->addText($col10,$row,$fontSize, $buchungen[$b]['anteil'].' Miteigentumsanteil an');
              }
              elseif($buchungen[$b]['buchungsart'] == 'H'){
                $pdf->addText($col10,$row,$fontSize, $buchungen[$b]['anteil'].' '.$buchungen[$b]['bezeichnung']);
              }
              $row = $row-12;
            }
            elseif($buchungen[$b]['buchungsart'] != 'N'){
              $pdf->addText($col10,$row,$fontSize, $buchungen[$b]['bezeichnung'].' an');
              $row = $row-12;
            }
          }
          else{
            $row = $row-12;
          }
          $pdf->addText($col10,$row,$fontSize,$flst->GemkgSchl." ".str_pad($flst->FlurNr,3," ",STR_PAD_LEFT)." ".str_pad($flst->Zaehler,5," ",STR_PAD_LEFT).$nennerausgabe);
          $pdf->addText($col34,$row,$fontSize,$flst->getPruefKZ());
          $pdf->addText($col59,$row,$fontSize,str_pad(str_space($flst->ALB_Flaeche,3).' m2',14,' ',STR_PAD_LEFT));
          $gesamtflaeche += $flst->ALB_Flaeche;
          if($buchungen[$b+1]['bvnr'] != $buchungen[$b]['bvnr']){
            if($buchungen[$b]['sondereigentum'] != ''){
              $sondereigentum = zeilenumbruch('verbunden mit dem Sondereigentum an '.$buchungen[$b]['sondereigentum'],40);
              $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($sondereigentum[0]));
              for ($j=1;$j<count($sondereigentum);$j++) {
                $pdf->addText($col6,$row-=12,$fontSize,utf8_decode($sondereigentum[$j]));
              }
            }
            if($buchungen[$b]['auftplannr'] != ''){
              $pdf->addText($col6,$row-=12,$fontSize,'Aufteilungsplan-Nr. '.$buchungen[$b]['auftplannr']);
            }
            if($buchungen[$b]['erbbaurechtshinw'] == 'E'){
              $pdf->addText($col6,$row-=12,$fontSize, 'belastet mit Erbbaurecht');
            }
            if($buchungen[$b]['erbbaurechtshinw'] == 'G'){
              $pdf->addText($col6,$row-=12,$fontSize, 'belastet mit Nutzungsrecht');
            }
          }
        } # ende Schleife Bestand
#        $pdf->addText($col7+10,$row-=12,$fontSize,str_repeat("-",29));
        $pdf->addText($col44,$row-=12,$fontSize,str_repeat("-",29));
#       $pdf->addText($col7+10,$row-=12,$fontSize,'Bestandsfl�che '.str_pad($gesamtflaeche,11,'*',STR_PAD_LEFT).' m2');
  $pdf->addText($col44,$row-=12,$fontSize,'Bestandsfl�che '.str_pad(str_space($gesamtflaeche,3).' m2',14,'*',STR_PAD_LEFT));
#       $pdf->addText($col7+10,$row-=12,$fontSize,str_repeat("=",29));
  $pdf->addText($col44,$row-=12,$fontSize,str_repeat("=",29));
      } # ende Ausgabe Formular 20
      break;
    } # end of switch for Bestandsnachweis
    $pdf->pagecount[] = $pdf->numPages;
    return $pdf;
  }

  function HausNrTextKorrektur() {
    # Funktion korrigiert den Text zur Angabe von Hausnummern in der Tabelle f_Adressen
    $sql ='SELECT FlurstKennz,Gemeinde,Strasse,HausNr FROM f_Adressen';
    $this->debug->write("<p>alb.php HausNrTextKorrektur() Abfragen der Adressen:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) {
      $this->debug->write("<br>Abbruch in alb.php HausNrTextKorrektur() Zeile: ".__LINE__."<br>sql: ".$sql,4);
      return 0;
    }
    while($rs=mysql_fetch_array($query)) {
      $HausTxt=trim($rs['HausNr']);
      $HausNrTeil=explode(' ',$HausTxt);
      $HausNr=intval($HausNrTeil[0]);
      for ($i=1;$i<count($HausNrTeil);$i++) {
        $HausNr.=' '.$HausNrTeil[$i];
      }
      if ($HausNr==0) {
          $HausNr='';
      }
      if ($HausNr!=$rs['HausNr']) {
        echo "<br>".$rs['HausNr']."->".$HausNr;
      }
      # Eintragen der Hausnr
      $sql ='UPDATE f_Adressen SET HausNr="'.$HausNr.'"';
      $sql.=' WHERE FlurstKennz = "'.$rs['FlurstKennz'].'" AND Gemeinde='.$rs['Gemeinde'].' AND Strasse = "'.$rs['Strasse'].'"';
      $this->debug->write("<p>alb.php HausNrTextKorrektur() Eintragen der neuen Nummer:<br>".$sql,4);
      $query1=mysql_query($sql);
      if ($query1==0) {
        $this->debug->write("<br>Abbruch in alb.php HausNrTextKorrektur() Zeile: ".__LINE__."<br>sql: ".$sql,4);
        return 0;
      }
    }
    return 1;
  }

  function GrundausstattungAnlegen() {
    # 2006-12-12 pk
    if ($this->checkHeader) {
      # Pr�fen ob die WLDGE Datei fehlerfrei ist
      $Fehlermeldung=$this->WLDGE_Datei_Pruefen();
    }
    if ($Fehlermeldung!='') { return $Fehlermeldung; }
    # Datei fehlerfrei
    # Direktes einlesen der WLDGE Datei in die ALB-Tabellen, SQL-Dump in Datei schreiben
    $this->database->logfile->write("SET client_encoding='UTF8';");    
    $this->database->logfile->write($this->database->commentsign.' Einlesen der WLDGE-Datei: '.$this->WLDGE_Datei['tmp_name']);

    # Einlesen der Daten aus der WLDGE-Datei in die alb-Tabellen
    $Fehlermeldung=$this->WLDGE_Datei_einlesen();
    if ($Fehlermeldung!='') {
      return $Fehlermeldung;
    }
    else {
      # Einlesen ist fehlerfrei erfolgt
      # Auff�llen der Zusatztabelle z_Fluren
      $ret=$this->database->updateFluren();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim Einf�gen der Fluren in function GrundausstattungAnlegen in alb.php line: '.__LINE__;
        $errmsg.='<br>'.$ret[1];
        return $errmsg;
      }
      $anzFluren=$this->database->getAnzFluren();
      echo "<br>Fluren nach Fortf�hrung gesamt: ".$anzFluren;
    }
    # reorganisieren des Datenbankspeicherns
    $this->database->vacuum();
 
    return '';
  }

  function WLDGE_Datei_Pruefen() {
    echo '<br>Beginne Pr�fung der WLDGE-Datei:';
    echo '<br>�ffne WLDGE-Datei.';
    # �ffnen der WLDGE_Datei
    $fp=fopen($this->WLDGE_Datei['tmp_name'],'r');
    echo '<br>Einlesen der ersten Zeile.';

    # Einlesen der ersten Zeile
    $line=fgets($fp);
    $Dateikennung=mb_substr($line,0,1,'utf8');
    $Aenderungsart=mb_substr($line,5,1,'utf8');
    if ($Dateikennung!='1') {
      $Fehler='Die Datei beginnt nicht mit der richtigen Dateikennung (1).';
      return $Fehler;
    }
    if ($this->database->ist_Fortfuehrung) {
      # 5. Stelle der Druckauftragsnummer muss ein S sein, das bedeutet die
      # Ver�nderungsdaten sind stichtagsbezogen
      if ($Dateikennung=='1' AND $Aenderungsart=='L') {
        $Fehler='Die Ver�nderungsdaten sind Fortf�hrungsfallbezogen.';
        $Fehler.='<br>Die Datei mu� stichtagsbezogen sein.';
        return $Fehler;
      }
      else {
        if ($Dateikennung=='1' AND $Aenderungsart!='S') {
          $Fehler='Es handelt sich nicht um eine Fortf�hrungsdatei.';
          $Fehler.='<br>Es wird nur die Fortf�hrungsart S (stichtagsbezogen) akzeptiert.';
          return $Fehler;
        }
      }
    }
    echo '<br>Dateikennung ist korrekt.';
    # Pr�fen der Druckauftragsart, sie darf nur 11=Ausgabe mit Entschl�sselung sein
    $Satzart=mb_substr($line,26,1,'utf8');
    $Satzunterart=mb_substr($line,30,2,'utf8');
    $Druckauftragsart=mb_substr($line,49,2,'utf8');
    echo '<br>Pr�fen der Druckauftragsart.';
    if ($Dateikennung=='1' AND $Satzart=='D' AND $Satzunterart=='00' AND $Druckauftragsart==10) {
      return 'Die Druckauftragsart muss 11 (Ausgabe mit Entschl�sselung) sein.';
    }
    else {
      if ($Dateikennung=='1' AND $Satzart=='D' AND $Satzunterart=='00' AND $Druckauftragsart!=11) {
        return 'Die Druckauftragsart "'.$Druckauftragsart.'" ist ung�ltig.';
      }
    }
    # Einlesen der Datens�tze 1.E.20 und 1.E.30
    echo '<br>Einlesen der Datens�tze 1.E.20 und 1.E.30...';
    do {
      $line=fgets($fp);
      $Dateikennung=mb_substr($line,0,1,'utf8');
      $Satzart=mb_substr($line,26,1,'utf8');
      $Satzunterart=mb_substr($line,30,2,'utf8');
      if ($Dateikennung=='1' AND $Satzart=='E' AND $Satzunterart=='20') {
        $line1E20=$line;
      }
      if ($Dateikennung=='1' AND $Satzart=='E' AND $Satzunterart=='30') {
        $line1E30=$line;
      }
    } while (($line1E20=='' OR $line1E30=='') AND $Dateikennung=='1');
    echo 'fertig';
    echo '<br>Einlesen der Datum.';
    # Einlesen des Datums f�r die Grundausstattung und den Fortf�hrungszeitraum
    $GA_Datum=mb_substr($line1E20,33,8,'utf8');
    $GA['Tag']=mb_substr($GA_Datum,6,2,'utf8');
    $GA['Monat']=mb_substr($GA_Datum,4,2,'utf8');
    $GA['Jahr']=mb_substr($GA_Datum,0,4,'utf8');
    $von_Zeitraum=mb_substr($line1E30,63,14,'utf8');
    $von['Tag']=mb_substr($von_Zeitraum,6,2,'utf8');
    $von['Monat']=mb_substr($von_Zeitraum,4,2,'utf8');
    $von['Jahr']=mb_substr($von_Zeitraum,0,4,'utf8');
    $von['Stunde']=mb_substr($von_Zeitraum,8,2,'utf8');
    $von['Minute']=mb_substr($von_Zeitraum,10,2,'utf8');
    $von['Sekunde']=mb_substr($von_Zeitraum,12,2,'utf8');
    $bis_Zeitraum=mb_substr($line1E30,78,14,'utf8');
    $bis['Tag']=mb_substr($bis_Zeitraum,6,2,'utf8');
    $bis['Monat']=mb_substr($bis_Zeitraum,4,2,'utf8');
    $bis['Jahr']=mb_substr($bis_Zeitraum,0,4,'utf8');
    $bis['Stunde']=mb_substr($bis_Zeitraum,8,2,'utf8');
    $bis['Minute']=mb_substr($bis_Zeitraum,10,2,'utf8');
    $bis['Sekunde']=mb_substr($bis_Zeitraum,12,2,'utf8');
    if ($this->database->ist_Fortfuehrung) {
      echo '<br>Pr�fung der Datei als Fortf�hrungsdatei.';
      if ($line1E20=='') {
        return 'Im Auftrag (Dateikennung 1) fehlt der Datensatz 1.E.20';
      }
      if ($line1E30=='') {
        return 'Im Auftrag (Dateikennung 1) fehlt der Datensatz 1.E.30';
      }
      # Pruefen der Kennung Erstabgabe
      $KennErst=mb_substr($line1E20,41,1,'utf8');
      switch ($KennErst) {
        case 'E' : return 'Es handelt sich um eine Erstabgabe, keine Fortf�hrung.';
        case 'W' : return 'Es handelt sich um eine Wiederholung. Diese sind nicht zugelassen.';
        case 'A' : return 'Es handelt sich um ein Altverfahren. Diese sind nicht zugelassen.';
        case 'L' : break; #alles ok
        default : return 'Die Kennung der Erstabgabe ('.$KennErst.') ist nicht g�ltig.';
      }
      # Pr�fen ob das Datum der Grundausstattung g�ltig ist
      if (!checkdate($GA['Monat'],$GA['Tag'],$GA['Jahr'])) {
        return 'Das Datum der Grundausstattung ('.$GA['Tag'].'.'.$GA['Monat'].'.'.$GA['Jahr'].') ist ung�ltig.';
      }
      # lesen des Datums der Grundausstattung und den Endzeitpung der letzten Fortf�hrung aus der Datenbank
      # zum Vergleich mit der Angabe in der WLDGE Datei
      $ret=$this->database->readLastUpdateDate($GA['Jahr'].$GA['Monat'].$GA['Tag']);
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim Lesen der Datumsangaben zur letzten ALB Aktualisierung in Zeile: '.$zeNr.'<br>'.$ze;
        $errmsg.='<br>beim Pr�fen der einzulesenden WLDGE Datei in function readLastUpdateDate alb.php line: '.__LINE__;
        $errmsg.='<br>'.$ret[1];
        echo $errmsg;
        exit;
      }
      else {
        $rs=$ret[1];
      }
      echo ' Datum der Grundausstattung in Datenbank: '.$rs['ga_datum'];
      echo "<br>WLDGE_DATUM_PRUEFUNG=".WLDGE_DATUM_PRUEFUNG;
      echo "<br>Datum der Grundausstattung in Fortf�hrungsdatei: ".$GA_Datum;
      if (WLDGE_DATUM_PRUEFUNG==1) {
        if ($GA_Datum!=$rs['ga_datum']) {
          return 'Das Datum der Grundausstattung in der Datei: '.$GA_Datum.' stimmt mit keinem Grundausstattungsdatum in der Datenbank �berein.';
        }
      }

      # pr�fen ob der Anfangszeitpunkt des Fortf�hrungszeitraumes g�ltig ist
      if (!checkdate($von['Monat'],$von['Tag'],$von['Jahr'])) {
        return 'Das Anfangszeitpunkt der Fortf�hrungszeitraumes ('.$von['Tag'].'.'.$von['Monat'].'.'.$von['Jahr'].' '.$von['Stunde'].':'.$von['Minute'].':'.$von['Sekunde'].') ist ung�ltig.';
      }
      # pr�fen, ob der Anfangszeitpunkt der Fortf�hrung mit dem Endzeitpunkt der letzten Fortf�hrung �bereinstimmt
      if (WLDGE_DATUM_PRUEFUNG==1) {
        echo "<br>Pr�fe ob Anfangszeitraum in Fortf�hrungsdatei mit Enddatum in Datenbank �bereinstimmt.";
        echo "<br>Endzeit in Datenbank :".$rs['bis_letzer_zeitraum']." Anfangszeit in FF-Datei: ".$von_Zeitraum; 
        if ($von_Zeitraum!=$rs['bis_letzer_zeitraum']) {
          return 'Der Anfangszeitpunkt des Fortf�hrungszeitraumes ('.$von_Zeitraum.') stimmt nicht mit dem Endzeitpunkt der letzten Fortf�hrung ('.$rs['bis_letzer_zeitraum'].') �berein.';
        }
      }

      # pr�fen ob der Endzeitpunkt des Fortf�hrungszeitraumes g�ltig ist
      if (!checkdate($bis['Monat'],$bis['Tag'],$bis['Jahr'])) {
        return 'Der Endzeitpunkt des Fortf�hrungszeitraumes ('.$bis['Tag'].'.'.$bis['Monat'].'.'.$bis['Jahr'].') ist ung�ltig.';
      }

    }
    else {
      # Pr�fung der Datei als Grundausstattung
      echo '<br>Pr�fung der Datei als Grundausstattung.';
      if ($line1E20=='') {
        return 'Der Datensatz 1.E.20 darf nicht leer sein.';
      }
      if ($line1E30=='') {
        return 'Der Datensatz 1.E.30 darf nicht leer sein.';
      }
      echo '<br>Pruefen der Kennung Erstabgabe.';
      # Pruefen der Kennung Erstabgabe
      $KennErst=mb_substr($line1E20,41,1,'utf8');
      switch ($KennErst) {
        case 'E' : break; # alles ok
        case 'W' : return 'Es handelt sich um eine Wiederholung, keine Grundausstattung.';
        case 'A' : return 'Es handelt sich um ein Altverfahren, keine Grundausstattung.';
        case 'L' : return 'Es handelt sich um ein laufendes Verfahren, keine Grundausstattung.';
        default : return 'Die Kennung der Erstabgabe ('.$KennErst.') ist nicht g�ltig.';
      }
      echo '<br>Pr�fen ob Anfangs- und Endpunkt des Fortf�hrungszeitpunktes gleich sind.';
      # Pr�fen ob Anfangs- und Endpunkt des Fortf�hrungszeitpunktes gleich sind
      if ($von_Zeitraum!=$bis_Zeitraum) {
        return 'Bei einer Grundausstattung muss die Zeitangabe f�r den Begin und das Ende des Fortf�hrungszeitraumes gleich sein.';
      }
    }
    echo '<br>Pr�fen ob das Datum der Grundausstattung bzw. des Endzeitpunktes des Fortf�hrungszeitraumes nach dem aktuellen Datum liegt.';
    # Pr�fen ob das Datum der Grundausstattung bzw. des Endzeitpunktes des Fortf�hrungszeitraumes nach dem aktuellen Datum liegt.
    if ($bis_Zeitraum>DATE("YmdHis",time())) {
      return 'Das Ende des Zeitraumes ('.$bis['Tag'].'.'.$bis['Monat'].'.'.$bis['Jahr'].' '.$bis['Stunde'].':'.$bis['Minute'].':'.$bis['Sekunde'].') aus dem Datensatz 1.E.30 liegt in der Zukunft.';
    }
    echo '<br>Pr�fen ob der Endzeitpunkt nach dem Anfangszeitpunkt des Fortf�hrungszeitraumes liegt.';
    # pr�fen ob der Endzeitpunkt nach dem Anfangszeitpunkt des Fortf�hrungszeitraumes liegt
    if ($von_Zeitraum>$bis_Zeitraum) {
      return 'Der Endzeitpunkt ('.$bis['Tag'].'.'.$bis['Monat'].'.'.$bis['Jahr'].' '.$bis['Stunde'].':'.$bis['Minute'].':'.$bis['Sekunde'].') des Fortf�hrungszeitraumes liegt vor dem Anfangzeitpunkt ('.$von['Tag'].'.'.$von['Monat'].'.'.$von['Jahr'].').';
    }
    echo '<br> >>>Es sind keine Fehler gefunden worden.<<<';
    # Es sind keine Fehler gefunden worden
    return '';
  } # end of WLDGE_Datei_Pruefen


  #########################################################################################################
  # WLDGE_Datei_einlesen
  # $createDump, die SQL-Statements werden in eine Datei geschrieben
  # $dbConn Datenbank connection ... wird noch nicht genutzt, es wird die aktuell offene genutzt
  function WLDGE_Datei_einlesen() {
    $fp=fopen($this->WLDGE_Datei['tmp_name'],'r');

    echo '<br>Lese Datei: '.$this->WLDGE_Datei['tmp_name'].' in '.$this->database->type.'-Datenbank: '.$this->database->dbName.' ein.';
    echo "<br>Starte die Aktualisierung!";

    # Leeren der vorhandenen Tabellen (bei Fortf�hrung nur die tempor�ren)
    # Wenn es sich um einen Grundbestandhandelt und truncateTables ausgew�hlt wurde,
    # werden die ALB Tabellen gel�scht, sonst nur die tempor�rer.
    if ($this->truncateTables OR $this->database->ist_Fortfuehrung) {
      echo "<br>Leeren der Tabellen f�r die Grunddaten.";
      $this->database->logfile->write($this->database->commentsign." Leeren der Tabellen f�r die Grunddaten.");
      $ret=$this->database->truncateAll();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim Leeren der Tabellen.';
        $errmsg.=' alb.php, truncateAll line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $lfd_Nr_Name=0;
    }
    else {
      # Es handelt sich um einen Grundbestand, der zu einem vorhandenen Bestand
      # hinzugef�gt werden soll. Tabellen nicht vor dem Einlesen leeren.
      # Abfragen der letzen lfd_Nr_Namen aus Datenbank
      $ret=$this->database->getLastLfdNrName();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim Abfragen der lfd Nr Namen.';
        $errmsg.=' alb.php, getLastLfdNrName line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      else {
        $lfd_Nr_Name=intval($ret[1]);
        $lfd_Nr_Name++;
      }
    }

    # 2006-12-12 pk
    # Wird nicht ausgef�hrt, wenn Ausf�hrung in Transaktion im Formular unterdr�ckt wurde
    $ret=$this->database->begintransaction();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Starten der Transaktion nach dem leeren der Tabellen.';
      $errmsg.=' alb.php, begintransaction line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }

    # Begin mit dem Einlesen der WLDGE Datei
    $zeNr=0;
    $BestandsKennz=0;
    $BuchungsKennz=0;
    $AusfStelle=0;
    $VerfBemerkung=0;
    $this->debug->write("<p>alb.php <b>WLDGE_Datei_einlesen:</b> Start Einlesen der WLDGE Datei in die Tabellen",4);
    echo "<br>Beginne mit dem Einlesen der WLDGE-Datei...";
    $this->database->logfile->write($this->database->commentsign." Beginne mit dem Einlesen der WLDGE-Datei");
    $zeNr1000=1000;
    $zeNr100000=100000;
    $mehrzeilig=0;
    $starttime=time();
    while (!feof($fp)) {
		  $ze = trim(unix2dos(utf8_encode(fgets($fp))));
      if ($ze!='') {
        $zeNr++;
        if ($zeNr==$zeNr1000) {
          echo "<br>".$zeNr." Zeilen eingelesen. ".time();
          $zeNr1000+=1000;
          if ($zeNr==$zeNr100000) {
            $ret=$this->database->committransaction();
            echo "<br><br>Transaktion bis Zeile ".$zeNr." abgeschlossen. Fahre mit neuer fort.<br>";
            $zeNr100000+=100000;
            $ret=$this->database->begintransaction();
          }
        }
        $Dateikennung=mb_substr($ze,0,1,'utf8');
				if($Dateikennung == '2' OR $Dateikennung == '4'){
					$this->Bestandsdateikennung = $Dateikennung;
				}
        $Satzart=mb_substr($ze,26,1,'utf8');
        $Satzunterart='';
        $ret[0]=1; $ret[1]='';
        $satzignore=0;
        switch ($Dateikennung) {
          ############## Dateikennung 1: Anforderungsdaten
          case "1" : {
            switch ($Satzart) {
              case "E" : {
                $Satzunterart=mb_substr($ze,30,2,'utf8');
                switch ($Satzunterart) {
                  case "20" : {
                    $GA_Datum=mb_substr($ze,33,8,'utf8');
                    $GA['Tag']=mb_substr($GA_Datum,6,2,'utf8');
                    $GA['Monat']=mb_substr($GA_Datum,4,2,'utf8');
                    $GA['Jahr']=mb_substr($GA_Datum,0,4,'utf8');
                  } break;
                  case "30" ; {
                    $von_Zeitraum=mb_substr($ze,63,14,'utf8');
                    $von['Tag']=mb_substr($von_Zeitraum,6,2,'utf8');
                    $von['Monat']=mb_substr($von_Zeitraum,4,2,'utf8');
                    $von['Jahr']=mb_substr($von_Zeitraum,0,4,'utf8');
                    $von['Stunde']=mb_substr($von_Zeitraum,8,2,'utf8');
                    $von['Minute']=mb_substr($von_Zeitraum,10,2,'utf8');
                    $von['Sekunde']=mb_substr($von_Zeitraum,12,2,'utf8');
                    $bis_Zeitraum=mb_substr($ze,78,14,'utf8');
                    $bis['Tag']=mb_substr($bis_Zeitraum,6,2,'utf8');
                    $bis['Monat']=mb_substr($bis_Zeitraum,4,2,'utf8');
                    $bis['Jahr']=mb_substr($bis_Zeitraum,0,4,'utf8');
                    $bis['Stunde']=mb_substr($bis_Zeitraum,8,2,'utf8');
                    $bis['Minute']=mb_substr($bis_Zeitraum,10,2,'utf8');
                    $bis['Sekunde']=mb_substr($bis_Zeitraum,12,2,'utf8');
                  } break;
                } # end of switch Satzunterart
              } break;
            } # end of switch Satzart
            # Die Datenzeilen der Satzart 1 Anforderungsdaten werden erst am Schlu� verwendet
            # zum Eintragen des Aktualisierungsvorgangs
            $satzignore=1;
          } break;
          ############## Dateikennung 2: Bestand
          case "2" : {
            if ($Satzart==1 OR $Satzart==2 OR $Satzart==4) {
              # Wenn neues Bestandskennzeichen, neues Grundbuchblatt eintragen
              $BestandsKennz_alt=$BestandsKennz;
              $BestandsKennz=mb_substr($ze,1,13,'utf8');
              if ($BestandsKennz!=$BestandsKennz_alt) {
                # Anlegenen eines neuen Grundbuchblattes
                $Bezirk=trim(mb_substr($ze,1,6,'utf8'));
                $Blatt=trim(mb_substr($ze,8,6,'utf8'));
                $Pruefzeichen=mb_substr($ze,24,1,'utf8');
                $AktualitaetsNr=trim(mb_substr($ze,94,4,'utf8'));
                $ret=$this->database->insertGrundbuch($Bezirk,$Blatt,$AktualitaetsNr,$Pruefzeichen);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einlesen eines Grundbuchblattes in function insertGrundbuch alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $Grundbuecher++;
                }
              }
            }
            if ($Satzart==5 OR $Satzart==6 OR $Satzart==8) {
              # Wenn neues Buchungskennzeichen, neues Grundst�ck eintragen
              $BuchungsKennz_alt=$BuchungsKennz;
              $BuchungsKennz=str_replace('�','>',mb_substr($ze,1,18,'utf8'));
              if ($BuchungsKennz!=$BuchungsKennz_alt) {
                $Bezirk=mb_substr($BuchungsKennz,0,6,'utf8');
                $Blatt=trim(mb_substr($BuchungsKennz,7,6,'utf8'));
                $BVNR=trim(mb_substr($BuchungsKennz,14,4,'utf8'));
                $Buchungsart=mb_substr($ze,25,1,'utf8');
                $ret=$this->database->insertGrundstueck($Bezirk,$Blatt,$BVNR,$Buchungsart);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einlesen eines Grundst�ckes in function insertGrundstueck alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $g_Grundstuecke++;
                }
              }
            }
            switch ($Satzart) {
              case "0" : {
                # Diese Satzart kommt nur bei Fortf�hrungen vor
                # historische Grundbuchbl�tter werden mit der Konstante 'hist' in die Spalte AktualitaetsNr eingetragen
                # damit k�nnen die historischen Daten aus dem Grunddatenbestand gel�scht werden
                $Bezirk=mb_substr($ze,1,6,'utf8');
                $Blatt=mb_substr($ze,8,6,'utf8');
                $Pruefzeichen=mb_substr($ze,24,1,'utf8');
                $ret=$this->database->insertGrundbuch($Bezirk,$Blatt,'hist',$Pruefzeichen);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einlesen eines Grundbuchblattes in function insertGrundbuch alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $Grundbuecher++;
                }
              } break;
              case "1" : { # 2.1.0 Bestand/Eigent�mer
                $Satzunterart=mb_substr($ze,47,1,'utf8');
                if ($Satzunterart=="0") { # Namensnummer, Eigentumart und Anteil
                  # erster Teil der Namensnummern
                  $NamensNr=intval(mb_substr($ze,27,4,'utf8'));
                  # weitere Unternummern abfragen und wenn vorhanden an die Nummer anh�ngen mit . getrennt
                  for ($i=0;$i<4;$i++) {
                    $NrTeil=mb_substr($ze,31+$i*3,3,'utf8');
                    if ($NrTeil!='.00') { $NamensNr.=$NrTeil; }
                  }
                  $Eigentuemerart=trim(mb_substr($ze,49,2,'utf8'));
                  $Anteilsverhaeltnis=trim(mb_substr($ze,52,16,'utf8'));
                  $lfd_Nr_Name++;
                  # Eintragen eines neuen Eigent�mers
                  $ret=$this->database->insertEigentuemer($Bezirk,$Blatt,$NamensNr,$Eigentuemerart,$Anteilsverhaeltnis,$lfd_Nr_Name);
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Einlesen eines Eigent�mers in function insertEigentuemer alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $g_Eigentuemer++;
                  }
                }
                else { # 2.1.1-4
                  # 28.11.2006 H.Riedel, nur Leerzeichen am Ende des Namenstrings loeschen --> Darstellung Geburtsdatum
                  # $Namen=trim(mb_substr($ze,48,52));
                  $Namen=rtrim(mb_substr($ze,48,52,'utf8'));
                  if ($Satzunterart==1) { # Neuer Name, erster Teil
                    $ret=$this->database->insertName($lfd_Nr_Name,$Satzunterart,$Namen);
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Einlesen eines Namen in function insertName alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $g_Namen++;
                    }
                  }
                  else { # Schon eingetragener Name, zus�tzliche Namensteile
                    $ret=$this->database->updateName($lfd_Nr_Name,$Satzunterart,$Namen);
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Aktualisieren von Namenseintr�gen in function updateName alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                  }
                }
              } break;
              case "2" : { # 2.2 Eintragen von Zus�tzen zum Eigent�mer im Grundbuchblatt
                $TextZeile=mb_substr($ze,30,2,'utf8');
                if ($TextZeile=="01") {
                  $Zusatz_Eigentuemer=trim(mb_substr($ze,33,52,'utf8'));
                }
                else {
                  $Zusatz_Eigentuemer.=' '.trim(mb_substr($ze,33,52,'utf8'));
                }
                $ret=$this->database->updateGrundbuch($Bezirk,$Blatt,$Zusatz_Eigentuemer,'');
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Aktualisieren der Eigent�merdaten eines Grundbuches in function updateGrundbuch alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
              } break;
              case "4" : { # 2.4 Eintragen der Bestandsfl�che im Grundbuchblatt
                $Bestandsflaeche=intval(mb_substr($ze,30,9,'utf8'));
                $ret=$this->database->updateGrundbuch($Bezirk,$Blatt,'',$Bestandsflaeche);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Aktualisieren der Bestandsfl�che des Grundbuches in function updateGrundbuch alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
              } break;
              case "5" : { # 2.5 Bestand/Anteil,Aufteilungsplannummer
                $Anteil=trim(mb_substr($ze,30,24,'utf8'));
                $AuftPlanNr=trim(mb_substr($ze,55,12,'utf8'));
                $ret=$this->database->updateGrundstueck($Bezirk,$Blatt,$BVNR,$Anteil,$AuftPlanNr,'');
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Aktualisieren der Grundstuecksdaten in function updateGrundstueck alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
              } break;
              case "6" : { # 2.6 Bestand/Buchung
                $FlurstKennz=mb_substr($ze,30,23,'utf8');
                $ErbbaurechtsHinw=mb_substr($ze,56,1,'utf8');
                $ret=$this->database->insertBuchung($FlurstKennz,$Bezirk,$Blatt,$BVNR,$ErbbaurechtsHinw);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Buchung in function insertBuchungen alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $g_Buchungen++;
                }
              } break;
              case "8" : { # 2.8 Bestand/Sondereigentum
                $TextZeile=mb_substr($ze,30,2,'utf8');
                if ($TextZeile=="01") {
                  $Sondereigentum=trim(mb_substr($ze,33,52,'utf8'));
                }
                else {
                  $Sondereigentum.=' '.trim(mb_substr($ze,33,52,'utf8'));
                }
                $ret=$this->database->updateGrundstueck($Bezirk,$Blatt,$BVNR,'','',$Sondereigentum);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Aktualisieren der Grundstuecksdaten mit Sondereigentum in function updateGrundstueck alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
              } break;
            }
          } break; # end Dateikennung 2 Bestand

          ############## Dateikennung Flurst�ck
          case "3" : {
            $FlurstKennz_alt=$FlurstKennz;
            $FlurstKennz=mb_substr($ze,1,23,'utf8');
            $GemkgSchl=mb_substr($ze,1,6,'utf8');
            $FlurNr=mb_substr($ze,8,3,'utf8');
            $Pruefzeichen=mb_substr($ze,24,1,'utf8');
            if ($FlurstKennz!=$FlurstKennz_alt) {
              # Anlegenen eines neuen Flurst�cks
              $ret=$this->database->insertFlurstueck($FlurstKennz,$GemkgSchl,$FlurNr,$Pruefzeichen);
              if ($ret[0] AND DBWRITE) {
                $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                $errmsg.='<br>beim Einf�gen eines Flurst�cks in function insertFlurstueck alb.php line: '.__LINE__;
                $errmsg.='<br>'.$ret[1];
                echo $errmsg;
              }
              else {
                $Flurstuecke++;
              }
            }
            switch ($Satzart) {
              case "B" : { # 3.B Status, Entstehung, Letzte Fortf�hrung, Fl�che, Aktual. Nummer
                $Status=mb_substr($ze,31,1,'utf8');
                $Entsteh=trim(mb_substr($ze,33,13,'utf8'));
                $LetzFF=trim(mb_substr($ze,47,13,'utf8'));
                $Flaeche=intval(mb_substr($ze,83,19,'utf8'));
                $AktuNr=trim(mb_substr($ze,94,2,'utf8'));
                $ret=$this->database->updateFlurstueck($FlurstKennz,$Status,$Entsteh,$LetzFF,$Flaeche,$AktuNr,'','','','','','');
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Aktualisieren eines Flurst�cks in function updateFlurstueck alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
              } break;
              case "D" : { # 3.D Karte, BauBlock, Koordinaten, Forst-, Finanzamt
                # 29.11.2006 H.Riedel, rtrim eingefuehrt, damit in Flurkartenausgabe Riss an richtiger Stelle
                # $Karte=trim(mb_substr($ze,30,14));
                $Karte=rtrim(mb_substr($ze,30,14,'utf8'));
                $BauBlock=trim(mb_substr($ze,58,12,'utf8'));
                $KoorRW=doubleval(trim(mb_substr($ze,71,8,'utf8')))/10;
                $KoorHW=doubleval(trim(mb_substr($ze,80,8,'utf8')))/10;
                $Forstamt=trim(mb_substr($ze,89,4,'utf8'));
                $Finanzamt=trim(mb_substr($ze,94,4,'utf8'));
                $ret=$this->database->updateFlurstueck($FlurstKennz,'','','','','',$Karte,$BauBlock,$KoorRW,$KoorHW,$Forstamt,$Finanzamt);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Aktualisieren eines Flurst�cks in function updateFlurstueck alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
              } break;
              case "F" : { # 3.F Hinweise zum Flurst�ck
                $Hinweise=explode(",",mb_substr($ze,30,59,'utf8'));
                for ($i=0;$i<count($Hinweise);$i++) {
                  $ret=$this->database->insertHinweis($FlurstKennz,trim($Hinweise[$i]));
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Einf�gen eines Hinweises f�r ein Flurst�ck in function insertHinweis alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $f_Hinweise++;
                  }
                }
              } break;
              case "G" : { # 3.G Adressen
                $Gemeinde=intval(mb_substr($ze,30,12,'utf8'));
                $Strasse=mb_substr($ze,42,5,'utf8');
                $HausNr=trim(preg_replace('(�+)',' ',preg_replace('( |-|/|\.)','�',rtrim(ltrim(mb_substr($ze,48,8,'utf8'),'0')))));
                $ret=$this->database->insertAdresse($FlurstKennz,$Gemeinde,$Strasse,$HausNr);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Adresse zum Flurst�ck in function insertAdresse alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Adressen++;
                }
              } break;
              case "H" : { # 3.H unverschl�sselte Lagebezeichnungen
                $lfdNr=mb_substr($ze,30,2,'utf8');
                $Lage=trim(mb_substr($ze,33,32,'utf8'));
                $ret=$this->database->insertLage($FlurstKennz,$lfdNr,$Lage);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Lagebezeichnung zum Flurst�ck in function insertLage alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Lage++;
                }
              } break;
              case "K" : { # 3.K Nutzungsarten
                # mit 21- prefix
                # $Nutzungsart=mb_substr($ze,33,6);
                # ohne 21- prefix
                $Nutzungsart=mb_substr($ze,36,3,'utf8');
                $NutzungFlaeche=intval(mb_substr($ze,40,7,'utf8'));
                $ret=$this->database->insertNutzung($FlurstKennz,$Nutzungsart,$NutzungFlaeche);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Nutzung zum Flurst�ck in function insertNutzung alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Nutzungen++;
                }
              } break;
              case "M" : { # 3.M Klassifizierungen
                $TabKenn=mb_substr($ze,33,2,'utf8');
                $Klass=mb_substr($ze,36,3,'utf8');
                $KlassFlaeche=intval(mb_substr($ze,40,7,'utf8'));
                $KlassAngabe=trim(mb_substr($ze,48,23,'utf8'));
                $ret=$this->database->insertKlassifizierung($FlurstKennz,$TabKenn,$Klass,$KlassFlaeche,$KlassAngabe);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Klassifizierung zum Flurst�ck in function insertKlassifizierung alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Klassifizierungen++;
                }
              } break;
              case "N" : { # 3.N freier Text
                $lfdNr=mb_substr($ze,30,2,'utf8');
                $freierText=trim(mb_substr($ze,33,52,'utf8'));
                $ret=$this->database->insertText($FlurstKennz,$lfdNr,$freierText);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen eines Textes zum Flurst�ck in function insertText alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Texte++;
                }
              } break;
              case "P" : { # 3.P Anliegervermerk
                $Kennung=mb_substr($ze,30,1,'utf8');
                $AnlFlstKennz=trim(mb_substr($ze,32,23,'utf8'));
                $AnlFlstPruefz=trim(mb_substr($ze,56,1,'utf8'));
                $ret=$this->database->insertAnlieger($FlurstKennz,$Kennung,$AnlFlstKennz,$AnlFlstPruefz);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen eines Anliegers zum Flurst�ck in function insertAnlieger alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Anlieger++;
                }
              } break;
              case "R" : { # 3.R Baulastenblattnummern
                $BlattNr=explode(",",mb_substr($ze,30,33,'utf8'));
                for ($i=0;$i<count($BlattNr);$i++) {
                  $ret=$this->database->insertBaulast($FlurstKennz,trim($BlattNr[$i]));
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Eintragen einer Baulast zum Flurst�ck in function insertBaulast alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $f_Baulasten++;
                  }
                }
              } break;
              case "U" : { # 3.U Verfahren
                $AusfStelle=trim(mb_substr($ze,30,5,'utf8'));
                $VerfNr=trim(mb_substr($ze,36,6,'utf8'));
                $VerfBem=trim(mb_substr($ze,43,2,'utf8'));
                $ret=$this->database->insertVerfahren($FlurstKennz,$AusfStelle,$VerfNr,$VerfBem);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen einer Baulast zum Flurst�ck in function insertVerfahren alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Verfahren++;
                }
              } break;
              case "V" : { # 3.V Historie, Vorg�nger
                $Vorgaenger=mb_substr($ze,30,23,'utf8');
                $ret=$this->database->insertHistorie($Vorgaenger,$FlurstKennz);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen eines Vorg�ngers zum Flurst�ck in function insertHistorie alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                /* 2005-12-27 pk
                else {
                  if ($this->database->getAffectedRows($ret[1])) {
                    $f_Historie++;
                  }
                }
                */
              } break;
              case "W" : { # 3.W Historie, Nachfolger
                $Nachfolger=mb_substr($ze,30,23,'utf8');
                $ret=$this->database->insertHistorie($FlurstKennz,$Nachfolger);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen eines Nachfolgers zum Flurst�ck in function insertHistorie alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                /* 2005-12-27 pk
                else {
                  if ($this->database->getAffectedRows($ret[1])) {
                    $f_Historie++;
                  }
                }
                */
              } break;
              case "X" : { # 3.X Buchungskennzeichen
                $satzignore=1;
                # Hier sind alle Buchungen angegeben, auch die von historischen Flurst�cken
                # Die Buchungen der nicht historischen Flurst�cke wurde schon �ber die Zeilen 2.6 vorgenommen
                # wird nur gebraucht, wenn historische Flurst�cke gehalten werden sollen oder zum Pr�fen
              } break;
            } # ende of switch Satzart
          } break; # ende of Dateikennung 3 Flurst�ck

          # 2006-07-04 pk
          ############## Dateikennung: Flurst�cke mit Eigent�mer-/Erbbauberechtigtenangaben
          case "4" : {
            $FlurstKennz_alt=$FlurstKennz;
            $FlurstKennz=mb_substr($ze,1,23,'utf8');
            $GemkgSchl=mb_substr($ze,1,6,'utf8');
            $FlurNr=mb_substr($ze,8,3,'utf8');
            $Pruefzeichen=mb_substr($ze,24,1,'utf8');
            if ($FlurstKennz!=$FlurstKennz_alt) {
              # Anlegenen eines neuen Flurst�cks
              $ret=$this->database->insertFlurstueck($FlurstKennz,$GemkgSchl,$FlurNr,$Pruefzeichen);
              if ($ret[0] AND DBWRITE) {
                $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                $errmsg.='<br>beim Einf�gen eines Flurst�cks in function insertFlurstueck alb.php line: '.__LINE__;
                $errmsg.='<br>'.$ret[1];
                echo $errmsg;
              }
              else {
                $Flurstuecke++;
              }
            }
            switch ($Satzart) {
              case "B" : { # 4.B Status, Entstehung, Letzte Fortf�hrung, Fl�che, Aktual. Nummer
                $Status=mb_substr($ze,31,1,'utf8');
                $Entsteh=trim(mb_substr($ze,33,13,'utf8'));
                $LetzFF=trim(mb_substr($ze,47,13,'utf8'));
                $Flaeche=intval(mb_substr($ze,83,19,'utf8'));
                $AktuNr=trim(mb_substr($ze,94,2,'utf8'));
                $ret=$this->database->updateFlurstueck($FlurstKennz,$Status,$Entsteh,$LetzFF,$Flaeche,$AktuNr,'','','','','','');
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Aktualisieren eines Flurst�cks in function updateFlurstueck alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
              } break;
              case "D" : { # 4.D Karte, BauBlock, Koordinaten, Forst-, Finanzamt
                $Karte=trim(mb_substr($ze,30,14,'utf8'));
                $BauBlock=trim(mb_substr($ze,58,12,'utf8'));
                $KoorRW=doubleval(trim(mb_substr($ze,71,8,'utf8')))/10;
                $KoorHW=doubleval(trim(mb_substr($ze,80,8,'utf8')))/10;
                $Forstamt=trim(mb_substr($ze,89,4,'utf8'));
                $Finanzamt=trim(mb_substr($ze,94,4,'utf8'));
                $ret=$this->database->updateFlurstueck($FlurstKennz,'','','','','',$Karte,$BauBlock,$KoorRW,$KoorHW,$Forstamt,$Finanzamt);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Aktualisieren eines Flurst�cks in function updateFlurstueck alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
              } break;
              case "F" : { # 4.F Hinweise zum Flurst�ck
                $Hinweise=explode(",",mb_substr($ze,30,59,'utf8'));
                for ($i=0;$i<count($Hinweise);$i++) {
                  $ret=$this->database->insertHinweis($FlurstKennz,trim($Hinweise[$i]));
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Einf�gen eines Hinweises f�r ein Flurst�ck in function insertHinweis alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $f_Hinweise++;
                  }
                }
              } break;
              case "G" : { # 4.G Adressen
                $Gemeinde=intval(mb_substr($ze,30,12,'utf8'));
                $Strasse=mb_substr($ze,42,5,'utf8');
                $HausTxt=trim(mb_substr($ze,48,8,'utf8'));
                $HausNrTeil=explode(' ',$HausTxt);
                $HausNr=intval($HausNrTeil[0]);
                for ($i=1;$i<count($HausNrTeil);$i++) {
                  $HausNr.=' '.$HausNrTeil[$i];
                }
                if ($HausNr==0) {
                  $HausNr='';
                }
                $ret=$this->database->insertAdresse($FlurstKennz,$Gemeinde,$Strasse,$HausNr);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Adresse zum Flurst�ck in function insertAdresse alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Adressen++;
                }
              } break;
              case "H" : { # 4.H unverschl�sselte Lagebezeichnungen
                $lfdNr=mb_substr($ze,30,2,'utf8');
                $Lage=trim(mb_substr($ze,33,30,'utf8'));
                $ret=$this->database->insertLage($FlurstKennz,$lfdNr,$Lage);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Lagebezeichnung zum Flurst�ck in function insertLage alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Lage++;
                }
              } break;
              case "K" : { # 4.K Nutzungsarten
                # mit 21- prefix
                # $Nutzungsart=mb_substr($ze,33,6);
                # ohne 21- prefix
                $Nutzungsart=mb_substr($ze,36,3,'utf8');
                $NutzungFlaeche=intval(mb_substr($ze,40,7,'utf8'));
                $ret=$this->database->insertNutzung($FlurstKennz,$Nutzungsart,$NutzungFlaeche);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Nutzung zum Flurst�ck in function insertNutzung alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Nutzungen++;
                }
              } break;
              case "M" : { # 4.M Klassifizierungen
                $TabKenn=mb_substr($ze,33,2,'utf8');
                $Klass=mb_substr($ze,36,3,'utf8');
                $KlassFlaeche=intval(mb_substr($ze,40,7,'utf8'));
                $KlassAngabe=trim(mb_substr($ze,48,23,'utf8'));
                $ret=$this->database->insertKlassifizierung($FlurstKennz,$TabKenn,$Klass,$KlassFlaeche,$KlassAngabe);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen einer Klassifizierung zum Flurst�ck in function insertKlassifizierung alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Klassifizierungen++;
                }
              } break;
              case "N" : { # 4.N freier Text
                $lfdNr=mb_substr($ze,30,2,'utf8');
                $freierText=trim(mb_substr($ze,33,52,'utf8'));
                $ret=$this->database->insertText($FlurstKennz,$lfdNr,$freierText);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Einf�gen eines Textes zum Flurst�ck in function insertText alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Texte++;
                }
              } break;
              case "P" : { # 4.P Anliegervermerk
                $Kennung=mb_substr($ze,30,1,'utf8');
                $AnlFlstKennz=trim(mb_substr($ze,32,23,'utf8'));
                $AnlFlstPruefz=trim(mb_substr($ze,56,1,'utf8'));
                $ret=$this->database->insertAnlieger($FlurstKennz,$Kennung,$AnlFlstKennz,$AnlFlstPruefz);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen eines Anliegers zum Flurst�ck in function insertAnlieger alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Anlieger++;
                }
              } break;
              case "R" : { # 4.R Baulastenblattnummern
                $BlattNr=explode(",",mb_substr($ze,30,33,'utf8'));
                for ($i=0;$i<count($BlattNr);$i++) {
                  $ret=$this->database->insertBaulast($FlurstKennz,trim($BlattNr[$i]));
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Eintragen einer Baulast zum Flurst�ck in function insertBaulast alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $f_Baulasten++;
                  }
                }
              } break;
              case "U" : { # 4.U Verfahren
                $AusfStelle=trim(mb_substr($ze,30,5,'utf8'));
                $VerfNr=trim(mb_substr($ze,36,6,'utf8'));
                $VerfBem=trim(mb_substr($ze,43,2,'utf8'));
                $ret=$this->database->insertVerfahren($FlurstKennz,$AusfStelle,$VerfNr,$VerfBem);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen einer Baulast zum Flurst�ck in function insertVerfahren alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $f_Verfahren++;
                }
              } break;
              case "V" : { # 4.V Historie, Vorg�nger
                $Vorgaenger=mb_substr($ze,30,23,'utf8');
                $ret=$this->database->insertHistorie($Vorgaenger,$FlurstKennz);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen eines Vorg�ngers zum Flurst�ck in function insertHistorie alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                /* 2005-12-27 pk
                else {
                  if ($this->database->getAffectedRows($ret[1])) {
                    $f_Historie++;
                  }
                }
                */
              } break;
              case "W" : { # 4.W Historie, Nachfolger
                $Nachfolger=mb_substr($ze,30,23,'utf8');
                $ret=$this->database->insertHistorie($FlurstKennz,$Nachfolger);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen eines Nachfolgers zum Flurst�ck in function insertHistorie alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                /* 2005-12-27 pk
                else {
                  if ($this->database->getAffectedRows($ret[1])) {
                    $f_Historie++;
                  }
                }
                */
              } break;
              case "Z" : { # 4.Z Bestandskennzeichen und Buchungskennzeichen
                # Wenn neues Bestandskennzeichen, neues Grundbuchblatt eintragen
                $BestandsKennz_alt=$BestandsKennz;
                $BestandsKennz=mb_substr($ze,32,13,'utf8');
                $Bezirk=trim(mb_substr($BestandsKennz,0,6,'utf8'));
                $Blatt=trim(mb_substr($BestandsKennz,7,6,'utf8'));
                if ($BestandsKennz!=$BestandsKennz_alt) {
                  # Anlegenen eines neuen Grundbuchblattes
                  $Pruefzeichen=mb_substr($ze,54,1,'utf8');
                  $AktualitaetsNr=trim(mb_substr($ze,98,4,'utf8'));
                  $ret=$this->database->insertGrundbuch($Bezirk,$Blatt,$AktualitaetsNr,$Pruefzeichen);
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Einlesen eines Grundbuchblattes in function insertGrundbuch alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $Grundbuecher++;
                  }
                }
                # Wenn neues Buchungskennzeichen, neues Grundst�ck eintragen und neue Buchung f�r Flurst�ck
                $BuchungsKennz_alt=$BuchungsKennz;
                $BuchungsKennz=str_replace('�','>',mb_substr($ze,32,18,'utf8'));
                $BVNR=trim(mb_substr($BuchungsKennz,14,4,'utf8'));
                if ($BuchungsKennz!=$BuchungsKennz_alt) {
                  $Buchungsart=mb_substr($ze,55,1,'utf8');
                  $ret=$this->database->insertGrundstueck($Bezirk,$Blatt,$BVNR,$Buchungsart);
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Einlesen eines Grundst�ckes in function insertGrundstueck alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $g_Grundstuecke++;
                  }
								 }																																													# angepasst wegen WLDGE-Datei der WVG
								 $FlurstKennz_altZ=$FlurstKennzZ;																														# angepasst wegen WLDGE-Datei der WVG
								 $FlurstKennzZ=mb_substr($ze,1,23,'utf8');																									# angepasst wegen WLDGE-Datei der WVG
								 if ($BuchungsKennz!=$BuchungsKennz_alt or $FlurstKennzZ!=$FlurstKennz_altZ) {							# angepasst wegen WLDGE-Datei der WVG
                  # Buchung des Flurst�cks auf dem Grundst�ck
                  $ErbbaurechtsHinw='';
                  $ret=$this->database->insertBuchung($FlurstKennz,$Bezirk,$Blatt,$BVNR,$ErbbaurechtsHinw);
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Einf�gen einer Buchung in function insertBuchungen alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $g_Buchungen++;
                  }

                }

                $Satzart2=mb_substr($ze,56,1,'utf8');
                switch ($Satzart2) {
                  case "1" : { # 4.Z.1 Buchungskennzeichen, Namensnummer, Eigentumerart, Anteil, Namen
                    $Satzunterart=mb_substr($ze,76,1,'utf8');
                    if ($Satzunterart=="0") { # 4.Z.1.0 Namensnummer, Eigentumart und Anteil
                      # erster Teil der Namensnummern
                      $NamensNr=intval(mb_substr($ze,57,4,'utf8'));
                      # weitere Unternummern abfragen und wenn vorhanden an die Nummer anh�ngen mit . getrennt
                      for ($i=0;$i<4;$i++) {
                        $NrTeil=mb_substr($ze,61+$i*3,3,'utf8');
                        if ($NrTeil!='.00') { $NamensNr.=$NrTeil; }
                      }
                      $Eigentuemerart=trim(mb_substr($ze,78,2,'utf8'));
                      $Anteilsverhaeltnis=trim(mb_substr($ze,81,16,'utf8'));
                      $lfd_Nr_Name++;
                      # Eintragen eines neuen Eigent�mers
                      $ret=$this->database->insertEigentuemer($Bezirk,$Blatt,$NamensNr,$Eigentuemerart,$Anteilsverhaeltnis,$lfd_Nr_Name);
                      if ($ret[0] AND DBWRITE) {
                        $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                        $errmsg.='<br>beim Einlesen eines Eigent�mers in function insertEigentuemer alb.php line: '.__LINE__;
                        $errmsg.='<br>'.$ret[1];
                        echo $errmsg;
                      }
                      else {
                        $g_Eigentuemer++;
                      }
                    }
                    else { # 4.Z.1.1-4
                      $Namen=trim(mb_substr($ze,77,52,'utf8'));
                      if ($Satzunterart==1) { # Neuer Name, erster Teil
                        $ret=$this->database->insertName($lfd_Nr_Name,$Satzunterart,$Namen);
                        if ($ret[0] AND DBWRITE) {
                          $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                          $errmsg.='<br>beim Einlesen eines Namen in function insertName alb.php line: '.__LINE__;
                          $errmsg.='<br>'.$ret[1];
                          echo $errmsg;
                        }
                        else {
                          $g_Namen++;
                        }
                      }
                      else { # Schon eingetragener Name, zus�tzliche Namensteile
                        $ret=$this->database->updateName($lfd_Nr_Name,$Satzunterart,$Namen);
                        if ($ret[0] AND DBWRITE) {
                          $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                          $errmsg.='<br>beim Aktualisieren von Namenseintr�gen in function updateName alb.php line: '.__LINE__;
                          $errmsg.='<br>'.$ret[1];
                          echo $errmsg;
                        }
                      }
                    }
                  } break;
                  case "2" : { # 4.Z.2 Eintragen von Zus�tzen zum Eigent�mer/Erbauberechtigten im Grundbuchblatt
                    $TextZeile=mb_substr($ze,60,2,'utf8');
                    if ($TextZeile=="01") {
                      $Zusatz_Eigentuemer=trim(mb_substr($ze,63,52,'utf8'));
                    }
                    else {
                      $Zusatz_Eigentuemer.=' '.trim(mb_substr($ze,63,52,'utf8'));
                    }
                    $ret=$this->database->updateGrundbuch($Bezirk,$Blatt,$Zusatz_Eigentuemer,'');
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Aktualisieren der Eigent�merdaten eines Grundbuches in function updateGrundbuch alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                  } break;
                } # end Switch Satzart2 in Spalte 56
              } break; # end of case "Z"
            } # end of Satzart Spalte 26
          } break; # end Dateikennung 4 Bestand

          ############## Dateikennung 7 Entschl�sselungen
          # Entschl�sselungen f�r Liegenschaftskataster f�hrende Stellen, Gemarkungen, Landkreise, Gemeinden und Strassen
          case "7" : {
            $Satzunterart=mb_substr($ze,27,1,'utf8');
            switch ($Satzart) {
              case "A" : { # 7.A Liegenschaftskataster f�hrende Stellen
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $Katasteramt=mb_substr($ze,1,4,'utf8');
                    $ArtAmt=trim(mb_substr($ze,29,26,'utf8'));
                    $Name='';
                  }
                  else {
                    $Name.=' '.trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*27,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Eintragen des neuen Forstamtes
                    $ret=$this->database->insertKatasteramt($Katasteramt,$ArtAmt,trim($Name));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Eintragen eines Katasteramtes in function insertKatasteramt alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_Katasteraemter++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "B" : { # 7.B Gemarkungen und Grundbuchbezirke
                $GMGB=intval(mb_substr($ze,1,6,'utf8'));       # LG 0
                $Gemeinde=intval(mb_substr($ze,8,8,'utf8'));   # LG 11
                $Amtsgericht=trim(mb_substr($ze,21,4,'utf8')); # LG 5
                $Bezeichnung=trim(mb_substr($ze,29,26,'utf8'));
                if ($Gemeinde!='') {
                  # Eintragen des GMGB als Gemarkung
                  $ret=$this->database->insertGemarkung($GMGB,$Gemeinde,$Amtsgericht,$Bezeichnung);
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Eintragen einer Gemarkung in function insertGemarkung alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $v_Gemarkungen++;
                  }
                }
                if ($Amtsgericht!='') {
                  # Eintragen des GMGB als Grundbuchbezirk
                  $ret=$this->database->insertGrundbuchbezirk($GMGB,$Amtsgericht,$Bezeichnung);
                  if ($ret[0] AND DBWRITE) {
                    $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                    $errmsg.='<br>beim Eintragen eines Grundbuchbezirkes in function insertGrundbuchbezirk alb.php line: '.__LINE__;
                    $errmsg.='<br>'.$ret[1];
                    echo $errmsg;
                  }
                  else {
                    $v_Grundbuchbezirke++;
                  }
                }

                # Eintragen der neuen Gemarkung
              } break;
              case "C" : { # 7.C Landkreise
                $Kreis=mb_substr($ze,1,5,'utf8');
                $Name=trim(mb_substr($ze,29,26,'utf8'));
                # Eintragen des neuen Kreises
                $ret=$this->database->insertKreis($Kreis,$Name);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen eines Kreises in function insertKreis alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $v_Kreise++;
                }
              } break;
              case "D" : { # 7.D Gemeinden
                $Gemeinde=mb_substr($ze,1,8,'utf8');
                $Name=trim(mb_substr($ze,29,26,'utf8'));
                # Eintragen der neuen Gemeinde
                $ret=$this->database->insertGemeinde($Gemeinde,$Name);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen einer Gemeinde in function insertGemeinde alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $v_Gemeinden++;
                }
              } break;
              case "E" : { # 7.C Stra�enname
                $Gemeinde=mb_substr($ze,1,8,'utf8');
                $Strasse=mb_substr($ze,13,5,'utf8');
                $Name=trim(mb_substr($ze,29,30,'utf8'));
                # Eintragen der neuen Strasse
                $ret=$this->database->insertStrasse($Gemeinde,$Strasse,$Name);
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen einer Strasse in function insertStrasse alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $v_Strassen++;
                }
              } break;
            } # end of switch Satzart
          } break; # end of Dateikennung 7

          ############## Dateikennung 8 Entschl�sselungen
          # Entschl�sselungen f�r Grundbuch�mter (Amtsgerichte), Eigent�merarten und Buchungsarten
          case "8" : {
            $Satzunterart=mb_substr($ze,27,1,'utf8');
            switch ($Satzart) {
              case "A" : { # 8.A Grundbuch�mter (Amtsgerichte)
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $Amtsgericht=mb_substr($ze,1,4,'utf8');
                    $Name=trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  else {
                    $Name.=' '.trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*27,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Eintragen des neuen Amtsgerichtes
                    $ret=$this->database->insertAmtsgericht($Amtsgericht,trim($Name));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Eintragen eines Amtsgerichtes in function insertAmtsgericht alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_Amtsgerichte++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "B" : { # 8.B Eigent�merarten
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $Eigentuemerart=mb_substr($ze,1,2,'utf8');
                    $Bezeichnung=trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  else {
                    $Bezeichnung.=' '.trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*27,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Eintragen der neuen Eigent�merart
                    $ret=$this->database->insertEigentuemerart($Eigentuemerart,trim($Bezeichnung));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Eintragen einer Eigentuemerart in function insertEigentuemerart alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_EigentuemerArten++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "C" : { # 8.C Buchungsarten
                $Buchungsart=mb_substr($ze,1,1,'utf8');
                $Bezeichnung=trim(mb_substr($ze,29,60,'utf8'));
                # Eintragen der neuen Buchungsart
                $ret=$this->database->insertBuchungsart($Buchungsart,trim($Bezeichnung));
                if ($ret[0] AND DBWRITE) {
                  $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                  $errmsg.='<br>beim Eintragen einer Buchungsart in function insertBuchungsart alb.php line: '.__LINE__;
                  $errmsg.='<br>'.$ret[1];
                  echo $errmsg;
                }
                else {
                  $v_Buchungsarten++;
                }
              } break;
            } # end of switch Satzart
          } break; # end of Dateikennung 8

          ############## Dateikennung 9 Entschl�sselungen
          # Entschl�sselungen f�r �mter, Hinweise zu Flurst�cken, Nutzungsarten, Klassifizierungen, ausf�hrende Stelle und Bemerkungen
          case "9" : {
            $Satzunterart=mb_substr($ze,27,1,'utf8');
            switch ($Satzart) {
              case "A" : { # 9.A Forst�mter
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $Forstamt=mb_substr($ze,3,2,'utf8');
                    $Name=trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  else {
                    $Name.=' '.trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*27,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Eintragen des neuen Forstamtes
                    $ret=$this->database->insertForstamt($Forstamt,trim($Name));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Eintragen eines Forstamtes in function insertForstamt alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_Forstaemter++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "B" : { # 9.B Finanz�mter
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $Finanzamt=mb_substr($ze,1,4,'utf8');
                    $Name=trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  else {
                    $Name.=' '.trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*27,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Eintragen des neuen Finanzamtes
                    $ret=$this->database->insertFinanzamt($Finanzamt,trim($Name));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Eintragen eines Finanzamtes in function insertFinanzamt alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_Finanzaemter++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "C" : { # 9.C Hinweise zum Flurst�ck
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $HinwZFlst=mb_substr($ze,1,2,'utf8');
                    $Bezeichnung=trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  else {
                    $Bezeichnung.=' '.trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*27,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Eintragen der neuen Nutzungsart
                    $ret=$this->database->insertHinweisart($HinwZFlst,trim($Bezeichnung));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Eintragen einer Hinweisart in function insertHinweisart alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_Hinweise++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "D" : { # 9.D Nutzungsart
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $Nutzungsart=mb_substr($ze,3,3,'utf8');
                    $Bezeichnung =trim(mb_substr($ze,29+$Feld*35,30,'utf8'));
                    $Abkuerzung =trim(mb_substr($ze,59+$Feld*35,4,'utf8'));
                  }
                  else {
                    $Bezeichnung.=' '.trim(mb_substr($ze,29+$Feld*35,30,'utf8'));
                    $Abkuerzung.=' '.trim(mb_substr($ze,59+$Feld*35,4,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*35,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Eintragen der neuen Nutzungsart
                    $ret=$this->database->insertNutzungsart($Nutzungsart,trim($Bezeichnung),trim($Abkuerzung));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Eintragen einer Nutzungsart in function insertNutzungsart alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_Nutzungsarten++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "E" : { # 9.E Klassifizierung
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $TabKenn=mb_substr($ze,1,2,'utf8');
                    $Klass=mb_substr($ze,3,3,'utf8');
                    $Bezeichnung=trim(mb_substr($ze,29+$Feld*35,30,'utf8'));
                    $Abkuerzung=trim(mb_substr($ze,59+$Feld*35,4,'utf8'));
                  }
                  else {
                    $Bezeichnung.=' '.trim(mb_substr($ze,29+$Feld*35,30,'utf8'));
                    $Abkuerzung.=' '.trim(mb_substr($ze,59+$Feld*35,4,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*35,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Anlegen einer neuen Klassifizierung
                    $ret=$this->database->insertKlassifizierungsart($TabKenn,$Klass,trim($Bezeichnung),trim($Abkuerzung));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Einf�gen einer Klassifizierungsart in function insertKlassifizierungsart alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_Klassifizierungen++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "F" : { # # 9.F Ausf�hrende Stelle
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $AusfStelle=mb_substr($ze,1,5,'utf8');
                    $Name=trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  else {
                    $Name.=' '.trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*27,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Anlegen einer neuen Ausf�hrenden Stelle
                    $ret=$this->database->insertAusfuehrendeStelle($AusfStelle,trim($Name));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Einf�gen einer ausf�hrenden Stelle in function insertAusfuehrendeStelle alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_AusfuehrendeStellen++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "G" : { # 9.G Bemerkungen zum Verfahren
                for ($Feld=0;$Feld<3;$Feld++) {
                  if ($Satzunterart=='0' AND $Feld==0) {
                    $VerfBemerkung=mb_substr($ze,1,2,'utf8');
                    $Bezeichnung=trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  else {
                    $Bezeichnung.=' '.trim(mb_substr($ze,29+$Feld*27,26,'utf8'));
                  }
                  $Satzfolge=mb_substr($ze,28+$Feld*27,1,'utf8');
                  if ($Satzfolge=='9') {
                    $mehrzeilig=0;
                    $Feld=3; # nicht weiter einlesen
                    # Anlegen einer neuen Bemerkung zu einem Verfahren
                    $ret=$this->database->insertBemerkgZumVerfahren($VerfBemerkung,trim($Bezeichnung));
                    if ($ret[0] AND DBWRITE) {
                      $errmsg ='<br>Abbruch beim Einlesen der WLDGE-Datei in Zeile: '.$zeNr.'<br>'.$ze;
                      $errmsg.='<br>beim Einf�gen einer Bemerkung zum Verfahren in function insertBemerkgZumVerfahren alb.php line: '.__LINE__;
                      $errmsg.='<br>'.$ret[1];
                      echo $errmsg;
                    }
                    else {
                      $v_BemerkgZumVerfahren++;
                    }
                  }
                  else {
                    $mehrzeilig=1;
                  }
                }
              } break;
              case "Z" : { # 9.Z Abrechnungsdaten
                echo "<p>Abrechnungsdaten:".$ze;
                $Satzfolge=mb_substr($ze,28,1,'utf8');
                $Konstante9=mb_substr($ze,1,25,'utf8');
                $Satzunterart=mb_substr($ze,27,1,'utf8');
                $anzBereichskennz=mb_substr($ze,29,7,'utf8');
                $anzFlurstuecke=mb_substr($ze,36,7,'utf8');
                $anzBestaende=mb_substr($ze,43,7,'utf8');
                $ret[0]='';
                # Damit die Ausgabe: "Konnte nicht eingelesen werden" f�r diese Zeile unterdr�ckt wird
              } break;
            } # end of switch Satzart
          } break; # end of Dateikennung 9
        } # ende switch Dateikennung
        # registrieren aller nicht verarbeiteten Datens�tze
        # Eintr�ge, aus denen kein SQL-Statement abgeleitet werden konnte
        # Zeilen, die zu mehrzeiligen Argumenten zusammengesetzt werden m�ssen werden ignoriert,
        # z.B. in 7.A Name der Liegenschaftskatasterf�hrenden Stelle

        if ($ret[0] AND !($mehrzeilig OR $satzignore)) {
          if ($ret[1]=='') {
            $rest[]='Zeile '.$zeNr.' ignoriert, Dateikennung:'.$Dateikennung.' Satzart:'.$Satzart.' Unterart:'.$Satzunterart;
          }
          else {
            $rest[]='Fehler in Zeile '.$zeNr.', Dateikennung:'.$Dateikennung.' Satzart:'.$Satzart.' Unterart:'.$Satzunterart;
          }
        }
      } # ende Zeile hat Inhalt
    } # ende Zeilen lesen
    $endtime=time();
    echo "<br>...Einlesen beendet.";
    if ($Konstante9=='9999999999999999999999999' AND $Satzunterart=='0' AND $Satzfolge=='9') {
      $Ausgabe.='<b>Abrechnungsdaten aus WLDGE Datei:</b>';
      $Ausgabe.='<br>Anzahl der Bereichskennzeichen: '.$anzBereichskennz;
      $Ausgabe.='<br>Anzahl der Flurst�cke: '.$anzFlurstuecke;
      $Ausgabe.='<br>Anzahl der Bestandsdatens�tze: '.$anzBestaende;
      $this->dategrundausstattung=$GA['Jahr'].'-'.$GA['Monat'].'-'.$GA['Tag'];
      $this->zeitraumvon=$von['Jahr'].'-'.$von['Monat'].'-'.$von['Tag'].' '.$von['Stunde'].':'.$von['Minute'].':'.$von['Sekunde'];
      $this->zeitraumbis=$bis['Jahr'].'-'.$bis['Monat'].'-'.$bis['Tag'].' '.$bis['Stunde'].':'.$bis['Minute'].':'.$bis['Sekunde'];
    }
    else {
      $ret=$this->database->rollbacktransaction();
      return 'Fehlerhafte oder nicht vorhandene Abrechnungsdaten.';
    }
    fclose($fp);

    if ($errmsg!='') {
      $ret=$this->database->rollbacktransaction();
      return $errmsg;
    }
    else {
      $ret=$this->database->committransaction();
      $Ausgabe.='<p><b>Gelesene Zeilen gesamt:</b> '.$zeNr;
      $Ausgabe.='<p><b>Anzahl der Eingelesenen Datens�tze:</b>';
      $Ausgabe.='<br><i>Bestand</i>';
      $Ausgabe.='<br>'.$tableprefix.'Grundbuecher: '.$Grundbuecher;
      $Ausgabe.='<br>'.$tableprefix.'g_Grundstuecke: '.$g_Grundstuecke;
      $Ausgabe.='<br>'.$tableprefix.'g_Eigentuemer: '.$g_Eigentuemer;
      $Ausgabe.='<br>'.$tableprefix.'g_Namen: '.$g_Namen;
      $Ausgabe.='<br>'.$tableprefix.'g_Buchungen: '.$g_Buchungen;
      $Ausgabe.='<br><i>Flurst�cke</i>';
      $Ausgabe.='<br>'.$tableprefix.'Flurstuecke: '.$Flurstuecke;
      $Ausgabe.='<br>'.$tableprefix.'f_Hinweise: '.$f_Hinweise;
      $Ausgabe.='<br>'.$tableprefix.'f_Adressen: '.$f_Adressen;
      $Ausgabe.='<br>'.$tableprefix.'f_Lage: '.$f_Lage;
      $Ausgabe.='<br>'.$tableprefix.'f_Nutzungen: '.$f_Nutzungen;
      $Ausgabe.='<br>'.$tableprefix.'f_Klassifizierungen: '.$f_Klassifizierungen;
      $Ausgabe.='<br>'.$tableprefix.'f_Texte: '.$f_Texte;
      $Ausgabe.='<br>'.$tableprefix.'f_Anlieger: '.$f_Anlieger;
      $Ausgabe.='<br>'.$tableprefix.'f_Baulasten: '.$f_Baulasten;
      $Ausgabe.='<br>'.$tableprefix.'f_Verfahren: '.$f_Verfahren;
      # 2005-12-27 pk
      $f_Historie=$this->database->getAnzHistorien(1,'','');
      $Ausgabe.='<br>'.$tableprefix.'f_Historie: '.$f_Historie;
      $Ausgabe.='<br><i>Entschl�sselungen</i>';
      $Ausgabe.='<br>'.$tableprefix.'v_Katasteraemter: '.$v_Katasteraemter;
      $Ausgabe.='<br>'.$tableprefix.'v_Gemarkungen: '.$v_Gemarkungen;
      $Ausgabe.='<br>'.$tableprefix.'v_Grundbuchbezirke: '.$v_Grundbuchbezirke;
      $Ausgabe.='<br>'.$tableprefix.'v_Kreise: '.$v_Kreise;
      $Ausgabe.='<br>'.$tableprefix.'v_Gemeinden: '.$v_Gemeinden;
      $Ausgabe.='<br>'.$tableprefix.'v_Strassen: '.$v_Strassen;
      $Ausgabe.='<br>'.$tableprefix.'v_Amtsgerichte: '.$v_Amtsgerichte;
      $Ausgabe.='<br>'.$tableprefix.'v_EigentuemerArten: '.$v_EigentuemerArten;
      $Ausgabe.='<br>'.$tableprefix.'v_Buchungsarten: '.$v_Buchungsarten;
      $Ausgabe.='<br>'.$tableprefix.'v_Forstaemter: '.$v_Forstaemter;
      $Ausgabe.='<br>'.$tableprefix.'v_Finanzaemter: '.$v_Finanzaemter;
      $Ausgabe.='<br>'.$tableprefix.'v_Hinweise: '.$v_Hinweise;
      $Ausgabe.='<br>'.$tableprefix.'v_Nutzungsarten: '.$v_Nutzungsarten;
      $Ausgabe.='<br>'.$tableprefix.'v_Klassifizierungen: '.$v_Klassifizierungen;
      $Ausgabe.='<br>'.$tableprefix.'v_AusfuehrendeStellen: '.$v_AusfuehrendeStellen;
      $Ausgabe.='<br>'.$tableprefix.'v_BemerkgZumVerfahren: '.$v_BemerkgZumVerfahren;
      if (count($rest)>0) {
        $Ausgabe.='<p><b>Anzahl nicht eingelesener Datenzeilen:</b>';
        for ($i=0;$i<count($rest);$i++) {
          $Ausgabe.='<br>'.$rest[$i];
        }
      }
      $Ausgabe.='<p>Dauer: '.DATE("i",$endtime-$starttime)."min : ".DATE("s",$endtime-$starttime)."s";
      $this->Protokoll_Einlesen=$Ausgabe;
      return '';
    }
  } # end of function WLDGE_Datei_Einlesen


  #########################################################
  # ALB Fortf�hren (Aktualisierung)
  #
  function Fortfuehren() {
    # Pr�fen ob die WLDGE Datei fehlerfrei ist
    $this->database->logfile->write($this->database->commentsign.' Pr�fen der Eingangsdaten.');
    if ($this->checkHeader) {
      # Pr�fen ob die WLDGE Datei fehlerfrei ist
      $Fehlermeldung=$this->WLDGE_Datei_Pruefen();
    }
    if ($Fehlermeldung!='') {
      $this->WLDGE_Datei_fehlerhaft=1;
      return $Fehlermeldung;
    }

    # Datei fehlerfrei
    # Einlesen der WLDGE-Datei in tempor�re Tabellen, SQL-Dump in Datei schreiben
    $this->database->logfile->write($this->database->commentsign.' Einlesen der WLDGE-Datei: '.$this->WLDGE_Datei['tmp_name']);

    $Fehlermeldung=$this->WLDGE_Datei_einlesen();
    if ($Fehlermeldung!='') { return $Fehlermeldung; }

    ###################################################################################
    # Einlesen ist fehlerfrei erfolgt, nun Aktualisieren der vorhandenen ALB-Tabellen #
    #                                                                                 #
    # am Besten vorher alten Zustand der Tabellen sichern                             #
    # ggf. durch Applikation alle anderen Zugriffe verbieten                          #
    ###################################################################################

    $this->database->logfile->write($this->database->commentsign.' Aktualisieren des Bestandes');

    # Start aktualisieren
    $this->database->begintransaction();
    echo "<br>Starte Fortf�hrung...";
    $starttime=time();
    $Ausgabe.='<b>Protokoll der Aktualisierung:</b>';
    $exitMsg='Abbruch in ALB->Fortfuehren() Zeile: ';

    ############################################ Aktualisieren der Grundb�cher
    $Ausgabe.="<br><i>Bestand</i>";
    echo "<br><i>Bestand</i>";

    # Abfrage der Anzahl an historischer Grundbuecher
    $ret=$this->database->getAnzGrundbuecher('hist');
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der historischen Grundbuecher in function countGrundbuecher alb.php line: '.__LINE__;
      $errmsg.='<br>'.$ret[1];
      return $errmsg;
    }
    $anzGrundbuecherHist=$ret[1];
    $Ausgabe.="<br>Historische Grundbucheintr�ge: ".$anzGrundbuecherHist;
    echo "<br>Historische Grundbucheintr�ge: ".$anzGrundbuecherHist;

    # L�schen aller Grundbuchbl�tter, zu denen neue Informationen vorhanden sind
    # Wenn die Option historische_loeschen gew�hlt wurde, werden auch alle historischen gel�scht
    # Alle die gel�scht werden, werden hinterher wieder eingef�gt (geupdateed), au�er die, die historisch sind
    # denn die sind entweder schon drin, oder sollen nicht rein.
    $ret=$this->database->deleteGrundbuecher($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen der Grundbuecher, die neu eingetragen werden sollen in function deleteGrundbuecher alb.php line: '.__LINE__;
      $errmsg.='<br>'.$ret[1];
      return $errmsg;
    }
    $anzGrundbuecherDeleted=$this->database->getAffectedRows($ret[1]);
    if ($this->historische_loeschen) {
      $Ausgabe.="<br>Grundb�cher aktualisiert oder gel�scht: ".$anzGrundbuecherDeleted;
      echo "<br>Grundb�cher aktualisiert oder gel�scht: ".$anzGrundbuecherDeleted;
    }
    else {
      $Ausgabe.="<br>Grundb�cher aktualisiert: ".$anzGrundbuecherDeleted;
      echo "<br>Grundb�cher aktualisiert: ".$anzGrundbuecherDeleted;
    }

    # Wenn historische_loeschen=0, werden alle in der Fortf�hrungsdatei als historisch gekennzeichnete Grundb�cher im aktuellen Bestand auch als historisch gekennzeichnet.
    if (!$this->historische_loeschen) {
      $ret=$this->database->setGrundbuecherHist();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim Kennzeichnen der historisch gewordenen Grundbuecher in function setGrundbuecherHist() alb.php line: '.__LINE__;
        $errmsg.='<br>'.$ret[1];
        return $errmsg;
      }
    }

    # Einf�gen aller neuen Grundbuecher, au�er den historischen
    # wenn historische_loeschen=1 werden trotzdem nur die neuen und ge�nderten �bernommen,
    # denn die historischen sind ja im Bestand schon vorhanden und als solche gekennzeichnet
    $ret=$this->database->insertNeueGrundbuecher();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen der neuen Grundbuecher in function insertNeueGrundbuecher alb.php line: '.__LINE__;
      $errmsg.='<br>'.$ret[1];
      return $errmsg;
    }
    # Differenz aus den nach dem L�schen wieder neu eingetragenen und den vorher gel�schten Grundb�chern
    # Entspricht der Anzahl an neu im Bestand eingetragenen Grundb�chern
    $anzGrundbuecherNeu=$this->database->getAffectedRows($ret[1])-$anzGrundbuecherDeleted;
    $Ausgabe.="<br>Grundb�cher Neu: ".$anzGrundbuecherNeu;
    echo "<br>Grundb�cher Neu: ".$anzGrundbuecherNeu;

    /*
    * L�schen aller Grundstuecke, f�r die �nderungsdaten vorliegen
    */
    if($this->Bestandsdateikennung == '2')$ret=$this->database->deleteGrundstueckeByGrundbuecher($this->historische_loeschen);				# angepasst wegen WVG-WLDGE-Datei
		else $ret=$this->database->deleteNewGrundstuecke();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen der Grundst�cke mit �nderungen';
      $errmsg.=' in function deleteGrundstueckeByGrundbuecher alb.php line: '.__LINE__;
      $errmsg.='<br>'.$ret[1];
      return $errmsg;
    }
    $anzGrundstueckeHist=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Grundst�cke mit �nderungsdaten gel�scht: ".$anzGrundstueckeHist;
    echo "<br>Grundst�cke mit �nderungsdaten gel�scht: ".$anzGrundstueckeHist;
    /*
     * L�schen aller Buchungen, f�r die �nderungsdaten vorliegen
     */
    if($this->Bestandsdateikennung == '2')$ret=$this->database->deleteBuchungenByGrundbuecher($this->historische_loeschen);					# angepasst wegen WVG-WLDGE-Datei
		else $ret=$this->database->deleteNewBuchungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen der Buchungen mit �nderungen';
      $errmsg.=' in function deleteBuchungenByGrundbuecher alb.php line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzBuchungenHist=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Buchungen mit �nderungsdaten gel�scht: ".$anzBuchungenHist;
    echo "<br>Buchungen mit �nderungsdaten gel�scht: ".$anzBuchungenHist;

    /*
     * L�schen aller Zuordnungen von Eigent�mern, f�r die �nderungsdaten vorliegen
     */
    if($this->Bestandsdateikennung == '2')$ret=$this->database->deleteEigentuemerByGrundbuecher($this->historische_loeschen);
		else $ret=$this->database->deleteNewEigentuemer();																								# angepasst wegen WVG-WLDGE-Datei
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen der Eigent�mer auf Grundbuchbl�ttern';
      $errmsg.=' in function deleteEigentuemerByGrundbuecher katasterp.php line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzEigentuemerHist=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Eigent�mer mit �nderungsdaten gel�scht: ".$anzEigentuemerHist;
    echo "<br>Eigent�mer mit �nderungsdaten gel�scht: ".$anzEigentuemerHist;

    /* ############################################### Aktualisieren der Bestandsdaten
    * 1 Aktualisieren aller bestehenden Bestandsdaten
    * 1.1. Aktualisierung der Grundstucksdaten
    * 1.1.1 Einf�gen neuer Grundstuecke
    * Es wird davon ausgegangen, dass zu Grundb�chern, die als historisch gekennzeichneten wurden, keine Grundst�cke, Buchungen
    * oder Eigent�mer in der Fortf�hrungsdatei aufgelistet sind.
    */
    $ret=$this->database->insertNewGrundstuecke();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Hinf�gen neuer Grundst�cke in function';
      $errmsg.=' insertNewGrundstuecke alb.php line: '.__LINE__.'<br>'.$ret[1];
      echo $errmsg;
      return $errmsg;
    }
    $anzGrundstueckeNeu=$this->database->getAffectedRows($ret[1])-$anzGrundstueckeDelete;
    $Ausgabe.="<br>Grundst�cke Neu: ".$anzGrundstueckeNeu;
    echo "<br>Grundst�cke Neu: ".$anzGrundstueckeNeu;

    # 1.2 Einf�gen der neuen Eintragungen f�r die Buchungen
    $ret=$this->database->insertNewBuchungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Hinf�gen neuer Buchungen in function';
      $errmsg.=' insertNewBuchungen alb.php line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzBuchungenNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Buchungen Neu: ".$anzBuchungenNeu-$anzBuchungenUpdate;
    echo "<br>Buchungen Neu: ".$anzBuchungenNeu-$anzBuchungenUpdate;

    # 1.3 Aktualisieren der Eigent�mer Zuordnungen.
    # 1.3.1 Aktualisierung der Adressdaten von schon vorhandenen Eigentuemern
    #       Bezirk, Blatt und NamensNr von bestehender Eigent�mertabelle wird mit neuer Eigent�mertabelle gleichgesetzt
    #       und alle Felder der dazugeh�rigen Eintr�ge zu Namen durch die neue Namen �berschieben.
    $ret=$this->database->updateEigentuemer();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Eigent�mer und Namen in function';
      $errmsg.=' updateEigentuemer alb.php line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzNamenUpdate=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Namen Aktualisiert: ".$anzNamenUpdate;
    echo "<br>Namen Aktualisiert: ".$anzNamenUpdate;

    # 1.3.2. Finden von schon vorhandenen Namen im Grundbestand und Kennzeichnen in neuer Tabelle
    # A) �bernahme der alten lfdNr f�r Namen in die Tabelle der neue Namen
    #    In den F�llen wo alle 4 Namensteile der alten und der neuen Namenstabelle identisch sind
    #    wird die alte lfd_Nr f�r Namen in die neue Tabelle zwischengespeichert
    $ret=$this->database->updateLfdNrName();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim �bernehmen der alten lfdNr f�r die neue Tabelle';
      $errmsg.=' updateLfdNrNamen alb.php line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzNamenVorhanden=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Namen �bernommen: ".$anzNamenVorhanden;
    echo "<br>Namen �bernommen: ".$anzNamenVorhanden;

    # 1.3.3. Anh�ngen aller neuen Namen an die Namenstabelle des Grundbestands
    #        Es sind alle die Namen neu, f�r die im vorhergehenden Schritt keine �bereinstimmungen gefunden wurden.
    $ret=$this->database->insertNewNamen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Hinzuf�gen neuer Namen';
      $errmsg.=' insertNewNamen alb.php line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzNamenNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Namen Neu: ".$anzNamenNeu;
    echo "<br>Namen Neu: ".$anzNamenNeu;

    # 1.3.4. Jetzt werden die Nummern, die �ber Autonummer in der Grundbestandstabelle f�r die neuen Namen vergeben wurden
    #        durch wiederholen von Schritt A zur�ck in die neue Namenstabelle geschrieben.
    #        In der neuen Namentabelle haben jetzt alle neue Namen und alle schon vorhandene Namen eine Nummer im Fortf�hrungsbestand
    #        und eine Nummer im Grundbestand
    $ret=$this->database->updateLfdNrName();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim �bernehmen der alten lfdNr f�r die neue Tabelle';
      $errmsg.=' updateLfdNrName alb.php line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }

    # 1.3.5 �bernahme der neuen Eigent�merdaten in den Grundbestand
    #       die lfd_Nr f�r die Namen im Bestand werden aus der Spalte lfd_Nr_alt entnommen

    $ret=$this->database->insertNewEigentuemer();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Hinzuf�gen neuer Namen';
      $errmsg.=' insertNewEigentuemer alb.php line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzEigentuemerNeu=$this->database->getAffectedRows($ret[1])-$anzEigentuemerUpdate;
    $Ausgabe.="<br>Eigent�merdaten hinzugef�gt: ".$anzEigentuemerNeu;
    echo "<br>Eigent�merdaten hinzugef�gt: ".$anzEigentuemerNeu;

    /*
     *    Flurst�cksdaten
     */
    /* 2. Aktualisieren der Flurst�cksdaten
     *    Der �bersicht halber werden zun�chst alle Eintr�ge von historisch gewordenen
     *    Flurst�cken gel�scht (wenn historische_loeschen=1 ist).
     *    Im nachhinein werden weitere L�schanfragen ausgef�hrt,
     *    z.B. vor dem Eintragen neuer Adressen, die auch zusammen mit dem L�schvorgang f�r
     *    historische Flurst�cke h�tten durchgef�hrt werden k�nnen.
     */
    $Ausgabe.="<br><i>Flurst�cke</i>";
    echo "<br><i>Flurst�cke</i>";

    if ($this->historische_loeschen) {
      # 2.1 L�schen aller historischen Flurst�cke und der dazugeh�rigen Daten
      # 2.1.1 L�schen aller historischen Flurst�cke
      # alle Flurst�cke, die in der �nderungsdatei mit einem Status 'H' eingelesen wurden
      # werden in dem vorhandenen Datenbestand gel�scht.
      $ret=$this->database->deleteHistFlurstuecke();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen historischer Flurst�cke';
        $errmsg.=' alb.php deleteHistFlurstuecke line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzFlurstueckeHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Flurst�cke Gel�scht: ".$anzFlurstueckeHist;
      echo "<br>hist. Flurst�cke Gel�scht: ".$anzFlurstueckeHist;

      # 2.1.2 L�schen der Zuordnungen zum Bestand in g_Buchungen
      $ret=$this->database->deleteBuchungenByHistFlurstuecke();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen historischer Buchungen';
        $errmsg.=' alb.php, deleteHistBuchungen line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzBuchungenHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Buchungen Gel�scht: ".$anzBuchungenHist;
      echo "<br>hist. Buchungen Gel�scht: ".$anzBuchungenHist;

      # 2.1.3 L�schen der Zuordnungen zu Adressen
      $ret=$this->database->deleteHistAdressen();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen historischer Adressen';
        $errmsg.=' alb.php, deleteHistAdressen line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzAdressenHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Adressen Gel�scht: ".$anzAdressenHist;
      echo "<br>hist. Adressen Gel�scht: ".$anzAdressenHist;

      # 2.1.4 L�schen der historischen Zuordnungen zu Anlieger
      $ret=$this->database->deleteHistAnlieger();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen historischer Anlieger';
        $errmsg.=' alb.php, deleteHistAnlieger line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzAnliegerHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Anlieger Gel�scht: ".$anzAnliegerHist;
      echo "<br>hist. Anlieger Gel�scht: ".$anzAnliegerHist;

      # 2.1.5 L�schen der historischen Zuordnung zu Baulasten
      $ret=$this->database->deleteHistBaulasten();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen historischer Baulasten';
        $errmsg.=' alb.php, deleteHistBaulasten line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzBaulastenHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Baulasten Gel�scht: ".$anzBaulastenHist;
      echo "<br>hist. Baulasten Gel�scht: ".$anzBaulastenHist;

      # 2.1.6 L�schen der Zuordnung zu Hinweisen zum Flurst�ck
      $ret=$this->database->deleteHistHinweise();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen historischer Hinweise';
        $errmsg.=' alb.php, deleteHistHinweise line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzHinweiseZFlstHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Hinweise zum Flurst�ck Gel�scht: ".$anzHinweiseZFlstHist;
      echo "<br>hist. Hinweise zum Flurst�ck Gel�scht: ".$anzHinweiseZFlstHist;

      # 2.1.7 L�schen der Zuordnung zur Flurst�ckshistorie
      # 2.1.7.1 L�schen aller Vorg�nger von historischen Flurst�cke in x_f_Historie
      #         Dies ist nur eine �nderung der eingelesenen Informationen
      #         wird nicht in der Ausgabe gelistet
      $ret=$this->database->deleteTempHistVorgaenger();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen der tempor�r eingelesenen Vorg�nger von historischen Flurst�cken';
        $errmsg.=' alb.php, deleteTempHistVorgaenger line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }

      # 2.1.7.2 L�schen aller Eintr�ge in f_Historie bei denen die Nachfolger historische Flurst�cke sind
      $ret=$this->database->deleteHistHistorie();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen der historischen Eintr�ge in der Historien Tabelle';
        $errmsg.=' alb.php, deleteHistHistorie line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzHistorieHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Historiebeziehungen Gel�scht: ".$anzHistorieHist;
      echo "<br>hist. Historiebeziehungen Gel�scht: ".$anzHistorieHist;

      # 2.1.8 L�schen der Zuordnung zu Klassifizierungen von historischen Flurstuecken
      $ret=$this->database->deleteHistKlassifizierungen();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen der historischen Klassifizierungen';
        $errmsg.=' alb.php, deleteHistKlassifizierungen line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzKlassifizierungenHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Klassifizierungen Gel�scht: ".$anzKlassifizierungenHist;
      echo "<br>hist. Klassifizierungen Gel�scht: ".$anzKlassifizierungenHist;

      # 2.1.9 L�schen der Zuordnung zu Lagebezeichnungen von historischen Flurstuecken
      $ret=$this->database->deleteHistLagen();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen der historischen Lagen';
        $errmsg.=' alb.php, deleteHistLagen line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzLagebezeichnungenHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Lagebezeichnungen Gel�scht: ".$anzLagebezeichnungenHist;
      echo "<br>hist. Lagebezeichnungen Gel�scht: ".$anzLagebezeichnungenHist;

      # 2.1.10 L�schen der Zuordnung zu Nutzungen von historischen Flurstuecken
      $ret=$this->database->deleteHistNutzungen();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen der historischen Nutzungen';
        $errmsg.=' alb.php, deleteHistNutzungen line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzNutzungenHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Nutzungen Gel�scht: ".$anzNutzungenHist;
      echo "<br>hist. Nutzungen Gel�scht: ".$anzNutzungenHist;

      # 2.1.11 L�schen der Zuordnung zu Texten von historischen Flurstuecken
      $ret=$this->database->deleteHistTexte();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen der historischen Texte';
        $errmsg.=' alb.php, deleteHistTexte line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzTexteHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Texte Gel�scht: ".$anzTexteHist;
      echo "<br>hist. Texte Gel�scht: ".$anzTexteHist;

      # 2.1.12 L�schen der Zuordnung zu Verfahren von historischen Flurstuecken
      $ret=$this->database->deleteHistVerfahren();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim L�schen der historischen Verfahren';
        $errmsg.=' alb.php, deleteHistVerfahren line: '.__LINE__.'<br>'.$ret[1];
        return $errmsg;
      }
      $anzVerfahrenHist=$this->database->getAffectedRows($ret[1]);
      $Ausgabe.="<br>hist. Verfahrenszuordnungen Gel�scht: ".$anzVerfahrenHist;
      echo "<br>hist. Verfahrenszuordnungen Gel�scht: ".$anzVerfahrenHist;
    }
    else {
      /*
       * Kennzeichnen der historischen Flurst�cke als historisch
       */
      $ret=$this->database->setFlurstueckeHist();
      if ($ret[0] AND DBWRITE) {
        $errmsg ='<br>Abbruch beim Kennzeichnen der historisch gewordenen Flurst�cken in function setFlurstueckeHist() alb.php line: '.__LINE__;
        $errmsg.='<br>'.$ret[1];
        return $errmsg;
      }
    } # end of historische Flurst�cke sollen nicht gel�scht werden

    # 2.2 Aktualisieren der vorhandenen Flurst�cke und dessen Angaben
    # 2.2.1 Aktualisieren der Tabelle mit den Flurst�cken
    # 2.2.1.1 Abfrage der Anzahl der nicht historischen neuen Flurst�cke
    $ret=$this->database->getAnzNewFlurstuecke();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der neuen Flurst�cke';
      $errmsg.=' alb.php, getAnzNewFlurstuecke line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzTempFlurst=$ret[1];

    # 2.2.1.2 Abfrage der Anzahl der vorhandenen Flurst�cke vor dem Update
    $ret=$this->database->getAnzFlurstuecke();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Flurst�cke';
      $errmsg.=' alb.php, getAnzNewFlurstuecke line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzFlurstAlt=$ret[1];

    # 2.2.1.3 Aktualisieren der Flurst�ckstabelle
    $ret=$this->database->replaceFlurstuecke($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der bestehenden Flurst�ckstabelle';
      $errmsg.=' alb.php, replaceFlurstuecke line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }

    # 2.2.1.4 Abfragen der Anzahl Flurst�cke nach der Aktualisierung
    $ret=$this->database->getAnzFlurstuecke();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Flurst�cke';
      $errmsg.=' alb.php, getAnzNewFlurstuecke line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzFlurstNeu=($ret[1]-$anzFlurstAlt);
    $Ausgabe.="<br>Flurst�cke Neu: ".$anzFlurstNeu;
    echo "<br>Flurst�cke Neu: ".$anzFlurstNeu;

    $anzFlurstUpdate=($anzTempFlurst-$anzFlurstNeu);
    $Ausgabe.="<br>Flurst�cke Aktualisiert: ".$anzFlurstUpdate;
    echo "<br>Flurst�cke Aktualisiert: ".$anzFlurstUpdate;

    # 2.2.2 Aktualisieren der Flurst�ckshistorie
    # L�schen der Historien, die durch neue �berschrieben werden.
    $ret=$this->database->deleteNewHistorien();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen der bestehenden Historien, die ersetzt werden sollen.';
      $errmsg.=' alb.php, insertNewHistorien line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzHistorieUpdate=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Historiebeziehungen aktualisiert: ".$anzHistorieUpdate;
    echo "<br>Historiebeziehungen aktualisiert: ".$anzHistorieUpdate;

    # Einf�gen aller neuen Historienbeziehungen
    $ret=$this->database->insertNewHistorien();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einlesen und Aktualisieren der bestehenden Historien';
      $errmsg.=' alb.php, insertNewHistorien line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzHistorieNeu=$this->database->getAffectedRows($ret[1])-$anzHistorieUpdate;
    $Ausgabe.="<br>Historiebeziehungen Neu: ".$anzHistorieNeu;
    echo "<br>Historiebeziehungen Neu: ".$anzHistorieNeu;

    # 2.2.3 Aktualisieren der Nutzungen auf Flurst�cken
    # 2.2.3.1 L�schen aller Nutzungsarteneintr�ge f�r Flurst�cke, zu denen neue Nutzungen angegeben wurden
    $ret=$this->database->deleteOldNutzungen($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen der unaktuellen Nutzungen';
      $errmsg.=' alb.php, deleteOldNutzungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzNutzungenAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Nutzungen Gel�scht: ".$anzNutzungenAlt;
    echo "<br>alte Nutzungen Gel�scht: ".$anzNutzungenAlt;

    # 2.2.3.2 Eintragen aller neuen Nutzungsarten
    $ret=$this->database->insertNewNutzungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen der neuen Nutzungen';
      $errmsg.=' alb.php, insertNewNutzungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzNutzungenNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Nutzungen Neu: ".$anzNutzungenNeu;
    echo "<br>Nutzungen Neu: ".$anzNutzungenNeu;

    # 2.2.4 Aktualisieren der Adressen
    # 2.2.4.1 L�schen aller Adresseintr�ge von vorhandenen Flurst�cke mit �nderungen
    $ret=$this->database->deleteOldAdressen($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen alter Adressen';
      $errmsg.=' alb.php, deleteOldAdressen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzAdressenAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Adressen Gel�scht: ".$anzAdressenAlt;
    echo "<br>alte Adressen Gel�scht: ".$anzAdressenAlt;

    # 2.2.4.2 Eintragen aller neuen Adressen
    $ret=$this->database->insertNewAdressen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen neuer Adressen';
      $errmsg.=' alb.php, insertNewAdressen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzAdressenNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Adressen Neu: ".$anzAdressenNeu;
    echo "<br>Adressen Neu: ".$anzAdressenNeu;

    # 2.2.5 Aktualisieren der Lagebezeichnungen
    # 2.2.5.1 L�schen der Eintr�ge in f_Lage, dessen FlurstKennz in x_f_Lage vorhanden sind
    $ret=$this->database->deleteOldLagen($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen unaktueller Lagebezeichnungen';
      $errmsg.=' alb.php, deleteOldLagen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzLageAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Lagebezeichnungen Gel�scht: ".$anzLageAlt;
    echo "<br>alte Lagebezeichnungen Gel�scht: ".$anzLageAlt;

    # 2.2.5.2 Einf�gen aller Eintr�ge aus x_f_Lage in f_Lage
    $ret=$this->database->insertNewLagen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen neuer Lagen';
      $errmsg.=' alb.php, insertNewLagen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzLageNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Lagebezeichnungen Neu: ".$anzLageNeu;
    echo "<br>Lagebezeichnungen Neu: ".$anzLageNeu;

    /*# 2.2.5.3 L�schen aller Lagebezeichnungen f�r Flurst�cke, die mindestens einen Adresseintrag haben
    $ret=$this->database->deleteAddressLagen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen von Lagen, die Adressen haben';
      $errmsg.=' alb.php, deleteAddressLagen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzLageAdr=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Lagebez. wegen vorh. Adresse Gel�scht: ".$anzLageAdr;
    echo "<br>Lagebez. wegen vorh. Adresse Gel�scht: ".$anzLageAdr;
    */

    # 2.2.6 Aktualisieren der Verfahrenszuordnungen
    # 2.2.6.1 L�schen der Eintr�ge in f_Verfahren, dessen FlurstKennz in x_f_Verfahren vorhanden sind
    $ret=$this->database->deleteOldVerfahren($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen unaktueller Verfahrenszuordnungen';
      $errmsg.=' alb.php, deleteOldVerfahren line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzVerfahrenAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Verfahrenszuordnungen Gel�scht: ".$anzVerfahrenAlt;
    echo "<br>alte Verfahrenszuordnungen Gel�scht: ".$anzVerfahrenAlt;

    # 2.2.6.2 Eintragen aller neuen Verfahrenszuordnungen
    $ret=$this->database->insertNewVerfahren();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen neuer Verfahrenszuordnungen';
      $errmsg.=' alb.php, insertNewVerfahren line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzVerfahrenNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Verfahrenszuordnungen Neu: ".$anzVerfahrenNeu;
    echo "<br>Verfahrenszuordnungen Neu: ".$anzVerfahrenNeu;

    # 2.2.7 Aktualisieren der Baulastenzuordnungen
    # 2.2.7.1 L�schen der Eintr�ge in f_Baulasten, dessen FlurstKennz in x_f_Baulasten vorhanden sind
    $ret=$this->database->deleteOldBaulasten($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen unaktueller Baulastenzuordnungen';
      $errmsg.=' alb.php, deleteOldBaulasten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzBaulastenAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Baulastenzuordnungen Gel�scht: ".$anzBaulastenAlt;
    echo "<br>alte Baulastenzuordnungen Gel�scht: ".$anzBaulastenAlt;

    # 2.2.7.2 Eintragen aller neuen Baulastenzuordnungen
    $ret=$this->database->insertNewBaulasten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen neuer Baulastenzuordnungen';
      $errmsg.=' alb.php, insertNewBaulasten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzBaulastenNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Baulastenzuordnungen Neu: ".$anzBaulastenNeu;
    echo "<br>Baulastenzuordnungen Neu: ".$anzBaulastenNeu;

    # 2.2.8 Aktualisieren der Hinweisezuordnungen
    # 2.2.8.1 L�schen der Eintr�ge in f_Hinweise, dessen FlurstKennz in x_f_Hinweise vorhanden sind
    $ret=$this->database->deleteOldHinweise($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen unaktueller Hinweisezuordnungen';
      $errmsg.=' alb.php, deleteOldHinweise line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzHinweiseAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Hinweisezuordnungen Gel�scht: ".$anzHinweiseAlt;
    echo "<br>alte Hinweisezuordnungen Gel�scht: ".$anzHinweiseAlt;

    # 2.2.8.2 Eintragen aller neuen Hinweisezuordnungen
    $ret=$this->database->insertNewHinweise();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen neuer Hinweisezuordnungen';
      $errmsg.=' alb.php, insertNewHinweise line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzHinweiseNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Hinweisezuordnungen Neu: ".$anzHinweiseNeu;
    echo "<br>Hinweisezuordnungen Neu: ".$anzHinweiseNeu;

    # 2.2.9 Aktualisieren der Klassifizierungenzuordnungen
    # 2.2.9.1 L�schen der Eintr�ge in f_Klassifizierungen, dessen FlurstKennz in x_f_Klassifizierungen vorhanden sind
    $ret=$this->database->deleteOldKlassifizierungen($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen unaktueller Klassifizierungenzuordnungen';
      $errmsg.=' alb.php, deleteOldKlassifizierungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzKlassifizierungenAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Klassifizierungenzuordnungen Gel�scht: ".$anzKlassifizierungenAlt;
    echo "<br>alte Klassifizierungenzuordnungen Gel�scht: ".$anzKlassifizierungenAlt;

    # 2.2.9.2 Eintragen aller neuen Klassifizierungenzuordnungen
    $ret=$this->database->insertNewKlassifizierungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen neuer Klassifizierungenzuordnungen';
      $errmsg.=' alb.php, insertNewKlassifizierungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzKlassifizierungenNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Klassifizierungenzuordnungen Neu: ".$anzKlassifizierungenNeu;
    echo "<br>Klassifizierungenzuordnungen Neu: ".$anzKlassifizierungenNeu;

    # 2.2.10 Aktualisieren der Textezuordnungen
    # 2.2.10.1 L�schen der Eintr�ge in f_Texte, dessen FlurstKennz in x_f_Texte vorhanden sind
    $ret=$this->database->deleteOldTexte($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen unaktueller Textezuordnungen';
      $errmsg.=' alb.php, deleteOldTexte line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzTexteAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Textezuordnungen Gel�scht: ".$anzTexteAlt;
    echo "<br>alte Textezuordnungen Gel�scht: ".$anzTexteAlt;

    # 2.2.10.2 Eintragen aller neuen Textezuordnungen
    $ret=$this->database->insertNewTexte();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen neuer Textezuordnungen';
      $errmsg.=' alb.php, insertNewTexte line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzTexteNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Textezuordnungen Neu: ".$anzTexteNeu;
    echo "<br>Textezuordnungen Neu: ".$anzTexteNeu;

    # 2.2.11 Aktualisieren der Anliegerzuordnungen
    # 2.2.11.1 L�schen der Eintr�ge in f_Anlieger, dessen FlurstKennz in x_f_Anlieger vorhanden sind
    $ret=$this->database->deleteOldAnlieger($this->historische_loeschen);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim L�schen unaktueller Anliegerzuordnungen';
      $errmsg.=' alb.php, deleteOldAnlieger line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzAnliegerAlt=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>alte Anliegerzuordnungen Gel�scht: ".$anzAnliegerAlt;
    echo "<br>alte Anliegerzuordnungen Gel�scht: ".$anzAnliegerAlt;

    # 2.2.11.2 Eintragen aller neuen Anliegerzuordnungen
    $ret=$this->database->insertNewAnlieger();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen neuer Anliegerzuordnungen';
      $errmsg.=' alb.php, insertNewAnlieger line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzAnliegerNeu=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Anliegerzuordnungen Neu: ".$anzAnliegerNeu;
    echo "<br>Anliegerzuordnungen Neu: ".$anzAnliegerNeu;

    #############################################
    # 3. Aktualisieren der Tabellen mit Entschl�sselungen
    $Ausgabe.="<br><i>Entschl�sselungen</i>";
    echo "<br><i>Entschl�sselungen</i>";

    # Aktualisierung Katasteraemter
    $ret=$this->database->getAnzKatasteraemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Katasteraemter';
      $errmsg.=' alb.php, getAnzKatasteraemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzKatasteraemterAlt=$ret[1];
    $ret=$this->database->replaceKatasteraemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Katasteraemter';
      $errmsg.=' alb.php, replaceKatasteraemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzKatasteraemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Katasteraemter nach Aktualisierung';
      $errmsg.=' alb.php, getAnzKatasteraemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzKatasteraemter=($ret[1]-$anzKatasteraemterAlt);
    $Ausgabe.="<br>Katasteraemter Ge�ndert/Neu: ".$anzKatasteraemter;
    echo "<br>Katasteraemter Ge�ndert/Neu: ".$anzKatasteraemter;

    # Aktualisierung Forstaemter
    $ret=$this->database->getAnzForstaemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Forst�mter';
      $errmsg.=' alb.php, getAnzForstaemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzForstaemterAlt=$ret[1];
    $ret=$this->database->replaceForstaemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Forst�mter';
      $errmsg.=' alb.php, replaceForstaemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzForstaemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Forst�mter nach Aktualisierung';
      $errmsg.=' alb.php, getAnzForstaemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzForstaemter=($ret[1]-$anzForstaemterAlt);
    $Ausgabe.="<br>Forst�mter Ge�ndert/Neu: ".$anzForstaemter;
    echo "<br>Forst�mter Ge�ndert/Neu: ".$anzForstaemter;

    # Aktualisierung Gemarkungen
    $ret=$this->database->getAnzGemarkungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Gemarkungen';
      $errmsg.=' alb.php, getAnzGemarkungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzGemarkungenAlt=$ret[1];
    $ret=$this->database->replaceGemarkungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Gemarkungen';
      $errmsg.=' alb.php, replaceGemarkungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzGemarkungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Gemarkungen nach Aktualisierung';
      $errmsg.=' alb.php, getAnzGemarkungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzGemarkungen=($ret[1]-$anzGemarkungenAlt);
    $Ausgabe.="<br>Gemarkungen Ge�ndert/Neu: ".$anzGemarkungen;
    echo "<br>Gemarkungen Ge�ndert/Neu: ".$anzGemarkungen;

    # Aktualisierung Grundbuchbezirke
    $ret=$this->database->getAnzGrundbuchbezirke();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Grundbuchbezirke';
      $errmsg.=' alb.php, getAnzGrundbuchbezirke line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzGrundbuchbezirkeAlt=$ret[1];
    $ret=$this->database->replaceGrundbuchbezirke();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Grundbuchbezirke';
      $errmsg.=' alb.php, replaceGrundbuchbezirke line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzGrundbuchbezirke();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Grundbuchbezirke nach Aktualisierung';
      $errmsg.=' alb.php, getAnzGrundbuchbezirke line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzGrundbuchbezirke=($ret[1]-$anzGrundbuchbezirkeAlt);
    $Ausgabe.="<br>Grundbuchbezirke Ge�ndert/Neu: ".$anzGrundbuchbezirke;
    echo "<br>Grundbuchbezirke Ge�ndert/Neu: ".$anzGrundbuchbezirke;

    # Aktualisierung Kreise
    $ret=$this->database->getAnzKreise();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Kreise';
      $errmsg.=' alb.php, getAnzKreise line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzKreiseAlt=$ret[1];
    $ret=$this->database->replaceKreise();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Kreise';
      $errmsg.=' alb.php, replaceKreise line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzKreise();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Kreise nach Aktualisierung';
      $errmsg.=' alb.php, getAnzKreise line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzKreise=($ret[1]-$anzKreiseAlt);
    $Ausgabe.="<br>Kreise Ge�ndert/Neu: ".$anzKreise;
    echo "<br>Kreise Ge�ndert/Neu: ".$anzKreise;

    # Aktualisierung Gemeinden
    $ret=$this->database->getAnzGemeinden();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Gemeinden';
      $errmsg.=' alb.php, getAnzGemeinden line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzGemeindenAlt=$ret[1];
    $ret=$this->database->replaceGemeinden();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Gemeinden';
      $errmsg.=' alb.php, replaceGemeinden line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzGemeinden();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Gemeinden nach Aktualisierung';
      $errmsg.=' alb.php, getAnzGemeinden line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzGemeinden=($ret[1]-$anzGemeindenAlt);
    $Ausgabe.="<br>Gemeinden Ge�ndert/Neu: ".$anzGemeinden;
    echo "<br>Gemeinden Ge�ndert/Neu: ".$anzGemeinden;

    # Aktualisierung Strassen
    $ret=$this->database->getAnzStrassen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Strassen';
      $errmsg.=' alb.php, getAnzStrassen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzStrassenAlt=$ret[1];
    $ret=$this->database->replaceStrassen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Strassen';
      $errmsg.=' alb.php, replaceStrassen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzStrassen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Strassen nach Aktualisierung';
      $errmsg.=' alb.php, getAnzStrassen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzStrassen=($ret[1]-$anzStrassenAlt);
    $Ausgabe.="<br>Strassen Ge�ndert/Neu: ".$anzStrassen;
    echo "<br>Strassen Ge�ndert/Neu: ".$anzStrassen;

    # Aktualisierung Amtsgerichte
    $ret=$this->database->getAnzAmtsgerichte();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Amtsgerichte';
      $errmsg.=' alb.php, getAnzAmtsgerichte line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzAmtsgerichteAlt=$ret[1];
    $ret=$this->database->replaceAmtsgerichte();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Amtsgerichte';
      $errmsg.=' alb.php, replaceAmtsgerichte line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzAmtsgerichte();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Amtsgerichte nach Aktualisierung';
      $errmsg.=' alb.php, getAnzAmtsgerichte line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzAmtsgerichte=($ret[1]-$anzAmtsgerichteAlt);
    $Ausgabe.="<br>Amtsgerichte Ge�ndert/Neu: ".$anzAmtsgerichte;
    echo "<br>Amtsgerichte Ge�ndert/Neu: ".$anzAmtsgerichte;

    # Aktualisierung Eigentuemerarten
    $ret=$this->database->getAnzEigentuemerarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Eigentuemerarten';
      $errmsg.=' alb.php, getAnzEigentuemerarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzEigentuemerartenAlt=$ret[1];
    $ret=$this->database->replaceEigentuemerarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Eigentuemerarten';
      $errmsg.=' alb.php, replaceEigentuemerarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzEigentuemerarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Eigentuemerarten nach Aktualisierung';
      $errmsg.=' alb.php, getAnzEigentuemerarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzEigentuemerarten=($ret[1]-$anzEigentuemerartenAlt);
    $Ausgabe.="<br>Eigentuemerarten Ge�ndert/Neu: ".$anzEigentuemerarten;
    echo "<br>Eigentuemerarten Ge�ndert/Neu: ".$anzEigentuemerarten;

    # Aktualisierung Buchungsarten
    $ret=$this->database->getAnzBuchungsarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Buchungsarten';
      $errmsg.=' alb.php, getAnzBuchungsarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzBuchungsartenAlt=$ret[1];
    $ret=$this->database->replaceBuchungsarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Buchungsarten';
      $errmsg.=' alb.php, replaceBuchungsarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzBuchungsarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Buchungsarten nach Aktualisierung';
      $errmsg.=' alb.php, getAnzBuchungsarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzBuchungsarten=($ret[1]-$anzBuchungsartenAlt);
    $Ausgabe.="<br>Buchungsarten Ge�ndert/Neu: ".$anzBuchungsarten;
    echo "<br>Buchungsarten Ge�ndert/Neu: ".$anzBuchungsarten;

    # Aktualisierung Finanzaemter
    $ret=$this->database->getAnzFinanzaemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Finanzaemter';
      $errmsg.=' alb.php, getAnzFinanzaemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzFinanzaemterAlt=$ret[1];
    $ret=$this->database->replaceFinanzaemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Finanzaemter';
      $errmsg.=' alb.php, replaceFinanzaemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzFinanzaemter();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Finanzaemter nach Aktualisierung';
      $errmsg.=' alb.php, getAnzFinanzaemter line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzFinanzaemter=($ret[1]-$anzFinanzaemterAlt);
    $Ausgabe.="<br>Finanzaemter Ge�ndert/Neu: ".$anzFinanzaemter;
    echo "<br>Finanzaemter Ge�ndert/Neu: ".$anzFinanzaemter;

    # Aktualisierung Hinweise
    $ret=$this->database->getAnzHinweise();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Hinweise';
      $errmsg.=' alb.php, getAnzHinweise line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzHinweiseAlt=$ret[1];
    $ret=$this->database->replaceHinweise();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Hinweise';
      $errmsg.=' alb.php, replaceHinweise line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzHinweise();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Hinweise nach Aktualisierung';
      $errmsg.=' alb.php, getAnzHinweise line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzHinweise=($ret[1]-$anzHinweiseAlt);
    $Ausgabe.="<br>Hinweise Ge�ndert/Neu: ".$anzHinweise;
    echo "<br>Hinweise Ge�ndert/Neu: ".$anzHinweise;

    # Aktualisierung Nutzungsarten
    $ret=$this->database->getAnzNutzungsarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Nutzungsarten';
      $errmsg.=' alb.php, getAnzNutzungsarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzNutzungsartenAlt=$ret[1];
    $ret=$this->database->replaceNutzungsarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Nutzungsarten';
      $errmsg.=' alb.php, replaceNutzungsarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzNutzungsarten();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Nutzungsarten nach Aktualisierung';
      $errmsg.=' alb.php, getAnzNutzungsarten line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzNutzungsarten=($ret[1]-$anzNutzungsartenAlt);
    $Ausgabe.="<br>Nutzungsarten Ge�ndert/Neu: ".$anzNutzungsarten;
    echo "<br>Nutzungsarten Ge�ndert/Neu: ".$anzNutzungsarten;

    # Aktualisierung Klassifizierungen
    $ret=$this->database->getAnzKlassifizierungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden Klassifizierungen';
      $errmsg.=' alb.php, getAnzKlassifizierungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzKlassifizierungenAlt=$ret[1];
    $ret=$this->database->replaceKlassifizierungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der Klassifizierungen';
      $errmsg.=' alb.php, replaceKlassifizierungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzKlassifizierungen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der Klassifizierungen nach Aktualisierung';
      $errmsg.=' alb.php, getAnzKlassifizierungen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzKlassifizierungen=($ret[1]-$anzKlassifizierungenAlt);
    $Ausgabe.="<br>Klassifizierungen Ge�ndert/Neu: ".$anzKlassifizierungen;
    echo "<br>Klassifizierungen Ge�ndert/Neu: ".$anzKlassifizierungen;

    # Aktualisierung AusfuehrendeStellen
    $ret=$this->database->getAnzAusfuehrendeStellen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden AusfuehrendeStellen';
      $errmsg.=' alb.php, getAnzAusfuehrendeStellen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzAusfuehrendeStellenAlt=$ret[1];
    $ret=$this->database->replaceAusfuehrendeStellen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der AusfuehrendeStellen';
      $errmsg.=' alb.php, replaceAusfuehrendeStellen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzAusfuehrendeStellen();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der AusfuehrendeStellen nach Aktualisierung';
      $errmsg.=' alb.php, getAnzAusfuehrendeStellen line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzAusfuehrendeStellen=($ret[1]-$anzAusfuehrendeStellenAlt);
    $Ausgabe.="<br>AusfuehrendeStellen Ge�ndert/Neu: ".$anzAusfuehrendeStellen;
    echo "<br>AusfuehrendeStellen Ge�ndert/Neu: ".$anzAusfuehrendeStellen;

    # Aktualisierung BemerkungenZumVerfahren
    $ret=$this->database->getAnzBemerkungenZumVerfahren();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der bestehenden BemerkungenZumVerfahren';
      $errmsg.=' alb.php, getAnzBemerkungenZumVerfahren line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzBemerkungenZumVerfahrenAlt=$ret[1];
    $ret=$this->database->replaceBemerkungenZumVerfahren();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Aktualisieren der BemerkungenZumVerfahren';
      $errmsg.=' alb.php, replaceBemerkungenZumVerfahren line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $ret=$this->database->getAnzBemerkungenZumVerfahren();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Abfragen der Anzahl der BemerkungenZumVerfahren nach Aktualisierung';
      $errmsg.=' alb.php, getAnzBemerkungenZumVerfahren line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
    $anzBemerkungenZumVerfahren=($ret[1]-$anzBemerkungenZumVerfahrenAlt);
    $Ausgabe.="<br>BemerkungenZumVerfahren Ge�ndert/Neu: ".$anzBemerkungenZumVerfahren;
    echo "<br>BemerkungenZumVerfahren Ge�ndert/Neu: ".$anzBemerkungenZumVerfahren;

    ######## Ende mit Fortf�hrung der Entschl�sselungstabellen

    $Ausgabe.='<p>Dauer: '.DATE("i",time()-$starttime)."min : ".DATE("s",time()-$starttime)."s";
    $this->Protokoll_Aktualisieren=$Ausgabe;

    # Auff�llen der Zusatztabelle z_Fluren
    $ret=$this->database->updateFluren();
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<br>Abbruch beim Einf�gen der Fluren in function GrundausstattungAnlegen in alb.php line: '.__LINE__;
      $errmsg.='<br>'.$ret[1];
      return $errmsg;
    }
    $anzFluren=$this->database->getAffectedRows($ret[1]);
    $Ausgabe.="<br>Fluren nach Fortf�hrung gesamt: ".$anzFluren;
    echo "<br>Fluren nach Fortf�hrung gesamt: ".$anzFluren;

    echo "<br>...Fortf�hrung beendet.";
  }
}
