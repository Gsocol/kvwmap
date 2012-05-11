
<script language="JavaScript">
<!--

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function send(){
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			check = confirm('Sie haben kein Polygon angelegt. Trotzdem speichern?');
  		if(check == true){
  			document.GUI.oid.value = document.GUI.oid_save.value;
				document.GUI.go_plus.value = 'Senden';
				document.GUI.submit();
  		}
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
			document.GUI.oid.value = document.GUI.oid_save.value;
			document.GUI.go_plus.value = 'Senden';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.oid.value = document.GUI.oid_save.value;
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
	}
}

function buildwktpolygonfromsvgpath(svgpath){
	var koords;
	wkt = "POLYGON((";
	parts = svgpath.split("M");
	for(j = 1; j < parts.length; j++){
		if(j > 1){
			wkt = wkt + "),("
		}
		koords = ""+parts[j];
		coord = koords.split(" ");
		wkt = wkt+coord[1]+" "+coord[2];
		for(var i = 3; i < coord.length-1; i++){
			if(coord[i] != ""){
				wkt = wkt+","+coord[i]+" "+coord[i+1];
			}
			i++;
		}
	}
	wkt = wkt+"))";
	return wkt;
}

function update_form(art){
	if(art == 'jbe' || art == 'jbf' || art == 'jbe' || art == 'agf' || art == 'atf' || art == 'slf'){
		document.getElementById('zuordnung').style.display = '';
		document.getElementById('status').style.display = '';
		document.GUI.nummer.value = '';
		document.getElementById('lfdnr').style.display = 'none';
	}
	else{
		document.GUI.jb_zuordnung.value = '';
		document.GUI.status.value = 0;
		document.getElementById('zuordnung').style.display = 'none';
		document.getElementById('status').style.display = 'none';
		document.getElementById('lfdnr').style.display = '';
	}
}

//-->
</script>

<?php
	if ($this->Meldung=='') {
	  $bgcolor=BG_FORM;
	}
	else {
	  $bgcolor=BG_FORMFAIL;
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
	}
?>

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr>
    <td> <div align="center"></div></td>
    <td colspan="3"><div align="center"><strong><font size="+1"><?php echo $this->titel; ?></font></strong>
      </div></td>
  </tr>
  <tr>
    <td rowspan="9">&nbsp;</td>
    <td colspan="2" rowspan="9">
      <?php
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  <tr>
    <td><hr align="center" noshade></td>
  </tr>
  <tr>
  	<td>Fl�che:<br><input size="12" type="text" name="area" value="">&nbsp;ha</SUP></td>
  </tr>
  <tr>
  	<td>Name:<br><input type="text" name="name" value="<? echo $this->jagdbezirk['name'] ? $this->jagdbezirk['name']: $this->formvars['name']; ?>"></td>
  </tr>
  <tr>
  	<td>Art:<br>
  		<select onchange="update_form(this.value);" name="art">
  			<option <? if($this->jagdbezirk['art'] == 'ejb' OR $this->formvars['art'] == 'ejb'){echo 'selected';} ?> value="ejb">Eigenjagdbezirk</option>
  			<option <? if($this->jagdbezirk['art'] == 'gjb' OR $this->formvars['art'] == 'gjb'){echo 'selected';} ?> value="gjb">gem. Jagdbezirk</option>
  			<option <? if($this->jagdbezirk['art'] == 'tjb' OR $this->formvars['art'] == 'tjb'){echo 'selected';} ?> value="tjb">Teiljagdbezirk</option>
  			<option <? if($this->jagdbezirk['art'] == 'sf' OR $this->formvars['art'] == 'sf'){echo 'selected';} ?> value="sf">Sonderfl�che</option>
  			<option <? if($this->jagdbezirk['art'] == 'jbe' OR $this->formvars['art'] == 'jbe'){echo 'selected';} ?> value="jbe">Enklave</option>
  			<option <? if($this->jagdbezirk['art'] == 'jbf' OR $this->formvars['art'] == 'jbf'){echo 'selected';} ?> value="jbf">jagdbezirksfreie Fl�che</option>
  			<option <? if($this->jagdbezirk['art'] == 'agf' OR $this->formvars['art'] == 'agf'){echo 'selected';} ?> value="agf">Angliederungsfl�che</option>
  			<option <? if($this->jagdbezirk['art'] == 'atf' OR $this->formvars['art'] == 'atf'){echo 'selected';} ?> value="atf">Abtrennungsfl�che</option>
  			<option <? if($this->jagdbezirk['art'] == 'slf' OR $this->formvars['art'] == 'slf'){echo 'selected';} ?> value="slf">Schmalfl�che</option>
  		</select>
  	</td>
  </tr>
  <tr id="lfdnr" width="100%" style="display:<? if(in_array($this->jagdbezirk['art'], array('jbe', 'jbf', 'agf', 'atf', 'slf')) OR in_array($this->formvars['art'], array('jbe', 'jbf', 'agf', 'atf', 'slf'))){ echo 'none';}else{echo '';} ?>">
  	<td>lfd.-Nummer:<br><input type="text" name="nummer" value="<? echo $this->jagdbezirk['id'] ? $this->jagdbezirk['id']: $this->formvars['nummer']; ?>"></td>
  </tr>
  <tr id="zuordnung" width="100%" style="display:<? if(in_array($this->jagdbezirk['art'], array('jbe', 'jbf', 'agf', 'atf', 'slf')) OR in_array($this->formvars['art'], array('jbe', 'jbf', 'agf', 'atf', 'slf'))){ echo '';}else{echo 'none';} ?>">
    <td>Zuordnung:<br>
    	<input type="text" name="jb_zuordnung" value="<? echo $this->jagdbezirk['jb_zuordnung'] ? $this->jagdbezirk['jb_zuordnung']: $this->formvars['jb_zuordnung']; ?>">
    </td>
  </tr>
  <tr id="status" width="100%" style="display:<? if(in_array($this->jagdbezirk['art'], array('jbe', 'jbf', 'agf', 'atf', 'slf')) OR in_array($this->formvars['art'], array('jbe', 'jbf', 'agf', 'atf', 'slf'))){ echo '';}else{echo 'none';} ?>">
    <td>Status<br>
    	<select name="status">
    		<option value="0" <? if($this->jagdbezirk['status'] == 'f' OR $this->formvars['status'] == 'f'){echo 'selected="true"';} ?>>aktuell</option>
    		<option value="1" <? if($this->jagdbezirk['status'] == 't' OR $this->formvars['status'] == 't'){echo 'selected="true"';} ?>>historisch</option>
    	</select>
    </td>
  </tr>
  <tr>
    <td><hr align="center" noshade></td>
  </tr>
  <tr>
  	<td>Geometrie �bernehmen von:<br>
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<option value="">--- Auswahl ---</option>
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select>
  	</td>
  </tr>
  <tr>
    <td width="100%" height="50" align="center" valign="bottom"><input type="button" name="senden" value="Senden" onclick="send();"></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  	<td>&nbsp;</td>
  	<td align="right"><input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang</td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="">
<INPUT TYPE="HIDDEN" NAME="oid_save" VALUE="<? echo $this->formvars['oid'] ? $this->formvars['oid']: $this->formvars['oid_save']; ?>">
<INPUT TYPE="HIDDEN" NAME="areaunit" VALUE="hektar">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>">
<INPUT TYPE="HIDDEN" NAME="go" VALUE="jagdkatastereditor" >
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
