SELECT
    ProductHasCategory.category_name AS Category,
    Store.restaurant AS Store_Type,
    SUM(TRANSACTION.sold_quantity) AS Q_Sold
FROM TRANSACTION
INNER JOIN Store ON TRANSACTION
    .store_id = Store.store_id
INNER JOIN ProductHasCategory ON TRANSACTION
    .pid = ProductHasCategory.pid
GROUP BY
    Category,
    Store_Type
ORDER BY
    Category ASC;
