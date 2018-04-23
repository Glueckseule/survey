<?php
  session_start();
?>

<html lang="de">
  <head>
    <meta charset="UTF-8">
    <title>Umfrage</title>
    <meta name="author" content="Daniela">
    <meta name="keywords" content="Essen, Umfrage">
    <meta name="description" content="Umfrage im Rahmen des Human Information Behaviour Seminars an der Universit&auml;t Regensburg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link rel="stylesheet" href="neogridic.css">
    <link rel="stylesheet" href="layout.css">
  </head>

  <body class="grid w960">

	<h2 class="row c12">Studie im Rahmen des 'Human Information Behaviour'-Seminars</h2>

	<p class="row c12">
  <!--Text bei Beginn der Studie-->
	Danke für das Interesse an unserer Studie. Die Bearbeitung der Aufgaben wird ca. 15 Minuten dauern und findet vollständig am Computer statt.
	Nutzen Sie dafür am besten Safari, Chrome oder Firefox.
  Die Teilnahme ist komplett freiwillig und kann jederzeit unterbrochen werden.
  <br><br>
	Die Antworten werden anonymisiert und sicher gespeichert und lediglich im Rahmen dieser Studie ausgewertet.
  Bei Fragen können Sie sich jederzeit unter der angegebenen Email-Adresse an uns wenden.
  <br><br>
  Um nach einer Frage fortfahren zu können,muss für jeden der drei Schieberegler ein Wert gesetzt werden.
  Ziehen Sie den Regler, um ihre Antwort einzustellen.
  Es gibt keinen direkten Zusammenhang zwischen den einzelnen Fragen. Bitte lesen Sie die Aufgabenstellung genau durch, bevor Sie antworten.<br>
  <h3 style="color: red" class="c12">Für eine erfolgreiche Bearbeitung der Umfrage ist es wichtig, dass Sie die Informationen zu den Bildern durchlesen! </h3>
  <p class="c12">Email: daniela.krapf@stud.uni-regensburg.de</p>
	</p>

  <div class="row c12">
    <form action="main.php" method="POST">
    	<input type="submit" class="button c12" value="Starten!">
    </form>
  </div>

	</body>
</html>
