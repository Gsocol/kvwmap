<?php 
$tab1_id="wasserentnahmebenutzer_aufforderung_zur_erklaerung";
$tab1_name="Aufforderung zur Erklärung";
$tab1_active=false;
$tab1_visible=true;
$tab2_id="wasserentnahmebenutzer_entgeltbescheid";
$tab2_name="Entgeltbescheid";
$tab2_active=true;
$tab2_visible=true;
include_once ('includes/header.php');

$gesamtEntnahmemenge = 0;
$gesamtEntgelt = 0;

if($_SERVER ["REQUEST_METHOD"] == "POST")
{
//     print_r($_POST);
    
    $entgeltbescheid_erstellen = htmlspecialchars($_POST["entgeltscheid_erstellen"]);
    $auswahl_checkbox_array = $_POST["auswahl_checkbox"];
    if(!empty($entgeltbescheid_erstellen) && !empty($auswahl_checkbox_array) && is_array($auswahl_checkbox_array))
    {
        $wrzs = array();
        
        foreach ($auswahl_checkbox_array as $auswahl_checkbox) 
        {
            if(!empty($auswahl_checkbox))
            {
                $auswahl_checkbox_escaped = htmlspecialchars($auswahl_checkbox);
                
                $festsetzungWrz = new WasserrechtlicheZulassungen($this);
                $wrz = $festsetzungWrz->find_by_id($this, 'id', $auswahl_checkbox_escaped);
                // 		    var_dump($wrz);
                // 		    echo "<br />wrz id: " . $wrz->getId();
                if(!empty($wrz))
                {
                    $wrzs[] = $wrz;
                }
            }
        }
        
        $festsetzung_dokument_name = festsetzung_erstellen($this, $wrzs);
        if(!empty($festsetzung_dokument_name))
        {
            $this->add_message("notice", "Festsetzungsbescheid: '" . $festsetzung_dokument_name . "' erfolgreich erstellt!");
        }
        else
        {
            $this->add_message("error", "Der Festsetzungsbescheid konnte nicht erstellt werden!");
        }
    }
}

