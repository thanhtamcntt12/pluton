ALTER TABLE  `customers` ADD  `staff_id` INT( 10 ) NOT NULL AFTER  `another_user_id` ,
ADD  `customer_status` INT NOT NULL DEFAULT  '1' AFTER  `staff_id` ;
