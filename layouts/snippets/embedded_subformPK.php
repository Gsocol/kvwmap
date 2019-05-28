<?
	include(SNIPPETS . 'generic_form_parts.php');
	# dies ist das Snippet für die SubformEmbeddedPK-Liste mit Links untereinander
	# Variablensubstitution
	$layer = $this->qlayerset[$i];
	$attributes = $layer['attributes'];

	$doit = false;
	$anzObj = count($layer['shape']);
  if ($anzObj > 0) {
		$this->found = 'true';
		$doit = true;
  }

	if ($doit == true) {
		if ($layer['template']=='generic_layer_editor_doc_raster.php') { # die Raster-Darstellung kann auch anstatt der SubFormEmbedded-Liste verwendet werden
			include(SNIPPETS.'sachdatenanzeige_embedded.php');
		}
		else { 
			if(0){		?>
				<table border="0" cellspacing="0" cellpadding="2" width="100%"><?
					for ($k=0;$k<$anzObj;$k++) {
						echo '<tr>';
						for ($j = 0; $j < count($attributes['name']); $j++) {
							if($layer['attributes']['privileg'][$j] >= '0'){
								echo '<td>'.attribute_value($this, $layer, NULL, $j, $k, NULL, $size, $select_width, $this->user->rolle->fontsize_gle, false, NULL, NULL, NULL, 'subform_'.$layer['Layer_ID']).'</td>';
							}
						}
						echo '</tr>';
					}
?>				<tr>
						<td><input type="button" value="Speichern" onclick="subsave_data(<? echo $layer['Layer_ID']; ?>, this.closest('div').id, this.closest('div').id, false);"></td>
					</tr>
				</table>
<?		}
			else{ ?>
				<table border="0" cellspacing="0" cellpadding="2" width="100%"><?
					$preview_attributes = explode(' ', $this->formvars['preview_attribute']);
					for ($k=0;$k<$anzObj;$k++) {
						$dataset = $layer['shape'][$k]; # der aktuelle Datensatz
						for ($p = 0; $p < count($preview_attributes); $p++) {
							$output[$p] = $preview_attributes[$p];
							for ($j = 0; $j < count($attributes['name']); $j++) {
								if ($preview_attributes[$p] == $attributes['name'][$j]) {
									$output[$p] = '';
									switch ($attributes['form_element_type'][$j]) {
										case 'Auswahlfeld' : {
											if (is_array($attributes['dependent_options'][$j])) {		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												for ($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++) {
													if ($attributes['enum_value'][$j][$k][$e] == $dataset[$attributes['name'][$j]]) {
														$output[$p] = $attributes['enum_output'][$j][$k][$e];
														break;
													}
												}
											}
											else {
												for ($e = 0; $e < count($attributes['enum_value'][$j]); $e++) {
													if ($attributes['enum_value'][$j][$e] == $dataset[$attributes['name'][$j]]) {
														$output[$p] = $attributes['enum_output'][$j][$e];
														break;
													}
												}
											} 
										} break;
										
										case 'Autovervollständigungsfeld' : case 'Autovervollständigungsfeld_zweispaltig' :{
											$output[$p] = $attributes['enum_output'][$j][$k];
										} break;

										case 'Dokument' : {
											if ($dataset[$attributes['name'][$j]]!='') {
												$dokumentpfad = $dataset[$attributes['name'][$j]];
												$pfadteil = explode('&original_name=', $dokumentpfad);
												$dateiname = $pfadteil[0];
												if ($layer['document_url'] != '')$dateiname = url2filepath($dateiname, $layer['document_path'], $layer['document_url']);
												$dateinamensteil = explode('.', $dateiname);
												$type = strtolower($dateinamensteil[1]);
												$thumbname = $this->get_dokument_vorschau($dateinamensteil);
												if ($layer['document_url'] != '') {
													$url = '';										# URL zu der Datei (komplette URL steht schon in $dokumentpfad)
													$target = 'target="_blank"';
													$thumbname = dirname($dokumentpfad).'/'.basename($thumbname);
												}
												else {
													$original_name = $pfadteil[1];
													$this->allowed_documents[] = addslashes($dateiname);
													$this->allowed_documents[] = addslashes($thumbname);
													$url = IMAGEURL.$this->document_loader_name.'?dokument=';			# absoluter Dateipfad
												}
												if (in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf')) ) {
													echo '<tr><td><a class="preview_link" '.$target.' href="'.$url.$dokumentpfad.'"><img class="preview_image" src="'.$url.$thumbname.'"></a></td></tr>';
												}
												else {
													echo '<tr><td><a class="preview_link" '.$target.' href="'.$url.$dokumentpfad.'"><img class="preview_doc" src="'.$url.$thumbname.'"></a></td></tr>';
												}
												$output[$p] = '<table><tr><td>'.$original_name.'</td>';
											}
											else {
												$output[$p] = '<table><tr><td></td>';
											}
											$output[$p] .= '<td><img border="0" title="zum Datensatz" src="'.GRAPHICSPATH.'zum_datensatz.gif"></td></tr></table>';
											echo '<input type="hidden" name="'.$layer['Layer_ID'].';'.$attributes['real_name'][$attributes['name'][$j]].';'.$attributes['table_name'][$attributes['name'][$j]].';'.$dataset[$attributes['table_name'][$attributes['name'][$j]].'_oid'].';'.$attributes['form_element_type'][$j].'_alt'.';'.$attributes['nullable'][$j].'" value="'.$dataset[$attributes['name'][$j]].'"></td>';
										} break;
								
										case 'Link': {
											$output[$p] = basename($dataset[$preview_attributes[$p]]);
										} break;
								
										default : {
											$output[$p] = $dataset[$preview_attributes[$p]];
										}
									}
									if ($output[$p] == '') {
										$output[$p] = ' ';
									}
								}
							}
						}
						if ($this->formvars['embedded'] == 'true') {
							echo '<tr style="border: none">
											<td'. get_td_class_or_style(array($dataset[$attributes['style']], 'subFormListItem')) . '><a style="font-size: '.$this->user->rolle->fontsize_gle.'px;" href="javascript:if (document.getElementById(\'subform'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'\').innerHTML == \'\')ahah(\'index.php\', \'go=Layer-Suche_Suchen&selected_layer_id='.$layer['Layer_ID'].'&value_'.$layer['maintable'].'_oid='.$dataset[$layer['maintable'].'_oid'].'&embedded=true&subform_link=true&fromobject=subform'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'&targetobject='.$this->formvars['targetobject'].'\', new Array(document.getElementById(\'subform'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'\'), \'\'), new Array(\'sethtml\', \'execute_function\'));clearsubforms('.$layer['Layer_ID'].');">'.implode(' ', $output).'</a><div id="subform'.$layer['Layer_ID'].$this->formvars['count'].'_'.$k.'"></div></td>
										</tr>
				';
						}
						else {
							echo '<tr style="border: none">
											<td test="'.$attributes['style'].'"' . get_td_class_or_style(array($dataset[$attributes['style']])) . '>';
							echo '<a style="font-size: '.$this->user->rolle->fontsize_gle.'px;"';
											if ($this->formvars['no_new_window'] != true) {
												echo 	' target="_blank"';
											}
							echo ' href="javascript:overlay_link(\'go=Layer-Suche_Suchen&selected_layer_id='.$layer['Layer_ID'].'&value_'.$layer['maintable'].'_oid='.$dataset[$layer['maintable'].'_oid'].'&subform_link=true\')">'.implode(' ', $output).'</a></td>
										</tr>';
						}
					} ?>
				</table><?
			}
		}
		if ($anzObj > 1) { ?>
			<script type="text/javascript">
				document.getElementById('show_all_<? echo $this->formvars['targetobject'];?>').style.display = '';
			</script><?
		}
	} 
  else {
		# nix machen
  }
?>