<?php
if (Functions::Verify_Session() == true){
	header( 'Location: blog/adm/admin.php?mode=login&password=cool' );
} else {
	Functions::Verify_Session_Redirect();
}