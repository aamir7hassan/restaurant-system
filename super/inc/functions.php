<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	defined('__EXEC__') or die("Cannot access file directly!");
	
	
	$con =  mysqli_connect ('takki-db.cgqeqdugauga.us-east-2.rds.amazonaws.com','admin' ,'Temp1234' ,'takki' ) or die(mysqli_error());
	if(!$con) {
	   die('disconnected');
	} 
	function db()
	{
		return new MysqliDb ('takki-db.cgqeqdugauga.us-east-2.rds.amazonaws.com', 'admin', 'Temp1234', 'takki');
	}
	

	function option1($id)
	{
		$db = db();

		$db->where('id', $id);
		if($db->update('accounts', array('packages' => 'Option 1')))
			return "<span class='alert alert-success'>Account package changed successfully.</span>";
		else
			return "<span class='alert alert-danger'>Account package failed to update.</span>";
	}

	function option2($id)
	{
		$db = db();

		$db->where('id', $id);
		if($db->update('accounts', array('packages' => 'Option 2')))
			return "<span class='alert alert-success'>Account package changed successfully.</span>";
		else
			return "<span class='alert alert-danger'>Account package failed to update.</span>";
	}

	function option3($id)
	{
		$db = db();

		$db->where('id', $id);
		if($db->update('accounts', array('packages' => 'Option 3')))
			return "<span class='alert alert-success'>Account package changed successfully.</span>";
		else
			return "<span class='alert alert-danger'>Account package failed to update.</span>";
	}

	function option4($id)
	{
		$db = db();

		$db->where('id', $id);
		if($db->update('accounts', array('packages' => 'Option 4')))
			return "<span class='alert alert-success'>Account package changed successfully.</span>";
		else
			return "<span class='alert alert-danger'>Account package failed to update.</span>";
	}

	function delivery($id,$val,$sku) {
		global $con;
		if($val!="") {
			$del = $val=="1"?"0":"1";
			$q = "update accounts set delivery='".$del."' where id=".$id;
			$res = mysqli_query($con,$q);
			// if($sku!="") {
				// $qs = "update `".$sku."_settings` set value='$del' where `index` = 'delivery_show'";
				// $r = mysqli_query($con,$qs);
				// if($r) {
					// return "<span class='alert alert-success'>Delivery status updated.</span>";
				// } else {
					// return "<span class='alert alert-danger'>Delivery status failed to update.</span>";
				// }
			// }
			if($res) {
				return "<span class='alert alert-success'>Delivery status updated.</span>";
			} else {
				return "<span class='alert alert-danger'>Delivery status failed to update.</span>";
			}
		}
	}
	
	function reservation($id,$val,$sku) {
		global $con;
		if($val!="") {
			$del = $val=="1"?"0":"1";
			$q = "update accounts set reservation='".$del."' where id=".$id;
			$res = mysqli_query($con,$q);
			if($res) {
				return "<span class='alert alert-success'>Reservation status updated.</span>";
			} else {
				return "<span class='alert alert-danger'>Reservation status failed to update.</span>";
			}
		}
	}
	
	function getAccounts($ids='') {
		global $con;
		if(!empty($ids)) {
			$where = 'where id in ('.$ids.')  && `delete` = 0';
		} else {
			$where="where `delete` = 0";
		}
		$q = 'select * from accounts '.$where;
		$r = mysqli_query($con,$q);
		if(is_object($r) && $r->num_rows > 0) {
			return $r;
		} 
		return FALSE;
	}
	
	function getSettings($tbl) {
		$db = db();
		if($tbl!=""){
			$settings = $db->get($tbl);
			if ($db->count > 0)
				return $settings;
			return FALSE;
		}
	}

	function getReportOrders($tbl,$start=null,$end=null) {
		global $con;
		$total_sales = 0;
		$delivery    = 0;
		if(is_null($start) && is_null($end)) {
			$q = "select price,tip,type,delivery_charge from $tbl where status = 'paid'";
		} else {
			$start = date('Y-m-d',strtotime($start));
			$end = date('Y-m-d',strtotime($end));
			$q = "select price,tip,type,delivery_charge from $tbl where status = 'paid' && date(reserved_time) >= '$start' && date(released_time) <= '$end'";
		}
		if($tbl!="") {
			$res = mysqli_query($con,$q);
			if(is_object($res) && $res->num_rows > 0) {
				while($row = mysqli_fetch_assoc($res)) {
					$total = $row['price'] + $row['tip'] + $row['delivery_charge'];
					$total_sales += $total;
					if($row['type']=='delivery') {
						$delivery += $row['price'] + $row['tip']+ $row['delivery_charge'];
					}
				}
			}
		}
		$data['total_sales'] = $total_sales;
		$data['delivery']	 = $delivery;
		return $data;
	}

	function accounts($id=NULL)
	{
		$db = db();

		$db->where('`delete`', 0);
		$accounts = $db->get ("accounts");
		if ($db->count > 0)
			return $accounts;
		return FALSE;
	}
	
	function enable($id)
	{
		$db = db();

		$db->where('id', $id);
		if($db->update('accounts', array('status' => 1)))
			return "<span class='alert alert-success'>Account enabled successfully.</span>";
		else
			return "<span class='alert alert-danger'>Account enable failed.</span>";
	}

	function disable($id)
	{
		$db = db();

		$db->where('id', $id);
		if($db->update('accounts', array('status' => 0)))
			return "<span class='alert alert-success'>Account disabled successfully.</span>";
		else
			return "<span class='alert alert-danger'>Account disable failed.</span>";

	}

	function update($id){
		$db = db();

		if($db->rawQuery('UPDATE accounts SET display = CASE WHEN `display` = 1 THEN 0 ELSE 1 END WHERE id = ?', array($id)))
			return "<span class='alert alert-success'>Account updated successfully.</span>";
		else
			return "<span class='alert alert-danger'>Account update failed.</span>";
	}

	function delete($id)
	{
		$db = db();

		$db->where('id', $id);
		if($db->update('accounts', array('delete' => '1')))
			return "<span class='alert alert-success'>Account deleted successfully.</span>";
		else
			return "<span class='alert alert-danger'>Account deletion failed.</span>";

	}

	function duplicate($id)
	{
		$db = db();

		$db->where('id', $id);
		$db->where('`delete`', 0);
		$account = $db->getOne("accounts");

		if ($db->count > 0) {
			$old_sku        = $account['sku'];
			$old_sku_parts  = explode('_', $old_sku);
			$new_sku        = uniqid();
			unset($account['id']);

			$account['restaurant_name'] = $account['restaurant_name'].' - copy';
			$account['sku']     = $new_sku;
			$account['unique']  = $new_sku;
			$account['delete']  = 1;
			$account['created'] = Date('Y-m-d H:i:s');

			$db->startTransaction();

			$new_id         = $db->insert('accounts', $account);

			$query1  = $db->rawQuery("CREATE TABLE `{$new_sku}_app_sessions` LIKE `{$old_sku}_app_sessions`");
			$insert1 = $db->rawQuery("INSERT `{$new_sku}_app_sessions` SELECT * FROM `{$old_sku}_app_sessions`");

			$query2  = $db->rawQuery("CREATE TABLE `{$new_sku}_attributes` LIKE `{$old_sku}_attributes`");
			$insert2 = $db->rawQuery("INSERT `{$new_sku}_attributes` SELECT * FROM `{$old_sku}_attributes`");

			$query3  = $db->rawQuery("CREATE TABLE `{$new_sku}_categories` LIKE `{$old_sku}_categories`");
			$insert3 = $db->rawQuery("INSERT `{$new_sku}_categories` SELECT * FROM `{$old_sku}_categories`");

			$query4  = $db->rawQuery("CREATE TABLE `{$new_sku}_comments` LIKE `{$old_sku}_comments`");
			$insert4 = $db->rawQuery("INSERT `{$new_sku}_comments` SELECT * FROM `{$old_sku}_comments`");

			$query5  = $db->rawQuery("CREATE TABLE `{$new_sku}_groups` LIKE `{$old_sku}_groups`");
			$insert5 = $db->rawQuery("INSERT `{$new_sku}_groups` SELECT * FROM `{$old_sku}_groups`");

			$query6  = $db->rawQuery("CREATE TABLE `{$new_sku}_logs` LIKE `{$old_sku}_logs`");
			//$insert6 = $db->rawQuery("INSERT `{$new_sku}_logs` SELECT * FROM `{$old_sku}_logs`");

			$query7  = $db->rawQuery("CREATE TABLE `{$new_sku}_meals` LIKE `{$old_sku}_meals`");
			$insert7 = $db->rawQuery("INSERT `{$new_sku}_meals` SELECT * FROM `{$old_sku}_meals`");

			$query8  = $db->rawQuery("CREATE TABLE `{$new_sku}_meal_attributes` LIKE `{$old_sku}_meal_attributes`");
			$insert8 = $db->rawQuery("INSERT `{$new_sku}_meal_attributes` SELECT * FROM `{$old_sku}_meal_attributes`");

			$query9  = $db->rawQuery("CREATE TABLE `{$new_sku}_meal_categories` LIKE `{$old_sku}_meal_categories`");
			$insert9 = $db->rawQuery("INSERT `{$new_sku}_meal_categories` SELECT * FROM `{$old_sku}_meal_categories`");

			$query10  = $db->rawQuery("CREATE TABLE `{$new_sku}_orders` LIKE `{$old_sku}_orders`");
			$insert10 = $db->rawQuery("INSERT `{$new_sku}_orders` SELECT * FROM `{$old_sku}_orders`");

			$query11  = $db->rawQuery("CREATE TABLE `{$new_sku}_order_details` LIKE `{$old_sku}_order_details`");
			$insert11 = $db->rawQuery("INSERT `{$new_sku}_order_details` SELECT * FROM `{$old_sku}_order_details`");

			$query12  = $db->rawQuery("CREATE TABLE `{$new_sku}_settings` LIKE `{$old_sku}_settings`");
			$insert12 = $db->rawQuery("INSERT `{$new_sku}_settings` SELECT * FROM `{$old_sku}_settings`");

			$query13  = $db->rawQuery("CREATE TABLE `{$new_sku}_tables` LIKE `{$old_sku}_tables`");
			$insert13 = $db->rawQuery("INSERT `{$new_sku}_tables` SELECT * FROM `{$old_sku}_tables`");

			$query14  = $db->rawQuery("CREATE TABLE `{$new_sku}_users` LIKE `{$old_sku}_users`");
			$insert14 = $db->rawQuery("INSERT `{$new_sku}_users` SELECT * FROM `{$old_sku}_users`");

			$query15  = $db->rawQuery("CREATE TABLE `{$new_sku}_users_groups` LIKE `{$old_sku}_users_groups`");
			$insert15 = $db->rawQuery("INSERT `{$new_sku}_users_groups` SELECT * FROM `{$old_sku}_users_groups`");

			$query16  = $db->rawQuery("CREATE TABLE `{$new_sku}_waiters` LIKE `{$old_sku}_waiters`");
			$insert16 = $db->rawQuery("INSERT `{$new_sku}_waiters` SELECT * FROM `{$old_sku}_waiters`");

			$query17  = $db->rawQuery("CREATE TABLE `{$new_sku}_waiter_notice` LIKE `{$old_sku}_waiter_notice`");
			$insert17 = $db->rawQuery("INSERT `{$new_sku}_waiter_notice` SELECT * FROM `{$old_sku}_waiter_notice`");

			$query17  = $db->rawQuery("CREATE TABLE `{$new_sku}_waiter_notifications` LIKE `{$old_sku}_waiter_notifications`");
			$insert17 = $db->rawQuery("INSERT `{$new_sku}_waiter_notifications` SELECT * FROM `{$old_sku}_waiter_notifications`");

			$query18  = $db->rawQuery("CREATE TABLE `{$new_sku}_waiter_table_relation` LIKE `{$old_sku}_waiter_table_relation`");
			$insert18 = $db->rawQuery("INSERT `{$new_sku}_waiter_table_relation` SELECT * FROM `{$old_sku}_waiter_table_relation`");

			$query20  = $db->rawQuery("CREATE TABLE `{$new_sku}_waiter_logs` LIKE `{$old_sku}_waiter_logs`");

			$query21  = $db->rawQuery("CREATE TABLE `{$new_sku}_food_types` LIKE `{$old_sku}_food_types`");
			$insert21 = $db->rawQuery("INSERT `{$new_sku}_food_types` SELECT * FROM `{$old_sku}_food_types`");

			if($new_id && $db->tableExists($new_sku.'_settings') && $db->tableExists($new_sku.'_waiter_table_relation')){

						$db->where('id', $new_id);
						$db->update('accounts', array('delete' => 0));

						$db->commit();
						 return "<span class='alert alert-success'>Account duplicated successfully.</span>";
			}
			else{
				$db->rollback();
				return "<span class='alert alert-danger'>Some issues occured while trying to create new store.</span>";
			}
		}
		return "<span class='alert alert-danger'>Selected store not found.</span>";
	}

	
	
