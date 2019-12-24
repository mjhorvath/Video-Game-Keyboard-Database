-- VOLATILE!!!
-- See: https://stackoverflow.com/questions/59473548/comments-in-imported-text-file

SET FOREIGN_KEY_CHECKS = 0;
LOAD DATA LOCAL INFILE 'C:\\Users\\mikh2\\Documents\\GitHub\\Video-Game-Keyboard-Diagrams\\sql_scripts\\_bindings.txt' INTO TABLE isometr1_keyboard.bindings
CHARACTER SET 'utf8'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n';

SET FOREIGN_KEY_CHECKS = 0;
LOAD DATA LOCAL INFILE 'C:\\Users\\mikh2\\Documents\\GitHub\\Video-Game-Keyboard-Diagrams\\sql_scripts\\_commands.txt' INTO TABLE isometr1_keyboard.commands
CHARACTER SET 'utf8'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n';

SET FOREIGN_KEY_CHECKS = 0;
LOAD DATA LOCAL INFILE 'C:\\Users\\mikh2\\Documents\\GitHub\\Video-Game-Keyboard-Diagrams\\sql_scripts\\_legends.txt' INTO TABLE isometr1_keyboard.legends
CHARACTER SET 'utf8'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n';

SET FOREIGN_KEY_CHECKS = 0;
LOAD DATA LOCAL INFILE 'C:\\Users\\mikh2\\Documents\\GitHub\\Video-Game-Keyboard-Diagrams\\sql_scripts\\_contrib_games.txt' INTO TABLE isometr1_keyboard.contrib_games
CHARACTER SET 'utf8'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n';

SET FOREIGN_KEY_CHECKS = 0;
LOAD DATA LOCAL INFILE 'C:\\Users\\mikh2\\Documents\\GitHub\\Video-Game-Keyboard-Diagrams\\sql_scripts\\_contrib_styles.txt' INTO TABLE isometr1_keyboard.contrib_styles
CHARACTER SET 'utf8'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n';

SET FOREIGN_KEY_CHECKS = 0;
LOAD DATA LOCAL INFILE 'C:\\Users\\mikh2\\Documents\\GitHub\\Video-Game-Keyboard-Diagrams\\sql_scripts\\_contrib_layouts.txt' INTO TABLE isometr1_keyboard.contrib_layouts
CHARACTER SET 'utf8'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n';

-- note that the "lowcap_optional" column has a bit data type and requires special handling
-- see: https://stackoverflow.com/questions/15683809/load-data-from-csv-inside-bit-field-in-mysql
SET FOREIGN_KEY_CHECKS = 0;
LOAD DATA LOCAL INFILE 'C:\\Users\\mikh2\\Documents\\GitHub\\Video-Game-Keyboard-Diagrams\\sql_scripts\\_positions.txt' INTO TABLE isometr1_keyboard.positions
CHARACTER SET 'utf8'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n'
(position_id,layout_id,key_number,position_left,position_top,position_width,position_height,symbol_norm_cap,symbol_norm_low,symbol_altgr_cap,symbol_altgr_low,@lowcap_optional,@numpad)
set lowcap_optional=cast(@lowcap_optional as signed)
set numpad=cast(@numpad as signed)
SHOW WARNINGS;

SET FOREIGN_KEY_CHECKS = 0;
LOAD DATA LOCAL INFILE 'C:\\Users\\mikh2\\Documents\\GitHub\\Video-Game-Keyboard-Diagrams\\sql_scripts\\_records_styles.txt' INTO TABLE isometr1_keyboard.records_styles
CHARACTER SET 'utf8'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n';


EDIT isometri_keyboard.bindings
WHERE game_id = 95;


ALTER TABLE bindings MODIFY column key_number tinyint(3) unsigned NOT NULL AFTER layout_id;


DELETE FROM isometr1_keyboard.bindings
WHERE game_id = 113;

DELETE FROM isometr1_keyboard.commands
WHERE game_id = 113;

CHECK TABLE isometri_keyboard.bindings FOR UPGRADE;


--VOLATILE!!!
SET FOREIGN_KEY_CHECKS=0;
delete from bindings;
delete from commands;
delete from cutouts;
delete from effects;
delete from games;
delete from genres;
delete from keystyles;
delete from layouts;
delete from legends;
delete from platforms;
delete from positions;
delete from stylegroups;
delete from styles;


--VOLATILE!!!
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE (bindings);
TRUNCATE TABLE (commands);
TRUNCATE TABLE (cutouts);
TRUNCATE TABLE (effects);
TRUNCATE TABLE (games);
TRUNCATE TABLE (genres);
TRUNCATE TABLE (keystyles);
TRUNCATE TABLE (layouts);
TRUNCATE TABLE (legends);
TRUNCATE TABLE (platforms);
TRUNCATE TABLE (positions);
TRUNCATE TABLE (stylegroups);
TRUNCATE TABLE (styles);


use isometri_keyboard;
SET FOREIGN_KEY_CHECKS=0;


ALTER DATABASE isometri_keyboard DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.bindings CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.combos CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.cutouts CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.effects CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.games CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.genres CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.joysticks CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.layouts CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.legends CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.mice CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.notes CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.platforms CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.positions CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE isometri_keyboard.styles CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
