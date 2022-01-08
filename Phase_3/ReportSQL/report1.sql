#Category Report
SELECT phc.category_name AS category_name, 
COUNT(p.pid) AS product_count, 
MIN(p.retail_price) AS min_price,
AVG(p.retail_price) AS avg_price,
MAX(p.retail_price) AS max_price
FROM Product AS p
INNER JOIN ProductHasCategory AS phc ON phc.pid = p.pid GROUP BY category_name
ORDER BY category_name ASC
