-- create sqlite table task with columns is not exists
-- id, path, filename, code, content, cat, tags, status, x, y, z, t, created_at, updated_at
-- id is auto increment

CREATE TABLE IF NOT EXISTS task 
(
    id INTEGER PRIMARY KEY AUTOI_NCREMENT,
    path TEXT NOT NULL,
    filename TEXT NOT NULL,
    code TEXT NOT NULL,
    content TEXT NOT NULL,
    cat TEXT NOT NULL,
    tags TEXT NOT NULL,
    status TEXT NOT NULL,
    x INTEGER NOT NULL,
    y INTEGER NOT NULL,
    z INTEGER NOT NULL,
    t INTEGER NOT NULL,
    created TEXT NOT NULL,
    updated TEXT NOT NULL
);


