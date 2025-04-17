<?php
if (!defined("ABSPATH")) die("Brak dostępu");

function page($content) {
	echo "<main id='page'>";
	$Parsedown = new Parsedown();
	
	echo "<p>" . $Parsedown->text($content) . "</p>";
	echo "</main>";
}
?>