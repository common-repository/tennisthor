		<div class="table-responsive">
		    <table id="adminlist" class="table table-striped table-bordered table-hover dataTables-example">
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
		    	</thead>
		                                     	
		        <tbody>
		        	<tr>
		        		<td><?php echo esc_attr($body['tour_detail'][0]['tour_name']);?></td>
		        		<td><?php echo esc_attr($body['tour_detail'][0]['sport_name']);?></td>
		        		<td><?php echo esc_attr($body['tour_detail'][0]['type_game_name']);?></td>
		        		<td><?php echo esc_attr($body['tour_detail'][0]['name']);?></td>                           		
		        		<td><?php echo esc_attr($body['tour_detail'][0]['max_players']);?></td>
		        		<td><?php echo esc_attr($body['tour_detail'][0]['age_min']);?></td>
		        		<td><?php echo esc_attr($body['tour_detail'][0]['age_max']);?></td>
		        		<td><?php echo esc_attr($body['tour_detail'][0]['team_count']);?></td>
		        		<td><?php echo esc_attr($body['tour_detail'][0]['gender_rules']);?></td>
		        	</tr>
		        </tbody>
		    </table>
		</div>
		
		<div class="col-sm-6">
		<div class="table-responsive">
		    <table id="adminlist" class="table table-striped table-bordered table-hover dataTables-example">
		    	<thead>
		    		<th><?php echo $body['col_txt10'];?></th>
		    		<th><?php echo $body['col_txt11'];?></th>
		    		<th><?php echo $body['col_txt12'];?></th>
		    	</thead>
		        <tbody>
		                <?php      
		                $lats = array();           
		                $logs = array();           
		                foreach($body['tour_in_clubs'] as $clubs):
		                
		                $lats[] = $clubs['map_lat'];
		                $logs[] = $clubs['map_long'];
		    				
		//    				$team_slug = url_title($all_player['team_name'].'-'.$tour_detail['sport_name'].'-'.$tour_detail['type_game_name'], 'dash', true);
		//    				$team_profile_link = '/'.$lang_code.'/team/'.common::toBase($all_player['team_id']).'/'.$team_slug;
		//                                $clubs['club_name'];
						$club_slug = $clubs['club_name'];
						$detail_link = "";
		    		?>
		                    <tr>
		        		<td><?php echo $clubs['club_name'];?></td>                           		
		        		<td><?php echo $clubs['city'];?></td>
		        		<td><?php echo $clubs['country'];?></td>
		                    </tr>
		    		<?php endforeach;?>
		    		<?php
		$lat_average = 0;	
		$log_average = 0;	
		if(count($lats) > 0)
		{
			$lats = array_filter($lats);
			$lat_average = array_sum($lats)/count($lats);				

			$logs = array_filter($logs);
			$log_average = array_sum($logs)/count($logs);
		}



		   		
		    		?>
		    		
		        </tbody>
		    </table>
		</div>
		</div>
		<div class="clearfix m-t"></div>		


	<?php 
		$redirect_to_login = $body['tour_detail_link'];

		if($body['tour_detail'][0]['show_register'] == "1"){
	?>
	<a class="tennisthor_btn btn btn-primary" href="<?php echo $redirect_to_login;?>"><?php echo $body['col_txt13'];?></a>
	<?php } ?>


<div class="clearfix m-t"></div>

		<div class="table-responsive">
		    <table id="adminlist" class="table table-striped table-bordered table-hover dataTables-example">
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt14'];?></th>
		    		<td><?php echo esc_attr($body['tour_detail'][0]['tourn_type']);?></td>
		    	</tr>
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt15'];?></th>
		    		<td>
						<?php
							echo esc_attr($body['tour_start_date']);
	                    ?>                            			
		    		</td>
		    	</tr>
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt16'];?></th>
		    		<td>
							<?php
			                    echo esc_attr($body['registration_date']);
		                    ?>                            			
		    		</td>
		    	</tr>
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt17'];?></th>
		    		<td><?php echo esc_attr($body['tour_detail'][0]['rating_name']);?></td>
		    	</tr>
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt18'];?></th>
		    		<td><?php echo esc_attr($body['club_currency']);?></td>
		    	</tr>
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt19'];?></th>
		    		<td><?php echo esc_attr($body['tour_detail'][0]['schema_type']);?></td>
		    	</tr>
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt20'];?></th>
		    		<td><?php echo esc_attr($body['tour_detail'][0]['schema_rules']);?></td>
		    	</tr>
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt21'];?></th>
		    		<td><?php echo esc_attr($body['tour_detail'][0]['group_rules']);?></td>
		    	</tr>
		    	<tr>
		    		<th width="15%"><?php echo $body['col_txt22'];?></th>
		    		<td><?php echo html_entity_decode($body['tour_detail'][0]['description']);?></td>
		    	</tr>
		    	<tr>
		    		<td colspan="2">
		    			<div id="map" style="width: auto;height:450px;"></div>
		    		</td>
		    	</tr>						          
		    </table>
		</div>	
		
		
<input type="hidden" name="map_lat" id="map_lat"  value="<?php echo $body['tour_detail'][0]['map_lat'];?>">                      
<input type="hidden" name="map_long" id="map_long"  value="<?php echo $body['tour_detail'][0]['map_long'];?>">                      
<script>
	var map_lat = '<?php echo $body['tour_detail'][0]["map_lat"];?>';
	var map_long = '<?php echo $body['tour_detail'][0]["map_long"];?>';
	var address_not_found_club_google_map = 'Address not found on google map';
	var google_map_marker_flag = false;
	
	var map_lat_lng_json = '<?php echo json_encode($body['tour_in_clubs']);?>';
	map_lat_lng_arr = JSON.parse(map_lat_lng_json);
	
	var lat_average = '<?php echo $lat_average;?>';
	var log_average = '<?php echo $log_average;?>';
</script>

<?php
wp_enqueue_script('tennisthor-tourinfo-01', plugins_url('../../assets/js/club_map.js',__FILE__));
?>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWl9wl9h1Ofp2Ds_1o1ILQi9tlxalB9Mc&callback=initMap">
</script>
<script>
	setTimeout(function(){
		//geoCodeByLatLong(geocoder, map);
		get_all_club_on_map();
		
		},1500
	)
	
</script>		