<?php
	global $supportedLanguages;
	include(LAYOUTPATH.'languages/layer_formular_'.$this->user->rolle->language.'.php'); ?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script src="funktionen/tooltip.js" language="JavaScript"	type="text/javascript"></script>
<script type="text/javascript">
	Text[5] = ["Hilfe:","Für einen Layer lassen sich verschiedene Klassifizierungen erstellen. Klassen mit dem gleichen Eintrag im Klassen-Feld \"Klassifizierung\" gehören zu einer Klassifizierung. Welche Klassifizierung in einem Layer verwendet wird, wird über das Layer-Feld \"Klassifizierung\" festgelegt."];
	Text[6] = ["Hilfe:","Wird hier der Name einer Grafikdatei aus dem Ordner <?php echo GRAPHICSPATH; ?>custom angegeben, wird diese Grafik an Stelle der vom MapServer erzeugten Grafik in der Legende angezeigt. Außerdem kann hier die Höhe und Breite der Legendengrafik angegeben werden."];
	Text[7] = ["Hilfe:","Hier kann die Zeichenreihenfolge in der Karte und optional eine abweichende Reihenfolge für die Legende festgelegt werden."];

	function toggleAutoClassForm(){
		form = document.getElementById('autoClassForm');
		if(form.style.display == 'none')form.style.display = ''
			else form.style.display = 'none';
	}

	function updateAutoClassesForm(){
		if (document.GUI.classification_method.value == 1) {
			document.getElementById('tr_num_classes').style.display='none';
			document.getElementById('tr_color').style.display='none';
		}
		else {
			document.getElementById('tr_num_classes').style.display='';
			document.getElementById('tr_color').style.display='';
		}
	}
</script>

<style>
	.navigation{
		border-collapse: collapse; 
		width: 100%;
		min-width: 940px;
	}

	.navigation th{
		border: 1px solid <?php echo BG_DEFAULT ?>;
		border-collapse: collapse;
		width: 17%;
	}
	
	.navigation th div{
		padding: 3px;
	}
	
	.navigation th:hover{
		background-color: <?php echo BG_DEFAULT ?>;
	}
</style>

<table>
	<tr>
    <td style="">
			<span class="px17 fetter"><? echo $strLayer;?>:</span>
      <select id="selected_layer_id" style="width:250px" size="1" name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
      <option value="">--------- <?php echo $this->strPleaseSelect; ?> --------</option>
        <?
    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
    			echo '<option';
    			if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
    				echo ' selected';
    			}
    			echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
    		}
    	?>
      </select>
		</td>
  </tr>
</table>

