<?php
	global $supportedLanguages;
	include(LAYOUTPATH.'languages/layer_formular_'.$this->user->rolle->language.'.php'); 
	include_once(CLASSPATH . 'FormObject.php');		?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script src="funktionen/tooltip.js" language="JavaScript"	type="text/javascript"></script>
<script type="text/javascript">
	Text[0] = ["Hilfe:","Wendet eine Prozessierungsanweisung für den Layer an. Die unterstützten Anweisungen hängen vom Layertyp und dem verwendeten Treiber ab. Es gibt Anweisungen für Attribute, Connection Pooling, OGR Styles und Raster. siehe Beschreibung zum Layerattribut PROCESSING unter: http://www.mapserver.org/mapfile/layer.html. Mehrere Prozessinganweisungen werden hier eingegeben getrennt durch Semikolon. z.B. CHART_SIZE=60;CHART_TYPE=pie für die Darstellung eines Tortendiagramms des Typs MS_LAYER_CHART"];
	Text[1] = ["Hilfe:","Die Haupttabelle ist diejenige der im Query-SQL-Statement abgefragten Tabellen, die die oid liefern soll.<br><br>Die Haupttabelle muss oids besitzen, diese müssen allerdings nicht im SQL angegeben werden.<br><br>Ist das Feld Haupttabelle leer, wird der Name der Haupttabelle automatisch eingetragen. Bei einer Layerdefinition über mehrere Tabellen hinweg kann es sein, dass kvwmap die falsche Tabelle als Haupttabelle auswählt. In diesem Fall kann hier händisch die gewünschte Tabelle eingetragen werden. Achtung: Wenn die Tabellennamen im Query-SQL geändert werden, muss auch der Eintrag im Feld Haupttabelle angepasst werden!"];
	Text[2] = ["Hilfe:","Das Query-SQL ist das SQL-Statement, welches für die Sachdatenabfrage verwendet wird. Es kann eine beliebige Abfrage auf Tabellen oder Sichten sein, eine WHERE-Bedingung ist aber erforderlich. Der Schemaname wird hier nicht angegeben, sondern im Feld 'Schema'"];
	Text[3] = ["Hilfe:","Das Data-Feld wird vom Mapserver für die Kartendarstellung verwendet (siehe Mapserver-Doku). Etwaige Schemanamen müssen hier angegeben werden."];
	Text[4] = ["Hilfe:","Bei Punktlayern kann durch Angabe dieses Wertes die Clusterbildung aktiviert werden. Der Wert ist der Radius in Pixeln, in dem Punktobjekte zu einem Cluster zusammengefasst werden. <br>Damit die Cluster dargestellt werden können, muss es eine Klasse mit der Expression \"('[Cluster:FeatureCount]' != '1')\" geben. Cluster:FeatureCount kann auch als Labelitem verwendet werden, um die Anzahl der Punkte pro Cluster anzuzeigen."];	
	Text[5] = ["Hilfe:","Hier muss die Spalte aus der Haupttabelle angegeben werden, mit dem die Datensätze identifiziert werden können (z.B. der Primärschlüssel oder die oid)."];

	function updateConnection(){
		if(document.getElementById('connectiontype').value == 6){
			document.getElementById('connection_div').style.display = 'none';
			document.getElementById('connection_id_div').style.display = '';
		}
		else{
			document.getElementById('connection_div').style.display = '';
			document.getElementById('connection_id_div').style.display = 'none';
		}
	}

	function testConnection() {
		if (document.getElementById('connectiontype').value == 7) {
			getCapabilitiesURL=document.getElementById('connection').value+'&service=WMS&request=GetCapabilities';		
			getMapURL = document.getElementById('connection').value+'&service=WMS&request=GetMap&srs=epsg:<?php echo $this->formvars['epsg_code']; ?>&BBOX=<?php echo $this->user->rolle->oGeorefExt->minx; ?>,<?php echo $this->user->rolle->oGeorefExt->miny; ?>,<?php echo $this->user->rolle->oGeorefExt->maxx; ?>,<?php echo $this->user->rolle->oGeorefExt->maxy; ?>&width=<?php echo $this->user->rolle->nImageWidth; ?>&height=<?php echo $this->user->rolle->nImageHeight; ?>';
			document.getElementById('test_img').src = getMapURL;
			document.getElementById('test_img').style.display='block';
			document.getElementById('test_link').href=getCapabilitiesURL;
			document.getElementById('test_link').innerHTML=getCapabilitiesURL;
		}
		else {
			getCapabilitiesURL=document.getElementById('connection').value+'&service=WFS&request=GetCapabilities';
			document.getElementById('test_link').href=getCapabilitiesURL;
			document.getElementById('test_link').innerHTML=getCapabilitiesURL;
		}
	}
	
	function toggleForm(id){
		if(id == 'stellenzuweisung'){
			document.GUI.stellenzuweisung.value = 1;
			document.getElementById('layerform').style.display = 'none';
			document.getElementById('layerform_link').style.backgroundColor = '';
			document.getElementById('layerform_link').style.color = '#888';
			document.getElementById('saveAsNewLayerButton').style.display = 'none';
		}
		else{
			document.getElementById('stellenzuweisung').style.display = 'none';
			document.getElementById('stellenzuweisung_link').style.backgroundColor = '';
			document.getElementById('stellenzuweisung_link').style.color = '#888';
			document.getElementById('saveAsNewLayerButton').style.display = 'inline-block';
		}
		document.getElementById(id).style.display = 'inline-block';
		document.getElementById(id+'_link').style.backgroundColor = '#c7d9e6';
		document.getElementById(id+'_link').style.color = '#111';
	}

	keypress_bound_ctrl_s_button_id = 'layer_formular_submit_button';
