 <h2> 
  <?php 
        echo $this->titel;
        ?>
</h2>
<?php
if ($this->Fehlermeldung!='') { 
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?> 
<input type="hidden" name="go" value="ALB_Aenderung">
<br>
<table border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td> 
      <input type="radio" name="WLDGE_lokal" value="1"<?php if ($this->ALB->WLDGE_Datei['name']=='') { ?> checked<?php } ?>>
    </td>
    <td>WLDGE Datei liegt schon auf dem Server<br>
      <input type="text" name="WLDGE_Datei_lokal" value="<?php echo WLDGEFILEPATH.WLDGEFILENAME; ?>" size="50" maxlength="150">
    </td>
  </tr>
  <tr> 
    <td> 
      <input type="radio" name="WLDGE_lokal" value="2"<?php if ($this->formvars['WLDGE_lokal']==2) { ?> checked<?php } ?>>
    </td>
    <td>Mehrere WLDGE Dateien im Stapel verarbeiten. Verzeichnis auf dem Server:<br>
      <input type="text" name="WLDGE_Pfad_lokal" value="<?php
        if ($this->formvars['WLDGE_Pfad_lokal']=='') {
        	echo WLDGEFILEPATH;
        }
        else {
        	echo $this->formvars['WLDGE_Pfad_lokal'];
        } 
       ?>" size="50" maxlength="150">
    </td>
  </tr>  
  <tr> 
    <td> 
      <input type="radio" name="WLDGE_lokal" value="0"<?php if ($this->ALB->WLDGE_Datei['name']!='') { ?> checked<?php } ?>>
    </td>
    <td> WLDGE-Datei vom Client auf den Server laden <br>
      <input name="WLDGE_Datei" type="file" size="70" maxlength="150">
    </td>
  </tr>
  <tr> 
    <td> 
     <input type="radio" name="ist_Fortfuehrung" value="1" checked>
    </td>
    <td>Es handelt sich um Fortfuehrungsdaten</td>
  </tr>
  <tr> 
    <td> 
     <input type="radio" name="historische_loeschen" value="1"<?php if (WLDGE_HISTORISCHE_LOESCHEN_DEFAULT) { ?> checked<?php } ?>>
    </td>
    <td>Historische Best�nde und Flurst�cke l�schen</td>
  </tr><?php
 # 2006-12-12 pk
?>
  <tr>
    <td>
      <input type="radio" name="ist_Fortfuehrung" value="0">
    </td>
    <td>Es handelt sich um einen Grunddatenbestand</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Einlesen in MySQL
    <input type="radio" name="databasetype" value="mysql">
    oder in PostgreSQL 
    <input type="radio" name="databasetype" value="postgresql" checked>
    </td>
  </tr>
    <tr>
    <td valign="top">
      <input type="checkbox" name="disableDbWrite" value="1">
    </td>
    <td>Schreiben in die Datenbank unterdr�cken.<br>
    <em><font size="-2">(Wenn die SQL-Statements sp�ter �ber einen anderen Datenbankclient aus der Logdatei eingelesen werden sollen.)</font></em></td>
  </tr>
  <tr>
    <td valign="top">
      <input type="checkbox" name="truncateTables" value="1">
    </td>
    <td>Datenbanktabellen vorher leeren.<br>
      <em><font size="-2">(Nur notwendig, wenn der erste Teil eines neuen Grunddatenbestandes
       eingelesen wird.)</font></em></td>
  </tr>
  <tr>
    <td valign="top"><input name="blocktransaction" type="checkbox" id="blocktransaction" value="1"></td>
    <td>ohne Transaktion ausf&uuml;hren<br>
      <em><font size="-2">(Erh&ouml;ht die Einlesegeschwindigkeit, sollte aber
      nur f&uuml;r den Grundbestand verwendet werden)</font></em></td>
  </tr>
  <tr>
    <td valign="top"><input name="dontCheckHeader" type="checkbox" id="dontCheckHeader" value="1"></td>
    <td>Pr�fung der WLDGE Kopfzeilen unterdr�cken<br>
      <em><font size="-2">(Wird ben�tigt, wenn Grundbestand in mehreren Teilen eingelesen wird,
       Fortf�hrungen nicht aufeinanderfolgend sind oder zu Testzwecken.)</font></em></td>
  </tr>
  <tr>
    <td valign="top"><input name="logALBSQL" type="checkbox" id="logALBSQL" value="1" checked></td>
    <td>SQL-Statements in ALB-Logdateien schreiben<br>
      <em><font size="-2">(Kann benutzt werden um den Einlesevorgang zu dokumentieren oder die 
      Daten sp�ter �ber einen SQL-Client in eine Datenbank einzulesen.
      <br>Wenn hier nicht gew�hlt, wird SQL in Abh�ngigkeit der Konstante LOG_LEVEL getrennt f�r MySQL und PostgreSQL gelogged.
      <br>Wenn hier ausgew�hlt, erscheint das SQL zum ALB nicht in der debug Datei.)</font></em></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td> 
      <input type="submit" name="submit" value="Abschicken">
      <input type="submit" name="go_plus" value="Abbrechen">
   </td>
  </tr>
</table>
