DROP PROCEDURE IF EXISTS sp_TimeDimension;

CREATE PROCEDURE sp_TimeDimension (startDate DATE, endDate DATE)
BEGIN

  DROP TABLE IF EXISTS TIME_DIMENSION;

  CREATE TABLE IF NOT EXISTS TIME_DIMENSION  (
    ID INT NOT NULL AUTO_INCREMENT,
    FULLDATE        DATE,
    DAY_OF_MONTH    INT,
    DAY_OF_YEAR     INT,
    DAY_OF_WEEK     INT,
    DAY_NAME        VARCHAR(12),
    MONTH_NUMBER    INT,
    MONTH_NAME      VARCHAR(12),
    WEEK_NUMBER     INT,
    YEAR            INT,
    QUARTER         TINYINT,
    PRIMARY KEY(ID)
  ) ENGINE=InnoDB;

  START TRANSACTION;

  WHILE startDate < endDate DO
    INSERT INTO TIME_DIMENSION (
        FULLDATE ,
        DAY_OF_MONTH ,
        DAY_OF_YEAR ,
        DAY_OF_WEEK ,
        DAY_NAME ,
        MONTH_NUMBER,
        MONTH_NAME,
        WEEK_NUMBER,
        YEAR,
        QUARTER
    ) VALUES (
        startDate,
        DAYOFMONTH(startDate),
        DAYOFYEAR(startDate),
        DAYOFWEEK(startDate),
        DAYNAME(startDate),
        MONTH(startDate),
        MONTHNAME(startDate),
        WEEK(startDate),
        YEAR(startDate),
        QUARTER(startDate)
    );

    SET startDate = DATE_ADD(startDate, INTERVAL 1 DAY);
  END WHILE;

  COMMIT;
END;

CALL sp_TimeDimension('2000-01-01', '2036-12-31');