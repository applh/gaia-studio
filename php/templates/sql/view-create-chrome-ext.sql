-- create view code from table geocms where path = chrome-ext

CREATE OR REPLACE VIEW `chrome-ext` AS
SELECT * FROM `geocms` WHERE `path` = 'chrome-ext';