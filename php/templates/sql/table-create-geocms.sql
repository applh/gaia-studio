
CREATE TABLE IF NOT EXISTS `geocms` 
(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    path TEXT,
    filename TEXT,
    code TEXT,
    url TEXT,
    title TEXT,
    content TEXT,
    media TEXT,
    template TEXT,
    cat TEXT,
    tags TEXT,
    created TEXT,
    updated TEXT,
    hash TEXT,
    x REAL,
    y REAL,
    z REAL,
    t REAL
);
