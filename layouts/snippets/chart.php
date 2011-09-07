
<?php
// Einstellung f�r die Diagrammtypen
$host='https://139.30.111.146/cgi-bin/owtchart?'; // Hostadresse mit cgi-Pfad zur owtchart-Datei
$numbofsets='&NumSets=1'; // Anzahl der Datens�tze
$width='&W=400'; $height='&H=300'; // Breite und H�he der Graphik
$bgcolour='&BGColor=FFFFFF'; // Hintergrundfarbe
// $transp='&TransBG=1'; // Darstellung des Hintergrundes als transparent -->> Boolean: 1=true / 0=false
// $axescolor='&LineColor=FF194A'; // Farbe der Achsen [NICHT F�R PIECHARTS G�LTIG]
// $showgrid='&Grid=0'; // Darstellen der Gitternetzlinien -->> Boolean: 1=true / 0=false [NICHT F�R PIECHARTS G�LTIG]
// $colorgrid='$GridColor=FFFD83'; // Farbe der Gitternetzlinien [NICHT F�R PIECHARTS G�LTIG]
$depth3d='&3DDepth=25'; // Tiefe bei 3D-Darstellung 
$angle3d='&3DAngle=45'; // Winkel der 3d-Darstellung der Graphik [zwischen 1� und 89� , default ist 30�]  
$title="&Title='Zugriffe pro Layer'"; // �berschrift [mit einem ";" wird ein Zeilenumbruch eingef�gt]
$titlecolor='&TitleColor=0F0F0F'; // Farbe von �berschrift und Untertitel 
$titlesize='&TitleFont=L'; // Schriftgr��e der �berschrift und der Untertitel [Values: S (small) , L (large) , M (medium) , G (giant)] 
$valueplotcolor='&PlotColor=CCDCF2'; // Farbe der Werte festlegen, die in der Graphik ausgeben werden sollen
// Optionen f�r die X-Achse (NICHT F�R PIE CHARTS G�LTIG!)
$xaxis='&XAxis=1'; 	// ob die X-Achse angezeigt/ausgeblenden werden soll -->> boolean: 1=true (default) 2=false
$xaxistitle="&XTitle='Name der Layer'"; // Beschriftung der X-Achses
$xaxistitlefont='&XTitleFont=S'; // Schriftgr��e der Beschriftung der X-Achse
$xaxistitlecolor='&XTitleColor=C7FCFF'; // Farbe der Beschriftung der X-Achse
$xaxislabelfontsize='&XLabelFont=T'; // Gr��e der Beschriftung der Werte der X-Achse [Values: T (Tiny) 	S (Small) 	M (Medium Bold) 	L (Large) 	G (Giant)]
$xaxislabelspace='&XLabelSpacing=3'; // Beschriebt den Mindestabstand zwischen zwei Beschriftungen der X-Achse
$xaxislabelcolor='&XLabelColor=ADADAD'; // Farbe der Beschriftung der Werte der X-Achse
// Optionen f�r die Y-Achse (NICHT F�R PIE CHART G�LTIG)
$yaxis='&YAxis=0'; // ob die Y-Achse angezeigt/ausgeblendet werden soll -->> boolean: 1=true (default) 2=false
$yaxistitle="&YTitle='Anzahl der Zugriffe'"; // Beschriftung der Y-Achse 
$yaxistitlefontsize='&YTitleFont=T'; // Schriftgr��e der Beschriftung der Y-Achse  [Values: T (Tiny) 	S (Small) 	M (Medium Bold) L (Large) 	G (Giant)]
$yaxisscaletyp='&YScaleType=LOG'; // Art der Skalierung [Values: LINEAR	LOG]
$yaxisscalemin='&YMin=50'; // Minimum der Y-Achse
$yaxisscalemin='&YMax=500'; // Maximum der Y-Achse
$yaxisscaleinterval='&YInterval=20'; // Interval der Scalierung der Y-Achse
$yaxistitlecolor='&YTitleColor=717171'; // Farbe der Beschriftung der Y-Achse
$yaxislabelfontsize='YLabelFont=L'; // Schriftgr��e der Beschriftung der Werter der Y-Achse [Values: T (Tiny) 	S (Small) 	M (Medium Bold) L (Large) 	G (Giant)]
$yaxislabelcolor='&YLabelColor=8B8B8B'; // Farbe der Beschriftung der Werte der Y-Achse

