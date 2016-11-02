-- Function for simple debugging inside SQL magic

DROP TABLE IF EXISTS _debug;
CREATE TABLE IF NOT EXISTS _debug (
	ID 		      INT PRIMARY KEY AUTO_INCREMENT,
	MESSAGE     MEDIUMTEXT DEFAULT '',
	CREATED_AT  DATETIME
);

DROP FUNCTION IF EXISTS fnDebug;

DELIMITER //

CREATE FUNCTION fnDebug (message MEDIUMTEXT)
RETURNS MEDIUMTEXT
BEGIN

-- Write data to the debug table
INSERT INTO _debug (MESSAGE, CREATED_AT)
SELECT message, NOW();

RETURN message;

END //

DELIMITER ;

-- SELECT fnDebug('cat');
-- SELECT * FROM _debug;