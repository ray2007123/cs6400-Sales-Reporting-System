-- DROP TABLE IF EXISTS RevenuebyYearbyState;

SELECT state_name AS StateName FROM City;

CREATE VIEW RevenuebyYearbyState AS SELECT
St.store_id AS store_id,
St.street_address AS store_address,
St.city_name AS city_name,
DP.date_time AS date_time,
T.sold_quantity,
DP.discount_price,
P.retail_price*(1 - DP. discount_price) * T.sold_quantity AS total
FROM Store AS St
INNER JOIN Transaction AS T ON St.store_id = T.store_id
INNER JOIN DiscountPrice AS DP ON DP.pid = T.pid AND DP.date_time = T.date_time JOIN Product AS P ON P.pid= T.pid
WHERE St.state_name = 'State';

-- display by year
SELECT
              store.store_id,
              store.street_address,
              store.city_name,
              year(transaction.date_time) AS year,
              SUM(transaction.sold_quantity * IFNULL(discountprice.discount_price, product.retail_price) ) AS revenue
            FROM`store` JOIN transaction ON store.store_id=transaction.store_id
            LEFT JOIN discountprice ON transaction.date_time=discountprice.date_time AND transaction.pid=discountprice.pid
            LEFT JOIN product ON transaction.pid=product.pid
            WHERE store.state_name='$selectedState'
            GROUP BY year(transaction.date_time),store.store_id
            ORDER BY year(transaction.date_time) ASC;


-- display by TotalRevenue
SELECT
              store.store_id,
              store.street_address,
              store.city_name,
              year(transaction.date_time) AS year,
              SUM(transaction.sold_quantity * IFNULL(discountprice.discount_price, product.retail_price) ) AS revenue
            FROM store JOIN transaction ON store.store_id=transaction.store_id
            LEFT JOIN discountprice ON transaction.date_time=discountprice.date_time AND transaction.pid=discountprice.pid
            LEFT JOIN product ON transaction.pid=product.pid
            WHERE store.state_name='$selectedState'
            GROUP BY year(transaction.date_time),store.store_id
            ORDER BY revenue DESC;
