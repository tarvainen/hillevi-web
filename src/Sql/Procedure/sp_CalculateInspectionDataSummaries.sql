DROP PROCEDURE IF EXISTS sp_CalculateInspectionDataSummaries;

DELIMITER //

CREATE PROCEDURE sp_CalculateInspectionDataSummaries (
  UserId    INT,      -- The user's id
  StartDate DATETIME, -- Start date time of the recalculation
  EndDate   DATETIME  -- End date time of the recalculation
)
  BEGIN

    -- Define the interval: how many seconds is the 'duration' of a single row
    SET @Interval = 10 * 60; -- 10 minutes (600 seconds)

    SELECT    COALESCE(StartDate, '1990-01-01'),
      COALESCE(EndDate, '2036-01-01')
    INTO      StartDate,
      EndDate;

    DROP TEMPORARY TABLE IF EXISTS tmp_data;
    CREATE TEMPORARY TABLE tmp_data (
      UserId      INT           DEFAULT 0,
      StartTime   DATETIME,
      EndTime     DATETIME,
      KeysTyped   INT           DEFAULT 0,
      TypingSpeed DECIMAL(10,2) DEFAULT 0,
      KeyCombos   INT           DEFAULT 0
    ) ENGINE = Memory;

    /** 1. Delete all existing data between the date range */

    DELETE FROM inspection_data_summary
    WHERE       user_id = UserId AND
                startTime >= StartDate AND
                endTime <= EndDate;

    START TRANSACTION;

    /** 2. Insert all data in to the temporary table in small pieces */

    -- Typing speed and total amount of keys typed
    INSERT INTO tmp_data (
      UserId, StartTime, EndTime, KeysTyped, TypingSpeed
    )
      SELECT      UserId,
                  MIN(_keys.startTime),
                  MAX(_keys.endTime),
                  SUM(_keys.total),
                  SUM(_keys.total) / @Interval

      FROM        keystroke _keys
      WHERE       _keys.user_id = UserId
      GROUP BY    ROUND(UNIX_TIMESTAMP(_keys.startTime) / @Interval);

    -- Key combos
    INSERT INTO tmp_data (
      UserId, StartTime, EndTime, KeyCombos
    )
      SELECT      UserId,
                  MIN(_combos.startTime),
                  MAX(_combos.endTime),
                  SUM(_combos.amount)
      FROM        key_combo _combos
      WHERE       _combos.user_id = UserId
      GROUP BY    ROUND(UNIX_TIMESTAMP(_combos.startTime) / @Interval);

    /** 3. Select all from temporary table to the real table */

    INSERT INTO inspection_data_summary (
      user_id, startTime, endTime, keysTyped, typingSpeed, keyCombos
    )
      SELECT      t.UserId,
                  t.StartTime,
                  t.EndTime,
                  IFNULL(SUM(t.KeysTyped), 0),
                  IFNULL(SUM(t.TypingSpeed), 0),
                  IFNULL(SUM(t.KeyCombos), 0)
      FROM        tmp_data t
      GROUP BY    t.UserId, t.StartTime;

    COMMIT;

    SELECT * FROM inspection_data_summary;

    -- After all drop all temp tables
    DROP TEMPORARY TABLE IF EXISTS tmp_data;

  END //

DELIMITER ;

-- Example call:
-- CALL sp_CalculateInspectionDataSummaries(1, NULL, NULL);