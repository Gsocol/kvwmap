BEGIN;

ALTER TABLE `styles` CHANGE `width` `width` VARCHAR(50) NULL DEFAULT NULL;

COMMIT;