if ($this->formvars['chart']=='pie') { 
// Optionen f�r die Darstellung als PieChart    
$typ='TYPE=Pie'; // Diagrammtyp
$piepctlabelart='&PiePctType=ABOVE';  // Darstellung der Prozent-Beschriftung  [Values: NONE ABOVE BELOW RIGHT LEFT ]
$pieradius='&PieRadius=100';  // Gibt den Radius des PieChart an �berschreibt den default-Wert. 
$piefontsizelabel='&PieLabelFont=S';   // Schriftgr��e der Beschriftung [Values: T (tiny) S (small) M (medium bold) L (large) G (giant)]
$pieformatlabel='&PiePctFmt=%.2f%%'; // Formatierung der Beschriftung der 
}

if ($this->formvars['chart']=='bar'){
$typ='TYPE=Bar'; // Diagrammtyp
$barstacktype='&StackType=SUM'; // Darstellung bei mehreren Datens�tzen [Values: DEPTH (default)	SUM 	BESIDE  	LAYER]
$barwidth='&BarWidth=25'; // prozentuale Angabe �ber das Intervall der Balken entlang der X-Achse (default '75')
$barlabel='&BarLabels=0'; // Einstellung, ob die Balken beschriftet werden sollen -->> boolean: 1=true 2=false
$barformatlabel='&BarLabelFmt=---%.0f--'; // Formatierung der Beschriftung der Balken
}   

$numbofpoints='&NumPts='.count($accessarray);// Anzahl der Werte im Chart

if (count($accessarray)==''){
  if ($this->formvars['chart']=='bar') {
    $numbofpoints='&NumPts=2';
    $value_access='0!0';
    $value_label=';';

  }
  if ($this->formvars['chart']=='pie') {
    $numbofpoints='&NumPts=1';
    $value_access='';
    $value_label='';
  }
  
}
else {
	for ($i=0; $i<count($accessarray);$i++) {
	  if ($i==0) {
		$value_access=$accessarray[0]['NumberOfAccess'];
		$value_label=$accessarray[0]['lName'];
	  }
	  else {
		$value_access=$value_access.'!'.$accessarray[$i]['NumberOfAccess'];
		$value_label=$value_label.';'.$accessarray[$i]['lName'];
	  }
	}
}

$values='&VALS='.$value_access;
$xlabels='&XLabels='.$value_label;


// Einstellungen zur Ausgabe als Graphik   
$outputimagetyp='&ImageType=Png';  // Ausgabe der Graphik als Bildtyp [Values: Gif Png Jpeg Wbmp]
$qualityimage='&Jpeg_Quality=100'; // Qualtit�t der Ausgabe des Biles [Values: 1-100]

// Zusammenstellen der URL 
if ($this->formvars['chart']=='pie') {
$url=$host.$typ.$numbofsets.$numbofpoints.$values.$valueplotcolor.$xlabels.$width.$height.$bgcolour.$depth3d.$angle3d.$title.$titlecolor.$titlesize.$piepctlabelart.$pieradius.$piefontsizelabel.$pieformatlabel.$outputimagetyp.$qualityimage;
}
if ($this->formvars['chart']=='bar') {
$url=$host.$typ.$numbofsets.$numbofpoints.$values.$valueplotcolor.$xlabels.$width.$height.$bgcolour.$depth3d.$angle3d.$title.$titlecolor.$titlesize.$barwidth.$barlabel.$barformatlabel.$outputimagetyp.$qualityimage;
}
//Ausgabe der Graphik
echo '<img src="'.$url.'">';

?>
