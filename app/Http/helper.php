<?php
function admin_asset($url){
    return asset('public/'.$url);
}

function set_php_config(){
	ini_set('post_max_size', '52428800'); /*# 50MB*/
	ini_set('upload_max_filesize', '52428800'); /*# 50MB*/
	ini_set('max_execution_time', '300');  /*# 5 minutes*/
}