</script>

<style>
	.navigation{
		border-collapse: collapse; 
		width: 100%;
		min-width: 940px;
		background:rgb(248, 248, 249);
	}

	.navigation th{
		border: 1px solid #bbb;
		border-collapse: collapse;
		width: 17%;
	}
	
	.navigation th div{
		padding: 3px;
		padding: 9px 0 9px 0;
	}	
	
	.navigation th a{
		color: #888;
	}	
	
	.navigation th:hover{
		background-color: rgb(238, 238, 239);
		color: #666;
	}
	
	#form input[type="text"], #form select, #form textarea{
		width: 340px;
	}
		
	#stellenzuweisung{
		display: none;
		width: 100%;
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
    			echo ' value="'.$this->layerdaten['ID'][$i].'">' . $this->layerdaten['Bezeichnung'][$i] . ($this->layerdaten['alias'][$i] != '' ? ' [' . $this->layerdaten['alias'][$i] . ']' : '') . '</option>';
    		}
    	?>
      </select>
		</td>
  </tr>
</table>

<a style="float: right; margin-top: -30px; margin-right: 10px;" href="javascript:window.scrollTo(0, document.body.scrollHeight);"	title="nach unten">
	<i class="fa fa-arrow-down hover-border" aria-hidden="true"></i>
</a>

<table border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin: 10px">
	<tr align="center"> 
		<td style="width: 100%;">
			<table cellpadding="0" cellspacing="0" class="navigation">
				<tr>
					<th><a href="javascript:toggleForm('layerform');" style="color: #111;"><div style="background-color: #c7d9e6; width: 100%" id="layerform_link"><? echo $strCommonData; ?></div></a></th>
					<th><a href="index.php?go=Klasseneditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>"><div style="width: 100%"><? echo $strClasses; ?></div></a></th>
					<th><a href="index.php?go=Style_Label_Editor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>"><div style="width: 100%"><? echo $strStylesLabels; ?></div></a></th>
					<? if(in_array($this->formvars['connectiontype'], [MS_POSTGIS, MS_WFS])){ ?>
					<th><a href="index.php?go=Attributeditor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>"><div style="width: 100%"><? echo $strAttributes; ?></div></a></th>
					<? } ?>
					<th><a href="javascript:toggleForm('stellenzuweisung');"><div style="width: 100%" id="stellenzuweisung_link"><? echo $strStellenAsignment; ?></div></a></th>
					<? if(in_array($this->formvars['connectiontype'], [MS_POSTGIS, MS_WFS])){ ?>
					<th><a href="index.php?go=Layerattribut-Rechteverwaltung&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>"><div style="width: 100%"><? echo $strPrivileges; ?></div></a></th>
					<? } ?>
				</tr>
			</table>
		</td>
	</tr>	
