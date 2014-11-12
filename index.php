	
	<?php
	
		include 'TagCloudGenerator.inc.php';
		$tagCloudGenerator= new TagCloudGenerator();

	
		if (isset($_POST['theWords'])){

			$gematria = array ('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 1, 'K' => 2, 'L' => 3, 'M' => 4, 'N' => 5, 'O' => 6, 'P' => 7, 'Q' => 8, 'R' => 9, 'S' => 1, 'T' => 2, 'U' => 3, 'V' => 4, 'W' => 5, 'X' => 6, 'Y' => 7, 'Z' => 8,);
			$theWords = $_POST['theWords'];

			if (is_numeric($theWords)) {

				$result = word_cloud(query_numeric(db_connection(),$theWords));

			} else {

				$originalEntry = $theWords;
				$theValue = value_from_word($theWords);
				$result = word_cloud(query_numeric(db_connection(),$theValue));
				$result['value'] = $theValue;

			}

		} else {
				
			$result = '';
			
		}
		
		$output = '';
		$output = $output . 'Value: ' . $result['value'] . '<br/>' . output_for_words($result) . '<br/><br/>' . output_for_phrases($result) . '<br/>';
		require 'render.php';
	
	
		function output_for_phrases($result){
			global $tagCloudGenerator;
			if (count($result['phrases'])){
				$output = 'Phrases:<br/>';
				foreach($result['phrases'] as $phrase){
					$output .= $tagCloudGenerator->generateTagCloudFromString($phrase,'') . '<br/>';
				}
			}
			return $output;
		}
		
		function output_for_words($result){
			global $tagCloudGenerator;
			if (isset($result['words'])){
				$output = $tagCloudGenerator->generateTagCloudFromText(implode(' ',$result['words']));
			}
			return $output;
		}
	
		function db_connection(){
			require("eqcalc-settings.php");
			$connection=mysql_connect($host,$user,$password) or die("could not connect to server");
			$dbconn=mysql_select_db($database,$connection) or die("could not connect to database");
			return $connection;
		}
		
		function query_numeric($connection,$theWords){
			return mysql_query("SELECT * FROM qaballah WHERE thevalue = ".$theWords." ORDER BY thetext",$connection);
		}
		
		function word_cloud($matches){
				$words=Array(); $phrases=Array();
				while ($row1=mysql_fetch_array($matches))
				{
				extract($row1);
				if (strpos($thetext," ")) {
					$phrases[count($phrases)] = $thetext;
					} else {
					$words[count($words)] = $thetext;
				}
				}
			require_once("TagCloudGenerator.inc.php");
			$tagCloudGenerator= new TagCloudGenerator();
			$result['words'] = $words;
			$result['phrases'] = $phrases;
			return $result;
		}
		
		function value_from_word($theWords){
			global $gematria;
			$theWords = strtoupper(preg_replace('/[^a-zA-Z]+/', '', $theWords));
			$theNumber = 0;
			for ($i=0; $i < strlen($theWords); $i++) {
				$theNumber = $theNumber + $gematria[substr($theWords,$i,1)];
			}
			return $theNumber;
		}
	
	?>
