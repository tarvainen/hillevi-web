/** Event for calculating inspection data summaries in the database without separated cron jobs. */

DROP EVENT IF EXISTS e_CalculateInspectionDataSummaries;

CREATE EVENT e_CalculateInspectionDataSummaries
  ON SCHEDULE EVERY 1 MINUTE STARTS NOW()
DO BEGIN

  DECLARE i         INT DEFAULT 0;
  DECLARE n         INT DEFAULT 0;
  DECLARE u         INT DEFAULT 0;
  DECLARE startTime DATETIME;
  DECLARE endTime   DATETIME;

  -- We will call the procedure for the last 5 minutes
  SET startTime = DATE_SUB(NOW(), INTERVAL 5 MINUTE);
  SET endTime = NOW();

  SELECT COUNT(id) FROM user INTO n;

  -- Iterate through the users list
  WHILE i < n DO
    SELECT id FROM user LIMIT i,1 INTO u;

    CALL sp_CalculateInspectionDataSummaries(u, startTime, endTime);

    SET i = i + 1;
  END WHILE;

END;

DELIMITER ;
