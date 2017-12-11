<html>
	<head>
		<title>Ricerca</title>
		<script>
			function controllo_campi()
			{
				var n=document.getElementById("nelementi").value;
				var c=document.getElementById("citta").value;
				var r=document.getElementById("ricerca").value
				var esito=false;
				var verifica=/^\d{1,2}$/
				if(n!=""&&c!=""&&document.getElementById("ricerca").value!="")
					if(parseInt(n)<51)
						esito=true;
				if(!esito)
					echo ("alert('Errore nell'inserimento:\n I campi NON possono essere vuoti\n');");
				return esito;
			}
		</script>
	</head>
	<body>
		<?php 
			
			$nelementi=10;
			$citta="Bergamo";
			$ricerca="Pizzeria";
			
			if(isset($_POST["nelementi"]))
			{
				$nelementi=$_POST["nelementi"];
			}
			
			if(isset($_POST["citta"]))
			{
				$citta=$_POST["citta"];
			}
			
			if(isset($_POST["ricerca"]))
			{
				$ricerca=$_POST["ricerca"];
			}
			
			// COMPONGO LA QUERY
			$query="https://api.foursquare.com/v2/venues/search?v=20161016&query=$ricerca&limit=$nelementi&intent=checkin&client_id=DX23CQQLWTKIJEUORT3J1JYU3GGFZXJ4Y0RUN42XQZEMJIVR&client_secret=UPVWDC02URK4SOWO4WF4YBBTNZKTON1WVBVSF5BVHSSRTAG3&near=$citta";
			$chiamata = curl_init() or die(curl_error());
			curl_setopt($chiamata, CURLOPT_URL,$query);
			curl_setopt($chiamata, CURLOPT_RETURNTRANSFER, 1);
			$risposta_json=curl_exec($chiamata) or die(curl_error());
			
		    // DECODIFICO LA RISPOSTA IN JSON SALAVANDOLA NELLA VARIABILE $risposta
			$risposta = json_decode($risposta_json);
			# Stampa della tabella delle pizzerie.
				echo "<table align='center' style='border:3px solid black'>";
				echo "<tr>";
					echo ("<th style='border: 2px solid black; background-color: #FF0000;color: white;'>NOME (".$ricerca.")</th>");
					echo ("<th style='border: 2px solid black; background-color: #FF0000;color: white;'>LATITUDINE</th>");
					echo ("<th style='border: 2px solid black; background-color: #FF0000;color: white;'>LONGITUDINE</th>");
				echo "</tr>";
				for($i=0; $i<$nelementi; $i++)
				{
					echo ("<tr>");
						echo ("<td style='border: 1px solid black'>");
						echo ($risposta->response->venues[$i]->name);
						echo ("</td>");
						echo ("<td style='border: 1px solid black'>");
						echo ($risposta->response->venues[$i]->location->lat);
						echo ("</td>");
						echo ("<td style='border: 1px solid black'>");
						echo ($risposta->response->venues[$i]->location->lng);
						echo ("</td>");
					echo ("</tr>");
				}
			echo ("</table>");
			
			//SE CI SONO ERRORI (SPERO DI NO) LI STAMPO
			echo curl_error($chiamata);
			//CHIUDO IL CURL
			curl_close($chiamata);
			
			echo "<div align='center' style='font-family:Courier;  font-weight:bold;'>";
			echo "<form id='forma' method='post' onsubmit='controllo_campi()'><br/>";
				echo "<h1 style='color:red;'>INSERIMENTO DATI</h1>";
				echo "<p>Seleziona il numero elementi: <input type='number' placeholder='1-50' step='1' min='1' max='50' value='$nelementi' name='nelementi'id='nelementi'/></p>";
				echo "<p>Citta: <input type='text' placeholder='Bergamo, Milano, etc'value='$citta' name='citta' id='citta'/></p>";
				echo "<p>Cosa stai cercando? <input type='text' placeholder='Ristorante, Pizzeria, etc' value='$ricerca' name='ricerca' id='ricerca'/></p>";
				echo "<input type='submit' value='Aggiorna tabella'/>";
			echo "</form>";
			echo "</div>";
		?>
	</body>
</html>
