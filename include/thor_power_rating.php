<?php
add_shortcode( 'tennisthor_thor_power_rating', 'tennisthor_thor_power_rating_shortcode' );
if(!function_exists('tennisthor_thor_power_rating_shortcode'))
{
function tennisthor_thor_power_rating_shortcode() 
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	

	wp_enqueue_style('rating-css-01', plugins_url('../assets/css/dataTables/datatables.min.css',__FILE__));	
	wp_enqueue_script('rating-js-01', plugins_url('../assets/js/dataTables/datatables.min.js',__FILE__));	
	wp_enqueue_script('rating-css-02', plugins_url('../assets/js/thor_ratings.js',__FILE__));

	$params = [];
	if(isset($_GET['game_type']))
	{
		$params['tennisthor_type_game_id'] = sanitize_text_field($_GET['game_type']);
	}

	$response = wp_remote_post( 'https://www.tennisthor.com/api/apirating/thorpowerrating', array(
	    'body'    => $params,
	    'headers' => array('USER-TOKEN' => $tennis_token, 'USER-CLUB' => $enc_tennis_club_id, 'TENNISTHOR-LANG' => $current_lang),
	) );	
	
	$body = "";
	if($response['response']['code'] == 200)
	{			
		$body = json_decode($response['body'], true);			
	}
	else
	{
		tnthor_redirect('/');
	}	

	$page_option = get_option('tennisthor_thor_power_rating_page');
	$page = get_page($page_option);
	$rating_page_link = get_page_link($page);	

	$html = '';

	$sport_name = '';
	$game_type_name = '';
	foreach($body['link_sports'] as $listrow):

		$html .= '<p><a href="#" class="navy-link"><strong>'.$listrow['sport'].' Rating</strong>:</a>';

		$sep = "";
		foreach($listrow['game_types'] as $game_type):
			$enc_type_game_id = tennisthor_toBase($game_type['type_game_id']);
			$html .= $sep;
			if($body['default_type_game_id'] == $game_type['type_game_id'])
			{
            	$sport_name = $listrow['sport'];        	
            	$game_type_name = $game_type['type_game'];  									
			}			

			$html .= '<a href="'.$rating_page_link.'?game_type='.$enc_type_game_id.'" class="">'.$game_type['type_game'].'</a>';
			$sep = ", ";			
		endforeach;	
			
	endforeach;	
	

	$html .= '<div class="table-responsive tennisthor_rating_table">
	    <table id="adminlist" class="row-border">
	    	<thead>
	        	<th>'.$body['col1_txt'].'</th>
	            <th>'.$body['col2_txt'].'</th>
	            <th>'.$body['col3_txt'].'</th>
	            <th>'.$body['col4_txt'].'</th>
	            <th>'.$body['col5_txt'].'</th>
	            <th>'.$body['col6_txt'].'</th>
	            <th>'.$body['col7_txt'].'</th>
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
	            </tr>
	        </tfoot>    
	        <tbody>';
	        	
	        		foreach($body['thor_ratings'] as $thor_rating):
	        	
	        	$html .= '<tr>
	        		<td>
						<a href="'.esc_url($thor_rating['team_profile_link']).'">
							<img src="'.esc_url($thor_rating['image_path']).'" class="img-responsive" style="max-height: 48px;max-width:48px;" />				        			
						</a>
	        		</td>
	        		<td><a href="'.esc_url($thor_rating['team_profile_link']).'" class="navy-link">'.esc_attr($thor_rating['team_name']).'</a>';
	            		 if(trim($thor_rating['total_points']) != ""):
	            			$tem1_url = '';
	            		
	            			$html .= '&nbsp;<a href="'.esc_url($thor_rating['team_profile_link']).'"><i class="fa fa-line-chart"></i></a>';
	            		 endif;
	        			
	        		$html .= '</td>
	        		<td>'.esc_attr($sport_name).'</td>
	        		<td>'.esc_attr($game_type_name).'</td>
	        		<td>'.esc_attr($thor_rating['games_count']).'</td>
	        		<td>'.esc_attr($thor_rating['rank']).'</td>
	        		<td>'.esc_attr($thor_rating['total_points']).'</td>				        		
	        	</tr>';

	        		endforeach;

	        $html .= '</tbody>                            	
	    </table>
	</div>';


	$html .= '<script>';
		$html .= "var j_tab_txt1 = '".$body['j_tab_txt1']."';";
		$html .= "var j_tab_txt2 = '".$body['j_tab_txt2']."';";
		$html .= "var j_tab_txt3 = '".$body['j_tab_txt3']."';";
		$html .= "var j_tab_txt4 = '".$body['j_tab_txt4']."';";
		$html .= "var j_tab_txt5 = '".$body['j_tab_txt5']."';";
		$html .= "var j_tab_txt6 = '".$body['j_tab_txt6']."';";
		$html .= "var j_tab_txt7 = '".$body['j_tab_txt7']."';";
		$html .= "var j_tab_txt8 = '".$body['j_tab_txt8']."';";
		$html .= "var j_tab_txt9 = '".$body['j_tab_txt9']."';";
		$html .= "var j_tab_txt10 = '".$body['j_tab_txt10']."';";
		$html .= "var j_tab_txt11 = '".$body['j_tab_txt11']."';";
		$html .= "var j_tab_txt12 = '".$body['j_tab_txt12']."';";
		$html .= "var j_tab_txt13 = '".$body['j_tab_txt13']."';";
		$html .= "var j_tab_txt14 = '".$body['j_tab_txt14']."';";
	$html .= '</script>';



	return $html;
	
}
}
?>