<?php
//common function
if(!function_exists('tnthor_display_iframe'))
{
function tnthor_display_iframe($url,$title, $height_style = '')
{
	$token = get_option('tennisthor_admin_api_token');
	?>
<script>
window.addEventListener('message', function(e) {
  var $iframe = jQuery("#admin-iframe");
  var eventName = e.data[0];
  var data = e.data[1];
  switch(eventName) {
    case 'setHeight':
	data = data + 150;
      $iframe.height(data);
      break;
  }
}, false);


</script>	
	<h3><?php echo $title;?></h3>
	<iframe id="admin-iframe" src="<?php echo $url;?>?frame_enabled=yes&tennis-thor-api-key=<?php echo $token;?>" style="width:95%;<?php echo $height_style;?>"></iframe>
	<?php	
}
}

if(!function_exists('tnthor_display_success'))
{
function tnthor_display_success()
{
	if(isset($_SESSION['success_message']))
	{
		$msg = $_SESSION['success_message'];
		unset($_SESSION['success_message']);
	    return '<div class="notice notice-success is-dismissible">
	        <p>'.$msg.'</p>
	    </div>';			
	}

	if(isset($_SESSION['error_message']))
	{ 
		$msg = $_SESSION['error_message'];
		unset($_SESSION['error_message']);	
    	return '<div class="notice notice-error is-dismissible">'.$msg.'</div>';	
    }
}
}

if(!function_exists('tnthor_admin_redirect'))
{
function tnthor_admin_redirect($uri) {
	echo '<script>window.location.href="'.admin_url('admin.php?').''.$uri.'"</script>';
	exit;
}
}

?>