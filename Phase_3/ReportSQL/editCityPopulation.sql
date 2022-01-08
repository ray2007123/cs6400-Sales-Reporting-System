UPDATE
    City
SET
    population = '$populationNumber'
WHERE
    state_name = '$stateName' AND city_name = '$cityName'
LIMIT 1;
