USE game_database;


CREATE TABLE game_states (
	game_states_id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	state VARCHAR(30) NOT NULL,
	abbr CHAR(2) NOT NULL,
	PRIMARY KEY (game_states_id)
) ENGINE = InnoDB;

INSERT INTO game_states(state, abbr) VALUES
	('Alabama', 'AL'),
	('Alaska', 'AK'),
	('Arizona', 'AZ'),
	('Arkansas', 'AR'),
	('California', 'CA'),
	('Colorado', 'CO'),
	('Connecticut', 'CT'),
	('Delaware', 'DE'),
	('District of Columbia', 'DC'),
	('Florida', 'FL'),
	('Georgia', 'GA'),
	('Hawaii', 'HI'),
	('Idaho', 'ID'),
	('Illinois', 'IL'),
	('Indiana', 'IN'),
	('Iowa', 'IA'),
	('Kansas', 'KS'),
	('Kentucky', 'KY'),
	('Louisiana', 'LA'),
	('Maine', 'ME'),
	('Maryland', 'MD'),
	('Massachusetts', 'MA'),
	('Michigan', 'MI'),
	('Minnesota', 'MN'),
	('Mississippi', 'MS'),
	('Missouri', 'MO'),
	('Montana', 'MT'),
	('Nebraska', 'NE'),
	('Nevada', 'NV'),
	('New Hampshire', 'NH'),
	('New Jersey', 'NJ'),
	('New Mexico', 'NM'),
	('New York', 'NY'),
	('North Carolina', 'NC'),
	('North Dakota', 'ND'),
	('Ohio', 'OH'),
	('Oklahoma', 'OK'),
	('Oregon', 'OR'),
	('Pennsylvania', 'PA'),
	('Rhode Island', 'RI'),
	('South Carolina', 'SC'),
	('South Dakota', 'SD'),
	('Tennessee', 'TN'),
	('Texas', 'TX'),
	('Utah', 'UT'),
	('Vermont', 'VT'),
	('Virginia', 'VA'),
	('Washington', 'WA'),
	('West Virginia', 'WV'),
	('Wisconsin', 'WI'),
	('Wyoming', 'WY');

