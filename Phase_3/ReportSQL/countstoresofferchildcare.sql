SELECT
    COUNT(store_id) AS NUMBER
FROM
    childcare
WHERE
    time_limit > 0;
