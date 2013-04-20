
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
	<title>English Qaballah Calculator</title>
</head>
<body>
	<form method="post" action="index.php">
		<p>
			<label for="theWords">Enter a word or some text in the box to resolve its value. Or enter a number to lookup text in our database that resolves to that number.</label>
			<br/>
			<textarea name="theWords" id="theWords" cols="50" rows="3"></textarea>
		<br/>
			<input type="submit" value="Submit"> 
			<input type="reset" value="Clear">
		</p>
	</form>
	
	<?php
		require("eqcalc-settings.php");
		$connection=mysql_connect($host,$user,$password) or die("could not connect to server");
		$dbconn=mysql_select_db($database,$connection) or die("could not connect to database");
		if (isset($_POST['theWords'])) {
			$theWords = $_POST['theWords'];
			if (is_numeric($theWords)) {
				print "<p>Words and phrases with the value ". $theWords ." include the following:</p>";
				$matches=mysql_query("SELECT * FROM qaballah WHERE thevalue = ".$theWords." ORDER BY thetext",$connection);
				print '<div style="width: 90%; position: absolute; left: 5%;">';
				$bigString = "";
					while ($row1=mysql_fetch_array($matches))
					{
					extract($row1);
					if (strpos($thetext," ")) {
						print "<p>".$thetext."</p>";
						} else {
						$bigString = $bigString . $thetext . " ";
					}
					}
				require_once("TagCloudGenerator.inc.php");
				$tagCloudGenerator= new TagCloudGenerator();
				echo $tagCloudGenerator->generateTagCloudFromText($bigString);
				print '</div>';
			} else {
				$gematria = array (
				'A' => 1,
				'B' => 2,
				'C' => 3,
				'D' => 4,
				'E' => 5,
				'F' => 6,
				'G' => 7,
				'H' => 8,
				'I' => 9,
				'J' => 1,
				'K' => 2,
				'L' => 3,
				'M' => 4,
				'N' => 5,
				'O' => 6,
				'P' => 7,
				'Q' => 8,
				'R' => 9,
				'S' => 1,
				'T' => 2,
				'U' => 3,
				'V' => 4,
				'W' => 5,
				'X' => 6,
				'Y' => 7,
				'Z' => 8,
				);
				$originalEntry = $theWords;
				$theWords = strtoupper(preg_replace('/[^a-zA-Z]+/', '', $theWords));
				$theNumber = 0;
				for ($i=0; $i < strlen($theWords); $i++) {
					$theNumber = $theNumber + $gematria[substr($theWords,$i,1)];
				}
				print '<p>The Number of <strong>'. $originalEntry. '</strong> is: <strong>' . $theNumber . '</strong>. Other words with that number include:</p>';
				$bigString = "";
				print '<div style="width: 90%; position: absolute; left: 5%;">';
				$matches=mysql_query("SELECT * FROM qaballah WHERE thevalue = ".$theNumber." ORDER BY thetext",$connection);
					while ($row1=mysql_fetch_array($matches))
					{
					extract($row1);
					if (strpos($thetext," ")) {
						print "<p>".$thetext."</p>";
						} else {
						$bigString = $bigString . $thetext . " ";
					}
					}
				require_once("TagCloudGenerator.inc.php");
				$tagCloudGenerator= new TagCloudGenerator();
				echo $tagCloudGenerator->generateTagCloudFromText($bigString);
				print '</div><p>&nbsp;</p>';
			}
		}
	?>
	
</body>
</html>

