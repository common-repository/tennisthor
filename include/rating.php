<?php
add_shortcode( 'tennisthor_rating', 'tennisthor_rating_shortcode' );
if(!function_exists('tennisthor_rating_shortcode'))
{
function tennisthor_rating_shortcode() 
{
	global $tennis_club_id, $tennis_token, $enc_tennis_club_id;
	if(trim($tennis_club_id) == "")
		return false;

	$current_lang = '';
	if(isset($_COOKIE['tennis_lang']))
		$current_lang = $_COOKIE['tennis_lang'];	

	wp_enqueue_style('rating-css-01', plugins_url('../assets/css/dataTables/datatables.min.css',__FILE__));	
	wp_enqueue_script('rating-js-01', plugins_url('../assets/js/dataTables/datatables.min.js',__FILE__));	
	wp_enqueue_script('rating-css-02', plugins_url('../assets/js/ratings.js',__FILE__));

	$params = [];
	if(isset($_GET['rate_type']))
	{
		$params['tennisthor_rate_type'] = sanitize_text_field($_GET['rate_type']);
	}

	$response = wp_remote_post( 'https://www.tennisthor.com/api/apirating/index', array(
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

	$page_option = get_option('tennisthor_rating_page');
	$page = get_page($page_option);
	$rating_page_link = get_page_link($page);

	$html = '';	

	foreach($body['listrows'] as $listrow):

		$link = "";
		$enc_rating_id = tennisthor_toBase($listrow['list_user_id']);
		$html .= '<p><a href="'.$rating_page_link.'?rate_type='.$enc_rating_id.'" class="navy-link">'.$listrow['rating'].'</a></p>';
	endforeach;	
	
	
	$html .= '<div class="table-responsive ">
	    <table id="adminlist_players" class="row-border">
	    	<thead>

	    		<th>'.$body['col1_txt'].'</th>
	    		<th>'.$body['col2_txt'].'</th>
	    		<th>'.$body['col3_txt'].'</th>
	    		<th>'.$body['col4_txt'].'</th>
				<th>'.$body['col5_txt'].'</th>
				<th>'.$body['col6_txt'].'</th>
	    	</thead>
	        <tfoot>
	            <tr>					                    
					<th></th>
	                <th></th>
	                <th></th>
	                <th></th>
	                <th></th>
	                <th></th>
	            </tr>
	        </tfoot>                                	
	        <tbody>';
	    		 foreach($body['rating_rows'] as $rating_row):
	    		
	    			$html .= '<tr>
	    				<td>
	    					<img src="'.esc_url($rating_row['image_path']).'" class="img-responsive" style="max-height: 48px;max-width:48px;" />
	    				</td>
	    				<td><a href="'.esc_url($rating_row['team_profile']).'" class="navy-link">'.esc_attr($rating_row['team_name']).'</a></td>
	    				<td>'.esc_attr($rating_row['rank']).'</td>
	    				<td>'.esc_attr($rating_row['points']).'</td>
	    				<td>'.esc_attr($rating_row['power_rank']).'</td>
	    				<td>'.esc_attr($rating_row['power_points']).'</td>
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