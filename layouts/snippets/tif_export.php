<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function DruckAufloesung(pixel,breite) {
	pixel = pixel.replace(/,/g, ".");
	var cm = Math.round((breite/pixel)/200*2.54,1)+' cm';
	document.getElementById("cm").value = cm;
}

//-->
</script>

<? $this->formvars['resolution'] = str_replace(',','.',$this->formvars['resolution']); ?>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="3"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="center">
			<b>Aufl�sung:</b>  1 Pixel = <input type="text" value="<? echo round($this->formvars['resolution'],3); ?>" name="resolution" size="3" onkeyup="DruckAufloesung(this.value,<? echo $this->map->extent->maxx - $this->map->extent->minx; ?>)">&nbsp;m
		</td>
		<td>&nbsp;</td>
	</tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="center">
		    <? $cm = round((($this->map->extent->maxx - $this->map->extent->minx)/$this->formvars['resolution'])/200*2.54,0).' cm'; ?>
			F�r einen Ausdruck dieses TIFs mit 200 dpi<br>
			=> Breite des Ausdrucks :
			<input type="text" readonly="readonly" size="5" id="cm" style="border:0px;background-color:transparent;" value="<? echo $cm; ?>"><br>
            <br>
			(Hinweis: Die Halbierung der Pixelaufl�sung ergibt die Verdopplung<br>
			 der Druckaufl�sung und die Vervierfachung der Dateigr��e)<br>&nbsp;
		</td>
		<td>&nbsp;</td>
	</tr>
  <tr>
  	<td colspan="3" align="center"><input class="button" type="submit" name="go_plus" value="TIF-Datei erzeugen"></td>
  </tr>
  <?if($this->tif->tifimage != ''){
  		if($this->tif->tifimage == 'error'){ ?>
  <tr>
  	<td colspan="3" align="center"><b>TIF-Erzeugung fehlgeschlagen.</b></td>
  </tr>
  <?	}else{?>
  	<tr>
  		<td colspan="3" align="center">TIF-Datei erzeugt. <a href="<?echo $this->tif->tifimage;?>" type="multipart/form-data">Herunterladen</a></td>
  	</tr>
  	<tr>
  		<td colspan="3" align="center">TFW-Datei erzeugt. <a href="<?echo $this->tif->tfwfile;?>" type="multipart/form-data">Herunterladen</a></td>
  	</tr>
  <?	}
  	}?>
</table>

<input type="hidden" name="go" value="TIF_Export">


