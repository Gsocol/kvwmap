BEGIN;

CREATE OR REPLACE VIEW jagdkataster.befriedete_bezirke_flurstuecke AS 
 SELECT b.id, a.flurstueckskennzeichen, a.amtlicheflaeche, round((st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric)::double precision))::numeric, 0) AS fst_teilflaeche_abs, round((st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric)::double precision) / a.amtlicheflaeche * 100::double precision)::numeric, 1) AS fst_teilflaeche_proz, ((nas.nutzungsartengruppe::text || nas.nutzungsart::text) || nas.untergliederung1::text) || nas.untergliederung2::text AS naschluessel, COALESCE(bn.bezeichnung, nag.gruppe) AS nutzung, round((st_area_utm(st_intersection(b.the_geom, st_intersection(n.wkb_geometry, a.wkb_geometry)), 25833, 6384000::numeric)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric)::double precision))::numeric, 0) AS na_teilflaeche_abs, round((st_area_utm(st_intersection(b.the_geom, st_intersection(n.wkb_geometry, a.wkb_geometry)), 25833, 6384000::numeric)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric)::double precision) / (st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric)::double precision)) * 100::double precision)::numeric, 1) AS na_teilflaeche_proz
   FROM jagdkataster.befriedete_bezirke b
   LEFT JOIN alkis.ax_flurstueck a ON st_intersects(b.the_geom, a.wkb_geometry) AND a.endet IS NULL AND (st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric) / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric) * 100::numeric) > 0.1
   LEFT JOIN alkis.n_nutzung n ON st_intersects(n.wkb_geometry, a.wkb_geometry) AND st_area_utm(st_intersection(n.wkb_geometry, a.wkb_geometry), 25833, 6384000::numeric) > 0.001 AND st_area_utm(st_intersection(b.the_geom, st_intersection(n.wkb_geometry, a.wkb_geometry)), 25833, 6384000::numeric) > 0.1
   LEFT JOIN alkis.n_nutzungsartenschluessel nas ON n.nutzungsartengruppe = nas.nutzungsartengruppe AND n.werteart1 = nas.werteart1 AND n.werteart2 = nas.werteart2
   LEFT JOIN alkis.n_nutzungsartengruppe nag ON nas.nutzungsartengruppe = nag.schluessel
   LEFT JOIN jagdkataster.befriedete_bezirke_nutzungen bn ON bn.schluessel::text = (((nas.nutzungsartengruppe::text || nas.nutzungsart::text) || nas.untergliederung1::text) || nas.untergliederung2::text)
  ORDER BY a.flurstueckskennzeichen, round((st_area_utm(st_intersection(b.the_geom, st_intersection(n.wkb_geometry, a.wkb_geometry)), 25833, 6384000::numeric)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric)::double precision) / (st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric)::double precision)) * 100::double precision)::numeric, 1) DESC;


COMMIT;