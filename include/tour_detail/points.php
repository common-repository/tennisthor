<div class="col-sm-6">
	<div class="table-responsive">
	    <table id="points_tbl" class="table game_result_table table-striped table-bordered table-hover dataTables-example">
	    	<thead>
	    	<tr>
	    		<th width="30%"><?php echo $body['col_txt1'];?></th>
	    		<th width="35%"><?php echo $body['col_txt2'];?></th>
	    		<th width="35%"><?php echo $body['col_txt3'];?></th>
	    	</tr>  
	    	</thead>  	
	    	<tbody>
	    	<?php 
	    	$cnt = 0;
	    	foreach($body['levels'] as $level):?>
		    	<tr>
		    		<td><?php echo esc_attr($level['level_name']); ?></td>
		    		<td>
		    			<?php echo esc_attr($level['points']); ?>
		    		</td>
		    		<td>
		    			<?php echo esc_attr($level['prize']); ?>
		    		</td>
		    	</tr>							    	
	    	<?php $cnt++; endforeach;?>    	
	    	</tbody>
	    </table>
	</div>
</div>