<? if($this->formvars['selected_layer_id'] != ''){ ?>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin: 10px">
	<tr align="center"> 
		<td style="width: 100%;">
			<table cellpadding="0" cellspacing="0" class="navigation">
				<tr>
					<th class="fetter"><a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>"><div style="width: 100%"><? echo $strCommonData; ?></div></a></th>
					<th bgcolor="<?php echo BG_DEFAULT ?>" class="fetter"><? echo $strClasses; ?></th>
					<th class="fetter"><a href="index.php?go=Style_Label_Editor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>"><div style="width: 100%"><? echo $strStylesLabels; ?></div></a></th>
					<? if($this->layerdata['connectiontype'] == 6){ ?>
					<th class="fetter"><a href="index.php?go=Attributeditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>"><div style="width: 100%"><? echo $strAttributes; ?></div></a></th>
					<? } ?>
					<th class="fetter"><a href="index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&stellenzuweisung=1"><div style="width: 100%"><? echo $strStellenAsignment; ?></div></a></th>
					<? if($this->layerdata['connectiontype'] == 6){ ?>
					<th class="fetter"><a href="index.php?go=Layerattribut-Rechteverwaltung&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>"><div style="width: 100%"><? echo $strPrivileges; ?></div></a></th>
					<? } ?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="padding-top: 10px;">
		<table border="0" cellspacing="0" cellpadding="3" style="width: 100%; border:1px solid <?php echo BG_DEFAULT ?>">
			<tr>
				<td style="border-bottom:1px solid #C3C7C3">&nbsp;<?php echo $strID; ?></td>
				<td style="border-bottom:1px solid #C3C7C3">&nbsp;<?php echo $strClass; ?></td><?
				foreach($supportedLanguages as $language){
					if ($language != 'german') { ?>
						<td style="border-bottom:1px solid #C3C7C3">&nbsp;<?php echo $strClass.' '.$language; ?></td><?
					}
				} ?>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strExpression; ?></td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strText; ?></td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strClassification; ?>&nbsp;&nbsp;<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[5], Style[0], document.getElementById('TipLayer6'))" onmouseout="htm()">
						<div id="TipLayer6" style="visibility:hidden;position:absolute;z-index:1000;"></div>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strLegendGraphic; ?>&nbsp;&nbsp;<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[6], Style[0], document.getElementById('TipLayer7'))" onmouseout="htm()">
						<div id="TipLayer7" style="visibility:hidden;position:absolute;z-index:1000;"></div>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strOrder; ?>&nbsp;&nbsp;<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[7], Style[0], document.getElementById('TipLayer8'))" onmouseout="htm()">
						<div id="TipLayer8" style="visibility:hidden;right: 20px;position:absolute;z-index:1000;"></div>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><i style="padding: 6px" class="fa fa-trash" aria-hidden="true"></i></td>
	<!--			<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">ändern</td>	-->
			</tr>
			<?
			$last_classification = $this->classes[0]['classification'];
			for($i = 0; $i < count($this->classes); $i++){
				if($this->classes[$i]['classification'] != $last_classification){
					$last_classification = $this->classes[$i]['classification'];
					if($tr_color == 'gainsboro')$tr_color = '';
					else $tr_color = 'gainsboro';
				}
				echo '
			<tr style="background-color:'.$tr_color.'">
				<td style="border-bottom:1px solid #C3C7C3">
					<input type="text" size="6" name="new_class_id['.$this->classes[$i]['Class_ID'].']" value="'.$this->classes[$i]['Class_ID'].'">
				</td>'; ?>
				<td style="border-bottom:1px solid #C3C7C3">
					<input size="12" type="text" name="name[<?php echo $this->classes[$i]['Class_ID']; ?>]" value="<?php echo $this->classes[$i]['Name']; ?>">
				</td><?php
				foreach ($supportedLanguages as $language) {
					if ($language != 'german') { ?>
						<td style="border-bottom:1px solid #C3C7C3">
							<input size="12" type="text" name="name_<?php echo $language; ?>[<?php echo $this->classes[$i]['Class_ID']; ?>]" value="<?php echo $this->classes[$i]['Name_' . $language]; ?>">
						</td><?php
					}
				} ?>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<textarea name="expression[<?php echo $this->classes[$i]['Class_ID']; ?>]" cols="28" rows="3"><?php echo $this->classes[$i]['Expression']; ?></textarea>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<textarea name="text[<?php echo $this->classes[$i]['text']; ?>]" cols="18" rows="3"><?php echo $this->classes[$i]['text']; ?></textarea>
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<input type="text" name="classification[<?php echo $this->classes[$i]['Class_ID']; ?>]" size="18" value="<?php echo $this->classes[$i]['classification']; ?>">
				</td>
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<table cellpadding="0" cellspacing="2">
						<tr>
							<td colspan="4">
								<? echo $strImagefile; ?>:
								<input type="text" name="legendgraphic[<?php echo $this->classes[$i]['Class_ID']; ?>]" size="19" value="<?php echo $this->classes[$i]['legendgraphic']; ?>">
							</td>
						</tr>
						<tr>
							<td>
								<? echo $strWidth; ?>:&nbsp;
							</td>
							<td>
								<input size="1" type="text" name="legendimagewidth[<?php echo $this->classes[$i]['Class_ID']; ?>]" value="<?php echo $this->classes[$i]['legendimagewidth']; ?>">
							</td>
							<td>
								<? echo $strHeight; ?>:&nbsp;
							</td>
							<td>
								<input size="1" type="text" name="legendimageheight[<?php echo $this->classes[$i]['Class_ID']; ?>]" value="<?php echo $this->classes[$i]['legendimageheight']; ?>">
							</td>
						</tr>
					</table>
				</td>
				<td align="left" style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<table cellpadding="0" cellspacing="2">
						<tr>
							<td>
								<?php echo $strMap; ?>:&nbsp;
							</td>
							<td>
								<input size="3" type="text" name="order[<?php echo $this->classes[$i]['Class_ID']; ?>]" value="<?php echo $this->classes[$i]['drawingorder']; ?>">
							</td>
						</tr>
							<td>
								<?php echo $strLegend; ?>:&nbsp;
							</td>
							<td>
								<input size="3" type="text" name="classlegendorder[<?php echo $this->classes[$i]['Class_ID']; ?>]" value="<?php echo $this->classes[$i]['legendorder']; ?>">
							</td>
						</tr>
					</table>
				</td>				
				<td align="center" style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<? if($this->layerdata['editable']){ ?>
					<a href="javascript:Bestaetigung('index.php?go=Klasseneditor_Klasse_Löschen&class_id=<?php echo $this->classes[$i]['Class_ID']; ?>&selected_layer_id=<?php echo $this->formvars['selected_layer_id']; ?>',	'<?php echo $this->strDeleteWarningMessage; ?>');" title="<? echo $this->strDelete; ?>">
						<i style="padding: 6px" class="fa fa-trash" aria-hidden="true"></i>
					</a>
					<? } ?>
				</td>
			</tr><?php
			}
			if($this->layerdata['editable']){
			?>
			<tr>
				<td style="border-bottom:1px solid #C3C7C3" colspan="10">					
					<a href="javascript:void(0);" onclick="toggleAutoClassForm();" class="buttonlink" style="padding: 6px; line-height: 13px;" title="<? echo $strAddAutoClasses; ?>">AUTO</a>
					<a style="float: right;" href="index.php?go=Klasseneditor_Klasse_Hinzufügen&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>" title="<? echo $strAddClass; ?>">
						<i style="padding: 6px" class="fa fa-plus buttonlink" aria-hidden="true"></i>
					</a>
				</td>
			</tr>
			<tr id="autoClassForm" style="display:none">
				<td style="border-bottom:1px solid #C3C7C3" colspan="10">
					<div>
						<table>
							<tr>
								<td colspan="3" class="fett"><? echo $strAddAutoClasses; ?></td>
							</tr>
							<tr>
								<td>Methode:</td>
								<td>
									<select name="classification_method" onchange="updateAutoClassesForm();">
										<option value="1">für jeden Wert eine Klasse</option>
										<option value="2">gleiche Klassengrösse</option>
										<option value="3">gleiche Anzahl Klassenmitglieder</option>
										<!--option value="4">Clustering nach Jenk, Initialisierung mit Histogramm-Maxima</option-->
										<option value="5">Jenks-Caspall-Algorithmus</option>
									</select> <span title="Pflichtfeld">*</span>
								</td>
							</tr>
							<tr id="tr_num_classes" style="display:none">
								<td>Anzahl Klassen:</td>
								<td>
									<input type="text" name="num_classes" value="5"> <span title="Pflichtfeld">*</span>
								</td>
							</tr>
							<tr id="tr_color" style="display:none">
								<td>Farbe:</td>
								<td>
									<input type="text" name="classification_color" value="0 100 180"> <span title="Pflichtfeld">*</span>
								</td>
							</tr>
							<tr>
								<td>Attribut:</td>
								<td>
									<input type="text" name="classification_column" value=""> <span title="Pflichtfeld">*</span>
								</td>
							</tr>
							<tr>
								<td>Klassifizierung:</td>
								<td>
									<input type="text" name="classification_name" value="">
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input type="button" name="dummy" value="Klassen erzeugen" onclick="submitWithValue('GUI','go_plus','Autoklassen_Hinzufügen')">
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<? } ?>
		</table>
		</td>
		<td valign="top">
			<a style="float: right; margin-top: -50px; margin-right: 10px;" href="javascript:window.scrollTo(0, document.body.scrollHeight);"	title="nach unten">
				<i class="fa fa-arrow-down hover-border" aria-hidden="true"></i>
			</a>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr> 
		<td align="center">
			<input type="hidden" name="go_plus" id="go_plus" value="">
		<? if ($this->formvars['selected_layer_id'] > 0) { ?>
			<? if($this->layerdata['editable']){ ?>
			<input id="layer_formular_submit_button" type="button" name="dummy" value="<?php echo $strButtonSave; ?>" onclick="submitWithValue('GUI','go_plus','Speichern')">
			<?
			}
		 } ?>
		</td>
		<td valign="top">
			<a style="float: right; margin-right: 10px;" href="javascript:window.scrollTo(0, 0);"	title="nach oben">
				<i class="fa fa-arrow-up hover-border" aria-hidden="true"></i>
			</a>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>

<? } ?>

<input type="hidden" name="go" value="Klasseneditor">