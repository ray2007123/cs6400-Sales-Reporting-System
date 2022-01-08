CREATE VIEW JoinedTable AS
SELECT
    producthascategory.category_name AS category_name,
    transaction.pid AS pid,
    store.state_name AS state_name,
    transaction.sold_quantity AS sold_quantity,
    transaction.date_time AS date
FROM transaction
INNER JOIN producthascategory ON transaction.pid = producthascategory.pid
INNER JOIN store ON Store.store_id = transaction.store_id;



CREATE VIEW filterByYearAndMonth AS
SELECT
    category_name,
    state_name,
    SUM(sold_quantity) AS sum_quantity
FROM JoinedTable
WHERE YEAR(date) = '$userInputYear' AND MONTH(date)='$userInputMonth'
GROUP BY category_name, state_name;



SELECT
    filter.category_name AS category_name,
    filter.state_name AS state_name,
    filter.sum_quantity AS max_quantity
FROM filterByYearAndMonth AS filter
WHERE (category_name, sum_quantity) IN ( 
    SELECT category_name, MAX(sum_quantity)
    FROM filterByYearAndMonth
    GROUP BY category_name
      )ORDER BY category_name ASC;
      
      
SELECT
    Y.category_name,
    Y.max_quantity,
    z.state_name
FROM
    (
    SELECT
        X.category_name AS category_name,
        MAX(X.sumsold) AS max_quantity
    FROM
        (
        SELECT
            producthascategory.category_name AS category_name,
            store.state_name,
            SUM(TRANSACTION.sold_quantity) AS sumsold
        FROM
            producthascategory
        JOIN TRANSACTION ON producthascategory.pid = TRANSACTION.pid
    JOIN store ON TRANSACTION
        .store_id = store.store_id
    WHERE
        MONTH(TRANSACTION.date_time) = '$hMonth' AND YEAR(TRANSACTION.date_time) = '$hYear'
    GROUP BY
        producthascategory.category_name,
        store.state_name
    ) AS X
GROUP BY
    X.category_name
) AS Y
JOIN(
    SELECT
        producthascategory.category_name AS category_name,
        store.state_name,
        SUM(TRANSACTION.sold_quantity) AS sumsold
    FROM
        producthascategory
    JOIN TRANSACTION ON producthascategory.pid = TRANSACTION.pid
JOIN store ON TRANSACTION
    .store_id = store.store_id
WHERE
    MONTH(TRANSACTION.date_time) = '$hMonth' AND YEAR(TRANSACTION.date_time) = '$hYear'
GROUP BY
    producthascategory.category_name,
    store.state_name
) AS z
ON
    Y.category_name = z.category_name AND Y.max_quantity = z.sumsold
ORDER BY
    Y.category_name ASC;
