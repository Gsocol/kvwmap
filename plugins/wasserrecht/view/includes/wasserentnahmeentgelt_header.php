<div class="wasserrecht_display_table" style="width: 670px">
    <div class="wasserrecht_display_table_row">
    	<div class="wasserrecht_display_table_cell_caption">Erhebungsjahr:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <div class="wasserrecht_display_table_cell_white"><?php echo $erhebungsjahr ?></div>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Behörde:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php
            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Behoerde'] . '&value_id=' . $wrz->behoerde->getId() . '&operator_id==">' . $wrz->behoerde->getName() .'</a>';
        ?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Adressat:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php 
            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Personen'] . '&value_personen_id=' . $wrz->adressat->getId() . '&operator_personen_id==">' . $wrz->adressat->getName() .'</a>';
        ?>
    </div>
    
    <div class="wasserrecht_display_table_row">
    	<div class="wasserrecht_display_table_row_spacer"></div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <div class="wasserrecht_display_table_row_spacer"></div>
    </div>
    
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Anlage:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php 
            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Anlagen'] . '&value_anlage_id=' . $wrz->anlagen->getId() . '&operator_anlage_id==">' . $wrz->anlagen->getName() . '</a>';
        ?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Wasserrechtliche Zulassung:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php 
            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getBezeichnung() . '</a>';
        ?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Benutzung:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php
            if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->getBezeichnung()))
    		{
    		    echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe Gewässerbenutzungen'] . '&value_gwb_id=' . $gewaesserbenutzung->getId() . '&operator_gwb_id==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
    		}
    		else
    		{
    		    echo '<div class="wasserrecht_display_table_cell_white"></div>';
    		}
    	?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Hinweise:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php
    		 echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['FisWrV-WRe WrZ'] . '&value_wrz_id=' . $wrz->getId() . '&operator_wrz_id==">' . $wrz->getHinweisHTML() . '</a>';
    	?>
    </div>
    
   <div class="wasserrecht_display_table_row">
    	<div class="wasserrecht_display_table_row_spacer"></div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <div class="wasserrecht_display_table_row_spacer"></div>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Benutzungsart:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
         <?php
             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungArt))
    		 {
    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Art'] . '&value_id=' . $gewaesserbenutzung->gewaesserbenutzungArt->getId() . '&operator_id==">' . $gewaesserbenutzung->gewaesserbenutzungArt->getName() . '</a>';
    		 }
    		 else
    		 {
    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
    		 }
    	?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Benutzungszweck:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
         <?php
             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungZweck))
    		 {
    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Zweck'] . '&value_id=' . $gewaesserbenutzung->gewaesserbenutzungZweck->getId() . '&operator_id==">' . $gewaesserbenutzung->gewaesserbenutzungZweck->getName() . '</a>';
    		 }
    		 else
    		 {
    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
    		 }
    	?>
    </div>
    <div class="wasserrecht_display_table_cell_caption">Benutzungsumfang:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
         <?php
             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungUmfang))
    		 {
    		     echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=Layer-Suche_Suchen&selected_layer_id=' . $this->layer_names['Gewaesserbenutzungen_Umfang_Entnahme'] . '&value_id=' . $gewaesserbenutzung->gewaesserbenutzungUmfang->getId() . '&operator_id==">' . $gewaesserbenutzung->gewaesserbenutzungUmfang->getErlaubterUmfangHTML() . '</a>';
    		 }
    		 else
    		 {
    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
    		 }
    	?>
    </div>
</div>