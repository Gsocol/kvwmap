<script language="JavaScript" type="text/javascript">
<!--

function browser_switch(){
  if(navigator.appName == 'Microsoft Internet Explorer'){
		document.GUI.target = '_blank';
  }
  document.GUI.submit();
}

-->
</script>

<table width="100%" height="100" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td align="center" colspan="2" height="60" valign="bottom" align="left" >
    	<h3><?php echo $this->titel; ?></h3>
    </td>
  </tr>
  <tr>
  	<td align="center">Welche Attribute sollen zus�tzlich ausgegeben werden?</td>
  </tr>
  <tr> 
    <td colspan="2" ><div align="center">
      <table border="0" cellspacing="0" cellpadding="4">
      <? for($i = 0; $i < count($this->attribute); $i++){
			      $privileg[$this->attribute[$i]] = true;
			    } 
			?>
        <tr>
          <td width="250">
          <?
          if($privileg['flurstkennz']){ echo '<input name="flurstkennz" checked="true" type="checkbox">Flurst�ckskennzeichen<br>';}
			    if($privileg['gemkgname']){ echo '<input name="gemkgname" type="checkbox">Gemarkungsname<br>';}
			    if($privileg['gemkgschl']){ echo '<input name="gemkgschl" type="checkbox">Gemarkungsschl�ssel<br>';}
			    if($privileg['flurnr']){ echo '<input name="flurnr" type="checkbox">Flurnummer<br>';}
			    if($privileg['gemeindename']){ echo '<input name="gemeindename" type="checkbox">Gemeindename<br>';}
			    if($privileg['gemeinde']){ echo '<input name="gemeinde" type="checkbox">Gemeinde<br>';}
			    if($privileg['kreisname']){ echo '<input name="kreisname" type="checkbox">Kreisname<br>';}
			    if($privileg['kreisid']){ echo '<input name="kreisid" type="checkbox">Kreisschl�ssel<br>';}
			    if($privileg['finanzamtname']){ echo '<input name="finanzamtname" type="checkbox">Finanzamtname<br>';}
			    if($privileg['finanzamt']){ echo '<input name="finanzamt" type="checkbox">Finanzamtschl�ssel<br>';}
			    if($privileg['forstname']){ echo '<input name="forstname" type="checkbox">Forstamtname<br>';}
			    if($privileg['forstschluessel']){ echo '<input name="forstschluessel" type="checkbox">Forstamtschl�ssel<br>';}
			    if($privileg['flaeche']){ echo '<input name="flaeche" type="checkbox">Fl�che<br>';}
			    if($privileg['amtsgerichtnr']){ echo '<input name="amtsgerichtnr" type="checkbox">Amtsgerichtschl�ssel<br>';}
			    if($privileg['amtsgerichtname']){ echo '<input name="amtsgerichtname" type="checkbox">Amtsgerichtname<br>';}
			    if($privileg['grundbuchbezirkschl']){ echo '<input name="grundbuchbezirkschl" type="checkbox">Grundbuchbezirkschl�ssel<br>';}
			    ?>
          </td>
          <td>
          <?
			    if($privileg['grundbuchbezirkname']){ echo '<input name="grundbuchbezirkname" type="checkbox">Grundbuchbezirkname<br>';}
			    if($privileg['lagebezeichnung']){ echo '<input name="lagebezeichnung" type="checkbox">Lage<br>';}
			    if($privileg['entsteh']){ echo '<input name="entsteh" type="checkbox">Entstehung<br>';}
			    if($privileg['letzff']){ echo '<input name="letzff" type="checkbox">Fortf�hrung<br>';}
			    if($privileg['karte']){ echo '<input name="karte" type="checkbox">Flurkarte<br>';}
			    if($privileg['status']){ echo '<input name="status" type="checkbox">Status<br>';}
			    if($privileg['vorgaenger']){ echo '<input name="vorgaenger" type="checkbox">Vorgaenger<br>';}
			    if($privileg['nachfolger']){ echo '<input name="nachfolger" type="checkbox">Nachfolger<br>';}
			    if($privileg['klassifizierung']){ echo '<input name="klassifizierung" type="checkbox">Klassifizierung<br>';}
			    if($privileg['freitext']){ echo '<input name="freitext" type="checkbox">Freitext<br>';}
			    if($privileg['hinweis']){ echo '<input name="hinweis" type="checkbox">Hinweis<br>';}
			    if($privileg['baulasten']){ echo '<input name="baulasten" type="checkbox">Baulasten<br>';}
			    if($privileg['ausfstelle']){ echo '<input name="ausfstelle" type="checkbox">ausf�hrende Stelle<br>';}
			    if($privileg['verfahren']){ echo '<input name="verfahren" type="checkbox">Verfahren<br>';}
			    if($privileg['bestandsnr']){ echo '<input name="bestandsnr" type="checkbox">Bestand<br>';}
			    if($privileg['eigentuemer']){ echo '<input name="eigentuemer" type="checkbox">Eigent�mer<br>';}
			    ?>
          </td>
        </tr>
      </table>
      </div>
	</td>
  </tr>
   <tr> 
    <td align="center" ><input type="button" name="go_plus" onclick="browser_switch();" value="Exportieren"></td>
  </tr>
</table>

<input type="hidden" name="FlurstKennz" value="<? echo $this->formvars['FlurstKennz']; ?>"></td>
<input type="hidden" name="go" value="Nutzungsarten-CSV-Export_Exportieren"></td>
