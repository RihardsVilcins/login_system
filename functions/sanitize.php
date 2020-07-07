<?php

// function which escapes single and double quotes and defines sharacter set
function escape($string) {
	return htmlentities($string, ENT_QUOTES, 'utf-8');
}
?>
