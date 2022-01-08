/*SQL for "addHoliday" */
SELECT * FROM holiday WHERE DATE(date_time) = '$hDate';
UPDATE holiday SET name = '$hName' WHERE DATE(date_time) = '$hDate';
INSERT INTO holiday(name, date_time) VALUES ('$hName', '$hDate');"; 