</table>

<table id="form" border="0" cellpadding="0" cellspacing="0" style="width: 100%">
	<tr>
		<td align="center" style="padding: 10px;">
			<div id="layerform" style="width: 100%; background-color: #f8f8f9">
				<table border="0" cellspacing="0" cellpadding="3" style="width: 100%;border:1px solid #bbb">
					<tr>
						<th class="fetter" width="300px" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLayerID; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="id" type="text" value="<?php echo $this->formvars['selected_layer_id']; ?>" size="50" maxlength="11">
							<input name="old_id" type="hidden" value="<?php echo $this->formvars['selected_layer_id']; ?>">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strName; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="Name" type="text" value="<?php echo $this->formvars['Name']; ?>" size="50" maxlength="100">
						</td>
					</tr><?
					foreach($supportedLanguages as $language){
						if($language != 'german'){	?>
							<tr>
								<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strName.' '.$language; ?></th>
								<td colspan=2 style="border-bottom:1px solid #C3C7C3">
										<input name="Name_<? echo $language; ?>" type="text" value="<?php echo $this->formvars['Name_'.$language]; ?>" size="50" maxlength="100">
								</td>
							</tr><?
						}
					} ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strAlias; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="alias" type="text" value="<?php echo $this->formvars['alias']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDataType; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="Datentyp">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['Datentyp'] == '0'){echo 'selected ';} ?>value="0">MS_LAYER_POINT</option>
									<option <? if($this->formvars['Datentyp'] == 1){echo 'selected ';} ?>value="1">MS_LAYER_LINE</option>
									<option <? if($this->formvars['Datentyp'] == 2){echo 'selected ';} ?>value="2">MS_LAYER_POLYGON</option>
									<option <? if($this->formvars['Datentyp'] == 3){echo 'selected ';} ?>value="3">MS_LAYER_RASTER</option>
									<option <? if($this->formvars['Datentyp'] == 4){echo 'selected ';} ?>value="4">MS_LAYER_ANNOTATION</option>
									<option <? if($this->formvars['Datentyp'] == 5){echo 'selected ';} ?>value="5">MS_LAYER_QUERY</option>
									<option <? if($this->formvars['Datentyp'] == 6){echo 'selected ';} ?>value="6">MS_LAYER_CIRCLE</option>
									<option <? if($this->formvars['Datentyp'] == 7){echo 'selected ';} ?>value="7">MS_LAYER_TILEINDEX</option>
									<option <? if($this->formvars['Datentyp'] == 8){echo 'selected ';} ?>value="8">MS_LAYER_CHART</option>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strGroup; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="Gruppe">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<? 
									for($i = 0; $i < count($this->Groups['ID']); $i++){
										if($this->formvars['Gruppe'] == $this->Groups['ID'][$i]){
											echo '<option selected';
										}
										else{
											echo '<option';
										}
										echo ' value="'.$this->Groups['ID'][$i].'">'.$this->Groups['ID'][$i].' - '.$this->Groups['Bezeichnung'][$i].'</option>';
									}
									?>
								</select>
								</td>
						</tr>
						<tr>
							<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPath; ?></th>
							<td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
								<textarea name="pfad" cols="33" rows="4"><? echo $this->formvars['pfad'] ?></textarea>&nbsp;&nbsp;<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[2], Style[0], document.getElementById('TipLayer3'))" onmouseout="htm()">
								<div id="TipLayer3" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strData; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<textarea name="Data" cols="33" rows="4"><? echo $this->formvars['Data'] ?></textarea>&nbsp;&nbsp;<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[3], Style[0], document.getElementById('TipLayer4'))" onmouseout="htm()">
							<div id="TipLayer4" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strMaintable; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="maintable" type="text" value="<?php echo $this->formvars['maintable']; ?>" size="50" maxlength="100">&nbsp;&nbsp;<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[1], Style[0], document.getElementById('TipLayer1'))" onmouseout="htm()">
							<div id="TipLayer1" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strIdAttribute; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="oid" type="text" value="<?php echo $this->formvars['oid']; ?>" size="36" maxlength="100">&nbsp;&nbsp;<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[5], Style[0], document.getElementById('TipLayer5'))" onmouseout="htm()">
							<div id="TipLayer5" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>					
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strSchema; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="schema" type="text" value="<?php echo $this->formvars['schema']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDocument_path; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="document_path" type="text" value="<?php echo $this->formvars['document_path']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDocument_url; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="document_url" type="text" value="<?php echo $this->formvars['document_url']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<? if($this->formvars['selected_layer_id']){ ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDdlAttribute; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3"><?php
							include_once(CLASSPATH . 'LayerAttribute.php');
							$attributes = LayerAttribute::find($this, 'layer_id = ' . $this->formvars['Layer_ID']);
							echo FormObject::createSelectField(
								'ddl_attribute',
								array_map(
									function($attribute) {
										return array(
											'value' => $attribute->get('name'),
											'output' => $attribute->get('name')
										);
									},
									$attributes
								),
								$this->formvars['ddl_attribute'],
								1,
								'',
								'',
								'ddl_attribute',
								'',
								'',
								'-- Auswahl --'
							); ?>
						</td>
					</tr>
					<? } ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTileIndex; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="tileindex" type="text" value="<?php echo $this->formvars['tileindex']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTileItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="tileitem" type="text" value="<?php echo $this->formvars['tileitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelAngleItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelangleitem" type="text" value="<?php echo $this->formvars['labelangleitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelitem" type="text" value="<?php echo $this->formvars['labelitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelMaxScale; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelmaxscale" type="text" value="<?php echo $this->formvars['labelmaxscale']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelMinScale; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelminscale" type="text" value="<?php echo $this->formvars['labelminscale']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLabelRequires; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="labelrequires" type="text" value="<?php echo $this->formvars['labelrequires']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strConnectionType; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select id="connectiontype" name="connectiontype" onchange="updateConnection();">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['connectiontype'] == '0'){echo 'selected ';} ?>value="0">MS_INLINE</option>
									<option <? if($this->formvars['connectiontype'] == 1){echo 'selected ';} ?>value="1">MS_SHAPEFILE</option>
									<option <? if($this->formvars['connectiontype'] == 2){echo 'selected ';} ?>value="2">MS_TILED_SHAPEFILE</option>
									<option <? if($this->formvars['connectiontype'] == 3){echo 'selected ';} ?>value="3">MS_SDE</option>
									<option <? if($this->formvars['connectiontype'] == 4){echo 'selected ';} ?>value="4">MS_OGR</option>
									<option <? if($this->formvars['connectiontype'] == 5){echo 'selected ';} ?>value="5">MS_TILED_OGR</option>
									<option <? if($this->formvars['connectiontype'] == 6){echo 'selected ';} ?>value="6">MS_POSTGIS</option>
									<option <? if($this->formvars['connectiontype'] == 7){echo 'selected ';} ?>value="7">MS_WMS</option>
									<option <? if($this->formvars['connectiontype'] == 8){echo 'selected ' ;} ?>value="8">MS_ORACLESPATIAL</option>
									<option <? if($this->formvars['connectiontype'] == 9){echo 'selected ';} ?>value="9">MS_WFS</option>
									<option <? if($this->formvars['connectiontype'] == 10){echo 'selected ';} ?>value="10">MS_GRATICULE</option>
									<option <? if($this->formvars['connectiontype'] == 11){echo 'selected ';} ?>value="11">MS_MYGIS</option>
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strConnection; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<div id="connection_div" <? if($this->formvars['connectiontype'] == MS_POSTGIS){echo 'style="display: none"';} ?>>
								<textarea id="connection" name="connection" cols="33" rows="2"><?	echo $this->formvars['connection']; ?></textarea>
								<input
									type="button"
									onclick="testConnection();"
									value="Test"
									style="display: <? echo (in_array($this->formvars['connectiontype'], array(MS_WMS, MS_WFS)) ? 'inline' : 'none'); ?>;"
								><br>
								<img border="1" id ="test_img" src="" style="display: none;"><br>
								<a id="test_link" href="" target="_blank"></a>
							</div>
							<div id="connection_id_div" <? if($this->formvars['connectiontype'] != MS_POSTGIS){echo 'style="display: none"';} ?>>
					<? 		include_once(CLASSPATH . 'Connection.php');
								$connections = Connection::find($this);
								echo FormObject::createSelectField(
									'connection_id',
									array_map(
										function($connection) {
											return array(
												'value' => $connection->get('id'),
												'output' => $connection->get('name')
											);
										},
										$connections
									),
									$this->formvars['connection_id']
								); ?>
								<a href="index.php?go=connections_anzeigen&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>"><i class="fa fa-pencil fa_lg" style="margin-left: 5px;"></i></a>
							</div>
						</td>
					</tr>
		<? 	if($this->formvars['connectiontype'] == MS_WMS){ ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPrintConnection; ?></th>
						<td style="border-bottom:1px solid #C3C7C3">
							<textarea name="printconnection" cols="33" rows="2"><? echo $this->formvars['printconnection'] ?></textarea>
						</td>
					</tr>
		<?  } ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strClassItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="classitem" type="text" value="<?php echo $this->formvars['classitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStyleItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="styleitem" type="text" value="<?php echo $this->formvars['styleitem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strClassification; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="layer_classification" type="text" value="<?php echo $this->formvars['classification']; ?>" size="50" maxlength="50">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strFilterItem; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="filteritem" type="text" value="<?php echo $this->formvars['filteritem']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strClusterMaxdistance; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="cluster_maxdistance" type="text" value="<?php echo $this->formvars['cluster_maxdistance']; ?>" size="50" maxlength="11">&nbsp;<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[4], Style[0], document.getElementById('TipLayer5'))" onmouseout="htm()">
							<div id="TipLayer5" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTolerance; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="tolerance" type="text" value="<?php echo $this->formvars['tolerance']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strToleranceUnits; ?>*</th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="toleranceunits">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['toleranceunits'] == 'pixels'){echo 'selected';} ?> value="pixels">pixels</option>
									<option <? if($this->formvars['toleranceunits'] == 'meters'){echo 'selected';} ?> value="meters">meters</option>								
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strEpsgCode; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="epsg_code">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<? 
									foreach($this->epsg_codes as $epsg_code){
										echo '<option ';
										if($this->formvars['epsg_code'] == $epsg_code['srid'])echo 'selected ';
										echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
									}
									?>							
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strSelectionType; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="selectiontype" type="text" value="<?php echo $this->formvars['selectiontype']; ?>" size="50" maxlength="20">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strQueryMap; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="querymap">
									<option <? if($this->formvars['querymap'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
									<option <? if($this->formvars['querymap'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>								
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strProcessing; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="processing" type="text" value="<?php echo $this->formvars['processing']; ?>" size="50" maxlength="255">
							<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[0], Style[0], document.getElementById('TipLayer2'))" onmouseout="htm()">
							<div id="TipLayer2" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDescribtion; ?></th>
						<td style="border-bottom:1px solid #C3C7C3">
							<textarea name="kurzbeschreibung" cols="33" rows="2"><? echo $this->formvars['kurzbeschreibung'] ?></textarea>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDataOwner; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="datenherr" type="text" value="<?php echo $this->formvars['datenherr']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strMetaLink; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="metalink" type="text" value="<?php echo $this->formvars['metalink']; ?>" size="50" maxlength="255">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStatus; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="status" type="text" value="<?php echo $this->formvars['status']; ?>" size="50" maxlength="255">
						</td>
					</tr>
					<tr>
						<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTriggerFunction; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="trigger_function" type="text" value="<?php echo $this->formvars['trigger_function']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strSync; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="sync" type="checkbox" value="1"<?php if ($this->formvars['sync']) echo ' checked'; ?>>
							<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(['<?php echo $strHelp; ?>:',	'<?php echo $strSyncHelp; ?>'],	Style[0],	$('#TipSync')[0]);"	onmouseout="htm()">
							<div id="TipSync" style="visibility:hidden;position:absolute;z-index:1000;"></div>
						</td>
					</tr>
					<tr>
						<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strListed; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="listed" type="checkbox" value="1"<?php if ($this->formvars['listed']) echo ' checked'; ?>>
						</td>
					</tr>				
				</table>
				
				<table border="0" cellspacing="0" cellpadding="3" style="width:100%; border:1px solid #bbb">
					<tr align="center">
						<th class="fetter" bgcolor="<?php echo BG_DEFAULT ?>" style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strOWSParameter; ?></th>
					</tr>	
					<tr>
						<th class="fetter" width="300" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsSrs; ?></th>
						<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="ows_srs" type="text" value="<?php echo $this->formvars['ows_srs']; ?>" size="50" maxlength="255">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSName; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_name" type="text" value="<?php echo $this->formvars['wms_name']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSKeywordlist; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_keywordlist" type="text" value="<?php echo $this->formvars['wms_keywordlist']; ?>" size="50" maxlength="100">
						</td>
					</tr>				
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSServerVersion; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="wms_server_version">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['wms_server_version'] == '1.0.0'){echo 'selected';} ?> value="1.0.0">1.0.0</option>
									<option <? if($this->formvars['wms_server_version'] == '1.1.0'){echo 'selected';} ?> value="1.1.0">1.1.0</option>
									<option <? if($this->formvars['wms_server_version'] == '1.1.1'){echo 'selected';} ?> value="1.1.1">1.1.1</option>		 			
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSFormat; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<select name="wms_format">
									<option value=""><?php echo $this->strPleaseSelect; ?></option>
									<option <? if($this->formvars['wms_format'] == 'image/png'){echo 'selected';} ?> value="image/png">image/png</option>
									<option <? if($this->formvars['wms_format'] == 'image/jpeg'){echo 'selected';} ?> value="image/jpeg">image/jpeg</option>
									<option <? if($this->formvars['wms_format'] == 'image/gif'){echo 'selected';} ?> value="image/gif">image/gif</option>								
								</select>
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSConnectionTimeout; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_connectiontimeout" type="text" value="<?php echo $this->formvars['wms_connectiontimeout']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSAuthUsername; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_auth_username" type="text" value="<?php echo $this->formvars['wms_auth_username']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWMSAuthPassword; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wms_auth_password" type="text" value="<?php echo $this->formvars['wms_auth_password']; ?>" size="50" maxlength="100">
						</td>
					</tr>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWFS_geom; ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
								<input name="wfs_geom" type="text" value="<?php echo $this->formvars['wfs_geom']; ?>" size="50" maxlength="100">
						</td>
					</tr>
			</table>
		</div>
		
		<div id="stellenzuweisung" style="background-color: #f8f8f9;">
			<table border="0" cellspacing="0" cellpadding="3" style="width: 100%; border:1px solid #bbb">
				<tr align="center">
					<th class="fetter" bgcolor="<?php echo BG_DEFAULT ?>" style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strDefaultValues; ?></th>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTemplate; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="template" type="text" value="<?php echo $this->formvars['template']; ?>" size="50" maxlength="100">
					</td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strQueryable; ?></th>
					<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<select name="queryable">
								<option <? if($this->formvars['queryable'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
								<option <? if($this->formvars['queryable'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>								
							</select>
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strtransparency; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="transparency" type="text" value="<?php echo $this->formvars['transparency']; ?>" size="50" maxlength="3">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDrawingOrder; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="drawingorder" type="text" value="<?php echo $this->formvars['drawingorder']; ?>" size="50" maxlength="15">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLegendOrder; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="legendorder" type="text" value="<?php echo $this->formvars['legendorder']; ?>" size="50" maxlength="15">
					</td>
				</tr>				
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strminscale; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="minscale" type="text" value="<?php echo $this->formvars['minscale']; ?>" size="50" maxlength="15">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strmaxscale; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="maxscale" type="text" value="<?php echo $this->formvars['maxscale']; ?>" size="50" maxlength="15">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strsymbolscale; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="symbolscale" type="text" value="<?php echo $this->formvars['symbolscale']; ?>" size="50" maxlength="15">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $stroffsite; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="offsite" type="text" value="<?php echo $this->formvars['offsite']; ?>" size="50" maxlength="11">
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPostlabelcache; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<select name="postlabelcache">
								<option <? if($this->formvars['postlabelcache'] == '0'){echo 'selected ';} ?>value="0"><?php echo $this->strNo; ?></option>
								<option <? if($this->formvars['postlabelcache'] == 1){echo 'selected ';} ?>value="1"><?php echo $this->strYes; ?></option>								
							</select>
					</td>
				</tr>
				<tr>
					<th class="fetter" width="200" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strrequires; ?></th>
					<td width="370" colspan=2 style="border-bottom:1px solid #C3C7C3">
							<select name="requires">
								<option value="">--- Auswahl ---</option>
								<?
									for($i = 0; $i < count($this->grouplayers['ID']); $i++){
										echo '<option value="'.$this->grouplayers['ID'][$i].'" ';
										if($this->formvars['requires'] == $this->grouplayers['ID'][$i])echo 'selected="true"';
										echo ' >'.$this->grouplayers['Bezeichnung'][$i].'</option>';
									}
								?>
							</select>
					</td>
				</tr>
				<? if(count($this->formvars['selstellen']["Bezeichnung"]) > 0){ ?>
				<tr>
					<td align="center" colspan=3 style="height: 30px;border-bottom:1px solid #C3C7C3">
						<a href="javascript:document.GUI.assign_default_values.value=1;submitWithValue('GUI','go_plus','Speichern')"><? echo $strAssignDefaultValues; ?></a>
					</td>
				</tr>
				<? } ?>
			</table>
			<table border="0" cellspacing="0" cellpadding="3" style="width: 100%; border:1px solid #bbb">
				<tr align="center">
					<th class="fetter" bgcolor="<?php echo BG_DEFAULT ?>" width="670" style="border-bottom:1px solid #C3C7C3" colspan="3"><?php echo $strTasks; ?></th>
				</tr>
				<tr valign="top"> 
					<td align="right">Zugeordnete<br>
						<select name="selectedstellen" size="10" multiple>
						<? 
						for($i=0; $i < count($this->formvars['selstellen']["Bezeichnung"]); $i++){
								echo '<option value="'.$this->formvars['selstellen']["ID"][$i].'" title="'.$this->formvars['selstellen']["Bezeichnung"][$i].'">'.$this->formvars['selstellen']["Bezeichnung"][$i].'</option>';
							 }
						?>
						</select>
					</td>
					<td align="center" valign="middle" width="1"> 
						<input type="button" name="addPlaces" value="&lt;&lt;" onClick=addOptions(document.GUI.allstellen,document.GUI.selectedstellen,document.GUI.selstellen,'value')>
						<input type="button" name="substractPlaces" value="&gt;&gt;" onClick=substractOptions(document.GUI.selectedstellen,document.GUI.selstellen,'value')>
					</td>
					<td>verfügbare<br>
						<select name="allstellen" size="10" multiple>
						<? for($i=0; $i < count($this->stellen["Bezeichnung"]); $i++){
								echo '<option value="'.$this->stellen["ID"][$i].'" title="'.$this->stellen["Bezeichnung"][$i].'">'.$this->stellen["Bezeichnung"][$i].'</option>';
							 }
						?>
						</select>
					</td>
				</tr>
			</table>
		</div>
		
		
		<? if($this->formvars['selected_layer_id']){ # Klassen werden nicht angezeigt aber fürs Kopieren eines Layers im Formular benötigt ?>
		<table border="0" cellspacing="0" cellpadding="3" style="display: none; border:1px solid #bbb">
			<tr>
				<th class="fetter" bgcolor="<?php echo BG_DEFAULT ?>" style="border-bottom:1px solid #C3C7C3" colspan="10"><a name="Klassen"></a><?php echo $strClasses; ?></th>
			</tr>
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
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3"><?php echo $strDelete; ?></td>
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
				<input type="hidden" name="ID['.$this->classes[$i]['Class_ID'].']" value="'.$this->classes[$i]['Class_ID'].'">
				<td style="border-bottom:1px solid #C3C7C3">'.$this->classes[$i]['Class_ID'].'</td>'; ?>
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
				<td style="border-left:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3">
					<? if($this->formvars['editable']){ ?>
					<a href="javascript:Bestaetigung('index.php?go=Layereditor_Klasse_Löschen&class_id=<?php echo $this->classes[$i]['Class_ID']; ?>&selected_layer_id=<?php echo $this->formvars['selected_layer_id']; ?>#Klassen',	'<?php echo $this->strDeleteWarningMessage; ?>');"><?php echo $this->strDelete; ?></a>
					<? } ?>
				</td>
			</tr><?php
			} ?>
		</table>
		<?}?>		
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr> 
		<td align="center">
			<input type="hidden" name="go_plus" id="go_plus" value="">
			<?
		if ($this->formvars['selected_layer_id'] > 0) { ?>
			<? if($this->formvars['editable']){ ?>
			<input id="layer_formular_submit_button" type="button" name="dummy" value="<?php echo $strButtonSave; ?>" onclick="submitWithValue('GUI','go_plus','Speichern')">
			<?
			}
		} ?>&nbsp;<input type="button" id="saveAsNewLayerButton" name="dummy" value="<?php echo $strButtonSaveAsNewLayer; ?>" onclick="submitWithValue('GUI','go_plus','Als neuen Layer eintragen')">		 
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>

<a style="float: right;margin-top: -30px; margin-right: 10px;" href="javascript:window.scrollTo(0, 0);"	title="nach oben">
	<i class="fa fa-arrow-up hover-border" aria-hidden="true"></i>
</a>

<input type="hidden" name="stellenzuweisung" value="<? echo $this->formvars['stellenzuweisung']; ?>">
<input type="hidden" name="go" value="Layereditor">
<input type="hidden" name="assign_default_values" value="0">
<input type="hidden" name="selstellen" value="<? 
	echo $this->formvars['selstellen']["ID"][0];
	for($i=1; $i < count($this->formvars['selstellen']["Bezeichnung"]); $i++){
		echo ', '.$this->formvars['selstellen']["ID"][$i];
	}
?>">

<? if($this->formvars['stellenzuweisung'] == 1){ ?>
	<script type="text/javascript">
		toggleForm('stellenzuweisung');
	</script>
<? }?>