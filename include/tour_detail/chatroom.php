<?php
	wp_enqueue_script('tour-js-8', plugins_url('../../assets/js/jScrollPane/jquery.mousewheel.js',__FILE__));	
	wp_enqueue_script('tour-js-9', plugins_url('../../assets/js/jScrollPane/jScrollPane.min.js',__FILE__));	
	wp_enqueue_script('tour-js-10', plugins_url('../../assets/js/tourchat.js',__FILE__));	
	
	wp_enqueue_style('tour-js-5', plugins_url('../../assets/js/jScrollPane/jScrollPane.css',__FILE__));	

?>

<div class="col-md-12"> 
<?php $display_clear_fix = false;?>
	<?php if(count($body['is_room_guest']) <=0 ):
		$display_clear_fix = true;
	?>    	       		
		<a href="<?php echo TENNISTHOR_DOMAIN;?>/tourchat/addmeinchat/<?php echo $body['enc_tour_id'];?>/<?php echo $body['enc_room_id'];?>" onclick="if(!confirm('Are you sure')){  return false; } " class="tennisthor_btn btn btn-primary pull-right"><?php echo $body['col_txt1'];?></a>
	<?php endif;?>  	
	
</div>
    	
<?php if($display_clear_fix):?>
	<div class="clearfix m-t"></div>
	<div class="clearfix m-t"></div>
<?php endif;?>


<div class="ibox chat-view">

    <div class="ibox-title">
    	<?php if(isset($last_message_time)):?>
        	<small class="pull-right text-muted"><?php echo $body['col_txt4'];?>:  <?php echo $body['last_message_time'];?></small>
        <?php endif;?>
         <?php echo $body['col_txt2'];?>
    </div>
    
    <div class="ibox-content">
        <div class="row chat_div_row">   

        
	        <div class="col-md-9 ">
	            <div class="chat-discussion" id="chatLineHolder"> 
	            </div>    
	            
	                
				<div id="chatContainer">
				    <div id="chatTopBar" class="rounded"></div>				    
				    <div id="chatBottomBar" class="rounded">
				    	<div class="tip"></div>
				    </div>				    
				</div> 
				
				<?php 

				if(count($body['is_room_guest']) > 0 AND $body['is_allow_chat_by_right'] AND $body['is_room_guest'][0]['blocked'] == "0"):?>
							<div class="clearfix m-t"></div>
				            <div class="form-group">
								<form id="submitForm" method="post" action="">
									<div class="row">
										<div class="col-md-10">
							            	<textarea class="form-control message-input" name="chatText" id="chatText" placeholder="Type a message here"></textarea>
							            </div>
							            <div class="col-md-2" style="padding-left: 5px;">
							            	<input type="submit" class="btn btn-primary send_msg_btn" value="Send" />			            
							            </div>
						            </div>
								</form>
				            </div>
				            <?php endif;?>  
	            
			</div>
			
	        <div class="col-md-3">
	            <div class="chat-users">
	                <div class="users-list" id="chatUsers">
	                </div>
	            </div>
	        </div>			
			
			
						
       	</div>  
       	
     	     	
	</div>
</div>
<?php
$image_path = "";
?>
<script>
	var chat_url = '/tourchat/ajax_chat/<?php echo $body['enc_tour_id'];?>/<?php echo $body['enc_room_id'];?>';
	var enc_tour_id = '<?php echo $body['enc_tour_id'];?>';
	var enc_room_id = '<?php echo $body['enc_room_id'];?>';
	var logged_user_name;
	var logged_user_avtar = '<?php echo $image_path;?>';
	var enc_logged_user = '<?php echo $body['enc_logged_user'];?>';
	var chat_write_url = '/tourchat/change_chat_write_right/<?php echo $body['enc_tour_id'];?>/<?php echo $body['enc_room_id'];?>/';
	var block_url = '/tourchat/block_users/<?php echo $body['enc_tour_id'];?>/<?php echo $body['enc_room_id'];?>/';
	var is_admin = '<?php echo $body['is_admin'];?>';	
	var add_player_url = '/tourchat/get_players_popup/<?php echo $body['enc_club_id'];?>/<?php echo $body['enc_room_id'];?>';	
	var add_to_chat_room_url = '/tourchat/add_user_to_chat/<?php echo $body['enc_club_id'];?>/<?php echo $body['enc_room_id'];?>/';	
	var remove_from_chat_room_url = '/tourchat/remove_user_from_chat/<?php echo $body['enc_club_id'];?>/<?php echo $body['enc_room_id'];?>/';		
	var admin_permission_flag = '<?php echo $body['messages_write'];?>';
	var no_message_yet_txt = '<?php echo $body['col_txt3'];?>';

</script>