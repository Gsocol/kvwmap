
<script language="JavaScript">
<!--

function send(){
	if(document.GUI.loc_x.value == ''){
		alert('Geben Sie einen Punkt an.');
	}
	else{
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
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
  <tr align="center"> 
    <td colspan="6"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr> 
    <td rowspan="11">&nbsp; </td>
    <td rowspan="11"> 
    <?php
	  # Wenn ein Polygon �bergeben wird, wird es in SVG mit dargestellt.
      include(LAYOUTPATH.'snippets/SVG_point.php');
    ?>
    </td>
    <td colspan="3"><hr align="center" noshade></td>
  <tr> 
    <td colspan="3">Gemeinde/ID:<br /> 
  <?php
    $this->GemFormObj->outputHTML();
    echo $this->GemFormObj->html;
  ?>
    </td>
  <tr> 
    <td colspan="3">Zonennummer: 
      <input name="zonennr" type="text" id="Zonennr" value="<?php echo $this->formvars['zonennr']; ?>" size="5" maxlength="5"> 
    </td>
  <tr> 
    <td colspan="3">Standort: 
      <input name="standort" type="text" value="<?php echo $this->formvars['standort']; ?>" size="25" maxlength="255"> 
    </td>
  <tr> 
    <td colspan="3">Richtwertdef.: 
      <input name="richtwertdefinition" type="text" id="richtwertdefinition" value="<?php echo $this->formvars['richtwertdefinition']; ?>" size="21" maxlength="50"> 
    </td>
  <tr> 
    <td colspan="3">Bodenwert: 
      <input name="bodenwert" type="text" id="Bodenwert" value="<?php echo $this->formvars['bodenwert']; ?>" size="4" maxlength="4">
      [&euro;/m&sup2;] </td>
  <tr> 
    <td colspan="3">Art der Erschlie&szlig;ung: 
      <?php 
                $FormatWerte = array('ohne','[ortsuebliche Erschl.]','(vollerschlossen)');
                $FormatBez = array('ohne','[orts�bliche Erschl.]','(vollerschlossen)'); 
                $Blattformat = new FormObject('erschliessungsart','select',$FormatWerte,array($this->formvars['erschliessungsart']),$FormatBez,1,$maxlenght,$multiple,NULL);
                $Blattformat->OutputHTML();
                echo $Blattformat->html;
              ?>
    </td>
  <tr> 
    <td colspan="3">Sanierungsgebiet: 
      <?php 
                $FormatWerte = array('ohne','Sanierungsanfangswert','Sanierungsendwert');               
                $FormatBez = array('ohne','Sanierungsanfangswert','Sanierungsendwert'); 
                $Blattformat = new FormObject('sanierungsgebiete','select',$FormatWerte,array($this->formvars['sanierungsgebiete']),$FormatBez,1,$maxlenght,$multiple,NULL);
                $Blattformat->OutputHTML();
                echo $Blattformat->html;
              ?>
    </td>
  <tr> 
    <td colspan="3"><input type="checkbox" name="sichtbarkeit" value="1" <?php if ($this->formvars['sichtbarkeit']!='') { ?> checked<?php } ?>>
      sichtbar</td>
  <tr> 
    <td>Stichtag:</td>
    <td>
<div align="right">31.12.</div></td>
    <td>
    <div align="left">
    <input name="datum" type="text" value="<?php echo $this->formvars['datum']; ?>" size="4" maxlength="4">
    </div></td>
  <tr> 
    <td colspan="3"><hr align="center" noshade></td>
  <tr> 
    <td width="25" colspan="2">&nbsp; </td>
    <td colspan="3"><table border="0">
        <tr> 
          <td><input type="reset" name="reset" value="Zur�cksetzen"></td>
          <td><input type="button" name="senden" value="Senden" onclick="send();"></td>
        </tr>
      </table></td>
  </tr>
</table>
<input type="hidden" name="oid" value="<? echo $this->formvars['oid']; ?>">
<input type="hidden" name="go" value="Bodenrichtwertformular">
<input type="hidden" name="go_plus" value="">
<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">

