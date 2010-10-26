<script type="text/javascript" src="http://include.reinvigorate.net/re_.js"></script>
<script type="text/javascript">
	try{
<?php
		if(isset($currentUser['User']['slug'])){
			echo 'var re_name_tag = "' . $currentUser['User']['slug'] . '";';
		}
?>
		if($('div.new_user').length > 0){
			var re_new_user_tag = true;
		}

		reinvigorate.track("h8664-j06plyeuf7");
	}
	catch(err){}
</script>