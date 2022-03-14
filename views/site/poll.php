<?php
echo print_r($poll_content);
echo "<form action='/index.php'>";
foreach ($poll_content as $question) {
	echo "<label>" . $question . "</label>" . "<br/>";
	echo '<input type="text" id="dsad" name="asd">';
}
echo '<input type="submit" value="Submit">';
echo "</form>";
?>