-- create view code from table geocms where path = class

CREATE OR REPLACE VIEW code AS
SELECT * FROM geocms WHERE path = 'class';