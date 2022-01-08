SELECT
       set1.pid,
       set1.name,
       set1.retailprice,
       set1.totalsold,
       set2.discountsold,
       set1.totalsold-set2.discountsold as retailprice_sold,
       set1.totalsold * set1.retailprice AS actual_revenue,
       set1.totalsold * set1.retailprice * 0.75 AS pre_revenue,
       set3.difference
       FROM (
           SELECT transaction.pid AS pid,
           product.product_name AS name,
           SUM(transaction.sold_quantity) AS totalsold,
           product.retail_price AS retailprice
           FROM transaction
               JOIN producthascategory AS pc ON transaction.pid = pc.pid
               JOIN product ON transaction.pid = product.pid
           WHERE pc.category_name = 'Couches and Sofas'
           GROUP BY transaction.pid) AS set1
       JOIN(
           SELECT transaction.pid AS pid,
           SUM(transaction.sold_quantity) AS discountsold

           FROM transaction
               JOIN producthascategory AS pc ON transaction.pid = pc.pid
               JOIN discountprice ON transaction.pid = discountprice.pid AND transaction.date_time = discountprice.date_time
           WHERE pc.category_name = 'Couches and Sofas'
           GROUP BY transaction.pid) AS set2
       ON set1.pid = set2.pid
       JOIN(
           SELECT
               x.pid AS pid,
               x.ar - x.pr AS difference
           FROM (
               SELECT transaction.pid AS pid,
               SUM(
                   product.retail_price * transaction.sold_quantity * 0.75
               ) AS pr,
               SUM(
                   transaction.sold_quantity * discountprice.discount_price
               ) AS ar

               FROM transaction
               JOIN product ON transaction.pid = product.pid
               JOIN producthascategory AS pc
               ON transaction.pid = pc.pid
               JOIN discountprice ON transaction.pid = discountprice.pid AND transaction.date_time = discountprice.date_time
               WHERE pc.category_name = 'Couches and Sofas'
               GROUP BY transaction.pid) AS x
               HAVING difference > 5000 OR Difference <-5000) AS set3 ON set1.pid = set3.pid
       ORDER BY difference DESC;
