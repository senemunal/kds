<?php
require_once('config.php');
if(session_destroy()){echo '<meta http-equiv="refresh" content="0; url=index.php">';}