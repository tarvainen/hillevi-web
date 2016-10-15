DROP PROCEDURE IF EXISTS sp_DateDimension;

DELIMITER //

CREATE PROCEDURE sp_DateDimension (startDate DATE, endDate DATE)
BEGIN

  CREATE TABLE IF NOT EXISTS TIME_DIMENSION  (
    ID INT NOT NULL AUTO_INCREMENT,
    FULLDATE        DATE,
    DAY_OF_MONTH    INT,
    DAY_OF_YEAR     INT,
    DAY_OF_WEEK     INT,
    DAY_NAME        VARCHAR(12),
    MONTH_NUMBER    INT,
    MONTH_NAME      VARCHAR(12),
    YEAR            INT,
    QUARTER         TINYINT,
    PRIMARY KEY(ID)
  ) ENGINE=InnoDB;

  DELETE FROM TIME_DIMENSION;

  WHILE startDate < endDate DO
    INSERT INTO TIME_DIMENSION (
        FULLDATE ,
        DAY_OF_MONTH ,
        DAY_OF_YEAR ,
        DAY_OF_WEEK ,
        DAY_NAME ,
        MONTH_NUMBER,
        MONTH_NAME,
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
        YEAR(startDate),
        QUARTER(startDate)
    );

    SET startDate = DATE_ADD(startDate, INTERVAL 1 DAY);
  END WHILE;
END;

//
DELIMITER ;

-- CALL sp_DateDimension('2016-01-01', '2016-12-31');