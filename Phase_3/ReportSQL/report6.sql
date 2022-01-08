DROP VIEW IF EXISTS
    PopulationCategory;
CREATE VIEW PopulationCategory AS SELECT
    city_name,
    state_name,
    CASE 
    WHEN population < 3700000 THEN 'Small' 
    WHEN population >= 3700000 AND population < 6700000 THEN 'Medium' 
    WHEN population >= 6700000 AND population < 9000000 THEN 'Large' 
    ELSE 'Extra Large'
END AS 'Population_Category'
FROM
    City;
    
   
   
   
-- ByPopulation     
SELECT 
            x.Population_Category,
            SUM(CASE WHEN y.year=2000 THEN y.quantity * y.price ELSE 0 END) AS '2000',
            SUM(CASE WHEN y.year=2001 THEN y.quantity * y.price ELSE 0 END) AS '2001',
            SUM(CASE WHEN y.year=2002 THEN y.quantity * y.price ELSE 0 END) AS '2002',
            SUM(CASE WHEN y.year=2003 THEN y.quantity * y.price ELSE 0 END) AS '2003',
            SUM(CASE WHEN y.year=2004 THEN y.quantity * y.price ELSE 0 END) AS '2004',
            SUM(CASE WHEN y.year=2005 THEN y.quantity * y.price ELSE 0 END) AS '2005',
            SUM(CASE WHEN y.year=2006 THEN y.quantity * y.price ELSE 0 END) AS '2006',
            SUM(CASE WHEN y.year=2007 THEN y.quantity * y.price ELSE 0 END) AS '2007',
            SUM(CASE WHEN y.year=2008 THEN y.quantity * y.price ELSE 0 END) AS '2008',
            SUM(CASE WHEN y.year=2009 THEN y.quantity * y.price ELSE 0 END) AS '2009',
            SUM(CASE WHEN y.year=2010 THEN y.quantity * y.price ELSE 0 END) AS '2010',
            SUM(CASE WHEN y.year=2011 THEN y.quantity * y.price ELSE 0 END) AS '2011',
            SUM(CASE WHEN y.year=2012 THEN y.quantity * y.price ELSE 0 END) AS '2012'
            FROM
            (SELECT store.store_id, populationcategory.Population_Category FROM store 
            JOIN populationcategory ON store.city_name = populationcategory.city_name AND 
            store.state_name = populationcategory.state_name) AS x
            JOIN 
            (SELECT transaction.store_id,transaction.pid,year(transaction.date_time)AS year,
            transaction.sold_quantity AS quantity, IFNULL(DiscountPrice.discount_price, product.retail_price) AS price
            FROM transaction LEFT JOIN DiscountPrice
            ON transaction.pid=DiscountPrice.pid AND transaction.date_time=DiscountPrice.date_time
            LEFT JOIN product ON transaction.pid = product.pid) AS y 
            ON x.store_id = y.store_id
            GROUP BY x.Population_Category
            ORDER BY find_in_set(x.Population_Category,'Small,Medium,Large,Extra Large');
 
 
 
            
-- ByYear           
SELECT y.year,
    SUM(CASE WHEN x.Population_Category= 'Small' THEN y.quantity * y.price ELSE 0 END) AS 'Small',
    SUM(CASE WHEN x.Population_Category= 'Medium' THEN y.quantity * y.price ELSE 0 END) AS 'Medium',
    SUM(CASE WHEN x.Population_Category= 'Large' THEN y.quantity * y.price ELSE 0 END) AS 'Large',
    SUM(CASE WHEN x.Population_Category= 'Extra Large' THEN y.quantity * y.price ELSE 0 END) AS 'Extra Large'
FROM
    (SELECT store.store_id, 
    populationcategory.Population_Category 
        FROM store JOIN populationcategory 
            ON store.city_name = populationcategory.city_name AND store.state_name = populationcategory.state_name
    ) AS x 
    JOIN
    (SELECT transaction.store_id, 
            transaction.pid, 
            year(transaction.date_time) AS year, 
            transaction.sold_quantity AS quantity, 
            IFNULL(DiscountPrice.discount_price, product.retail_price) AS price
        FROM transaction LEFT JOIN DiscountPrice
            ON transaction.pid=DiscountPrice.pid AND transaction.date_time=DiscountPrice.date_time
        LEFT JOIN product 
            ON transaction.pid = product.pid
    ) AS y 
    ON x.store_id = y.store_id
GROUP BY y.year
ORDER BY y.year ASC;
