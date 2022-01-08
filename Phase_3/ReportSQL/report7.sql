CREATE VIEW temp AS
SELECT YEAR(transaction.date_time) AS year, MONTH(transaction.date_time) AS month, transaction.store_id, SUM(transaction.sold_quantity * p.retail_price) AS volumn, childcare.time_limit AS time_limit
FROM transaction
LEFT OUTER JOIN childcare ON childcare.store_id = transaction.store_id
JOIN product AS p ON p.pid = transaction.pid
GROUP BY YEAR(transaction.date_time), MONTH(transaction.date_time), transaction.store_id, time_limit
ORDER BY YEAR(transaction.date_time) DESC, MONTH(transaction.date_time) DESC;

SELECT year, month, 
	CAST(SUM(CASE WHEN time_limit <= 30 THEN volumn ELSE 0 END) AS decimal(38, 2)) AS 'Max 30 Mins',
	CAST(SUM(CASE WHEN time_limit > 30 AND time_limit <= 45 THEN volumn ELSE 0 END) AS decimal(38, 2)) AS 'Max 45 Mins',
	CAST(SUM(CASE WHEN time_limit > 45 AND time_limit <= 60 THEN volumn ELSE 0 END) AS decimal(38, 2)) AS 'Max 60 Mins',
    CAST(SUM(CASE WHEN time_limit is NULL THEN volumn ELSE 0 END) AS decimal(38, 2)) AS 'No Childcare'
FROM temp
GROUP BY year, month
ORDER BY year DESC, month DESC
LIMIT 12