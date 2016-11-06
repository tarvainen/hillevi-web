DROP PROCEDURE IF EXISTS sp_KeyboardInspectionDataSummary;

CREATE PROCEDURE sp_KeyboardInspectionDataSummary (
  UserId          INT,
  StartTime       DATETIME,
  EndTime         DATETIME,
  CalculationMode TINYINT
) BEGIN

  -- Fallback to default values if we came in with NULL
  SELECT  COALESCE(StartTime, '1990-01-01'),
          COALESCE(EndTime,   '2036-01-01'),
          COALESCE(CalculationMode, 0)
  INTO    StartTime,
          EndTime,
          CalculationMode
  ;

  DROP TEMPORARY TABLE IF EXISTS tmp_data;
  CREATE TEMPORARY TABLE tmp_data (
    userId                  INT           DEFAULT 0,
    totalKeysTyped          BIGINT        DEFAULT 0,
    averageTypingSpeed      DECIMAL(10,2) DEFAULT 0.0,
    averageKeyPressDuration DECIMAL(10,4) DEFAULT 0.0,
    totalKeyCombos          BIGINT        DEFAULT 0.0,
    keyCombosTypingSpeed    DECIMAL(10,2) DEFAULT 0.0,
    timeGroup               VARCHAR(12)
  ) ENGINE = MEMORY;

  START TRANSACTION;

  -- Basic key strokes
  INSERT INTO tmp_data (userId, totalKeysTyped, averageTypingSpeed, averageKeyPressDuration, timeGroup)
  SELECT      UserId,
              SUM(k.total),
              SUM(k.total) / SUM(TIMESTAMPDIFF(SECOND, k.startTime, k.endTime)),
              AVG(NULLIF(k.keyDownTime, 0)), -- Ignore zero values (nobody can do that, right?)
              CASE (CalculationMode)
                WHEN 0 THEN NULL
                WHEN 1 THEN HOUR(k.startTime)
                WHEN 2 THEN DAY(k.startTime)
                WHEN 3 THEN MONTH(k.startTime)
              END
  FROM        keystroke k
  WHERE       k.startTime >= StartTime AND
              k.endTime <= EndTime AND
              k.user_id = UserId
  GROUP BY    k.user_id,
              CASE (CalculationMode)
                WHEN 0 THEN NULL
                WHEN 1 THEN HOUR(k.startTime)
                WHEN 2 THEN DAY(k.startTime)
                WHEN 3 THEN MONTH(k.startTime)
              END;

  -- Key combos
  INSERT INTO tmp_data (userId, totalKeyCombos, keyCombosTypingSpeed, timeGroup)
  SELECT      UserId,
              COUNT(c.id),
              COUNT(c.id) / SUM(TIMESTAMPDIFF(SECOND, c.startTime, c.endTime)),
              CASE (CalculationMode)
              WHEN 0 THEN NULL
                WHEN 1 THEN HOUR(c.startTime)
                WHEN 2 THEN DAY(c.startTime)
                WHEN 3 THEN MONTH(c.startTime)
              END
  FROM        key_combo c
  WHERE       c.startTime >= StartTime AND
              c.endTime <= EndTime AND
              c.user_id = UserId
  GROUP BY    c.user_id,
              CASE (CalculationMode)
                WHEN 0 THEN NULL
                WHEN 1 THEN HOUR(c.startTime)
                WHEN 2 THEN DAY(c.startTime)
                WHEN 3 THEN MONTH(c.startTime)
              END;
  COMMIT;

  -- Fetch data
  SELECT      t.userId                        as userId,
              SUM(t.totalKeysTyped)           as totalKeysTyped,
              SUM(t.averageTypingSpeed)       as averageTypingSpeed,
              SUM(t.averageKeyPressDuration)  as averageKeyPressDuration,
              SUM(t.totalKeyCombos)           as totalKeyCombos,
              SUM(t.keyCombosTypingSpeed)     as keyCombosTypingSpeed,
              t.timeGroup                     as timeGroup
  FROM        tmp_data t
  GROUP BY    t.userId, t.timeGroup;

  DROP TEMPORARY TABLE IF EXISTS tmp_data;

END;

-- CALL sp_KeyboardInspectionDataSummary(1, DATE_SUB(NOW(), INTERVAL 10 MINUTE), NOW(), 0);