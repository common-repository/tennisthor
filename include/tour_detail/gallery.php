<div class="lightBoxGallery">
	<?php 
	if(isset($body['image_data']) AND count($body['image_data']) > 0):
		foreach($body['image_data'] as $image):

			$image_path = $image['image_path'];             							
			$image_org_path = $image['image_org_path'];  
	?>
			<a href="<?php echo esc_url($image_org_path);?>" title="Image from <?php echo $body['tournament_title'];?>" data-gallery=""><img src="<?php echo esc_url($image_path);?>"></a>
		<?php endforeach;?>	
	<?php endif;?>
	
    <!-- The Gallery as lightbox dialog, should be a child element of the document body -->
    <div id="blueimp-gallery" class="blueimp-gallery">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
</div>
