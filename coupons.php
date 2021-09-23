<?php
	function carolls_coupons_statistic_init(){ 
	
		require_once CAROLLS_PLUGIN_DIR .'inc/db-pdo-app.php';
	
		echo "<h1>Coupon Statistik</h1>";
		
		$coupon_id = $_GET['coupon-id'];

		$Array = array();
		$i = 0;
		
		$statement2 = $pdo_app->prepare("SELECT * FROM ca_coupons_distributor WHERE ca_coupons_coupon_id = ?");
		$statement2->execute(array($coupon_id)); 
		while($row2 = $statement2->fetch())
		{
			 $Array [$i]["coupons_number_user_id"] = $row2['ca_coupons_admins_user_id'];
			 $Array [$i]["coupons_number_friend_id"]= '0';
			 $i++;
		}

		/*
		?><pre><?
		print_r($Array);
		?></pre><?
		*/

		$Array2 = array();
		$i = 0;
		
		$statement = $pdo_app->prepare("SELECT coupons_number_user_id, coupons_number_friend_id FROM ca_coupons_number WHERE coupons_number_coupon_id = ?");
		$statement->execute(array($coupon_id));
		while($row = $statement->fetch())
		{
			 $Array2 [$i]["coupons_number_user_id"] = $row['coupons_number_friend_id']; 
			 $Array2 [$i]["coupons_number_friend_id"] = $row['coupons_number_user_id'];
			 $i++;
		}
		
		/*
		?><pre><?
		print_r($result);
		?></pre><?
		*/
		
		$mergeArrays = array_merge($Array, $Array2);
		
		?><pre><?
		print_r($ergebnis);
		?></pre><?

		$datas = $mergeArrays;
		

		function generatePageTree($datas, $parent = 0, $depth=0){
			$ni=count($datas);
			if($ni === 0 || $depth > 1000) return '';
			$tree = '<ul>';
			for($i=0; $i < $ni; $i++){
				if($datas[$i]['coupons_number_friend_id'] == $parent){
					$tree .= '<li>';
					$tree .= '<div>'.$datas[$i]['coupons_number_user_id'].'</div>';
					$tree .= generatePageTree($datas, $datas[$i]['coupons_number_user_id'], $depth+1);
					$tree .= '</li>';
				}
			}
			$tree .= '</ul>';
			return $tree;
		}

		?><div class="items"><?
		echo(generatePageTree($datas));
		?></div><?


	}

?>
