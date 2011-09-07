
<script language="JavaScript" type="text/javascript">
<!--
function send_selected_flurst(url, target){
	var semi = false;
	var flurstkennz = "";
	var flurstarray = document.getElementsByName("check_flurstueck");
	for(i = 0; i < flurstarray.length; i++){
  	if(flurstarray[i].checked == true){
  		if(semi == true){
    		flurstkennz += ';';
    	}
    	flurstkennz += flurstarray[i].value;
    	semi = true;
    }
  }
  url += '&FlurstKennz='+flurstkennz;
  if(target == '_blank'){
  	window.open(url, "CSVExport", "toolbar=yes,status=yes,menubar=yes,width=2000,height=2000");
  }
  else{
  	location.href=url;
 	}
}

function browser_switch(url){
	if(navigator.appName == 'Microsoft Internet Explorer'){
		send_selected_flurst(url, '_blank');
	}
	else{
		send_selected_flurst(url, '');
	}
}

-->
</script>

<h2>Flurst&uuml;cke</h2>
<table width="600" border="0" cellpadding="2" cellspacing="0">
<?php
  $sql_aktalb="SELECT (max(ffzeitraum_bis)) FROM alb_fortfuehrung;";
  $query=pg_query($sql_aktalb);
  $r_aktalb = pg_fetch_array($query);
  $aktalb = date("d.m.Y", mktime(0, 0, 0, substr($r_aktalb[0], 5, 2), substr($r_aktalb[0], 8, 2), substr($r_aktalb[0], 0, 4)));
  $sql_aktalk="SELECT (max(datumein)) FROM edbsdatei;";
  @$query=pg_query($sql_aktalk);
  @$r_aktalk = pg_fetch_array($query);
  if($r_aktalk){
  	$aktalk = date("d.m.Y", mktime(0, 0, 0, substr($r_aktalk[0], 5, 2), substr($r_aktalk[0], 8, 2), substr($r_aktalk[0], 0, 4)));
  }

  $anzObj=count($this->qlayerset[$i]['shape']);
  if ($anzObj>0) {?>
	  <span style="font-size:80%;">
	  Stand ALB vom: <?php echo $aktalb; ?><br>
	  Stand ALK vom: <?php echo $aktalk; ?><br><br></span>
	  Anzahl der gefundenen Flurst�cke: <?php echo $anzObj.'<br><br>';

  	for($j = 0; $j < count($this->qlayerset[$i]['attributes']['name']); $j++){
  		if($this->qlayerset[$i]['attributes']['privileg'][$this->qlayerset[$i]['attributes']['name'][$j]] != ''){
  			$privileg[$this->qlayerset[$i]['attributes']['name'][$j]] = true;
  			if($j > 0){ $attribute .= ';';}
  			$attribute .= $this->qlayerset[$i]['attributes']['name'][$j];
  		}
  	}

  	for ($k=0;$k<$anzObj;$k++) {
      $flurstkennz=$this->qlayerset[$i]['shape'][$k]['flurstkennz'];
      #echo '<br>'.$flurstkennz; #2005-11-30_pk
      $flst=new flurstueck($flurstkennz,$this->pgdatabase);
      $flst->readALB_Data($flurstkennz);
    #  $flst->isALK($flurstkennz);
      if ($flst->FlurstNr!='') {
      	if($k > 0){
      		$Flurstueckskennz .= ';';
      	}
      	$Flurstueckskennz .= $flurstkennz;
?><tr>
    <td valign="top">
      <table border="0" cellspacing="0" cellpadding="2">
      	<tr>
      		<td colspan="2"></td>
      		<td rowspan="2" valign="bottom"><input type="checkbox" name="check_flurstueck" value="<?php echo $flst->FlurstKennz; ?>" checked>&nbsp;ausw�hlen</td>
      	</tr>
      	<? if($privileg['flurstkennz']){ ?>
        <tr>
          <td align="right"><strong>Flurst&uuml;ck&nbsp;</strong></td>
          <td> <? echo $flst->FlurstNr; ?>&nbsp;(<?php echo $flst->FlurstKennz; ?>)</td>
        </tr>
        <? }
        	 $both = ($privileg['gemkgname'] AND $privileg['gemkgschl']);
        	 if($privileg['gemkgname'] OR $privileg['gemkgschl']){
        ?>
        <tr>
          <td align="right"><strong>Gemarkung&nbsp;</strong></td>
          <td><?php if($privileg['gemkgname']){echo $flst->GemkgName;} ?> <?php if($both){ echo '('.$flst->GemkgSchl.')';} elseif($privileg['gemkgschl']){ echo $flst->GemkgSchl;}?></td>
        </tr>
        <? }
        	 if($privileg['flurnr']){ ?>
        <tr>
          <td height="20" align="right"><strong>Flur&nbsp;</strong></td>
          <td><?php echo $flst->FlurNr; ?></td>
        </tr>
        <? }
        	 $both = ($privileg['gemeinde'] AND $privileg['gemeindename']);
        	 if($privileg['gemeinde'] OR $privileg['gemeindename']){
        ?>
        <tr>
          <td align="right"><strong>Gemeinde&nbsp;</strong></td>
          <td><?php if($privileg['gemeindename']){ echo $flst->GemeindeName;} ?> <?php if($both){ echo '('.$flst->GemeindeID.')';} elseif($privileg['gemeinde']){ echo $flst->GemeindeID;} ?></td>
        </tr>
        <? }
        	 $both = ($privileg['kreisname'] AND $privileg['kreisid']);
        	 if($privileg['kreisname'] OR $privileg['kreisid']){
        ?>
        <tr>
          <td align="right"><strong>Kreis&nbsp;</strong></td>
          <td><?php if($privileg['kreisname']){ echo $flst->KreisName;} ?> <?php if($both){echo '('.$flst->KreisID.')';} elseif($privileg['kreisid']){ echo $flst->KreisID;} ?></td>
        </tr>
				<? }
					 $both = ($privileg['finanzamtname'] AND $privileg['finanzamt']);
					 if($privileg['finanzamtname'] OR $privileg['finanzamt']){
				?>
        <tr>
          <td align="right"><strong> Finanzamt&nbsp;</strong></td>
          <td><?php if($privileg['finanzamtname']){ echo $flst->FinanzamtName;} ?> <?php if($both){echo '('.$flst->FinanzamtSchl.')';} elseif($privileg['finanzamt']){ echo $flst->FinanzamtSchl; } ?></td>
        </tr>
				<? }
					 $both = ($privileg['forstname'] AND $privileg['forstschluessel']);
					 if($privileg['forstname'] OR $privileg['forstschluessel']){
				?>
        <tr>
          <td align="right"><strong>Forstamt&nbsp;</strong></td>
          <td><?php if($privileg['forstname']){echo $flst->Forstamt['name'];} ?> <?php if($both){echo '(00'.$flst->Forstamt['schluessel'].')';} elseif($privileg['forstschluessel']){ echo '00'.$flst->Forstamt['schluessel'];} ?></td>
        </tr>
				<? }
					 if($privileg['flaeche']){ ?>
        <tr>
          <td align="right"><strong>Fl&auml;che&nbsp;</strong></td>
          <td><?php echo $flst->ALB_Flaeche; ?>m&sup2;</td>
        </tr>
				<? }
					 $both = ($privileg['amtsgerichtname'] AND $privileg['amtsgerichtnr']);
					 if($privileg['amtsgerichtname'] OR $privileg['amtsgerichtnr']){
				?>
				<tr>
          <td align="right"><b>Amtsgericht:</b>&nbsp;</td>
          <td><?php if($privileg['amtsgerichtnr']){echo $flst->Amtsgericht['schluessel'];} ?>&nbsp;&nbsp;<?php if($privileg['amtsgerichtname']){ echo $flst->Amtsgericht['name'];} ?></td>
        </tr>
        <? }
        	 $both = ($privileg['grundbuchbezirkname'] AND $privileg['grundbuchbezirkschl']);
					 if($privileg['grundbuchbezirkname'] OR $privileg['grundbuchbezirkschl']){
				?>
        <tr>
          <td align="right"><b>Grundbuchbezirk:</b>&nbsp;</td>
          <td><?php if($privileg['grundbuchbezirkschl']){ echo $flst->Grundbuchbezirk['schluessel'];} ?>&nbsp;&nbsp;<?php if($privileg['grundbuchbezirkname']){ echo $flst->Grundbuchbezirk['name'];} ?></td>
        </tr>
				<? }
					 if($privileg['lagebezeichnung']){ ?>
        <tr>
          <td align="right" valign="top"><strong>Lage:&nbsp;</strong></td>
          <td><?php
          $anzStrassen=count($flst->Adresse);
          for ($s=0;$s<$anzStrassen;$s++) {
            echo $flst->Adresse[$s]["gemeindename"]; ?><br><?php
            echo $flst->Adresse[$s]["strassenname"]; ?>&nbsp;<?php
            echo $flst->Adresse[$s]["hausnr"]; ?><br><?php
          }
          $anzLage=count($flst->Lage);
          $Lage='';
          for ($j=0;$j<$anzLage;$j++) {
            $Lage.=' '.$flst->Lage[$j];
          }
          if ($Lage!='') {
            ?><br><?php echo TRIM($Lage);
          }
          ?>
          </td>
        </tr>
				<? }
					 if($privileg['entsteh']){ ?>
        <tr>
          <td align="right"><strong>Entstehung&nbsp;</strong></td>
          <td><?php echo $flst->Entstehung; ?></td>
        </tr>
				<? }
					 if($privileg['letzff']){ ?>
        <tr>
          <td align="right"><strong>Fortf&uuml;hrung&nbsp;</strong></td>
          <td><?php echo $flst->LetzteFF; ?></td>
        </tr>
				<? }
					 if($privileg['karte']){ ?>
        <tr>
          <td align="right"><strong> Flurkarte/Ri&szlig;&nbsp;</strong></td>
          <td><?php echo $flst->Flurkarte; ?></td>
        </tr>
        <? } ?>
        <?php if ($privileg['vorgaenger'] AND $flst->Vorgaenger != '') { ?>
			  <tr>
			  	<td align="right"><strong>Vorg�nger</strong></td>
			  	<td><?php for($v = 0; $v < count($flst->Vorgaenger); $v++)
			    						echo $flst->Vorgaenger[$v]['vorgaenger'].'<br>'; ?>
			    </td>
				</tr>
				<?php } ?>

        <?
        	if($this->Stelle->isFunctionAllowed('Bauakteneinsicht')){
						$this->bau = new Bauauskunft($this->baudatabase);
						$searchvars['flurstkennz'] = $flst->FlurstKennz;
				    $this->bau->getbaudaten($searchvars);
				    if(count($this->bau->baudata) != 0){
				      ?>
				      <tr>
			          <td align="right"><strong> Baudaten&nbsp;</strong></td>
			          <td><a href="index.php?go=Bauauskunft_Suche_Suchen&flurstkennz=<? echo $flst->FlurstKennz; ?>&distinct=1">anzeigen</a></td>
			        </tr>
				      <?
				    }
					} # ende Bauakteneinsicht
        ?>

      </table></td>

  </tr>

	<?php if ($privileg['klassifizierung'] AND $flst->Klassifizierung['tabkenn']!='') { ?>
  <tr>
    <td colspan="2">
  		<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td align="right"><strong>Gesetzl.&nbsp;Klassifizierung</strong></td>
			    <td><?php echo $flst->Klassifizierung['tabkenn']; ?></td>
			    <td>&nbsp;</td>
			  </tr>

			  <tr>
			    <td align="right"><?php echo $flst->Klassifizierung['flaeche']; ?> m&sup2;</td>
			    <td><?php echo $flst->Klassifizierung['tabkenn'].'-'.$flst->Klassifizierung['klass']; ?></td>
			    <td><?php echo $flst->Klassifizierung['bezeichnung']; ?></td>
			  </tr>

			  <tr>
			    <td align="right">&nbsp;</td>
			    <td>&nbsp;</td>
			    <td><?php echo $flst->Klassifizierung['abkuerzung']; ?></td>
			  </tr>
			</table>
		</td>
	</tr>
	<?php } ?>
	<?php if ($privileg['freitext'] AND count($flst->FreiText)>0) { ?>
  <tr>
    <td colspan="2">
    	<table border="0" cellspacing="0" cellpadding="2">
  			<tr valign="top">
    			<td align="right"><strong>Zus&auml;tzliche&nbsp;Angaben</strong></td>
    			<td>
    			<?php	for ($j=0;$j<count($flst->FreiText);$j++) {
    							if ($j>0) { ?><br>
      						<?php }
    							echo $flst->FreiText[$j]['text'];
  							}?>
  				</td>
  			</tr>
			</table>
		</td>
	</tr>
	<?php } ?>
	<?php if ($privileg['hinweis'] AND $flst->Hinweis[0]['hinwzflst']!='') { ?>
  <tr>
  	<td colspan="2">
  		<table border="0" cellspacing="0" cellpadding="2">
  			<tr>
    			<td valign="top"><strong>Hinweise:</strong>&nbsp;</td>
    			<td><?php
    			for($h = 0; $h < count($flst->Hinweis); $h++){
    				echo $flst->Hinweis[$h]['bezeichnung'].'<br>';
    			}
    			?></td>
  			</tr>
			</table>
		</td>
	</tr>
	<?php } ?>
	<?php if ($privileg['baulasten'] != '' AND count($flst->Baulasten)>0) { ?>
  <tr>
    <td colspan="2">
    	<table border="0" cellspacing="0" cellpadding="2">
  			<tr>
    			<td><strong>Baulastenblatt-Nr</strong></td>
    			<td><?php
          	for ($b=0;$b<count($flst->Baulasten);$b++) {
	            echo " ".$flst->Baulasten[$b]['blattnr'];
            } ?>
          </td>
        </tr>
			</table>
		</td>
	</tr>
	<?php } ?>
	<?php
	if (($privileg['verfahren'] OR $privileg['ausfstelle']) AND $flst->Verfahren[0]['flurstkennz']!='') {
		for($j = 0; $j < count($flst->Verfahren); $j++){
	?>
  <tr>
  	<td colspan="2">
  		<table border="0" cellspacing="0" cellpadding="2">
  		<?php if ($privileg['ausfstelle']){ ?>
      	<tr valign="top">
        	<td align="right"><strong>Ausf�hrende&nbsp;Stelle</strong></td>
        	<td><?php echo $flst->Verfahren[$j]['ausfstelleid']; ?></td>
        	<td><?php echo $flst->Verfahren[$j]['ausfstellename']; ?></td>
        </tr>
      <?php }
      	if ($privileg['verfahren']){ ?>
      	<tr valign="top">
        	<td align="right"><strong>Verfahren</strong></td>
        	<td><?php echo $flst->Verfahren[$j]['verfnr']; ?></td>
        	<td>(<?php echo $flst->Verfahren[$j]['verfbemid']; ?>)&nbsp;<?php echo $flst->Verfahren[$j]['verfbemerkung']; ?></td>
      	</tr>
      <? } ?>
    	</table>
    </td>
  </tr>
  <? 	} ?>
  <? } ?>
  <?php if ($privileg['nutzung']){ ?>
  <tr>
  	<td colspan="2">
    	<table border="0" cellspacing="0" cellpadding="2">
      	<tr>
        	<td colspan="3"><strong>Nutzung</strong></td>
        </tr>
        <tr>
        	<td><b>Fl&auml;che&nbsp;</b></td>
          <td><b>Nutzung&nbsp;</b></td>
          <td><b>Bezeichnung</b></td>
        </tr>
        <?php
        $anzNutzung=count($flst->Nutzung);
        for ($j=0;$j<$anzNutzung;$j++) { ?>
        <tr>
        	<td align="right"><?php echo $flst->Nutzung[$j][flaeche]; ?> m&sup2;&nbsp;</td>
          <td align="right">&nbsp;<?php echo $flst->Nutzung[$j][nutzungskennz]; ?>&nbsp;</td>
          <td><?php echo $flst->Nutzung[$j][bezeichnung];
						if ($flst->Nutzung[$j][kurzbezeichnung]!='') { ?> (<?php echo $flst->Nutzung[$j][kurzbezeichnung]; ?>)<?php } ?>
					</td>
        </tr>
        <?php } ?>
			</table>
    </td>
	</tr>
	<? } ?>
	<? if($privileg['eigentuemer']){?>
	<tr>
		<td colspan="2">
    	<table border="0" cellspacing="0" cellpadding="2">
      	<tr>
        	<td colspan="3"><strong>Eigent�mer</strong></td>
        </tr>
   <? for ($g=0;$g<count($flst->Grundbuecher);$g++) {
	   		$flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
	      for ($b=0;$b<count($flst->Buchungen);$b++) {
	      	if($privileg['bestandsnr']){
	      		$BestandStr =$flst->Buchungen[$b]['bezirk'].'-'.intval($flst->Buchungen[$b]['blatt']);
	          $BestandStr.=' '.str_pad($flst->Buchungen[$b]['pruefzeichen'],3,' ',STR_PAD_LEFT);
	          $BestandStr.=' BVNR'.str_pad(intval($flst->Buchungen[$b]['bvnr']),4,' ',STR_PAD_LEFT);
	          $BestandStr.=' ('.$flst->Buchungen[$b]['buchungsart'].')';
	          $BestandStr.=' '.$flst->Buchungen[$b]['bezeichnung'];	?>
	      <tr>
	      	<td>Bestand</td>
	      	<td colspan="2"><? echo $BestandStr; ?></td>
	      </tr>
	     <? }
			$Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
	        $anzEigentuemer=count($Eigentuemerliste);
	        for ($e=0;$e<$anzEigentuemer;$e++) { ?>
	      <tr>
	     		<td valign="top"><? echo $Eigentuemerliste[$e]->Nr ?>&nbsp;&nbsp;&nbsp;</td>
	     		<td valign="top">
	     		<?
            	$anzNamenszeilen=count($Eigentuemerliste[$e]->Name);
            	for ($n=0;$n<$anzNamenszeilen;$n++) {
            	  echo $Eigentuemerliste[$e]->Name[$n].'<br>';
            	} ?>
				<? if($this->Stelle->isFunctionAllowed('Adressaenderungen')) {
          	 				$eigentuemer = new eigentuemer(NULL, NULL);
          	 				$adressaenderungen =	$eigentuemer->getAdressaenderungen($Eigentuemerliste[$e]->lfd_nr);
						if ($adressaenderungen['neu_name3'] != '') {
							$user = new user(NULL, $adressaenderungen['user_id'], $this->database);
				?>
         			<table border="0" cellspacing="0" cellpadding="2">
         				<tr>
         					<td colspan="2">
			    	     		<?
         						echo '<b><u>Aktualisierte Adresse ('.$adressaenderungen['datum'].' - '.$user->Name.'):</u></b>';
         						?>
         					</td>
         				</tr>
         				<tr>
         					<td>
         					<?
         						echo '<b>'.$adressaenderungen['neu_name3'].'</b><br>';
         						echo '<b>'.$adressaenderungen['neu_name4'].'</b><br>';
         						?>
         					</td>
         					<td>
         						<? echo '<img src="'.GRAPHICSPATH.'pfeil_links.gif" width="12" height="12" border="0">'; ?>&nbsp;<a target="_blank" href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo LAYER_ID_ADRESSAENDERUNGEN; ?>&value_lfd_nr=<? echo $Eigentuemerliste[$e]->lfd_nr; ?>&operator_lfd_nr==&attributenames[0]=user_id&values[0]=<? echo $this->user->id ?>">Aktualisierte Adresse &auml;ndern</a>
         					</td>
         				<tr>
         			</table>
        <?		}
        			else{
        				echo '</td><td><br><br><img src="'.GRAPHICSPATH.'pfeil_links.gif" width="12" height="12" border="0">'; ?>&nbsp;<a target="_blank" href="index.php?go=neuer_Layer_Datensatz&selected_layer_id=<? echo LAYER_ID_ADRESSAENDERUNGEN; ?>&attributenames[0]=lfd_nr&&attributenames[1]=neu_name3&&attributenames[2]=neu_name4&attributenames[3]=user_id&values[0]=<? echo $Eigentuemerliste[$e]->lfd_nr; ?>&values[1]=<? echo $Eigentuemerliste[$e]->Name[2]; ?>&values[2]=<? echo $Eigentuemerliste[$e]->Name[3]; ?>&values[3]=<? echo $this->user->id ?>">Adresse aktualisieren</a><?
        			}
        		}	?>
         	</td>
         	<? } ?>
        </tr>
       <? }
			} ?>
			</table>
		</td>
	</tr>
	<?} ?>
<?php
     } # Ende es wurde auch was zum Flurst�ck gefunden
     else {
  ?><tr>
     	<td>Das Flurst�ck mit Kennzeichen: <?php echo $flurstkennz; ?> ist nicht in der aktuellen PostGIS-Datenbank enthalten.
     	<br>Aktualisieren Sie die ALB und ALK-Daten.</td>
    </tr><?php
     } ?>
  <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
 <td colspan="2">
  <a href="index.php?go=Flurstueck_Auswaehlen&searchInExtent=<?php echo $this->searchInExtent;
  ?>&GemID=<?php echo $flst->GemeindeID;
  ?>&GemkgID=<?php echo $flst->GemkgSchl; ?>&FlurID=<?php echo $flst->FlurID;
  ?>&FlstID=<?php echo $flst->FlurstKennz; ?>">zur Flurst�ckssuche</a> |
  <a href="index.php?go=Adresse_Auswaehlen&searchInExtent=<?php echo $this->searchInExtent;
  ?>&GemID=<?php echo $this->formvars['GemID'];
  ?>&StrID=<?php echo $this->formvars['StrID'];
  ?>&HausID=<?php echo $this->formvars['HausID'];
  ?>">zur Adresssuche</a> |
  <a href="index.php?go=Namen_Auswaehlen&name1=<?php echo $this->formvars['name1'];
  ?>&name2=<?php echo $this->formvars['name2'];
  ?>&name3=<?php echo $this->formvars['name3'];
  ?>&name4=<?php echo $this->formvars['name4'];
  ?>">zur Namensuche</a> |
  <a href="index.php?go=ZoomToFlst&FlurstKennz=<?php echo $flst->FlurstKennz; ?>">Kartenausschnitt</a>
  <?php
    $this->getFunktionen();
    if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) { ?>
      | <a href="index.php?go=showFlurstuckKoordinaten&FlurstKennz=<?php echo $flst->FlurstKennz; ?>" target="_blank">Koordinaten</a>
    <?php }
  ?>
 </td>
</tr>
<tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
    <td colspan="2">
        <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=30&wz=1" target="_blank">ALB-Auszug&nbsp;30&nbsp;mit&nbsp;WZ</a>
      <?php
      if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) { ?>
        | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=35&wz=1" target="_blank">ALB-Auszug&nbsp;35&nbsp;mit&nbsp;WZ</a>
      <?php }
      if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']) { ?>
        | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=40&wz=1" target="_blank">ALB-Auszug&nbsp;40&nbsp;mit&nbsp;WZ</a>
      <?php } ?>
    </td>
  </tr>
    <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
      <td colspan="2">
        <?php
        $this->getFunktionen();
        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=30&wz=0" target="_blank">ALB-Auszug&nbsp;30&nbsp;ohne&nbsp;WZ</a>
          <?php } ?>
        <?php
        if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=35&wz=0" target="_blank">ALB-Auszug&nbsp;35&nbsp;ohne&nbsp;WZ</a>
        <?php }
        if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="index.php?go=ALB_Anzeige&FlurstKennz=<?php echo $flst->FlurstKennz; ?>&formnummer=40&wz=0" target="_blank">ALB-Auszug&nbsp;40&nbsp;ohne&nbsp;WZ</a>
        <?php } ?>
      </td>
  </tr><?php
	  } # Ende der Schleife zur Abfrage und Anzeige der Flurst�cke
	?>

	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><b>F�r alle ausgew�hlten Flurst�cke:</b><br></td>
	</tr>
	<tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
    <td colspan="2">
        <a href="javascript:send_selected_flurst('index.php?go=ALB_Anzeige&formnummer=30&wz=1', '_blank');">ALB-Auszug&nbsp;30&nbsp;mit&nbsp;WZ</a>
      <?php
      if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) { ?>
        | <a href="javascript:send_selected_flurst('index.php?go=ALB_Anzeige&formnummer=35&wz=1', '_blank');">ALB-Auszug&nbsp;35&nbsp;mit&nbsp;WZ</a>
      <?php }
      if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']) { ?>
        | <a href="javascript:send_selected_flurst('index.php?go=ALB_Anzeige&formnummer=40&wz=1', '_blank');">ALB-Auszug&nbsp;40&nbsp;mit&nbsp;WZ</a>
      <?php } ?>
    </td>
  </tr>
    <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
      <td colspan="2">
        <?php
        $this->getFunktionen();
        if ($this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          <a href="javascript:send_selected_flurst('index.php?go=ALB_Anzeige&formnummer=30&wz=0', '_blank');">ALB-Auszug&nbsp;30&nbsp;ohne&nbsp;WZ</a>
          <?php } ?>
        <?php
        if ($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="javascript:send_selected_flurst('index.php?go=ALB_Anzeige&formnummer=35&wz=0', '_blank');">ALB-Auszug&nbsp;35&nbsp;ohne&nbsp;WZ</a>
        <?php }
        if ($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt'] AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']) { ?>
          | <a href="javascript:send_selected_flurst('index.php?go=ALB_Anzeige&formnummer=40&wz=0', '_blank');">ALB-Auszug&nbsp;40&nbsp;ohne&nbsp;WZ</a>
        <?php } ?>
      </td>
  </tr>
  <tr align="center" valign="top" bgcolor="<?php echo BG_DEFAULT ?>">
  	<td colspan="2">
  		<a href="javascript:browser_switch('index.php?go=Flurstuecks-CSV-Export&attributliste=<? echo $attribute; ?>');">CSV-Export</a>&nbsp;|
  		<a href="javascript:send_selected_flurst('index.php?go=ZoomToFlst', '');">Kartenausschnitt</a>
  	</td>
  </tr>
  <tr>
		<td>&nbsp;</td>
	</tr>

	<?
  } # Ende es liegen Flurst�cke im Suchbereich
  else {
  	?><br><strong><font color="#FF0000">
	  Zu diesem Layer wurden keine Objekte gefunden!</font></strong><br>
	  W�hlen Sie einen neuen Bereich oder pr�fen Sie die Datenquellen.<br>
	  <?php
  }
#/2005-11-30_pk
?>
</table>