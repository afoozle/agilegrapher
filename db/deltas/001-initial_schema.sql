--//

--
-- Initial Schema Creation
-- Author: Matthew Wheeler <matt@yurisko.net>

CREATE TABLE task (
    task_id         INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name            TEXT NOT NULL,
    description     TEXT NOT NULL,
    created         DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    completed       DATETIME
);

--//@UNDO

DROP TABLE task;

--//
