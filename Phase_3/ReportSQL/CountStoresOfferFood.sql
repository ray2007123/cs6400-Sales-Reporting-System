SELECT
    COUNT(store_id) AS NUMBER
FROM
    store
WHERE
    restaurant = 1 OR snack_bar = 1;