CREATE TABLE game_customers (
	game_customers_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT, 
	first_name VARCHAR(30) NOT NULL,
	last_name VARCHAR(30) NOT NULL,
	email VARCHAR(40) NOT NULL,
	phone VARCHAR(20) NOT NULL,
	password CHAR(255) NOT NULL,
	address_1 VARCHAR(100) NOT NULL,
	address_2 VARCHAR(100),
	city VARCHAR(50) NOT NULL,
	game_states_id TINYINT UNSIGNED NOT NULL,
	zip CHAR(5) NOT NULL,
	date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(game_customers_id),
	INDEX ind_game_states_id (game_states_id),
	CONSTRAINT fk_game_states_id FOREIGN KEY (game_states_id) REFERENCES game_states(game_states_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE = InnoDB;

create table game_transactions(
	game_transactions_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	amount_charged DECIMAL(6, 3) NOT NULL, 
	type VARCHAR(100) NOT NULL, 
	response_code VARCHAR(200) NOT NULL, 
	response_reason VARCHAR(200) NOT NULL, 
	response_text VARCHAR(400) NOT NULL, 
	date_created TIMESTAMP NOT NULL, 
	PRIMARY KEY(game_transactions_id)
) ENGINE = InnoDB;

ALTER TABLE game_transactions MODIFY COLUMN amount_charged DECIMAL(6,2) NOT NULL;

create table game_shipping_addresses(
	game_shipping_addresses_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	address_1 VARCHAR(100) NOT NULL, 
	address_2 VARCHAR(100), 
	city VARCHAR(50) NOT NULL, 
	game_states_id TINYINT UNSIGNED NOT NULL, 
	zip CHAR(5) NOT NULL, 
	date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(game_shipping_addresses_id)
) ENGINE = InnoDB;

create table game_billing_addresses(
	game_billing_addresses_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	address_1 VARCHAR(100) NOT NULL, 
	address_2 VARCHAR(100), 
	city VARCHAR(50) NOT NULL, 
	game_states_id TINYINT UNSIGNED NOT NULL, 
	zip CHAR(5) NOT NULL, 
	date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(game_billing_addresses_id)
) ENGINE = InnoDB;

create table game_carriers_methods(
	game_carriers_methods_id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	carrier VARCHAR(50) NOT NULL, 
	method VARCHAR(50) NOT NULL, 
	fee DECIMAL(6, 3) NOT NULL, 
	PRIMARY KEY(game_carriers_methods_id)
) ENGINE = InnoDB;

ALTER TABLE game_carriers_methods MODIFY COLUMN fee DECIMAL(6,2) NOT NULL;

INSERT INTO game_carriers_methods (carrier, method, fee) VALUES 
('UPS', 'Ground', '4.99'),
('UPS', 'Express', '9.99'),
('USPS', 'Standard', '3.99'),
('USPS', 'Expedited', '6.99'),
('FEDEX', 'Same Day', '49.99'),
('FEDEX', 'One Day', '29.99'),
('FEDEX', 'Three Days', '9.99');

create table game_orders(
	game_orders_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	game_customers_id MEDIUMINT UNSIGNED NOT NULL,
	game_transactions_id INT UNSIGNED NOT NULL,
	game_shipping_addresses_id INT UNSIGNED NOT NULL,
	game_carriers_methods_id TINYINT UNSIGNED NOT NULL,
	game_billing_addresses_id INT UNSIGNED NOT NULL,
	credit_no CHAR(4) NOT NULL, 
	credit_type VARCHAR(20) NOT NULL, 
	order_total DECIMAL(7, 3) NOT NULL, 
	shipping_fee DECIMAL(6, 3) NOT NULL, 
	order_date TIMESTAMP NOT NULL, 
	shipping_date TIMESTAMP NOT NULL, 
	PRIMARY KEY(game_orders_id),
	INDEX ind_game_customers_id (game_customers_id),
	CONSTRAINT fk_game_customers_id FOREIGN KEY (game_customers_id) REFERENCES game_customers(game_customers_id)
	ON DELETE CASCADE on UPDATE CASCADE,
	INDEX ind_game_transactions_id (game_transactions_id),
	CONSTRAINT fk_game_transactions_id FOREIGN KEY (game_transactions_id) REFERENCES game_transactions(game_transactions_id)
	ON DELETE CASCADE on UPDATE CASCADE,
	INDEX ind_game_shipping_addresses_id (game_shipping_addresses_id),
	CONSTRAINT fk_game_shipping_addresses_id FOREIGN KEY (game_shipping_addresses_id) REFERENCES game_shipping_addresses(game_shipping_addresses_id)
	ON DELETE CASCADE on UPDATE CASCADE,
	INDEX ind_game_carriers_methods_id (game_carriers_methods_id),
	CONSTRAINT fk_game_carriers_methods_id FOREIGN KEY (game_carriers_methods_id) REFERENCES game_carriers_methods(game_carriers_methods_id)
	ON DELETE CASCADE on UPDATE CASCADE,
	INDEX ind_game_billing_addresses_id (game_billing_addresses_id),
	CONSTRAINT fk_game_billing_addresses_id FOREIGN KEY (game_billing_addresses_id) REFERENCES game_billing_addresses(game_billing_addresses_id)
	ON DELETE CASCADE on UPDATE CASCADE
) ENGINE = InnoDB;

ALTER TABLE game_orders MODIFY COLUMN order_total DECIMAL(6,2) NOT NULL;
ALTER TABLE game_orders MODIFY COLUMN shipping_fee DECIMAL(6,2) NOT NULL;

create table game_categories(
	game_categories_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	category VARCHAR(100) NOT NULL,
	description VARCHAR(1000),
	PRIMARY KEY(game_categories_id)
) ENGINE = InnoDB;

create table game_editions(
	game_editions_id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	edition VARCHAR(20) NOT NULL,
	PRIMARY KEY(game_editions_id)
) ENGINE = InnoDB;

INSERT INTO game_editions (edition) VALUES
	('standard'),
	('deluxe'),
	('limited'),
	('anniversary'),
	('collectors'),
	('classic');
	
UPDATE game_editions set edition = 'Standard' WHERE game_editions_id = 1;
UPDATE game_editions set edition = 'Deluxe' WHERE game_editions_id = 2;
UPDATE game_editions set edition = 'Limited' WHERE game_editions_id = 3;
UPDATE game_editions set edition = 'Anniversary' WHERE game_editions_id = 4;
UPDATE game_editions set edition = 'Collectors' WHERE game_editions_id = 5;
UPDATE game_editions set edition = 'Classic' WHERE game_editions_id = 6;
	
create table game_conditions(
	game_conditions_id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	keyword VARCHAR(20) NOT NULL,
	PRIMARY KEY(game_conditions_id)
) ENGINE = InnoDB;

INSERT INTO game_conditions (keyword) VALUES
	('new - unopened'),
	('new - opened'),
	('used - like new'),
	('used - fine'),
	('used - worn'),
	('used - missing pieces');
	
UPDATE game_conditions set keyword = 'New' WHERE game_conditions_id = 1;
UPDATE game_conditions set keyword = 'Used - Like new' WHERE game_conditions_id = 2;
UPDATE game_conditions set keyword = 'Used - Good' WHERE game_conditions_id = 3;
UPDATE game_conditions set keyword = 'Used - Fine' WHERE game_conditions_id = 4;
UPDATE game_conditions set keyword = 'Used - Worn' WHERE game_conditions_id = 5;
UPDATE game_conditions set keyword = 'Used - Missing pieces' WHERE game_conditions_id = 6;

create table games(
	games_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	game_categories_id SMALLINT UNSIGNED NOT NULL,
	game_editions_id TINYINT UNSIGNED NOT NULL,
	game_conditions_id TINYINT UNSIGNED NOT NULL,
	price DECIMAL(6,3) NOT NULL,
	photo VARCHAR(100),
	stock_quantity MEDIUMINT UNSIGNED NOT NULL,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(games_id),
	INDEX ind_game_categories_id (game_categories_id),
	CONSTRAINT fk_game_categories_id FOREIGN KEY (game_categories_id) REFERENCES game_categories(game_categories_id)
	ON DELETE CASCADE on UPDATE CASCADE,
	INDEX fk_game_editions_id (game_editions_id),
	CONSTRAINT fk_game_editions_id FOREIGN KEY (game_editions_id) REFERENCES game_editions(game_editions_id)
	ON DELETE CASCADE on UPDATE CASCADE,
	INDEX ind_game_conditions_id (game_conditions_id),
	CONSTRAINT fk_game_conditions_id FOREIGN KEY (game_conditions_id) REFERENCES game_conditions(game_conditions_id)
	ON DELETE CASCADE on UPDATE CASCADE
) ENGINE = InnoDB;

ALTER TABLE games MODIFY COLUMN price DECIMAL(6,2) NOT NULL;

create table game_orders_games(
	game_orders_games_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	game_orders_id INT UNSIGNED NOT NULL,
	games_id MEDIUMINT UNSIGNED NOT NULL,
	quantity TINYINT UNSIGNED NOT NULL,
	price DECIMAL(7,3) NOT NULL,
	PRIMARY KEY(game_orders_games_id),
	INDEX ind_game_orders_id (game_orders_id),
	CONSTRAINT fk_game_orders_id FOREIGN KEY (game_orders_id) REFERENCES game_orders(game_orders_id)
	ON DELETE CASCADE on UPDATE CASCADE,
	INDEX ind_games_id (games_id),
	CONSTRAINT fk_games_id FOREIGN KEY (games_id) REFERENCES games(games_id)
	ON DELETE CASCADE on UPDATE CASCADE
) ENGINE = InnoDB;

ALTER TABLE game_orders_games MODIFY COLUMN price DECIMAL(6,2) NOT NULL;

create table game_carts(
	game_carts_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	game_customers_id MEDIUMINT UNSIGNED NOT NULL, 
	games_id MEDIUMINT UNSIGNED NOT NULL, 
	quantity TINYINT UNSIGNED NOT NULL, 
	date_added TIMESTAMP NOT NULL, 
	date_modified TIMESTAMP NOT NULL, 
	PRIMARY KEY(game_carts_id),
	INDEX ind_game_customers_id (game_customers_id),
	CONSTRAINT fk_carts_game_customers_id FOREIGN KEY (game_customers_id) REFERENCES game_customers(game_customers_id)
	ON DELETE CASCADE on UPDATE CASCADE,
	INDEX ind_games_id (games_id),
	CONSTRAINT fk_carts_games_id FOREIGN KEY (games_id) REFERENCES games(games_id)
	ON DELETE CASCADE on UPDATE CASCADE
) ENGINE = InnoDB;

ALTER TABLE game_conditions MODIFY COLUMN keyword varchar(100);
ALTER TABLE game_shipping_addresses MODIFY COLUMN address_2 varchar(100);
ALTER TABLE game_billing_addresses MODIFY COLUMN address_2 varchar(100);


INSERT INTO game_customers (first_name, last_name, email, phone, password, address_1, address_2, city, game_states_id, zip, date_created) VALUES
	('Bauer', 'Jack', 'jbauer@24.com', '024-242-4242', 'mypassword', '24 Cliche Plaza', 'Washington D.C. - CIA 24 NSA Ave', 'Washington D.C.', 36, '24242', current_timestamp),
	('Moe', 'Curly', 'mlc@stooges.com', '333-456-5555', 'mypassword', '3 Stooge lane', '', 'Los Angeles', 36, '44555', current_timestamp),
	('Blah', 'Yada', 'Blegh@yahoo.com', '555-555-5555', 'mypassword', '42 Electric Ave', '', 'New York City', 36, '55555', current_timestamp);
	
INSERT INTO game_transactions (amount_charged, type, response_code, response_reason, response_text, date_created) VALUES
	(58.89, 'regular', '100', '', 'OK', current_timestamp),
	(22.98, 'regular', '100', '', 'OK', current_timestamp);

INSERT INTO game_shipping_addresses (address_1, address_2, city, game_states_id, zip, date_created) VALUES 
	('42 Electric Ave', '', 'New York City', 36, '55555', current_timestamp);

INSERT INTO game_billing_addresses (address_1, address_2, city, game_states_id, zip, date_created) VALUES
	('24 Cliche Plaze', 'Washington D.C. - CIA 24 NSA Ave', 'Washington D.C.', 36, '24242', current_timestamp);	

INSERT INTO game_orders (game_customers_id, game_transactions_id, game_shipping_addresses_id, game_billing_addresses_id, game_carriers_methods_id, credit_no, credit_type, order_total, shipping_fee, shipping_date, order_date) VALUES
	(1, 1, 1, 1, 3, '2222', 'Visa', 23.99, 4.99, current_timestamp, current_timestamp),
	(1, 2, 1, 1, 4, '8983', 'Discover', 59.99, 2.99, current_timestamp, current_timestamp);
	
select * from game_customers;
select * from game_transactions;
select * from game_shipping_addresses;
select * from game_billing_addresses;
select * from game_orders;

SELECT first_name, last_name, credit_no, credit_type FROM game_customers, game_orders 
	WHERE game_customers.game_customers_id = game_orders.game_customers_id;

SELECT first_name, last_name, credit_no, credit_type FROM game_customers 
	INNER JOIN game_orders ON game_customers.game_customers_id = game_orders.game_customers_id;

SELECT first_name, last_name, credit_no, credit_type FROM game_customers 
	INNER JOIN game_orders USING (game_customers_id);
	
SELECT first_name, last_name, credit_no, credit_type, CONCAT_WS(' ', game_shipping_addresses.address_1, game_shipping_addresses.address_2, game_shipping_addresses.city, state, game_shipping_addresses.zip) as 'Shipping Address' FROM game_customers 
	INNER JOIN game_orders USING (game_customers_id)
	INNER JOIN game_shipping_addresses USING (game_shipping_addresses_id)
	INNER JOIN game_states ON game_shipping_addresses.game_states_id = game_states.game_states_id;

SELECT first_name, last_name, credit_no, credit_type, CONCAT_WS(' ', game_shipping_addresses.address_1, game_shipping_addresses.address_2, game_shipping_addresses.city, state, game_shipping_addresses.zip) as 'Shipping Address' FROM game_customers 
	LEFT JOIN game_orders USING (game_customers_id)
	LEFT JOIN game_shipping_addresses USING (game_shipping_addresses_id)
	LEFT JOIN game_states ON game_shipping_addresses.game_states_id = game_states.game_states_id 
	WHERE game_shipping_addresses.city = 'Youngstown'
	ORDER BY last_name ASC
	;
	
SELECT first_name, last_name, credit_no, credit_type, CONCAT_WS(' ', game_shipping_addresses.address_1, game_shipping_addresses.address_2, game_shipping_addresses.city, state, game_shipping_addresses.zip) as 'Shipping Address' FROM game_states 
	RIGHT JOIN game_shipping_addresses ON game_shipping_addresses.game_states_id = game_states.game_states_id 
	RIGHT JOIN game_orders USING (game_shipping_addresses_id)
	RIGHT JOIN game_customers USING (game_customers_id);
	
INSERT INTO game_categories (category, description) 
	VALUES
	('Strategio', 'Outwit and out maneuver your opponents!'),
	('Triviz', 'Who is the smartest? Who knows the most useless random factoids? Play with your friends and find out!'),
	('Puzzlo', 'Over 1001 puzzles for you and your friends!'),
	('Versus!', 'Gather your friends and get ready to go head to head in multiple competitive challenges!.'),
	('Partie', '100 quick party games that are guaranteed to liven any gathering.');

SELECT * from game_categories;

INSERT INTO games (game_categories_id, stock_quantity, price, date_added, game_editions_id, game_conditions_id)
	VALUES
	(1, 55, 29.99, current_timestamp(), 1, 1),
	(2, 127, 19.99,current_timestamp(), 1, 1),
	(3, 45, 39.99, current_timestamp(), 1, 1),
	(4, 76, 15.99, current_timestamp(), 1, 1),
	(5, 234, 20.99, current_timestamp(), 1, 1);
	
INSERT INTO games (game_categories_id, stock_quantity, price, date_added, game_editions_id, game_conditions_id)
	VALUES
	(1, 23, 49.99, current_timestamp(), 2, 1),
	(2, 12, 39.99,current_timestamp(), 2, 1),
	(3, 16, 59.99, current_timestamp(), 2, 1),
	(4, 31, 35.99, current_timestamp(), 2, 1),
	(5, 27, 40.99, current_timestamp(), 2, 1);
	
INSERT INTO games (game_categories_id, stock_quantity, price, date_added, game_editions_id, game_conditions_id)
	VALUES
	(1, 8, 39.99, current_timestamp(), 3, 1),
	(3, 4, 49.99, current_timestamp(), 3, 1);
	
INSERT INTO games (game_categories_id, stock_quantity, price, date_added, game_editions_id, game_conditions_id)
	VALUES
	(1, 23, 12.99, current_timestamp(), 1, 2),
	(2, 12, 7.99,current_timestamp(), 1, 2),
	(5, 27, 6.99, current_timestamp(), 1, 2);
	
INSERT INTO games (game_categories_id, stock_quantity, price, date_added, game_editions_id, game_conditions_id)
	VALUES
	(1, 2, 4.99, current_timestamp(), 1, 3),
	(3, 6, 5.99, current_timestamp(), 1, 3),
	(4, 3, 3.99, current_timestamp(), 1, 3),
	(5, 7, 4.99, current_timestamp(), 1, 3);
	
INSERT INTO games (game_categories_id, stock_quantity, price, date_added, game_editions_id, game_conditions_id)
	VALUES
	(1, 3, 12.99, current_timestamp(), 4, 1),
	(2, 10, 7.99,current_timestamp(), 4, 1),
	(5, 7, 6.99, current_timestamp(), 6, 1);

SELECT * FROM games;
	
INSERT INTO game_orders_games (game_orders_id, games_id, quantity, price)
	VALUES
	(3, 1, 1, 19.99),
	(3, 2, 1, 24.99),
	(4, 3, 1, 19.99);
	
/*View User Account Info including 
first_name, last_name, email, phone, address, date_created*/

SELECT first_name, last_name, email, phone, CONCAT_WS(' ',address_1, address_2, city, abbr, zip) as address, date_created
	from game_customers 
	INNER JOIN game_states USING(game_states_id);

/*View Available Hats including
category, size, color, price, photo*/

SELECT category, description, edition, keyword, code, price, stock_quantity, date_added
	FROM games
	INNER JOIN game_categories USING (game_categories_id)
	INNER JOIN game_editions USING (game_editions_id)
	INNER JOIN game_conditions USING (game_conditions_id)
	ORDER BY $order_by $asc_desc;

/*
View previous orders including
category, size, color, price, photo, shipping address, billing address, credit_card, shipping_date, order_date, order_total, shipping_cost
*/

SELECT game_orders_games.game_orders_id, CONCAT_WS(' ',game_shipping_addresses.address_1, game_shipping_addresses.address_2, game_shipping_addresses.city, state, game_shipping_addresses.zip) as 'Shipping Address', CONCAT_WS(' ',game_billing_addresses.address_1, game_billing_addresses.address_2, game_billing_addresses.city, state, game_billing_addresses.zip) as 'Billing Address', GROUP_CONCAT(category SEPARATOR '<br><hr>') as category, GROUP_CONCAT(edition SEPARATOR '<br><hr>') as edition, GROUP_CONCAT(keyword SEPARATOR '<br><hr>') as keyword, GROUP_CONCAT(game_orders_games.quantity SEPARATOR '<br><hr>') as quantity, GROUP_CONCAT(game_orders_games.price SEPARATOR '<br><hr>') as price, credit_no, credit_type, order_total, shipping_fee, order_date, shipping_date
	FROM game_customers
	INNER JOIN game_states USING (game_states_id)
	INNER JOIN game_orders USING (game_customers_id)
	INNER JOIN game_shipping_addresses USING (game_shipping_addresses_id)
	INNER JOIN game_billing_addresses USING (game_billing_addresses_id)
	INNER JOIN game_orders_games USING (game_orders_id)
	INNER JOIN games USING (games_id)
	INNER JOIN game_categories USING (game_categories_id)
	INNER JOIN game_editions USING (game_editions_id)
	INNER JOIN game_conditions USING (game_conditions_id)
	WHERE game_customers_id = 1
	GROUP BY game_orders_games.game_orders_id
	ORDER BY $order_by $asc_desc;

/* Update hat_colors table */

UPDATE hat_colors set code = '#800080' WHERE hat_colors_id = 8;
UPDATE hat_colors set code = '#FFFF00' WHERE hat_colors_id = 9; 
UPDATE hat_colors set code = '#00FF00' WHERE hat_colors_id = 10;
UPDATE hat_colors set code = '#FF00FF' WHERE hat_colors_id = 11;
UPDATE hat_colors set code = '#C0C0C0' WHERE hat_colors_id = 12;
UPDATE hat_colors set code = '#808080' WHERE hat_colors_id = 13;
UPDATE hat_colors set code = '#FFA500' WHERE hat_colors_id = 14;
UPDATE hat_colors set code = '#A52A2A' WHERE hat_colors_id = 15;
UPDATE hat_colors set code = '#800000' WHERE hat_colors_id = 16;
UPDATE hat_colors set code = '#008000' WHERE hat_colors_id = 17;
UPDATE hat_colors set code = '#808000' WHERE hat_colors_id = 18; 

ALTER table game_carriers_methods add column fee decimal(6, 3) not null; 
UPDATE game_carriers_methods set fee = '4.99' WHERE game_carriers_methods_id = 1;
UPDATE game_carriers_methods set fee = '9.99' WHERE game_carriers_methods_id = 2;
UPDATE game_carriers_methods set fee = '3.99' WHERE game_carriers_methods_id = 3;
UPDATE game_carriers_methods set fee = '6.99' WHERE game_carriers_methods_id = 4;
UPDATE game_carriers_methods set fee = '49.99' WHERE game_carriers_methods_id = 5;
UPDATE game_carriers_methods set fee = '29.99' WHERE game_carriers_methods_id = 6;
UPDATE game_carriers_methods set fee = '9.99' WHERE game_carriers_methods_id = 7;

/*
 = equal to 
 != not equal to
 < less than
 > greater than
 <= less than or equal to
 >= greater than or equal to
 IS NULL
 IS NOT NULL 
 BETWEEN
 NOT BETWEEN
 IN
 
 Logical operators
 AND / &&
 OR / ||
 
 LIKE NOT LIKE -> _ and % 
 _ one single character of anything
 % 0 or more of any character
 select first_name, last_name from hat_customers where first_name LIKE 'A___' ORDER BY last_name asc;
 select first_name, last_name from hat_customers where first_name LIKE 'A%' order by first_name asc, last_name DESC;
 select state, abbr from hat_states order by state asc limit 5;
 UPDATE hat_customers SET first_name = 'David' WHERE hat_customers_id = 1;
 DELETE from hat_customers WHERE hat_customers_id = 1 LIMIT 1; 
 
 
 */
 
