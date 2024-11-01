<div class="col-sm-10">

<div class="table-responsive">

    <table id="adminlist_players" class="table table-striped table-bordered table-hover dataTables-example">

    	<thead>

    		<th><?php echo $body['col_txt1'];?></th>
    		<th><?php echo $body['col_txt2'];?></th>
    		<th><?php echo $body['col_txt3'];?></th>
    		<th><?php echo $body['col_txt4'];?></th>
			<th><?php echo $body['col_txt5'];?></th>
			<th><?php echo $body['col_txt6'];?></th>
    	
    	</thead>
    	
    	<tbody>
    		<?php foreach($body['all_players'] as $all_player):
    				
    				$team_profile_link = $all_player['team_profile_link'];
    				$image_path = $all_player['image_path'];
    		?>
    			<tr>
    				<td>
    					<a href="<?php echo esc_url($team_profile_link);?>" class="navy-link">
    						<img title="<?php echo $all_player['team_name'];?>" alt="<?php echo $all_player['team_name'];?>" src="<?php echo esc_url($image_path);?>" class="img-responsive" style="max-height: 48px;max-width:48px;" />
    					</a>
    				</td>
    				<td><a href="<?php echo esc_url($team_profile_link);?>" class="navy-link"><?php echo $all_player['team_name'];?></a></td>
    				<td><?php echo esc_attr($all_player['rank']);?></td>
    				<td><?php echo esc_attr($all_player['points']);?></td>
    				<td><?php echo esc_attr($all_player['tour_place']);?></td>
    				<td><?php echo esc_attr($all_player['tour_points']);?></td>
    			</tr>
    		<?php endforeach;?>
    	</tbody>
	</table>
</div>
</div>