function festsetzung_erstellen(&$gui, &$wrzs)
{
    $gui->debug->write('*** wasserentnahmebenutzer_entgeltbescheid->festsetzung_erstellen ***', 4);
    
    if(!empty($wrzs) && count($wrzs) > 0 && !empty($wrzs[0]))
    {
        $festsetzungsSammelbescheidDaten = new FestsetzungsSammelbescheidDaten($gui);
        
        foreach ($wrzs as $wrz)
        {
            if(!empty($wrz))
            {
                //get all dependent objects
                $wrz->getDependentObjects($gui, $wrz);
                
                //alle Daten für den Festsetzungsbescheid sammeln
                if(empty($festsetzungsSammelbescheidDaten->getWrzs()))
                {
                    $festsetzungsSammelbescheidDaten->addWrz($wrz);
                }
                $festsetzungsSammelbescheidDaten->addAnlage($wrz->anlagen);
                $festsetzungsSammelbescheidDaten->addEntnahmemenge($wrz->getFestsetzungSummeEntnahmemengen());
                $festsetzungsSammelbescheidDaten->addEntgelt($wrz->getFestsetzungSummeEntgelt());
                $festsetzungsSammelbescheidDaten->addNicht_zugelassenes_entgelt($wrz->getFestsetzungSummeNichtZugelassenesEntgelt());
                $festsetzungsSammelbescheidDaten->addZugelassenes_entgelt($wrz->getFestsetzungSummeZugelassenesEntgelt());
                
                //bestehendes Festsetzungsdokument löschen, wenn vorhanden
                if($wrz->isFestsetzungFreigegeben() && !$wrz->isFestsetzungDokumentErstellt())
                {
                    if(!empty($wrz->getFestsetzungDokument()))
                    {
                        $oldFestsetzungsDocumentId = $wrz->getFestsetzungDokument();
                        $wrz->deleteFestsetzungDokument();
                        
                        $festsetzung_delete_dokument = new Dokument($gui);
                        $festsetzung_delete_dokument->deleteDocument($oldFestsetzungsDocumentId);
                    }
                }
                
                //Freitext bekommen
                $gewaesserbenutzungen = $wrz->gewaesserbenutzungen;
                foreach ($gewaesserbenutzungen as $gewaesserbenutzung)
                {
                    if(!empty($gewaesserbenutzung))
                    {
                        if(!empty($gewaesserbenutzung->gewaesserbenutzungUmfang) && empty($festsetzungsSammelbescheidDaten->getErlaubterUmfang()))
                        {
                            $erlaubterUmfang = $gewaesserbenutzung->gewaesserbenutzungUmfang->getErlaubterUmfang();
                            $festsetzungsSammelbescheidDaten->setErlaubterUmfang($erlaubterUmfang);
                        }
                        
                        $teilgewasserbenutzung = null;
                        if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0)
                        {
                            $teilgewasserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[0];
                        }
                        
                        if(!empty($teilgewasserbenutzung))
                        {
                            if(empty($festsetzungsSammelbescheidDaten->getFreitext()))
                            {
                                $festsetzungsSammelbescheidDaten->setFreitext($teilgewasserbenutzung->getFreitext());
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        //write the document path to the database
        $word_file_name = festsetzung_dokument_erstellen($gui, $festsetzungsSammelbescheidDaten);
        if(!empty($word_file_name))
        {
            $festsetzung_dokument = new Dokument($gui);
            $festsetzung_dokument_name = 'FestsetzungBescheid_' . basename($word_file_name, '.docx');
            $festsetzung_dokument_identifier = $festsetzung_dokument->createDocument($festsetzung_dokument_name, $word_file_name);
            
            foreach ($wrzs as $wrz)
            {
                $wrz->insertFestsetzungDokument($festsetzung_dokument_identifier);
            }
            
            return $festsetzung_dokument_name;
        }
    }
    
    return null;
}

/**
 * Fesetzungsbescheid erstellen
 */
function festsetzung_dokument_erstellen(&$gui, &$festsetzungsSammelbescheidDaten)
{
    $gui->debug->write('*** wasserentnahmebenutzer_entgeltbescheid->festsetzung_dokument_erstellen ***', 4);
    $festsetzungsSammelbescheidDaten->toString();
    $gui->debug->write('festsetzungsSammelbescheidDaten->isValid(): ' . var_export($festsetzungsSammelbescheidDaten->isValid(), true), 4);
    
    if($festsetzungsSammelbescheidDaten->isValid())
    {
        $wrz = $festsetzungsSammelbescheidDaten->getWrzs()[0];
        
        //get a unique word file name
        $uniqid = uniqid();
        $word_file_name = $uniqid . ".docx";
        $word_file = WASSERRECHT_DOCUMENT_PATH . $word_file_name;
        $gui->debug->write('word_file_name: ' . $word_file_name, 4);
        
        //get the parameter
        $datum = date("d.m.Y");
        $erhebungsjahr = htmlspecialchars($_REQUEST['erhebungsjahr']);
        
        $bearbeiter = $gui->user->Name . ' ' . $gui->user->Vorname;
        $bearbeiter_telefon = $gui->user->phon;
        $bearbeiter_email = $gui->user->email;
        if(!empty($wrz->behoerde))
        {
            $behoerde_name = $wrz->behoerde->getName();
            
            if(!empty($wrz->behoerde->adresse))
            {
                $bearbeiter_plz = $wrz->behoerde->adresse->getPLZ();
                $bearbeiter_ort = $wrz->behoerde->adresse->getOrt();
                
                $behoerde_strasse = $wrz->behoerde->adresse->getStrasse();
                $behoerde_hausnummer = $wrz->behoerde->adresse->getHausnummer();
                $behoerde_plz = $wrz->behoerde->adresse->getPLZ();
                $behoerde_ort = $wrz->behoerde->adresse->getOrt();
            }
            
            if(!empty($wrz->behoerde->art))
            {
                $behoerde_art_name = $wrz->behoerde->art->getName();
            }
            
            if(!empty($wrz->behoerde->konto))
            {
                $behoerde_iban = $wrz->behoerde->konto->getIBAN();
                $behoerde_bankname = $wrz->behoerde->konto->getBankname();
                $behoerde_bic = $wrz->behoerde->konto->getBIC();
            }
        }
        
        if(!empty($wrz->adressat))
        {
            $adressat_id = $wrz->adressat->getId();
            $adressat_name = $wrz->adressat->getName();
            
            if(!empty($wrz->adressat->adresse))
            {
                $adressat_strasse = $wrz->adressat->adresse->getStrasse();
                $adressat_hausnummer = $wrz->adressat->adresse->getHausnummer();
                $adressat_plz = $wrz->adressat->adresse->getPLZ();
                $adressat_ort = $wrz->adressat->adresse->getOrt();
            }
        }
        
        $erklaerung_datum = $wrz->getErklaerungDatum();
        
        $parameter = [
            "Datum" => $datum,
            "Erhebungsjahr" => $erhebungsjahr,
            "Bearbeiter" => $bearbeiter,
            "Bearbeiter_Telefon" => $bearbeiter_telefon,
            "Bearbeiter_EMail" => $bearbeiter_email,
            "Bearbeiter_PLZ" => $bearbeiter_plz,
            "Bearbeiter_Ort" => $bearbeiter_ort,
            "Adressat_ID" => $adressat_id,
            "Behoerde_Name" => $behoerde_name,
            "Behoerde_Strasse" => $behoerde_strasse,
            "Behoerde_Hnr" => $behoerde_hausnummer,
            "Behoerde_PLZ" => $behoerde_plz,
            "Behoerde_Ort" => $behoerde_ort,
            "Behoerde_Art_Name" => $behoerde_art_name,
            "Behoerde_IBAN" => $behoerde_iban,
            "Behoerde_Bankname" => $behoerde_bankname,
            "Behoerde_BIC" => $behoerde_bic,
            "Adressat_Name" => $adressat_name,
            "Adressat_Strasse" => $adressat_strasse,
            "Adressat_Hnr" => $adressat_hausnummer,
            "Adressat_PLZ" => $adressat_plz,
            "Adressat_Ort" => $adressat_ort,
            "Adressat_Ort" => $adressat_ort,
            "Erklaerung_Datum" => $erklaerung_datum,
            "Festsetzung_Freitext" => $festsetzungsSammelbescheidDaten->getFreitext()
        ];
        
        //write the word file
        writeFestsetzungsWordFile($gui, PLUGINS . 'wasserrecht/templates/Festsetzung_Sammelbescheid.docx', $word_file, $parameter, $festsetzungsSammelbescheidDaten);
        
        return $word_file_name;
    }
    
    return null;
}
?>

<div id="wasserentnahmebenutzer_entgeltbescheid" class="tabcontent" style="display: block">

	<form action="index.php" id="aufforderung_form" accept-charset="" method="POST">
	
		<?php 
		      include_once ('includes/wasserentnahmebenutzer_header.php');
		?>
    	
		<table id="wasserentnahmebenutzer_tabelle">
			<tr>
				<th>Anlage</th>
    			<th>Wasserrechtliche Zulassung</th>
    			<th>Benutzung</th>
    			<th>Aufforderung</th>
    			<th>Erklärung</th>
    			<th>Entnahmemenge</th>
    			<th>Entgelt</th>
    			<th>Festsetzung</th>
    			<th>Auswahl</th>
    			<th>Bescheid</th>
    			<th style="background-color: inherit; width: 10px"></th>
    			<th>Entgelt eingenommen</th>
    			<th>Entgelt abgeführt</th>
    		</tr>
    		<?php 
        		
        		  if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
        		  {
        		      $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
        		      
        		      //var_dump($wasserrechtlicheZulassungen);
        		      foreach($wasserrechtlicheZulassungen AS $wrz)
        		      {
        		          if(!empty($wrz) && in_array($getYear, $wrz->gueltigkeitsJahr))
        		          {
        		              if(empty($getBehoerde) || $getBehoerde === $wrz->behoerde->getId())
        		              {
        		                  if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
        		                  {
        		                      $gewaesserbenutzungen = $wrz->gewaesserbenutzungen;
        		                      
        		                      $gewaesserbenutzungen_count = 1;
        		                      if(!empty($gewaesserbenutzungen))
        		                      {
        		                          $gewaesserbenutzungen_count = count($gewaesserbenutzungen);
        		                      }
        		                      
        		                      for ($i = 0; $i < $gewaesserbenutzungen_count; $i++) 
        		                      {
        		                          $gewaesserbenutzung = $gewaesserbenutzungen[$i];
        		                          ?>
    		                          <tr>
                    		          		<td>
                    		          			<?php 
                    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_anlage_id=' . $wrz->anlagen->getId() . '&operator_anlage_id==">' . $wrz->anlagen->getName() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getBezeichnung() . '</a>';
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     if(!empty($gewaesserbenutzung))
                    		          			     {
                    		          			         echo '<a href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Gewässerbenutzungen'] . '&value_id=' . $gewaesserbenutzung->getId() . '&operator_id==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
                    		          			     }
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $wrz->getAufforderungDatumAbsendHTML();
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php
                    		          			     echo $wrz->getErklaerungDatumHTML();
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     if($wrz->isFestsetzungFreigegeben())
                    		          			     {
//                     		          			         $gewaesserbenutzung->getUmfangAllerTeilbenutzungen()
                    		          			         $entnahmemenge = $wrz->getFestsetzungSummeEntnahmemengen();
                    		          			         $gesamtEntnahmemenge = $gesamtEntnahmemenge + $entnahmemenge;
                    		          			         
                    		          			         echo $entnahmemenge;
                    		          			     }    
                                                ?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                        		          			if($wrz->isFestsetzungFreigegeben())
                        		          			{
                        		          			    $entgelt = $wrz->getFestsetzungSummeEntgelt();
                        		          			    $gesamtEntgelt = $gesamtEntgelt + $entgelt;
                        		          			    echo $entgelt;
                        		          			}
                                                ?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     if($wrz->isFestsetzungFreigegeben())
                    		          			     {?>
                    		          			     	<a href="<?php echo $this->actual_link . "?go=wasserentnahmeentgelt_festsetzung&getfestsetzung=" . $wrz->getId() ?>"><?php echo $wrz->getFestsetzungDatum(); ?></a>
                    		          			     <?php
                    		          			     }
                    		          			
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php 
                    		          			     if($wrz->isFestsetzungFreigegeben() && !$wrz->isFestsetzungDokumentErstellt())
                    		          			     {?>
                    		          			     	<input type="checkbox" name="auswahl_checkbox[]" value="<?php echo $wrz->getId(); ?>" />
                    		          			     <?php
                    		          			     }
                    		          			
                    		          			?>
                    		          		</td>
                    		          		<td>
                    		          			<?php echo $wrz->getFestsetzungDokumentDatum() ?>
                    		          		</td>
                    		          		<td style="background-color: inherit; width: 10px">
                    		          		</td>
                    		          		<td>
                    		          		</td>
                    		          		<td>
                    		          		</td>
                    		          	</tr>
                		           <?php
    		                      }
    		                  }
    		              }
    		          }
    		      }
    		  }
    		?>
    		<tr>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit">Summe:</td>
          		<td style="background-color: inherit"><input class="wasserentnahmebenutzer_entgeltbescheid_inputfield_small" type="text" id="summe_entnahmemengen" name="summe_zugelassene_entnahmemengen" readonly="readonly" value="<?php echo $gesamtEntnahmemenge === 0 ? "" : $gesamtEntnahmemenge ?>"></td>
          		<td style="background-color: inherit"><input class="wasserentnahmebenutzer_entgeltbescheid_inputfield_small" type="text" id="summe_entgelt" name="summe_entgelt" readonly="readonly" value="<?php echo $gesamtEntgelt === 0 ? "" : $gesamtEntgelt ?>"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit; width: 10px"></td>
          		<td style="background-color: inherit"></td>
          		<td style="background-color: inherit"></td>
    		</tr>
    	</table>
    	
    	<div class="wasserrecht_display_table" style="margin-top: 10px;float: right;">
    		
    		<div class="wasserrecht_display_table_row">
                <div class="wasserrecht_display_table_cell_caption">Sammelentgeltbescheid für ausgewählte Entnahmebenutzungen erstellen:</div>
                <div class="wasserrecht_display_table_cell_spacer"></div>
                <div class="wasserrecht_display_table_row_spacer"></div>
			</div>
    		 <div class="wasserrecht_display_table_row">
        		<div class="wasserrecht_display_table_cell_caption">
        			<input type="hidden" name="go" value="wasserentnahmebenutzer_entgeltbescheid">
        			<input type="submit" value="Entgeltscheid erstellen!" id="entgeltscheid_erstellen" name="entgeltscheid_erstellen" <?php echo $gesamtEntnahmemenge < 2000 ? "disabled='disabled'" : "" ?> />
        		</div>
             </div>
             
             <div class="wasserrecht_display_table_row">
		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		<div class="wasserrecht_display_table_cell_spacer"></div>
		   		<div class="wasserrecht_display_table_row_spacer"></div>
   			</div>
    	
       		  <div class="wasserrecht_display_table_row">
           			<div class="wasserrecht_display_table_cell_caption">Abgelegte Sammelbescheide</div>
    		  </div>
    		  <?php 
    			if(!empty($wrzProGueltigkeitsJahr) && !empty($wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen))
    			{
    			    $wasserrechtlicheZulassungen = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen;
    			    
    			    $dokumentIds = array();
    			    $festsetzungDokumente = array();
    			    
    			    foreach($wasserrechtlicheZulassungen AS $wrz)
    			    {
    			        if(!empty($wrz) && in_array($getYear, $wrz->gueltigkeitsJahr))
    			        {
    			            if(empty($getBehoerde) || $getBehoerde === $wrz->behoerde->getId())
    			            {
    			                if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
    			                {
    			                    $this->debug->write('dokumentIds: ' . var_export($dokumentIds, true), 4);
    			                    if($wrz->isFestsetzungDokumentErstellt())
    			                    {
    			                        if(!in_array($wrz->festsetzung_dokument->getId(), $dokumentIds))
    			                        {
    			                            $dokumentIds[] = $wrz->festsetzung_dokument->getId();
    			                            $wrz->festsetzung_dokument->addWrz_id($wrz->getId());
    			                            $festsetzungDokumente[$wrz->festsetzung_dokument->getId()] = $wrz->festsetzung_dokument;
    			                        }
    			                        else
    			                        {
    			                            $festsetzungDokumente[$wrz->festsetzung_dokument->getId()]->addWrz_id($wrz->getId());
    			                        }
    			                    }   
    			                }
    			            }
    			        }
    			    }
    			    
    			    if(count($festsetzungDokumente) > 0)
    			    {
    			        foreach ($festsetzungDokumente as $festsetzungDokument)
    			        {
    			            ?>
			                    <div class="wasserrecht_display_table_row">
                					<div class="wasserrecht_display_table_cell_caption">
                					<?php
                					   echo '<a href="' . $this->actual_link . WASSERRECHT_DOCUMENT_URL_PATH . $festsetzungDokument->getPfad() . '" target="_blank">' . $festsetzungDokument->getName() . ', WrZs: (' . $festsetzungDokument->getWrz_idsString() . ')</a>';
                					?>
                           			</div>
                				</div>
			                <?php 
    			         }
    			    }
    			}
    		?>
			
			<div class="wasserrecht_display_table_row">
		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		<div class="wasserrecht_display_table_cell_spacer"></div>
		   		<div class="wasserrecht_display_table_row_spacer"></div>
   			</div>
			
    		 <div class="wasserrecht_display_table_row">
        		<div class="wasserrecht_display_table_cell_caption">
        			<input type="submit" value="Verwaltungsaufwand beantragen!" id="verwaltungsaufwand_beantragen" name="verwaltungsaufwand_beantragen" />
        		</div>
             </div>
			
       </div>
    	
    </form>

</div>