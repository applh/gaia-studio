CREATE TABLE IF NOT EXISTS `user`
(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name TEXT,
    email TEXT,
    description TEXT,
    passhash TEXT,
    level INTEGER,
    code TEXT,
    created TEXT,
    updated TEXT
);