<?php

echo '
	<form method="post" action="index.php">
		<p>
			<label for="theWords">Enter a word or some text in the box to resolve its value. Or enter a number to lookup text in our database that resolves to that number.</label>
			<br/>
			<textarea name="theWords" id="theWords" cols="50" rows="3"></textarea>
		<br/>
			<input type="submit" value="Submit"> 
			<input type="reset" value="Clear">
		</p>
	</form>';

?>

	

