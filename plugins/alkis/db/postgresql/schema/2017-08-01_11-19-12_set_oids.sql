BEGIN;

SET search_path = alkis;

ALTER TABLE ap_pto SET WITH OIDS;
ALTER TABLE ax_anderefestlegungnachwasserrecht SET WITH OIDS;
ALTER TABLE ax_anschrift SET WITH OIDS;
ALTER TABLE ax_aufnahmepunkt SET WITH OIDS;
ALTER TABLE ax_besondereflurstuecksgrenze SET WITH OIDS;
ALTER TABLE ax_besonderegebaeudelinie SET WITH OIDS;
ALTER TABLE ax_besonderergebaeudepunkt SET WITH OIDS;
ALTER TABLE ax_buchungsblatt SET WITH OIDS;
ALTER TABLE ax_buchungsblattbezirk SET WITH OIDS;
ALTER TABLE ax_buchungsstelle SET WITH OIDS;
ALTER TABLE ax_denkmalschutzrecht SET WITH OIDS;
ALTER TABLE ax_dienststelle SET WITH OIDS;
ALTER TABLE ax_flurstueck SET WITH OIDS;
ALTER TABLE ax_forstrecht SET WITH OIDS;
ALTER TABLE ax_fortfuehrungsfall SET WITH OIDS;
ALTER TABLE ax_gebaeude SET WITH OIDS;
ALTER TABLE ax_gemarkung SET WITH OIDS;
ALTER TABLE ax_georeferenziertegebaeudeadresse SET WITH OIDS;
ALTER TABLE ax_grablochderbodenschaetzung SET WITH OIDS;
ALTER TABLE ax_grenzpunkt SET WITH OIDS;
ALTER TABLE ax_historischesflurstueckohneraumbezug SET WITH OIDS;
ALTER TABLE ax_historischesflurstueck SET WITH OIDS;
ALTER TABLE ax_historischesflurstueckalb SET WITH OIDS;
ALTER TABLE ax_historischesflurstueckohneraumbezug SET WITH OIDS;
ALTER TABLE ax_klassifizierungnachstrassenrecht SET WITH OIDS;
ALTER TABLE ax_lagebezeichnungkatalogeintrag SET WITH OIDS;
ALTER TABLE ax_lagebezeichnungohnehausnummer SET WITH OIDS;
ALTER TABLE ax_musterlandesmusterundvergleichsstueck SET WITH OIDS;
ALTER TABLE ax_naturumweltoderbodenschutzrecht SET WITH OIDS;
ALTER TABLE ax_punktortta SET WITH OIDS;
ALTER TABLE ax_schutzgebietnachnaturumweltoderbodenschutzrecht SET WITH OIDS;
ALTER TABLE ax_schutzgebietnachwasserrecht SET WITH OIDS;
ALTER TABLE ax_sicherungspunkt SET WITH OIDS;
ALTER TABLE ax_sonstigervermessungspunkt SET WITH OIDS;
ALTER TABLE ax_sonstigesbauwerkodersonstigeeinrichtung SET WITH OIDS;
ALTER TABLE ax_turm SET WITH OIDS;

COMMIT;