

# �bernahme der deutschen Bezeichnungen der Stellen als vorl�ufige Ma�nahme:
# �berarbeiten!
UPDATE `stelle` SET `Bezeichnung_low-german_windows-1252` = `Bezeichnung`;

# Neue Spalte f�r Plattdeutsche Men�bezeichnung in der Tabelle u_menues
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

