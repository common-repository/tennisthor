<?php

add_action('admin_post_nopriv_tennisthor_tournaments_hook','the_tennisthor_tournaments_hook_callback');
add_action('admin_post_tennisthor_tournaments_hook','the_tennisthor_tournaments_hook_callback');

if(!function_exists('the_tennisthor_tournaments_hook_callback'))
{
function the_tennisthor_tournaments_hook_callback()
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	

	$page_option = get_option('tennisthor_tournament_detail_page');
	$page = get_page($page_option);
	$detail_page_link = get_page_link($page);	
		
	$body = $_POST;	
	$body['tennisthor_club_id'] = $tennis_club_id;
	$body['tennisthor_detail_link'] = $detail_page_link;

	$body = recursive_sanitize_text_field($body);	
	$temp_body = http_build_query($body);
	
	$response = wp_remote_post('https://www.tennisthor.com/api/apitournament/index?'.$temp_body, array(
	    'body'    => $body,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	

	if($response['response']['code'] == 200)
	{			
		$body = $response['body'];
		echo $body;			
	}
}

}


if (!is_admin()) 
{

	add_shortcode( 'tennisthor_tournaments', 'tennisthor_tournaments_shortcode' );
	if(!function_exists('tennisthor_tournaments_shortcode'))
	{	
	function tennisthor_tournaments_shortcode() 
	{
		if(strpos($_SERVER['REQUEST_URI'], 'wp-json/') != "")
		{
			return '';
		}		
		global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
		if(trim($tennis_club_id) == "")
			return false;

		$current_lang = '';
		if(isset($_COOKIE['tennis_lang']))
			$current_lang = $_COOKIE['tennis_lang'];			

		$params = [];
		$response = wp_remote_post( 'https://www.tennisthor.com/api/apitournament/gettourlist', array(
		    'body'    => $params,
		    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
		) );	
		
		$body = "";
		if($response['response']['code'] == 200)
		{			
			$body = json_decode($response['body'], true);			
		}		
		
		//wp_enqueue_style('tennis-tour0', 'https://club1.tennisthor.com/assets/css/bootstrap.min.css');	
		//wp_enqueue_style('tennis-tour101', 'https://club1.tennisthor.com/assets/font-awesome/css/font-awesome.min.css');	

		wp_enqueue_style('tennis-tour1', plugins_url('../assets/css/dataTables/datatables.min.css',__FILE__));	
		wp_enqueue_script('tour-js-1', plugins_url('../assets/js/dataTables/datatables.min.js',__FILE__));	
		wp_enqueue_script('tour-js-2', plugins_url('../assets/js/tournament-list.js',__FILE__));
		?>
		<div class="table-responsive tourlists">
		    <table id="adminlist" class="row-border">
		    	<thead>
		    		<th><?php echo $body['col_txt1'];?></th>
		    		<th><?php echo $body['col_txt2'];?></th>
		    		<th><?php echo $body['col_txt3'];?></th>
		    		<th><?php echo $body['col_txt4'];?></th>
		    		<th><?php echo $body['col_txt5'];?></th>
		    		<th><?php echo $body['col_txt6'];?></th>
		    		<th><?php echo $body['col_txt7'];?></th>
		    		<th><?php echo $body['col_txt8'];?></th>
		    		<th><?php echo $body['col_txt9'];?></th>
		    		<th><?php echo $body['col_txt10'];?></th>
		    		<th><?php echo $body['col_txt11'];?></th>
		    		<th><?php echo $body['col_txt12'];?></th>
		    	</thead>
		        <tfoot>
		            <tr>					                    
						<th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		                <th></th>
		            </tr>
		        </tfoot>                                	
		        <tbody>
		        </tbody>
		    </table>
		</div>	
		
		<script>
			var enc_club_id = '<?php echo $enc_tennis_club_id; ?>';
			var tennisthor_wpadmin_url = '<?php echo TENNISTHOR_ADMIN_URL;?>';

			var j_tab_txt1 = '<?php echo $body['j_tab_txt1'];?>';
			var j_tab_txt2 = '<?php echo $body['j_tab_txt2'];?>';
			var j_tab_txt3 = '<?php echo $body['j_tab_txt3'];?>';
			var j_tab_txt4 = '<?php echo $body['j_tab_txt4'];?>';
			var j_tab_txt5 = '<?php echo $body['j_tab_txt5'];?>';
			var j_tab_txt6 = '<?php echo $body['j_tab_txt6'];?>';
			var j_tab_txt7 = '<?php echo $body['j_tab_txt7'];?>';
			var j_tab_txt8 = '<?php echo $body['j_tab_txt8'];?>';
			var j_tab_txt9 = '<?php echo $body['j_tab_txt9'];?>';
			var j_tab_txt10 = '<?php echo $body['j_tab_txt10'];?>';
			var j_tab_txt11 = '<?php echo $body['j_tab_txt11'];?>';
			var j_tab_txt12 = '<?php echo $body['j_tab_txt12'];?>';
			var j_tab_txt13 = '<?php echo $body['j_tab_txt13'];?>';
			var j_tab_txt14 = '<?php echo $body['j_tab_txt14'];?>';
			
		</script>

	
		

		<?php
	}
	}

}
?>