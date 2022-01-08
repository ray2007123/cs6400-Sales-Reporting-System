
-- Sales(Transaction) With Discount Price During Ad Campaign
CREATE VIEW SalesDuringCampaign AS
(
SELECT DISTINCT discount_sales_date, temp.pid, p.product_name, sold_quantity, discount_price
FROM
(
	SELECT d.date_time AS discount_sales_date, s.pid, s.sold_quantity, d.discount_price
	FROM discountprice d JOIN transaction s
	ON d.pid = s.pid AND d.date_time = s.date_time
) 
temp
JOIN campaign ac ON ac.date_time = temp.discount_sales_date
JOIN product p ON p.pid = temp.pid
);

-- All Sales(Transaction) With Discount Price
CREATE VIEW AllDiscountSales AS
(
SELECT d.date_time AS discount_sales_date, s.pid, p.product_name, sold_quantity, d.discount_price
FROM discountprice d 
JOIN transaction s ON d.pid = s.pid AND d.date_time = s.date_time
JOIN product p ON p.pid = s.pid
);

-- Sales(Transaction) With Discount Price Outside Ad Campaign
CREATE VIEW SalesOutsideCampaign AS
(
SELECT AllDiscountSales.* FROM AllDiscountSales
LEFT JOIN SalesDuringCampaign ON 
    AllDiscountSales.discount_sales_date=SalesDuringCampaign.discount_sales_date
    AND AllDiscountSales.pid=SalesDuringCampaign.pid
WHERE SalesDuringCampaign.discount_sales_date IS NULL
);


-- Final Output
CREATE VIEW CampaignAnalysis AS
(
SELECT temp_out_ads.pid AS ProductID, temp_out_ads.product_name AS ProductName, SoldDuringCampaign, SoldOutsideCampaign, SoldDuringCampaign - SoldOutsideCampaign AS Difference
FROM
(SELECT pid, product_name, SUM(sold_quantity) AS SoldOutsideCampaign
FROM SalesOutsideCampaign 
GROUP BY pid, product_name
) temp_out_ads
JOIN
(
SELECT pid, product_name, SUM(sold_quantity) AS SoldDuringCampaign
FROM SalesDuringCampaign 
GROUP BY pid, product_name
) temp_during_ads
ON temp_during_ads.pid = temp_out_ads.pid
)
;

-- Only the top 10, followed by the bottom 10 from these results should be in the final report output
SELECT * FROM (SELECT * FROM CampaignAnalysis ORDER BY Difference DESC LIMIT 10) top10
UNION
SELECT * FROM (SELECT * FROM CampaignAnalysis ORDER BY Difference ASC LIMIT 10) bottom10
ORDER BY Difference DESC


