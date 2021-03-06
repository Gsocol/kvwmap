<?
if($this->formvars['anzahl'] == ''){$this->formvars['anzahl'] = 0;}

include('funktionen/input_check_functions.php');
?>

<script type="text/javascript">

	var geom_not_null = false;
	var enclosingForm = <? echo $this->currentform; ?>;
		
	update_geometry = function(){
		document.getElementById("svghelp").SVGupdate_geometry();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
	}
	
	show_foreign_vertices = function(){
		document.getElementById("svghelp").SVGshow_foreign_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
	}

	scrolltop = function(){
		if(enclosingForm.name == 'GUI2'){
			document.getElementById('contentdiv').scrollTop = 0;
		}else{
			window.scrollTo(0,0);
		}
	}
	
	scrollbottom = function(){
		if(enclosingForm.name == 'GUI2'){
			document.getElementById('contentdiv').scrollTop = document.getElementById('contentdiv').scrollHeight;
		}else{
			window.scrollTo(0, document.body.scrollHeight);
		}
	}
	
	toggle_group = function(id){
		var group = document.getElementById('group'+id);
		var group_img = document.getElementById('group_img'+id);
		if(group.style.display == 'none'){
			group.style.display = '';
			group_img.src = 'graphics/minus.gif';
		}
		else{
			group.style.display = 'none';
			group_img.src = 'graphics/plus.gif';
		}
	}	
	
	check_visibility = function(layer_id, object, dependents, k){
		if(object == null)return;
		var group_display;
		dependents.forEach(function(dependent){
			var scope = object.closest('table');		// zuerst in der gleichen Tabelle suchen
			if(scope.querySelector('#vcheck_operator_'+dependent) == undefined){
				scope = document;			// ansonsten global
			}
			var operator = scope.querySelector('#vcheck_operator_'+dependent).value;
			var value = scope.querySelector('#vcheck_value_'+dependent).value;
			if(operator == '=')operator = '==';
			// visibility of attribute
			var name_dependent = scope.querySelector('#name_'+layer_id+'_'+dependent+'_'+k);
			var value_dependent = scope.querySelector('#value_'+layer_id+'_'+dependent+'_'+k);
			if(field_has_value(object, operator, value)){
				if(name_dependent != null)name_dependent.style.visibility = 'visible';
				value_dependent.style.visibility = 'visible';
			}
			else{
				if(name_dependent != null)name_dependent.style.visibility = 'hidden';
				value_dependent.style.visibility = 'hidden';
			}
			// visibility of row
			var row = value_dependent.parentNode;
			all_attributes_in_row = [].slice.call(row.childNodes);
			row_display = 'none';
			all_attributes_in_row.forEach(function(td){
				if(td.nodeType == 1 && td.id != '' && td.style.visibility != 'hidden'){
					row_display = '';
				}
			})
			row.style.display = row_display;
			// visibility of group
			if(row.closest('table').firstChild.children != null){
				all_trs = [].slice.call(row.closest('table').firstChild.children);		// alle trs in der Gruppe
				group_display = 'none';
				all_trs.forEach(function(tr){
					if(tr.id != '' && tr.style.display != 'none'){
						group_display = '';
					}
				})
				row.closest('div').closest('tr').style.display = group_display;
			}
		})
	}

	field_has_value = function(field, operator, value){
		var field_value = field.value;
		if(field.type == 'radio'){
			var radio = document.querySelector('input[name="'+field.name+'"]:checked');
			if(radio != null)field_value = radio.value;
		}
		if(field.type == 'checkbox'){
			if((operator == '==' && value == 't' && field.checked) || 
				 (operator == '==' && value != 't' && !field.checked) ||
				 (operator == '!=' && value == 't' && !field.checked) ||
				 (operator == '!=' && value != 't' && field.checked)
				 )return true;
			else return false;
		}
		else{
			if(operator == 'IN'){
				value_array = value.split('|');
				if(value_array.indexOf(field_value) > -1) return true;
				else return false;
			}
			else{
				return eval("'"+field_value+"' "+operator+" '"+value+"'")
			}
		}
	}	

	toggleGroup = function(groupname){			// fuer die spaltenweise Ansicht
		var group_elements = document.querySelectorAll('.group_'+groupname);
		var gap_elements = document.querySelectorAll('.gap_'+groupname);
		var group = document.getElementById(groupname);
		var img = document.getElementById('img_'+groupname);
		if(group.colSpan == group.dataset.colspan){		// wenn aktueller colspan = initialer colspan -> Gruppe ist aufgeklappt
			[].forEach.call(group_elements, function (group_element){
				group_element.style.display='none';		// Attribute der Gruppe ausblenden
			});
			[].forEach.call(gap_elements, function (gap_element){
				gap_element.colSpan=2;		// Leerspalte zwischen den Gruppen verbreitern
			});
			group.colSpan=1;
			img.src='graphics/plus.gif';
		}
		else{ // Gruppe ist zusammengeklappt
			[].forEach.call(group_elements, function (group_element){
				group_element.style.display='';		// Attribute der Gruppe einblenden
			});
			[].forEach.call(gap_elements, function (gap_element){
				gap_element.colSpan=1;		// Leerspalte zwischen den Gruppen verkleinern
			});
			group.colSpan=group.dataset.colspan;
			img.src='graphics/minus.gif';
		}
	}
	
	toggle_statistic_row = function(layer_id) {
		var x = document.getElementsByClassName('statistic_row_'+layer_id),
				i;
		for (i = 0; i < x.length; i++) {
			if (x[i].style.display == '') {
				x[i].style.display = 'none';
			}
			else {
				x[i].style.display = '';
			}
		}
	}
	
	buildJSONString = function(id, is_array) {
		var field = document.getElementById(id);
		values = new Array();
		elements = document.getElementsByClassName(id);
		for (i = 0; i < elements.length; i++) {
			value = elements[i].value;
			name = elements[i].name;
			type = elements[i].type;
			if(name.slice(-4) != '_alt'){
				if (type == 'file') { // Spezialfall bei Datei-Upload-Feldern:
					if (value != '') {
						value = 'file:' + name; // wenn value vorhanden, wurde eine Datei ausgewählt, dann den Namen des Input-Feldes einsammeln + einem Prefix "file:"
					}
					else {
						old_file_path = document.getElementsByName(name+'_alt');
						if (old_file_path[0] != undefined) {
							value = old_file_path[0].value; // ansonsten den gespeicherten alten Dateipfad
						}
					}
				}
				if (!is_array) { // Datentyp
					if (value == '') {
						value = 'null';
					}
					else {
						if (value.substring(0,1) != '{') {
							value = '"' + value + '"';
						}
					}
					id_parts = elements[i].id.split('_');
					if(id_parts.length == 3)attribute_name = id_parts[1];		// normales Attribut
					else attribute_name = id_parts.pop();										// Nutzerdatentyp-Attribut
					values.push('"' + attribute_name + '":' + value);
				}
				else {
					if (i > 0) { // Array (hier ist das erste Element ein Dummy -> auslassen)
						if (value != '') {
							values.push(value);
						}
					}
				}
			}
		}
		if (!is_array) {
			json = '{'+values.join()+'}';
		}
		else {
			json = JSON.stringify(values);
		}
		field.value = json;
		if (field.onchange) {
			field.onchange();
		}
	}

	addArrayElement = function(fieldname, form_element_type, oid){
		outer_div = document.getElementById(fieldname+'_elements');
		first_element = document.getElementById('div_'+fieldname+'_-1');
		new_element = first_element.cloneNode(true);
		last_id = outer_div.lastElementChild.id;
		parts = last_id.split('div_'+fieldname+'_');
		new_id = parseInt(parts[1])+1;
		new_element.id = 'div_'+fieldname+'_'+new_id;
		var regex = new RegExp(fieldname+'_-1', "g");
		new_element.innerHTML = new_element.innerHTML.replace(regex, fieldname+'_'+new_id);
		new_element.style.display = 'block';
		outer_div.appendChild(new_element);
		buildJSONString(fieldname, true);
	}
	
	removeArrayElement = function(fieldname, remove_element_id){
		getFileAttributesInArray(remove_element_id);
		outer_div = document.getElementById(fieldname+'_elements');
		remove_element = document.getElementById('div_'+remove_element_id);
		outer_div.removeChild(remove_element);
		buildJSONString(fieldname, false);
	}
	
	function getFileAttributesInArray(id){
		elements = document.getElementsByClassName(id);
		for(i = 0; i < elements.length; i++){
			if(elements[i].type == 'file'){
				old_file_path = document.getElementsByName(elements[i].name+'_alt');
				if(old_file_path[0] != undefined)enclosingForm.delete_documents.value += old_file_path[0].value+'|';
			}
			else{
				getFileAttributesInArray(elements[i].id);
			}
		}
	}
	
	nextdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id) != undefined && document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch weiterblättern?');
		}
		if(sure){
			enclosingForm.target = '';
			enclosingForm.go.value = 'get_last_query';
			if(enclosingForm.go_backup.value != ''){
				enclosingForm.go.value = enclosingForm.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = parseInt(obj.value) + <? echo $this->formvars['anzahl']; ?>;
			overlay_submit(enclosingForm, false);
		}
	}
	
	lastdatasets = function(layer_id, count){
		var sure = true;
		if(document.getElementById('changed_'+layer_id) != undefined && document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch weiterblättern?');
		}
		if(sure){
			enclosingForm.target = '';
			enclosingForm.go.value = 'get_last_query';
			if(enclosingForm.go_backup.value != ''){
				enclosingForm.go.value = enclosingForm.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = count - (count % <? echo $this->formvars['anzahl']; ?>);
			overlay_submit(enclosingForm, false);
		}
	}
	
	firstdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id) != undefined && document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch zurückblättern?');
		}
		if(sure){
			enclosingForm.target = '';
			enclosingForm.go.value = 'get_last_query';
			if(enclosingForm.go_backup.value != ''){
				enclosingForm.go.value = enclosingForm.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			obj.value = 0;
			overlay_submit(enclosingForm, false);
		}
	}

	prevdatasets = function(layer_id){
		var sure = true;
		if(document.getElementById('changed_'+layer_id) != undefined && document.getElementById('changed_'+layer_id).value == 1){
			sure = confirm('Die Daten in diesem Thema wurden verändert aber noch nicht gespeichert. Wollen Sie dennoch zurückblättern?');
		}
		if(sure){
			enclosingForm.target = '';
			enclosingForm.go.value = 'get_last_query';
			if(enclosingForm.go_backup.value != ''){
				enclosingForm.go.value = enclosingForm.go_backup.value;
			}
			obj = document.getElementById('offset_'+layer_id);
			if(obj.value == '' || obj.value == undefined){
				obj.value = 0;
			}
			obj.value = parseInt(obj.value) - <? echo $this->formvars['anzahl']; ?>;
			overlay_submit(enclosingForm, false);
		}
	}

	back = function(){
		enclosingForm.go.value = 'Layer-Suche';
		enclosingForm.submit();
	}

	druck = function(){
		enclosingForm.target = '_blank';
		enclosingForm.printversion.value = 'true';
		enclosingForm.submit();
	}
	
	reload_subform_list = function(list_div_id, list_edit, weiter_erfassen, weiter_erfassen_params){
		list_div = document.getElementById(list_div_id);
		var params = list_div.dataset.reload_params;
		if(enclosingForm.name == 'GUI2')params += '&mime_type=overlay_html';
		if(list_edit)params += '&list_edit='+list_edit;
		if(weiter_erfassen)params += '&weiter_erfassen='+weiter_erfassen;
		if(weiter_erfassen_params)params += '&weiter_erfassen_params='+weiter_erfassen_params;
		ahah('index.php?go=Layer-Suche_Suchen', params, new Array(list_div), new Array('sethtml'));
	}

	save = function(){
		form_fieldstring = enclosingForm.form_field_names.value+'';
		form_fields = form_fieldstring.split('|');
		for(i = 0; i < form_fields.length-1; i++){
			fieldstring = form_fields[i]+'';
			field = fieldstring.split(';');
			if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && (document.getElementsByName(fieldstring)[0].readOnly != true) && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
				message('Das Feld '+document.getElementsByName(fieldstring)[0].title + ' erfordert eine Eingabe.');
				return;
			}
			if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
				message('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
				return;
			}
		}
		enclosingForm.go.value = 'Sachdaten_speichern';
		document.getElementById('loader').style.display = '';
		setTimeout('document.getElementById(\'loaderimg\').src=\'graphics/ajax-loader.gif\'', 50);
		document.GUI.gle_changed.value = '';
		overlay_submit(enclosingForm, false);
	}

	<? echo $this->currentform; ?>.save_new_dataset = function(){
		if((geom_not_null && this.newpath.value == '' && this.loc_x == undefined) || (geom_not_null && this.loc_x != undefined && this.loc_x.value == '')){ 
			message('Sie haben keine Geometrie angegeben.');
			return;
		}
  	form_fieldstring = this.form_field_names.value+'';
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';'); 
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'SubFormFK' && field[6] != 'not_saveable' && (document.getElementsByName(fieldstring)[0].readOnly != true) && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
			  message('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			message('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  	}
  	this.go.value = 'neuer_Layer_Datensatz_speichern';
		document.getElementById('sachdatenanzeige_save_button').disabled = true;
		document.GUI.gle_changed.value = '';
  	overlay_submit(this, false);
	}

	subdelete_data = function(layer_id, fromobject, oid, reload_object){
		// layer_id ist die von dem Layer, in dem der Datensatz geloescht werden soll
		// fromobject ist die id von dem div, welches das Formular des Datensatzes enthaelt, welches entfernt wird
		// reload_object ist die id vom gesamten Subformular, welches nach Loeschung des Datensatzes aktualisiert werden soll (optional)
		if (confirm('Wollen Sie die ausgewählten Datensätze wirklich löschen?')) {
			var formData = new FormData();
			formData.append('go', 'Layer_Datensatz_Loeschen');
			formData.append('chosen_layer_id', layer_id);
			formData.append('oid', oid);
			formData.append('reload_object', reload_object);
			ahah('index.php', formData, new Array(document.getElementById(fromobject), ''), new Array('sethtml', 'execute_function'));
		}
	}

	subsave_data = function(layer_id, fromobject, targetobject, reload){
		// layer_id ist die von dem Layer, in dem die Datensätze gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular der Datensätze enthält
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des Datensatzes aktualisiert werden soll
		form_fields = Array.prototype.slice.call(document.getElementById(fromobject).querySelectorAll('.subform_'+layer_id));
		form_fieldstring = '';
		var formData = new FormData();
  	for(i = 0; i < form_fields.length; i++){
			if(form_fields[i].name.slice(-4) != '_alt')form_fieldstring += form_fields[i].name+'|';
  		field = form_fields[i].name.split(';');
  		if(field[4] != 'Dokument' && form_fields[i].readOnly != true && field[5] == '0' && form_fields[i].value == ''){
  			message('Das Feld '+form_fields[i].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(field[6] == 'date' && field[4] != 'Time' && form_fields[i].value != '' && !checkDate(form_fields[i].value)){
  			message('Das Datumsfeld '+form_fields[i].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
			if(form_fields[i].type != 'checkbox' || form_fields[i].checked){			
				if(form_fields[i].type == 'file' && form_fields[i].files[0] != undefined)value = form_fields[i].files[0];
				else value = form_fields[i].value;
				formData.append(form_fields[i].name, value);
			}
  	}
		formData.append('go', 'Sachdaten_speichern');
		formData.append('reload', reload);
		formData.append('selected_layer_id', layer_id);
		formData.append('targetobject', targetobject);
		formData.append('form_field_names', form_fieldstring);
		formData.append('embedded', 'true');
		ahah('index.php', formData, new Array(document.getElementById(fromobject), ''), new Array('sethtml', 'execute_function'));
	}

	subsave_new_layer_data = function(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, reload, list_edit){
		// layer_id ist die von dem Layer, in dem ein neuer Datensatz gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular zur Eingabe des neuen Datensatzes enthaelt
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des neuen Datensatzes aktualisiert werden soll
		// targetlayer_id ist die von dem Layer, zu dem das targetobject gehoert
		// targetattribute ist das Attribut, zu dem das targetobject gehoert
  	form_fields = Array.prototype.slice.call(document.getElementById(fromobject).querySelectorAll('.subform_'+layer_id));
		form_fieldstring = '';
		var formData = new FormData();
  	for(i = 0; i < form_fields.length; i++){
			if(form_fields[i].name.slice(-4) != '_alt')form_fieldstring += form_fields[i].name+'|';
  		field = form_fields[i].name.split(';');
			if(field[4] != 'Dokument' && form_fields[i].readOnly != true && form_fields[i].type != 'hidden' && field[5] == '0' && form_fields[i].value == ''){
  			message('Das Feld '+form_fields[i].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(field[6] == 'date' && field[4] != 'Time' && form_fields[i].value != '' && !checkDate(form_fields[i].value)){
  			message('Das Datumsfeld '+form_fields[i].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
			if(form_fields[i].type != 'checkbox' || form_fields[i].checked){			
				if(form_fields[i].type == 'file' && form_fields[i].files[0] != undefined)value = form_fields[i].files[0];
				else value = form_fields[i].value;
				formData.append(form_fields[i].name, value);
			}
  	}
		formData.append('go', 'neuer_Layer_Datensatz_speichern');
		formData.append('reload', reload);
		formData.append('selected_layer_id', layer_id);
		formData.append('targetobject', targetobject);
		formData.append('targetlayer_id', targetlayer_id);
		formData.append('targetattribute', targetattribute);
		formData.append('form_field_names', form_fieldstring);
		formData.append('embedded', 'true');
		formData.append('list_edit', list_edit);
		ahah('index.php', formData, new Array(document.getElementById(fromobject), document.getElementById(targetobject), ''), new Array('sethtml', 'sethtml', 'execute_function'));
	}

	clearsubforms = function(layer_id){
		layer_id = layer_id + '';
		alldivs = document.getElementsByTagName('div');
		for(i = 0; i < alldivs.length; i++){
			id = alldivs[i].id + '';
			if(id.substr(0, 7 + layer_id.length) == 'subform'+layer_id){
				alldivs[i].innerHTML = '';
			}
		}
	}

	clearsubform = function(subformid){
		document.getElementById(subformid).innerHTML = '';
	}
	
	switch_gle_view1 = function(layer_id){
		enclosingForm.chosen_layer_id.value = layer_id;
		enclosingForm.go.value='toggle_gle_view';
		overlay_submit(enclosingForm, false);
	}
	
	add_calendar = function(event, elementid, type, setnow){
		event.stopPropagation();
		remove_calendar();
		calendar = new CalendarJS();
		calendar.init(elementid, type, setnow);
		document.getElementById('layer').calendar = calendar;
	}
	 
	remove_calendar = function(){
		if(document.getElementById('layer').calendar != undefined)document.getElementById('layer').calendar.destroy();
	}

	autocomplete1 = function(event, layer_id, attribute, field_id, inputvalue, listentyp) {
		listentyp = listentyp || 'ok';
		var suggest_field = document.getElementById('suggests_' + field_id);
		if(event.key == 'ArrowDown'){
			suggest_field.firstChild.selectedIndex = suggest_field.firstChild.selectedIndex + 1;
		}
		else if(event.key == 'ArrowUp'){
			suggest_field.firstChild.selectedIndex = suggest_field.firstChild.selectedIndex - 1;
		}
		else if(event.key == 'Enter'){
			suggest_field.firstChild.click();
		}
		else if(event.key == 'Tab'){
			// nix machen
		}
		else if(event.key == 'Escape'){
			document.getElementById('output_'+field_id).onchange();
		}
		else{
			suggest_field.style.display = 'none';
			if (inputvalue.length > 0) {
				ahah('index.php', 'go=autocomplete_request&layer_id=' + layer_id + '&attribute=' + attribute + '&inputvalue=' + inputvalue + '&field_id=' + field_id + (listentyp != '' ? '&listentyp=' + listentyp : ''), new Array(suggest_field, ""), new Array("sethtml", "execute_function"));
			}
			else{
				document.getElementById(field_id).value = '';
			}
		}
	}

	get_current_attribute_values = function(layer_id, attributenamesarray, geom_attribute, k){
		var attributenames = '';
		var attributevalues = '';
		var geom = '';
		for(i = 0; i < attributenamesarray.length; i++){
			if(document.getElementById(layer_id+'_'+attributenamesarray[i]+'_'+k) != undefined){
				attributenames += attributenamesarray[i] + '|';
				attributevalues += encodeURIComponent(document.getElementById(layer_id+'_'+attributenamesarray[i]+'_'+k).value) + '|';
			}
			else if(attributenamesarray[i] == geom_attribute ){	// wenn es das Geometrieattribut ist, handelt es sich um eine Neuerfassung --> aktuelle Geometrie nehmen
				if(enclosingForm.loc_x != undefined && enclosingForm.loc_x.value != ''){		// Punktgeometrie
					geom = 'POINT('+enclosingForm.loc_x.value+' '+enclosingForm.loc_y.value+')';
				}
				else if(enclosingForm.newpathwkt.value == ''){		// Polygon- oder Liniengeometrie
					if(enclosingForm.newpath.value != ''){
						geom = buildwktpolygonfromsvgpath(enclosingForm.newpath.value);
					}
				}
				attributenames += attributenamesarray[i] + '|';
				if(geom != '')attributevalues += 'SRID=<? echo $this->user->rolle->epsg_code; ?>;' + geom + '|';		// EWKT mit dem user-epsg draus machen
				else attributevalues += 'POINT EMPTY|';		// leere Geometrie zurückliefern
			}
		}
		return new Array(attributenames, attributevalues);
	}
	
	auto_generate = function(attributenamesarray, geom_attribute, attribute, k, layer_id){
		names_values = get_current_attribute_values(layer_id, attributenamesarray, geom_attribute, k);
		ahah("index.php", "go=auto_generate&layer_id="+layer_id+"&attribute="+attribute+"&attributenames="+names_values[0]+"&attributevalues="+names_values[1], new Array(document.getElementById(layer_id+'_'+attribute+'_'+k)), new Array("setvalue"));
	}
	
	openCustomSubform = function(layer_id, attribute, attributenamesarray, field_id, k){
		names_values = get_current_attribute_values(layer_id, attributenamesarray, '', k);
		document.getElementById('waitingdiv').style.background = 'rgba(200,200,200,0.8)';
		document.getElementById('waitingdiv').style.display = '';
		subformWidth = document.GUI.browserwidth.value-70;
		subform = '<div style="position:relative; margin: 30px;width:'+subformWidth+'px; height:90%">';
		subform += '<div style="position: absolute;top: 2px;right: -2px"><a href="javascript:closeCustomSubform();" title="Schlie&szlig;en"><img style="border:none" src="<? echo GRAPHICSPATH.'exit2.png'; ?>"></img></a></div>';
		subform += '<iframe id="customSubform" style="width:100%; height:100%" src=""></iframe>';
		subform += '</div>';
		document.getElementById('waitingdiv').innerHTML= subform;
		ahah("index.php", "go=openCustomSubform&layer_id="+layer_id+"&attribute="+attribute+"&attributenames="+names_values[0]+"&attributevalues="+names_values[1]+"&field_id="+field_id, new Array(document.getElementById('customSubform')), new Array("src"));
	}
	
	closeCustomSubform = function(){
		document.getElementById('waitingdiv').style.display = 'none';
		document.getElementById('waitingdiv').innerHTML = '';
	}
	 
	update_buttons = function(all, layer_id){
		merk_link = document.getElementById('merk_link_'+layer_id);
		delete_link = document.getElementById('delete_link_'+layer_id);
		print_link = document.getElementById('print_link_'+layer_id);
		zoom_link = document.getElementById('zoom_link_'+layer_id);
		classify_link = document.getElementById('classify_link_'+layer_id);
		if(all == 'true'){		
			if(merk_link != undefined)merk_link.style.display = 'none';
			if(print_link != undefined)print_link.style.display = 'none';
			if(delete_link != undefined)delete_link.style.display = 'none';
			if(zoom_link != undefined)zoom_link.style.display = 'none';
			if(classify_link != undefined)classify_link.style.display = 'none';
		}
		else{
			if(merk_link != undefined)merk_link.style.display = '';
			if(print_link != undefined)print_link.style.display = '';
			if(delete_link != undefined)delete_link.style.display = '';
			if(zoom_link != undefined)zoom_link.style.display = '';
			if(classify_link != undefined)classify_link.style.display = '';
		}
	} 

	selectall = function(layer_id){
		var k = 0;
		var obj = document.getElementById(layer_id+'_'+k);
		var status = obj.checked;
		while(obj != undefined){
			obj.checked = !status;
			k++;
			obj = document.getElementById(layer_id+'_'+k);
		}
	}

	highlight_object = function(layer_id, oid){
		ahah('index.php', 'go=tooltip_query&querylayer_id='+layer_id+'&oid='+oid, new Array(top.document.GUI.result, ''), new Array('setvalue', 'execute_function'));
	}
	
	zoom2object = function(layer_id, geomtype, tablename, columnname, oid, selektieren){
		params = 'go=zoomto'+geomtype+'&oid='+oid+'&layer_tablename='+tablename+'&layer_columnname='+columnname+'&layer_id='+layer_id+'&selektieren='+selektieren;
		if(enclosingForm.id == 'GUI2'){					// aus overlay heraus --> Kartenzoom per Ajax machen
			startwaiting();
			get_map_ajax(params, '', 'highlight_object('+layer_id+', '+oid+');');		// Objekt highlighten
		}
		else{
			window.location.href = 'index.php?'+params;		// aus normaler Sachdatenanzeige heraus --> normalen Kartenzoom machen
		}
	}
	
	zoom2wkt = function(wkt, epsg){
		params = 'go=zoom2wkt&wkt='+wkt+'&epsg='+epsg;
		if(enclosingForm.id == 'GUI2'){					// aus overlay heraus --> Kartenzoom per Ajax machen
			startwaiting();
			get_map_ajax(params, '', '');
		}
		else{
			window.location.href = 'index.php?'+params;		// aus normaler Sachdatenanzeige heraus --> normalen Kartenzoom machen
		}
	}	

	check_for_selection = function(layer_id){
		go = 'false';
		checkbox_name_obj = document.getElementsByName('checkbox_names_'+layer_id);
		checkbox_name_string = checkbox_name_obj[0].value;
		checkbox_names = checkbox_name_string.split('|');
		for(i = 0; i < checkbox_names.length; i++){
			if(document.getElementsByName(checkbox_names[i])[0] != undefined && document.getElementsByName(checkbox_names[i])[0].checked == true){
				go = 'true';
			}
		}
		if(go == 'false'){
			message('Es wurde kein Datensatz ausgewählt.');
			return false;
		}
		else{
			return true;
		}
	}

	zoomto_datasets = function(layer_id, tablename, columnname){
		if(check_for_selection(layer_id)){
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.layer_tablename.value = tablename;
			enclosingForm.layer_columnname.value = columnname;
			enclosingForm.go.value = 'zoomto_selected_datasets';
			enclosingForm.submit();
		}
	}

	delete_datasets = function(layer_id) {
		var no_edit_checkboxes = document.querySelectorAll('.no_edit');
		[].forEach.call(no_edit_checkboxes, function (checkbox){
				checkbox.checked = false;		// nicht editierbare Datensaetze deselektieren
			});
		if (check_for_selection(layer_id)){
			if(confirm('Wollen Sie die ausgewählten Datensätze wirklich löschen?')){
				enclosingForm.chosen_layer_id.value = layer_id;
				enclosingForm.go.value = 'Layer_Datensaetze_Loeschen';
				enclosingForm.submit();
			}
		}
	}

	delete_document = function(attributename, layer_id, fromobject, targetobject, reload){
		if(confirm('Wollen Sie das ausgewählte Dokument wirklich löschen?')){
			field = document.getElementsByName(attributename);
			field[0].type = 'hidden'; // bei einem Typ "file" kann man sonst den value nicht setzen
			field[0].value = 'file:'+attributename;	// damit der JSON-String eines evtl. vorhandenen übergeordneten Attributs richtig gebildet wird
			field[0].onchange(); // --||--
			field[0].value = 'delete';
			if(fromobject != ''){		// SubForm-Layer
				subsave_data(layer_id, fromobject, targetobject, reload);
			}
			else{												// normaler Layer
				enclosingForm.go.value = 'Sachdaten_speichern';
				enclosingForm.submit();
			}
		}
	}

	daten_export = function(layer_id, anzahl, format){
		enclosingForm.all.value = document.getElementById('all_'+layer_id).value;
		if(enclosingForm.all.value || check_for_selection(layer_id)){				// entweder alle gefundenen oder die ausgewaehlten
			var option = document.createElement("option");
			option.text = anzahl;
			option.value = anzahl;
			enclosingForm.anzahl.add(option);
			enclosingForm.anzahl.selectedIndex = enclosingForm.anzahl.options.length-1;
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.go_backup.value = enclosingForm.go.value;
			enclosingForm.go.value = 'Daten_Export';
			enclosingForm.submit();
		}
	}

	select_this_dataset = function(layer_id, n){
		var k = 0;
		obj = document.getElementById(layer_id+'_'+k);
		while(obj != undefined){
			obj.checked = false;
			k++;
			obj = document.getElementById(layer_id+'_'+k);
		}
		document.getElementById(layer_id+'_'+n).checked = true;
	}

	add_to_clipboard = function(layer_id){
		if(check_for_selection(layer_id)){
			saved_go = enclosingForm.go.value;
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.go.value = 'Datensaetze_Merken';
			formdata = new FormData(enclosingForm);
			ahah("index.php", formdata, new Array(), new Array());
			enclosingForm.go.value = saved_go;
			message([{'type': 'notice', 'msg': 'Datensätze gemerkt'}]);
		}
	}

	remove_from_clipboard = function(layer_id){
		if(check_for_selection(layer_id)){
			saved_go = enclosingForm.go.value;
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.go.value = 'Datensaetze_nicht_mehr_merken';
			formdata = new FormData(enclosingForm);
			ahah("index.php", formdata, new Array(), new Array());
			enclosingForm.go.value = saved_go;
			message([{'type': 'notice', 'msg': 'Datensätze entfernt'}]);
		}
	}

	use_for_new_dataset = function(layer_id){
		if(check_for_selection(layer_id)){
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.pathwkt.value = '';
			enclosingForm.newpathwkt.value = '';
			enclosingForm.newpath.value = '';
			enclosingForm.go_backup.value = enclosingForm.go.value;
			enclosingForm.go.value = 'neuer_Layer_Datensatz';
			enclosingForm.submit();
		}
	}
	
	dublicate_dataset = function(layer_id){
		if(check_for_selection(layer_id)){
			if(confirm('Der Datensatz und alle mit ihm verknüpften Objekte werden kopiert. Wollen Sie fortfahren?')){
				enclosingForm.chosen_layer_id.value = layer_id;
				enclosingForm.go_backup.value = enclosingForm.go.value;
				enclosingForm.go.value = 'Datensatz_dublizieren';
				enclosingForm.submit();
			}
		}
	}	

	print_data = function(layer_id){
		if(check_for_selection(layer_id)){
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.go_backup.value = enclosingForm.go.value;
			enclosingForm.go.value = 'generischer_sachdaten_druck';
			enclosingForm.submit();
		}
	}

	showcharts = function(layer_id){
		if(document.getElementById('charts_'+layer_id).style.display == 'none'){
			document.getElementById('charts_'+layer_id).style.display = '';
		}
		else{
			document.getElementById('charts_'+layer_id).style.display = 'none';
		}
	}

	change_charttype = function(layer_id){
		if(document.getElementsByName('charttype_'+layer_id)[0].value == 'mirrorbar'){
			document.getElementById('split_'+layer_id).style.display = '';
		}
		else{
			document.getElementById('split_'+layer_id).style.display = 'none';
		}
	}

	create_chart = function(layer_id){
		if(check_for_selection(layer_id)){
			enclosingForm.target = "_blank";
			enclosingForm.chosen_layer_id.value = layer_id;
			enclosingForm.width.value = 700;
			enclosingForm.go_backup.value = enclosingForm.go.value;
			enclosingForm.go.value = 'generisches_sachdaten_diagramm';
			enclosingForm.submit();
			enclosingForm.target = "";
		}
	}


	update_require_attribute = function(object, attributes, k,layer_id, attributenamesarray){
		// object ist das Objekt welches diese Funktion ausgeloest hat
		// attributes ist eine Liste von zu aktualisierenden Attributen
		// k die Nummer des Datensatzes
		// attributenamesarray ein Array aller Attribute im Formular
		var datatype = '';
		if(object.dataset.datatype_id)datatype = '&datatype_id='+object.dataset.datatype_id;
		var attributenames = '';
		var attributevalues = '';
		// die Layer-ID muss aufgesplittet werden, um sie für css zu escapen
		var id = layer_id.toString();;
		var id1 = id.substring(0, 1);
		var id2 = id.substring(1);
		for(var i = 0; i < attributenamesarray.length; i++){
			var scope = object.closest('table'); // zuerst in der gleichen Tabelle suchen
			if (scope.querySelector('#\\3'+id1+' '+id2+'_'+attributenamesarray[i]+'_'+k) == undefined) {
				scope = document; // ansonsten global
			}
			if(scope.querySelector('#\\3'+id1+' '+id2+'_'+attributenamesarray[i]+'_'+k) != undefined){
				attributenames += attributenamesarray[i] + '|';
				attributevalues += scope.querySelector('#\\3'+id1+' '+id2+'_'+attributenamesarray[i]+'_'+k).value + '|';
			}
		}
		attribute = attributes.split(',');
		for(var i = 0; i < attribute.length; i++){
			var scope = object.closest('table'); // zuerst in der gleichen Tabelle suchen
			if (scope.querySelector('#\\3'+id1+' '+id2+'_'+attribute[i]+'_'+k) == undefined) {
				scope = document; // ansonsten global
			}
			var element = scope.querySelector('#\\3'+id1+' '+id2+'_'+attribute[i]+'_'+k);
			type = element.type;
			if(type == 'text'){action = 'setvalue'};
			if(type == 'select-one'){action = 'sethtml'};
			ahah("index.php", "go=get_select_list&layer_id="+layer_id+datatype+"&attribute="+attribute[i]+"&attributenames="+attributenames+"&attributevalues="+attributevalues+"&type="+type, new Array(element), new Array(action));
		}
	}

	change_orderby = function(attribute, layer_id){
		enclosingForm.go.value = 'get_last_query';
		if(document.getElementById('orderby'+layer_id).value == attribute){
			document.getElementById('orderby'+layer_id).value = attribute+' DESC';
		}
		else{
			document.getElementById('orderby'+layer_id).value = attribute;
		}
		overlay_submit(enclosingForm);
	}
	
	switch_edit_all = function(layer_id){
		if(document.getElementById('edit_all3_'+layer_id).style.display == 'none'){
			var obj = document.getElementById(layer_id+'_0');
			if(!obj.checked)selectall(layer_id);
			document.getElementById('edit_all1_'+layer_id).style.display = 'none';			
			document.getElementById('edit_all2_'+layer_id).style.display = '';
			document.getElementById('edit_all3_'+layer_id).style.display = '';
			document.getElementById('edit_all4_'+layer_id).style.display = '';
		}
		else{			
			document.getElementById('edit_all1_'+layer_id).style.display = '';			
			document.getElementById('edit_all2_'+layer_id).style.display = 'none';
			document.getElementById('edit_all3_'+layer_id).style.display = 'none';
			document.getElementById('edit_all4_'+layer_id).style.display = 'none';
		}
	}
	
	change_all = function(layer_id, k, layerid_attribute){
		allfield = document.getElementById(layerid_attribute+'_'+k);
		for(var i = 0; i < k; i++){			
			if(document.getElementById(layer_id+'_'+i).checked){
				formfield = document.getElementById(layerid_attribute+'_'+i);
				if(formfield.onchange){		// nur editierbare Felder aendern
					if(formfield.type == 'checkbox'){
						formfield.checked = allfield.checked;
					}
					else{
						formfield.value = allfield.value;
					}
					document.getElementById(layerid_attribute+'_'+i).onchange();
				}
			}
		}		
	}

	set_changed_flag = function(flag){
		if(flag != undefined){
			flag.value=1;
			if(flag.onchange)flag.onchange();
		}
	}

</script>