<?php ob_start();  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Model extends CI_Model
{
    
    function create_db($database, $user_name, $password)
    {   
        $dsn1      = "mysqli://root:@localhost/restaurent";
        $this->db1 = $this->load->database($dsn1, true); 
        
        if($this->db1->query('CREATE DATABASE '.$database))
            if($this->db1->query('CREATE USER '.$user_name.'@localhost IDENTIFIED BY "'.$password.'"'))
             if($this->db1->query("GRANT ALL PRIVILEGES ON $database.* TO '$user_name'@'%' WITH GRANT OPTION;"))
             {
                 return $this->create_tables($database, $user_name, $password);
             }
        return FALSE;     
    }
    
    
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('accounts');
    }

    public function create($password)
    {
        $data['name']               = $this->input->post('name');
        $data['surname']            = $this->input->post('surname');
        $data['restaurant_name']    = $this->input->post('restaurant_name');
        $data['email']              = $this->input->post('email');
        $data['phone']              = $this->input->post('phone');
        $data['city']               = $this->input->post('city');
        $data['sku']                = $this->input->post('sku');
        $data['unique']             = uniqid('-STORE-');
        
        $data['comments']           = $this->input->post('comments');
        $data['password']           = $password;
                
        $this->db->insert('accounts', $data);        
        return $this->db->insert_id();
    }
    
    public function get_account($id)
    {        
        $query = $this->db->select('sku, name, surname, restaurant_name')->from('accounts')->where('id', $id)->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        return FALSE;
    }
    
    public function get_account_by_unique($id)
    {        
        $query = $this->db->select('sku')->from('accounts')->where('unique', $id)->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        return FALSE;
    }

    public function create_tables($database, $user_name, $password)
    { 
        
        $dsn1      = "mysqli://$user_name:$password@localhost/$database";
        $this->db1 = $this->load->database($dsn1, true);  
        
        
        $query = "CREATE TABLE IF NOT EXISTS $database.`app_sessions` (
                `session_id` varchar(40) NOT NULL DEFAULT '0',
                `ip_address` varchar(45) NOT NULL DEFAULT '0',
                `user_agent` varchar(120) NOT NULL,
                `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
                `user_data` text NOT NULL,
                PRIMARY KEY (`session_id`),
                KEY `last_activity_idx` (`last_activity`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `attributes` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `index` varchar(50) NOT NULL COMMENT 'Index to check attribute names',
                `type` varchar(10) NOT NULL,
                `name` varchar(150) NOT NULL COMMENT 'product attribute display text',
                `values` text NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created date',
                PRIMARY KEY (`id`),
                UNIQUE KEY `index` (`index`),
                KEY `text` (`name`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Products attributes' AUTO_INCREMENT=7 ;
              ";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `categories` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `index` varchar(50) NOT NULL,
                `name` varchar(50) NOT NULL COMMENT 'category name',
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `index` (`index`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `comments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `meal_id` int(11) NOT NULL,
                `order_id` int(11) NOT NULL,
                `comment` text NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `groups` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(20) NOT NULL,
                `description` varchar(100) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";
        
        $this->db1->query($query);
        
        $query = "INSERT INTO `groups` (`id`, `name`, `description`) VALUES
                (1, 'admin', 'Super Admin'),
                (2, 'owner', 'Store Owner'),
                (3, 'waiter', 'Waiter'),
                (4, 'customer', 'Customer');";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `logs` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `errno` int(2) NOT NULL,
                `errtype` varchar(32) NOT NULL,
                `errstr` text NOT NULL,
                `errfile` varchar(255) NOT NULL,
                `errline` int(4) NOT NULL,
                `user_agent` varchar(120) NOT NULL,
                `ip_address` varchar(45) NOT NULL DEFAULT '0',
                `time` datetime NOT NULL,
                PRIMARY KEY (`id`,`ip_address`,`user_agent`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `meals` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `index` varchar(150) NOT NULL COMMENT 'unique identifier in string format',
                `name` varchar(150) NOT NULL COMMENT 'meal title',
                `description` text NOT NULL COMMENT 'Description of meals',
                `price` float NOT NULL COMMENT 'Price for meal',
                `quantity` int NOT NULL COMMENT 'Quantity for meal',
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'created date',
                PRIMARY KEY (`id`),
                UNIQUE KEY `index` (`index`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `meal_attributes` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `meal_id` int(11) NOT NULL COMMENT 'Forign key to meals table',
                `attribute_id` int(11) NOT NULL COMMENT 'Forign key to attribute table',
                PRIMARY KEY (`id`),
                KEY `fk_attr_attributes` (`attribute_id`),
                KEY `fk_meal_attributes` (`meal_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `meal_categories` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `meal_id` int(11) NOT NULL COMMENT 'Forign key to meals table',
                `category_id` int(11) NOT NULL COMMENT 'Forign key to category table',
                PRIMARY KEY (`id`),
                KEY `fk_meal_categories` (`meal_id`),
                KEY `fk_category_categories` (`category_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `orders` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `table_id` int(11) NOT NULL,
                `user_id` int(11) NOT NULL,
                `customer_name` varchar(100) NOT NULL COMMENT 'name enterd by customer',
                `price` float NOT NULL DEFAULT '0',
                `active` int(1) NOT NULL DEFAULT '1',
                `status` varchar(50) NOT NULL COMMENT 'paybill',
                `master` int(1) NOT NULL DEFAULT '0',
                `self_payment` int(1) NOT NULL DEFAULT '1',
                `payed_by` int(11) NOT NULL,
                `reserved_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `released_time` datetime NOT NULL,
                `closed_by` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_orders_table` (`table_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
              ";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `order_details` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `order_id` int(11) NOT NULL,
                `meal_id` int(11) NOT NULL COMMENT 'id from meals table',
                `price` float NOT NULL COMMENT 'price of meal',
                `attribute` text NOT NULL,
                `processed` int(1) NOT NULL DEFAULT '0' COMMENT 'waiter processed or not',
                `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `process_time` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `meal_order_fk` (`meal_id`),
                KEY `FK_orders_order` (`order_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
              ";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `tables` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier',
                `name` varchar(150) NOT NULL COMMENT 'name of the tables',
                `seats` int(11) NOT NULL COMMENT 'number of seats for a table',
                `qr_code` varchar(250) NOT NULL COMMENT 'QR code to access table',
                `unique` varchar(150) NOT NULL,
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'table added date',
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique` (`unique`),
                UNIQUE KEY `name` (`name`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `users` (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `ip_address` varbinary(16) NOT NULL,
                `username` varchar(100) NOT NULL,
                `password` varchar(80) NOT NULL,
                `salt` varchar(40) DEFAULT NULL,
                `email` varchar(100) NOT NULL,
                `activation_code` varchar(40) DEFAULT NULL,
                `forgotten_password_code` varchar(40) DEFAULT NULL,
                `forgotten_password_time` int(11) unsigned DEFAULT NULL,
                `remember_code` varchar(40) DEFAULT NULL,
                `created_on` int(11) unsigned NOT NULL,
                `last_login` int(11) unsigned DEFAULT NULL,
                `active` tinyint(1) unsigned DEFAULT NULL,
                `first_name` varchar(50) DEFAULT NULL,
                `last_name` varchar(50) DEFAULT NULL,
                `company` varchar(100) DEFAULT NULL,
                `phone` varchar(20) DEFAULT NULL,
                `site` varchar(50) NOT NULL COMMENT 'Site id to which this user belongs to',
                PRIMARY KEY (`id`),
                UNIQUE KEY `email` (`email`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
              ";
        
        $this->db1->query($query);
        
        $query = "INSERT INTO `users` (`email`,`created_on`, `password`, `active`, `first_name`, `last_name`, `company`, `phone`, `site`) VALUES
                ('admin@admin.com', 1460453630, 'e77b2506649d6a557d233b55a475518c24cf294c',  1, 'Admin', 'User', 'Nuro', '123-123-2346', ''), 
                ('".$this->input->post('email')."', 1460453630, '".$password."',  1, '".$this->input->post('name')."', '".$this->input->post('surname')."', '', '".$this->input->post('phone')."', '')
                ";
        
        $this->db1->query($query);
        $query = "CREATE TABLE IF NOT EXISTS `users_groups` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `group_id` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_group_groups` (`group_id`),
                KEY `fk_user_groups` (`user_id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;
              ";
        
        $this->db1->query($query);
        
        $query = "INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES(1, 1, 1), (2, 2, 2)";
        
        $this->db1->query($query);
        
        $query = "CREATE TABLE IF NOT EXISTS `waiter_notice` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `table_id` int(11) NOT NULL,
                `message` varchar(250) NOT NULL DEFAULT 'Please give attention to',
                `status` int(1) NOT NULL DEFAULT '1',
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11;";
        $this->db1->query($query);

        $query = "ALTER TABLE `meal_attributes`
                ADD CONSTRAINT `fk_attr_attributes` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
                ADD CONSTRAINT `fk_meal_attributes` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE CASCADE;";
        
        $this->db1->query($query);
        
        $query = "ALTER TABLE `meal_categories`
                ADD CONSTRAINT `fk_category_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
                ADD CONSTRAINT `fk_meal_categories` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE CASCADE;";
        
        $this->db1->query($query);
        
        $query = "ALTER TABLE `orders`
                  ADD CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
        
        $this->db1->query($query);
        
        $query = "ALTER TABLE `order_details`
                ADD CONSTRAINT `FK_orders_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                ADD CONSTRAINT `meal_order_fk` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
        
        $this->db1->query($query);
        
        $query ="CREATE TABLE `waiters` (
                `id` int(11) NOT NULL COMMENT 'unique identifier',
                `name` varchar(100) NOT NULL COMMENT 'name of waiter',
                `unique` varchar(150) NOT NULL COMMENT 'unique identifier',
                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
              );";
        $this->db1->query($query);
        
        return TRUE;
    }
}