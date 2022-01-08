-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_Spring21_team017`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_Spring21_team017 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_Spring21_team017;


GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_Spring21_team017`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;



-- Tables 
CREATE TABLE City (
    city_name         varchar(32)        NOT NULL,
    state_name        char(2)            NOT NULL,
    population        int(16) unsigned    NOT NULL, 
    PRIMARY KEY (city_name, state_name) 
);


CREATE TABLE Store (
    store_id            int(8) unsigned    NOT NULL, 
    phone_number        varchar(16)          NOT NULL, 
    street_address      varchar(256)        NOT NULL, 
    city_name           varchar(32)        NOT NULL,
    state_name          char(2)            NOT NULL,
    restaurant          boolean             NOT NULL,
    snack_bar           boolean             NOT NULL,
    PRIMARY KEY (store_id)
); 

CREATE TABLE ChildCare (
    store_id          int(8) unsigned    NOT NULL, 
    time_limit        int(16)             NOT NULL, 
    PRIMARY KEY (store_id, time_limit) 

); 


CREATE TABLE Product (
    pid                 int(8) unsigned        NOT NULL, 
    product_name        varchar(64)            NOT NULL, 
    retail_price        double                  NOT NULL, 
    PRIMARY KEY (pid) 
); 


CREATE TABLE Category (
    category_name           varchar(64)        NOT NULL, 
    PRIMARY KEY (category_name)
); 


CREATE TABLE ProductHasCategory (
    pid                 int(8) unsigned       NOT NULL, 
    category_name       varchar(64)           NOT NULL, 
    PRIMARY KEY (pid, category_name)
); 

 

CREATE TABLE Transaction (
    pid                 int(8) unsigned    NOT NULL, 
    store_id            int(8) unsigned    NOT NULL, 
    date_time           date                NOT NULL, 
    sold_quantity       int(32)             NOT NULL        DEFAULT 0, 
    PRIMARY KEY (pid, store_id , date_time)    
); 



CREATE TABLE DiscountPrice (
    pid                  int(8) unsigned    NOT NULL, 
    date_time            date                NOT NULL, 
    discount_price       double              NOT NULL,   
    PRIMARY KEY (pid, date_time)
); 



CREATE TABLE TimeDate (
    date_time            date                NOT NULL, 
    PRIMARY KEY (date_time)
); 

CREATE TABLE Campaign (
    date_time            date                NOT NULL, 
    description          varchar(256)        NOT NULL, 
    PRIMARY KEY (date_time, description)
); 


CREATE TABLE Holiday (
    date_time           date                NOT NULL, 
    holiday_name        varchar(64)        NOT NULL, 
    PRIMARY KEY (date_time, holiday_name)
); 



-- Constraints   Foreign Keys: FK_ChildTable_childColumn_ParentTable_parentColumn
ALTER TABLE Store 
    ADD CONSTRAINT fk_Store_city_name_state_name_City_city_name_state_name    FOREIGN KEY (city_name, state_name)     REFERENCES City (city_name, state_name); 


ALTER TABLE ChildCare 
     ADD CONSTRAINT fk_ChildCare_store_id_Store_store_id       FOREIGN KEY (store_id)  REFERENCES Store (store_id);


ALTER TABLE ProductHasCategory
    ADD CONSTRAINT fk_ProductHasCategory_pid_Product_pid                FOREIGN KEY (pid)           REFERENCES Product (pid), 
    ADD CONSTRAINT fk_ProductHasCategory_category_name_Category_category_name    FOREIGN KEY (category_name) REFERENCES Category (category_name); 



ALTER TABLE Transaction  
    ADD CONSTRAINT fk_Transaction_store_id_Store_store_id       FOREIGN KEY (store_id)  REFERENCES Store (store_id), 
    ADD CONSTRAINT fk_Transaction_pid_Product_pid               FOREIGN KEY (pid)       REFERENCES Product (pid), 
    ADD CONSTRAINT fk_Transaction_date_time_Date_date_time FOREIGN KEY (date_time) REFERENCES TimeDate (date_time); 
 


ALTER TABLE Campaign 
    ADD CONSTRAINT fk_Campaign_date_time_Date_date_time    FOREIGN KEY (date_time) REFERENCES TimeDate (date_time); 

   
ALTER TABLE Holiday 
    ADD CONSTRAINT fk_Holiday_date_time_Date_date_time     FOREIGN KEY (date_time) REFERENCES TimeDate (date_time); 
