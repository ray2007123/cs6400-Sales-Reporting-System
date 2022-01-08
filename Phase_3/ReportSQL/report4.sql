SELECT
    X.year,
    X.total_sold,
    X.ave_sold,
    Y.groundhogsold
FROM
    (
    SELECT
        YEAR(TRANSACTION.date_time) AS YEAR,
        SUM(TRANSACTION.sold_quantity) AS total_sold,
        SUM(TRANSACTION.sold_quantity) / 365 AS ave_sold
    FROM
        product
    JOIN producthascategory ON product.pid = producthascategory.pid
    JOIN TRANSACTION ON product.pid = TRANSACTION.pid
WHERE
    producthascategory.category_name = 'Outdoor Furniture'
GROUP BY
    YEAR
) AS X
LEFT JOIN(
    SELECT
        YEAR(date_time) AS YEAR,
        SUM(sold_quantity) AS groundhogsold
    FROM TRANSACTION
JOIN producthascategory ON TRANSACTION
    .pid = producthascategory.pid
WHERE
    MONTH(date_time) = 02 AND DAY(date_time) = 02 AND producthascategory.category_name = 'Outdoor Furniture'
GROUP BY
    YEAR(date_time)
) AS Y
ON
    X.year = Y.year
ORDER BY
    `x`.`year` ASC;
