<?
	include(LAYOUTPATH.'languages/generic_layer_editor_2_'.$this->user->rolle->language.'.php');
	$checkbox_names = '';
	$columnname = '';
	$geom_tablename = '';
	$geomtype = '';
	$dimension = '';
	$privileg = '';
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	if($this->currentform == 'document.GUI2')$size = 40;
	else $size = 61;
	$linksize = $this->user->rolle->fontsize_gle - 1;
	$select_width = ''; 
	if($layer['alias'] != '' AND $this->Stelle->useLayerAliases){
		$layer['Name'] = $layer['alias'];
	}
	$doit = false;
  $anzObj = count($layer['shape']);
  if ($anzObj > 0) {
  	$this->found = 'true';
  	$doit = true;
  }
  if($this->new_entry == true){
  	$anzObj = 1;
  	$doit = true;
  }
?>
<div id="layer" onclick="remove_calendar();">
<input type="hidden" value="" id="changed_<? echo $layer['Layer_ID']; ?>" name="changed_<? echo $layer['Layer_ID']; ?>">
<? if($this->new_entry != true AND $layer['requires'] == ''){ ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<? if($this->search == true AND !$this->user->rolle->visually_impaired AND $anzObj > 0 AND $this->formvars['printversion'] == '' AND $this->formvars['keinzurueck'] == '' AND $this->formvars['subform_link'] == ''){
				echo '<a href="javascript:currentform.go.value=\'get_last_search\';currentform.submit();" title="'.$strbackToSearch.'"><img class="hover-border" src="'.GRAPHICSPATH.'pfeil_links.gif"></a>';
			} ?>
		</td>
		<td width="99%" align="center"><h2 id="layername"><? echo $layer['Name']; ?></h2></td>
    <? if (!$this->user->rolle->visually_impaired AND $anzObj > 0) { ?>
			<td valign="top" style="padding: 0 10 0 0" class="layer_header">
				<? if($layer['template'] == '' OR $layer['template'] == 'generic_layer_editor_2.php'){ ?>
				<img onclick="checkForUnsavedChanges(event);switch_gle_view1(<? echo $layer['Layer_ID']; ?>);" title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border pointer" src="<? echo GRAPHICSPATH.'columns.png'; ?>">
				<? }else{ ?>
				<img onclick="checkForUnsavedChanges(event);switch_gle_view1(<? echo $layer['Layer_ID']; ?>);" title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border pointer" src="<? echo GRAPHICSPATH.'rows.png'; ?>">
				<? } ?>
			</td>
			<td align="right" class="layer_header">			
				<a href="javascript:scrollbottom();"><img class="hover-border" title="<? echo $strToBottom; ?>" src="<? echo GRAPHICSPATH; ?>pfeil.gif" width="11" height="11" border="0"></a>&nbsp;
			</td>
		<? } ?>
	</tr>
</table>
<? }

  if($doit == true){
		$table_id = rand(0, 100000);
		echo $layer['paging'];
?>
<table id="<? echo $table_id; ?>" border="0" cellspacing="0" cellpadding="2">
<?
	for ($k=0;$k<$anzObj;$k++) {
		$datapart = '';
		$checkbox_names .= 'check;'.$layer['attributes']['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].'|';
?>
	<tr>
	  <td>
			<img height="7" src="<? echo GRAPHICSPATH ?>leer.gif">
	    <div id="datensatz" 
			<? if($this->new_entry != true AND $this->user->rolle->querymode == 1){ ?>
			onmouseenter="if(typeof FormData !== 'undefined')highlight_object(<? echo $layer['Layer_ID']; ?>, '<? echo $layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['the_geom']].'_oid']; ?>');"
			<? } ?>
			>
	    <input type="hidden" value="" onchange="changed_<? echo $layer['Layer_ID']; ?>.value=this.value;document.GUI.gle_changed.value=this.value" name="changed_<? echo $layer['Layer_ID'].'_'.$layer['shape'][$k][$layer['maintable'].'_oid']; ?>"> 
	    <table id="dstable" class="tgle" <? if($layer['attributes']['group'][0] != ''){echo 'border="0" style="width:100%" cellpadding="6" cellspacing="0"';}else{echo 'border="1"';} ?>>
				<? if (!$this->user->rolle->visually_impaired) include(LAYOUTPATH . 'snippets/generic_layer_editor_2_layer_head.php'); ?>
        <tbody <? if($layer['attributes']['group'][0] == '')echo 'class="gle"'; ?>>
<?		$trans_oid = explode('|', $layer['shape'][$k]['lock']);
			if($layer['shape'][$k]['lock'] == 'bereits übertragen' OR $trans_oid[1] != '' AND $layer['shape'][$k][$layer['maintable'].'_oid'] == $trans_oid[1]){
				echo '<tr><td colspan="2" align="center"><span class="red">Dieser Datensatz wurde bereits übertragen und kann nicht bearbeitet werden.</span></td></tr>';
				$lock[$k] = true;
			}
						
			for($j = 0; $j < count($layer['attributes']['name']); $j++) {
				$attribute_class = (($this->new_entry == true AND $layer['attributes']['dont_use_for_new'][$j] == -1) ? 'hidden' : 'visible');
				if($layer['shape'][$k][$layer['attributes']['name'][$j]] == ''){
					#$layer['shape'][$k][$layer['attributes']['name'][$j]] = $this->formvars[$layer['Layer_ID'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j]];
				}
				if(($layer['attributes']['privileg'][$j] == '0' AND $layer['attributes']['form_element_type'][$j] == 'Auswahlfeld') OR ($layer['attributes']['form_element_type'][$j] == 'Text' AND $layer['attributes']['type'][$j] == 'not_saveable')){				# entweder ist es ein nicht speicherbares Attribut oder ein nur lesbares Auswahlfeld, dann ist es auch nicht speicherbar
					$layer['attributes']['form_element_type'][$j] .= '_not_saveable';
				}
				
				if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j-1]){		# wenn die vorige Gruppe anders ist, Tabelle beginnen
					$explosion = explode(';', $layer['attributes']['group'][$j]);
					if($explosion[1] != '')$collapsed = true;else $collapsed = false;
					$groupname = $explosion[0];
					$datapart .= '<tr>
									<td colspan="2" width="100%">
										<div style="border-bottom: 1px solid grey">
											<table width="100%" class="tgle" border="2"><tbody class="gle">
												<tr>
													<td bgcolor="'.BG_GLEATTRIBUTE.'" colspan="40">&nbsp;<a href="javascript:void(0);" onclick="toggle_group(\''.$layer['Layer_ID'].'_'.$j.'_'.$k.'\')">
														<img id="group_img'.$layer['Layer_ID'].'_'.$j.'_'.$k.'" border="0" src="'.GRAPHICSPATH.'/'; if($collapsed)$datapart .= 'plus.gif'; else $datapart .= 'minus.gif'; $datapart .= '"></a>&nbsp;&nbsp;<span class="fett">'.$groupname.'</span>
													</td>
												</tr>
											</table>
											<table width="100%" class="tgle" id="group'.$layer['Layer_ID'].'_'.$j.'_'.$k.'" '; if($collapsed)$datapart .= 'style="display:none"'; $datapart .= 'border="2"><tbody class="gle">';
				}

				if($layer['attributes']['visible'][$j]){
					if($layer['attributes']['type'][$j] != 'geometry'){
						if($layer['attributes']['privileg'][$j] != '0' AND !$lock[$k])$this->editable = $layer['Layer_ID'];
						if($layer['attributes']['alias'][$j] == '')$layer['attributes']['alias'][$j] = $layer['attributes']['name'][$j];
					
						if($layer['attributes']['arrangement'][$j] != 1){	# wenn Attribut nicht daneben -> neue Zeile beginnen
							$attributes_in_row_so_far = 1;					# Attributanzahl in dieser Zeile bis zu diesem Attribut
							$datapart .= '<tr id="tr_'.$layer['Layer_ID'].'_'.$layer['attributes']['name'][$j].'_'.$k.'" class="' . $attribute_class . '">';
						}
						else $attributes_in_row_so_far++;
						if($layer['attributes']['labeling'][$j] != 2){
							$td = '	<td class="gle-attribute-name" '; if($layer['attributes']['labeling'][$j] == 1 AND $layer['attributes']['arrangement'][$j] == 1 AND $layer['attributes']['arrangement'][$j+1] != 1)$td .= 'colspan="20" ';if($layer['attributes']['group'][0] != '' AND $layer['attributes']['arrangement'][$j] != 1)$td .= 'width="1%">';else $td.='width="1%">';
							$td.= attribute_name($layer['Layer_ID'], $layer['attributes'], $j, $k, $this->user->rolle->fontsize_gle, ($this->formvars['printversion'] == '' AND $anzObj > 1) ? true : false);
							$td.= '	</td>';
							if($nl AND $layer['attributes']['labeling'][$j] != 1)$next_line .= $td; else $datapart .= $td;
						}
						if($layer['attributes']['labeling'][$j] == 1)$nl = true;										# Attributname soll oben stehen -> alle weiteren tds für die nächste Zeile aufsammeln
					
						# Ermittlung einer geeigneten Größe für das Attribut
						if($layer['attributes']['arrangement'][$j+1] == 1 OR $layer['attributes']['arrangement'][$j] == 1){
							$b = $j+1;
							while($b < $j+4 AND $layer['attributes']['arrangement'][$b] == 1)$b++;			# 4 vorwärts gucken
							$attributes_in_row = $attributes_in_row_so_far + $b - $j -1;			# Anzahl der bisherigen Attribute in der Zeile dazurechnen
							$size_backup = $size;
							$size2 = $size/$attributes_in_row;
							$sw = 20*$size2;
							$select_width2 = 'max-width: '.$sw.'px;';
						}
						else{
							$size2 = $size;
							$select_width2 = $select_width;
						}
						if ($select_width2 == '') $select_width2 = 'max-width: 600px;';

						$td = '	<td
							id="row' . $j . '"
							colspan="' . ($layer['attributes']['arrangement'][$j+1] != 1 ? 20 - $attributes_in_row_so_far : '') . '"' .
							get_td_class_or_style(array($layer['shape'][$k][$layer['attributes']['style']], 'gle_attribute_value')) . '
						>';
						$td.=	attribute_value($this, $layer, NULL, $j, $k, NULL, $size2, $select_width2, $this->user->rolle->fontsize_gle);
						$td.= '<div onmousedown="resizestart(document.getElementById(\'row'.$j.'\'), \'col_resize\');" style="position: absolute; transform: translate(4px); top: 0px; right: 0px; height: 20px; width: 6px; cursor: e-resize;"></div>';
						$td.= '	</td>';
						if($nl)$next_line .= $td; else $datapart .= $td;
						if($layer['attributes']['arrangement'][$j+1] != 1)$datapart .= '</tr>';						# wenn nächstes Attribut nicht daneben -> Zeile abschliessen
						if($layer['attributes']['arrangement'][$j+1] != 1 AND $nl){												# die aufgesammelten tds in neuer Zeile ausgeben
							$datapart .= '<tr>'.$next_line.'</tr>';
							$next_line = '';
							$nl = false;
						}
					
						if($layer['attributes']['privileg'][$j] >= '0'){
							$this->form_field_names .= $layer['Layer_ID'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';'.$layer['attributes']['form_element_type'][$j].';'.$layer['attributes']['nullable'][$j].';'.$layer['attributes']['type'][$j].'|';
						}
					}
					else {
						$columnname = $layer['attributes']['real_name'][$layer['attributes']['name'][$j]];
						$geom_tablename = $layer['attributes']['table_name'][$layer['attributes']['name'][$j]];
						$geomtype = $layer['attributes']['geomtype'][$layer['attributes']['name'][$j]];
						# Frage den Geometrietyp aus der Layerdefinition, wenn in geometry_columns nur als Geometry definiert.
						if ($geomtype == 'GEOMETRY' OR empty($geomtype)) {
							$geomtypes = array('POINT', 'LINESTRING', 'POLYGON');
							$geomtype = $geomtypes[$layer['Datentyp']];
							?><input type="hidden" name="Datentyp" value="<?php echo $layer['Datentyp']; ?>"><?php
						}
						$dimension = $layer['attributes']['dimension'][$j];
						$privileg = $layer['attributes']['privileg'][$j];
						$nullable = $layer['attributes']['nullable'][$j];
						$this->form_field_names .= $layer['Layer_ID'].';'.$layer['attributes']['real_name'][$layer['attributes']['name'][$j]].';'.$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].';'.$layer['shape'][$k][$layer['attributes']['table_name'][$layer['attributes']['name'][$j]].'_oid'].';Geometrie;'.$layer['attributes']['nullable'][$j].'|';
					}
				}
				else{
					$invisible_attributes[$layer['Layer_ID']][] = '<input type="hidden" id="'.$layer['Layer_ID'].'_'.$layer['attributes']['name'][$j].'_'.$k.'" value="'.htmlspecialchars($layer['shape'][$k][$layer['attributes']['name'][$j]]).'">';
				}
				if($layer['attributes']['group'][$j] != $layer['attributes']['group'][$j+1]){		# wenn die nächste Gruppe anders ist, Tabelle schliessen
					$datapart .= '</table></div></td></tr>';
				}
			}
			if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY')$geomtype = 'Polygon';
			elseif($geomtype == 'POINT')$geomtype = 'Point';
			elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING')$geomtype = 'Line';
			
			if($this->new_entry != true)echo $datapart;
				
				if(($columnname != '' OR $layer['shape'][$k]['wfs_geom'] != '') AND $this->new_entry != true AND $this->formvars['printversion'] == ''){
					if($layer['attributes']['group'][0] != ''){ ?>
						<tr><td colspan="2"><table width="100%" class="tgle" border="2" cellpadding="0" cellspacing="0"><tbody class="gle">
					<? } ?>
				 
					<tr>
						<? if($layer['querymaps'][$k] != ''){ ?>
						<td <? if($layer['attributes']['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;" align="center"><img style="border:1px solid grey" src="<? echo $layer['querymaps'][$k]; ?>"></td>
						<? } else { ?>
			    	    <td <? if($layer['attributes']['group'][0] != '')echo 'width="200px"'; ?> bgcolor="<? echo BG_GLEATTRIBUTE; ?>" style="padding-top:5px; padding-bottom:5px;">&nbsp;</td>
			    	    <? } ?>
			    	    <td class="button_background" style="box-shadow: none; padding: 5px;" valign="middle" colspan="19">
<?						
							if(!$layer['shape'][$k]['wfs_geom']){		// kein WFS 
								echo '<input type="hidden" id="'.$layer['Layer_ID'].'_'.$columnname.'_'.$k.'" value="'.$layer['shape'][$k][$columnname].'">';						
?>								
								<table cellspacing="0" cellpadding="0">
									<tr>
<?								if($privileg == 1 AND !$lock[$k]) { ?>
										<td><a onclick="checkForUnsavedChanges(event);" title="<? echo $strEditGeom; ?>" href="index.php?go=<? echo $geomtype; ?>Editor&oid=<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>&selected_layer_id=<? echo $layer['Layer_ID'];?>&dimension=<? echo $dimension; ?>"><div class="button edit_geom"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
<?								} 
								if($layer['shape'][$k][$layer['attributes']['the_geom']]){ ?>
										<td><a title="<? echo $strMapZoom; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'zoomonly');"><div class="button zoom_normal"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<? if($layer['Layer_ID'] > 0){ ?>
										<td><a title="<? echo $strMapZoom.$strAndHighlight; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'false');"><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
										<td><a title="<? echo $strMapZoom.$strAndHide; ?>" href="javascript:zoom2object(<? echo $layer['Layer_ID'];?>, '<? echo $geomtype; ?>', '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>', '<?php echo $layer['shape'][$k][$geom_tablename.'_oid']; ?>', 'true');"><div class="button zoom_select"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<? }} ?>
									</tr>
								</table>
<?							
						}
						else{		# bei WFS-Layern
?>						<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="padding: 0 0 0 5;"><a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:zoom2wkt('<? echo $layer['shape'][$k]['wfs_geom']; ?>', '<? echo $layer['epsg_code']; ?>');"><div class="button zoom_normal"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								</tr>
							</table>
<?															
						}							
?>								
							</td>
			    </tr>
			    
			    <? if($layer['attributes']['group'][0] != ''){ ?>
								</table></td></tr>
					<? }		    
	}

				if($this->new_entry == true){
					if($privileg == 1){
						if(!$this->user->rolle->geom_edit_first)echo $datapart.'</table><table class="tgle" border="0" cellspacing="0" cellpadding="2">';
						if($nullable === '0'){ ?>
							<script type="text/javascript">
    						geom_not_null = true;
    					</script>
<?					}
						$this->titel=$strTitleGeometryEditor;
						echo '
						<tr>
							<td colspan="2" align="center">';
								include(LAYOUTPATH.'snippets/'.$geomtype.'Editor.php');
						echo'
							</td>
						</tr>';						
						if($this->user->rolle->geom_edit_first)echo '</table><table class="tgle" border="0" cellspacing="0" cellpadding="2" style="width:100%"><tbody class="gle">'.$datapart;
					}
					else echo $datapart;
				}
 ?>
			</tbody>
				<? if ($this->user->rolle->visually_impaired) include(LAYOUTPATH . 'snippets/generic_layer_editor_2_layer_head.php'); ?>
			</table>
			</div>
			<img height="7" src="<? echo GRAPHICSPATH ?>leer.gif">
		</td>
	</tr>
<?
	}
	if($this->formvars['printversion'] == ''){
?>
	<tr id="dataset_operations">
		<td colspan="2"align="left">
		<? if($layer['connectiontype'] == 6 AND $this->new_entry != true AND $layer['Layer_ID'] > 0){ ?>
			<table width="100%" border="0" cellspacing="4" cellpadding="0">
				<tr>
					<td colspan="2">
						<i><? echo $layer['Name'] ?></i>:&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:selectall(<? echo $layer['Layer_ID']; ?>);">
						<? if ($layer['count'] > MAXQUERYROWS) {
						    echo $strSelectAllShown;
						   } else {
						    echo $strSelectAll;
						   } ?>
						</a>
					</td>
				</tr>
				<tr>
					<? if($layer['export_privileg'] != 0){ ?>
					<td style="padding: 5 0 0 0;">
						<select id="all_<? echo $layer['Layer_ID']; ?>" name="all_<? echo $layer['Layer_ID']; ?>" onchange="update_buttons(this.value, <? echo $layer['Layer_ID']; ?>);">
							<option value=""><? echo $strSelectedDatasets.':'; ?></option>
							<option value="true"><? echo $strAllDatasets.':'; ?><? if ($layer['count'] > MAXQUERYROWS){	echo "&nbsp;(".$layer['count'].")"; } ?></option>
						</select>
					</td>					
					<? }else{ ?>
					<td style="padding: 5 0 0 0;"><? echo $strSelectedDatasets.':'; ?></td>
					<? } ?>
				</tr>
				<tr>
					<td>
						<table cellspacing="0" cellpadding="0" class="button_background" style="box-shadow: none; border: 1px solid #bbb">
							<tr>
						<? if($this->formvars['go'] == 'Zwischenablage' OR $this->formvars['go'] == 'gemerkte_Datensaetze_anzeigen'){ ?>
								<td><a title="<? echo $strDontRememberDataset; ?>" href="javascript:remove_from_clipboard(<? echo $layer['Layer_ID']; ?>);"><div class="button nicht_mehr_merken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
							<? }else{ ?>
								<td id="merk_link_<? echo $layer['Layer_ID']; ?>"><a title="<? echo $strRemember; ?>" href="javascript:add_to_clipboard(<? echo $layer['Layer_ID']; ?>);"><div class="button merken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
							<? } ?>
					<? if($layer['privileg'] == '2'){ ?>
								<td id="delete_link_<? echo $layer['Layer_ID']; ?>"><a title="<? echo $strdelete; ?>" href="javascript:delete_datasets(<?php echo $layer['Layer_ID']; ?>);"><div class="button datensatz_loeschen"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></td>
					<?} if($layer['export_privileg'] != 0){ ?>
								<td><a title="<? echo $strExport; ?>" href="javascript:daten_export(<?php echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);"><div class="button datensatz_exportieren"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
					<? } if($layer['layouts']){ ?>
								<td id="print_link_<? echo $layer['Layer_ID']; ?>"><a title="<? echo $strPrint; ?>" href="javascript:print_data(<?php echo $layer['Layer_ID']; ?>);"><div class="button drucken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
					<? } ?>
					<? if($privileg != ''){ ?>
								<td id="zoom_link_<? echo $layer['Layer_ID']; ?>" style="padding: 0 0 0 15px"><a title="<? echo $strzoomtodatasets; ?>" href="javascript:zoomto_datasets(<?php echo $layer['Layer_ID']; ?>, '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>');"><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
								<td id="classify_link_<? echo $layer['Layer_ID']; ?>" style="padding: 0 5px 0 0">
									<select style="width: 130px" name="klass_<?php echo $layer['Layer_ID']; ?>">
										<option value=""><? echo $strClassify; ?>:</option>
										<?
										for($j = 0; $j < count($layer['attributes']['name']); $j++){
											if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
												echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
					<?}?>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="display:none">
					<td height="23" colspan="3">
						&nbsp;&nbsp;&bull;&nbsp;<a style="font-size: <? echo $this->user->rolle->fontsize_gle; ?>px" href="javascript:showcharts(<?php echo $layer['Layer_ID']; ?>);"><? echo $strCreateChart; ?></a>
					</td>
				</tr>
				<tr id="charts_<?php echo $layer['Layer_ID']; ?>" style="display:none">
					<td></td>
					<td>
						<table>
							<tr>
								<td colspan="2">
									&nbsp;&nbsp;<select name="charttype_<?php echo $layer['Layer_ID']; ?>" onchange="change_charttype(<?php echo $layer['Layer_ID']; ?>);">
										<option value="bar">Balkendiagramm</option>
										<option value="mirrorbar">doppeltes Balkendiagramm</option>
										<option value="circle">Kreisdiagramm</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Beschriftung:
								</td>
								<td>
									<select style="width:133px" id="" name="chartlabel_<?php echo $layer['Layer_ID']; ?>" >
										<?
										for($j = 0; $j < count($layer['attributes']['name']); $j++){
											if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
												echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;Wert:
								</td>
								<td>
									<select style="width:133px" name="chartvalue_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
										<option value="">--- Bitte Wählen ---</option>
										<?
										for($j = 0; $j < count($layer['attributes']['name']); $j++){
											if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
												echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr id="split_<?php echo $layer['Layer_ID']; ?>" style="display:none">
								<td>
									&nbsp;&nbsp;Trenn-Attribut:
								</td>
								<td>
									<select style="width:133px" name="chartsplit_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
										<option value="">--- Bitte Wählen ---</option>
										<?
										for($j = 0; $j < count($layer['attributes']['name']); $j++){
											if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
												echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<?} ?>
		</td>
	</tr>
	<? } ?>
</table>

<?
	
	for($l = 0; $l < count($invisible_attributes[$layer['Layer_ID']]); $l++){
		echo $invisible_attributes[$layer['Layer_ID']][$l]."\n";
	}
	
?>

<script type="text/javascript">
	var vchangers = document.getElementById(<? echo $table_id; ?>).querySelectorAll('.visibility_changer');
	[].forEach.call(vchangers, function(vchanger){vchanger.oninput();});
</script>

<input type="hidden" name="checkbox_names_<? echo $layer['Layer_ID']; ?>" value="<? echo $checkbox_names; ?>">
<input type="hidden" name="orderby<? echo $layer['Layer_ID']; ?>" id="orderby<? echo $layer['Layer_ID']; ?>" value="<? echo $this->formvars['orderby'.$layer['Layer_ID']]; ?>">

<?
  }
  elseif($layer['requires'] == '' AND $layer['required'] == ''){
?>
<table border="0" cellspacing="10" cellpadding="2">
  <tr>
		<td>
				<span style="color:#FF0000;"><? echo $strNoMatch; ?></span>
		</td>
  </tr>
	<? if($layer['privileg'] > 0){ ?>
	<tr align="center">
		<td><a href="index.php?go=neuer_Layer_Datensatz&selected_layer_id=<? echo $layer['Layer_ID']; ?>"><? echo $strNewDataset; ?></a></td>
	</tr>
	<? } ?>
</table>

<?
  }
?>
</div>
