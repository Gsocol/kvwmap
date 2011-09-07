# Hinzuf�gen von Plattdeutsch bei der Angabe einer Sprache und Character Set f�r die Rolle
ALTER TABLE `rolle` CHANGE `language` `language` ENUM( 'german', 'low-german', 'english', 'vietnamese' ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT 'german';

# Hinzuf�gen einer Spalte f�r die plattdeutsche Bezeichnung der Stellen
ALTER TABLE `stelle` ADD `Bezeichnung_low-german_windows-1252` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NULL AFTER `Bezeichnung`;
# �bernahme der deutschen Bezeichnungen der Stellen als vorl�ufige Ma�nahme:
# �berarbeiten!
UPDATE `stelle` SET `Bezeichnung_low-german_windows-1252` = `Bezeichnung`;

# Neue Spalte f�r Plattdeutsche Men�bezeichnung in der Tabelle u_menues
ALTER TABLE `u_menues` ADD `name_low-german_windows-1252` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_german1_ci NULL AFTER `name`;
# �bernahme der deutschen Bezeichnungen der Men�s als vorl�ufige Ma�nahme:
# �berarbeiten!
UPDATE `u_menues` SET `name_low-german_windows-1252` = `name`;

# �bersetzungshilfen Deutsch - Plattdeutsch
#
# Administration: Administratschoon
# Adresse: Adress
# Antrag: Andrag
# Allgemein: Allgemeen
# Auskunft: Utkunft
# Blatt: Bl��d (Mz. Bl�der)
# Bodenrichtwert: Boddenrichtweert
# Drucken: Printen
# Druckrahmen: Printrohmen
# Eigent�mer: Egend�mer
# Fachschale: Fachschaal
# Festpunkt: Wisst�ttel
# Funktion: Funkschoon (Mz. Funkschonen)
# Gutachterausschuss: Utschuss vun'n Gootachters
# Hilfe: H�lp
# Karte: Koort
# Nachweis: Nahwies
# Nutzer: Bruker
# Sonstige: S�ssige
# Stelle: St�e
# Suche: S�k
# Zone: Zoon
#
# Abrechnung: Afreken
# �nderung: �nnerung
# Erfassung: Upnahm
# Verwaltung: Verwalten
#
# aktualisieren: opfrischen
# anlegen: anleggen
# eingeben: ingeven
# einf�gen: inf�gen
# erstellen: herstellen
# kopieren:  koperen
# �bernehmen: �vernehmen

