<?php
//base_query exmaple-- SELECT * FROM tbl_user ORDER BY user_sn ASC
//query_param --- if query not contin parameters set this null
function spilit_result($page,$results_per_page,$base_query,$query_param){
	try{
		global $conn;
		$start = ($page-1) * $results_per_page;
		$sql_quary = $base_query." LIMIT ".$start.", ".$results_per_page;
		$sql = $conn->prepare($sql_quary);

		if(count($query_param)>0){
			foreach($query_param as $key => &$val){
	    		$sql->bindparam($key, $val);
			}
		}
		$sql->execute();
		$numRows = $sql->fetchAll();

		$sql = $conn->prepare($base_query);
		if(count($query_param)>0){
			foreach($query_param as $key => &$val){
	    		$sql->bindparam($key, $val);
			}

		}
		$sql->execute();
		$num_results = count($sql->fetchAll());
		$total_pages = ceil($num_results/$results_per_page);

		return array($numRows,$total_pages);
	}
	catch(Exception $e){
		//header("Location:index.php");
	}
}

function page_range($pps,$tot_page,$sel_page){
	/**
		pps-pages per side
		tot_page - total num of pages
		sel_page - selected page
	**/
	$min=null;
	$max=null;

	if($tot_page<=($pps*2)+1){
		$min=1;
		$max=$tot_page;
	}
	elseif(($pps+$sel_page)>$tot_page){
		$max=$tot_page;
		$min=$sel_page-((($pps+$sel_page)-$tot_page)+$pps);
	}
	elseif($sel_page-$pps<1){
		$min=1;
		$max=($pps-($sel_page-1))+$sel_page+$pps;
	}
	else{
		$min=$sel_page-$pps;
		$max=$sel_page+$pps;
	}

	return array($min,$max);
}

function spilit_result_limit_q($page,$results_per_page,$base_query,$query_param,$limit){
	try{
		global $conn;
		$start = ($page-1) * $results_per_page;
		$results_per_page_for_result=$results_per_page;
		if(($start+$results_per_page)>$limit){
			$results_per_page_for_result=$results_per_page-(($start+$results_per_page)-$limit);
		}
		$sql_quary = $base_query." LIMIT ".$start.", ".$results_per_page_for_result;
		$sql = $conn->prepare($sql_quary);

		if(count($query_param)>0){
			foreach($query_param as $key => &$val){
	    		$sql->bindparam($key, $val);
			}
		}
		$sql->execute();
		$numRows = $sql->fetchAll();

		$base_query=$base_query." LIMIT ".$limit;
		$sql = $conn->prepare($base_query);
		if(count($query_param)>0){
			foreach($query_param as $key => &$val){
	    		$sql->bindparam($key, $val);
			}
		}
		$sql->execute();
		$num_results = count($sql->fetchAll());
		$total_pages = ceil($num_results/$results_per_page);

		return array($numRows,$total_pages);
	}
	catch(Exception $e){
		header("Location:index.php");
	}
}
?>