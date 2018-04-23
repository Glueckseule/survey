<?php

  session_start();
  $session = session_id();

  //initialize counter, load counter
  $counter;
  if(isset($_SESSION["next"])){
    $counter = $_SESSION["next"];
  } else {
    $counter = 1;
  }

  //array with data from csv;
  $dataInput = array();
  $lineInput = array();
  $numScreens = 24;
  $linepicker;

  //get rand once, then store in session variable and always use this exact rand for whole session
  if(isset($_SESSION["line"])){
    $linepicker = $_SESSION["line"];
  } else {
    //read lines.csv with all numbers of lines not used yet
    if (($handle = fopen("lines.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 4000, ",")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            array_push($lineInput, $data[$c]);
        }
      }
      fclose($handle);
    }
    //get one number from the lines.csv
    $linepicker = $lineInput[rand(0, count($lineInput))];
    //make array with all numbers from lines.csv except current screenline (linepicker), store this array in lines.CSV
    $remainingLines = array();
    for ($i=0; $i < count($lineInput) ; $i++) {
      if($lineInput[$i] != $linepicker){
        array_push($remainingLines, $lineInput[$i]);
      }
    }
    $fp = fopen('temporary.csv', 'w');
    $daten = $remainingLines;
    fputcsv($fp, $daten);
    fclose($fp);
    unlink('lines.csv');
    rename('temporary.csv', 'lines.csv');
    $_SESSION["line"] = $linepicker;
  }

  //check if first is selected
  if(isset($_POST['eatSliderMoved'])){
    $eat = $_POST['eatSliderMoved'];
  } else {
    $eat = NULL;
  }

  //check if second is selected
  if(isset($_POST['useSliderMoved'])){
    $use = $_POST['useSliderMoved'];
  } else {
    $use = NULL;
  }

  //check if third is selected
  if(isset($_POST['awaySliderMoved'])){
    $away = $_POST['awaySliderMoved'];
  } else {
    $away = NULL;
  }

  //read all data from csv according to counter;
  if (($handle = fopen("data_settings.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 100000, ";")) !== FALSE) {
      $num = count($data);
      for ($c=0; $c < $num; $c++) {
          array_push($dataInput, $data[$c]);
      }
    }
    fclose($handle);
  }
  $dataInput = array_chunk($dataInput, 25);
  $currentData = $dataInput[$linepicker];
  //slice, damit erster Unterstrich weg ist, Explode, damit der String durch die Unterstriche zum Array wird
  $screen = array_slice(explode("_", $currentData[$counter]), 1);
  //variable to check if information is relevant
  $is_real = $screen[2];
  $errorString = "<h3>Inwiefern stimmen Sie folgenden Aussagen zu?</h3>";

  //check if all sliders have been clicked on
  if(isset($_POST['next']) && $eat != NULL && $use != NULL && $away != NULL) {
    if($is_real == "True"){
      //write data into CSV
      $lineid = $linepicker;
      $t = time();
      $daten = array($lineid, $session, $screen[0], $screen[1], $eat, $use, $away, $t);
      $fp = fopen('food_data.csv', 'a');
      fputcsv($fp, $daten);
      fclose($fp);
    }
    //check if it's the last page --> redirect
    if ($counter==$numScreens) {
      header('Location:demographics.php');
      exit;
    }
    $counter += 1;
    $_SESSION["next"] = $counter;
  } else if (isset($_POST['next']) && (isset($eat) || isset($use) || isset($away))){
    $errorString = "<h3 style='color:red;'>Bitte zuerst alle drei Skalen bewerten.</h3>";
  }

  //set screen new, else there is an error
  $screen = array_slice(explode("_", $currentData[$counter]), 1);

  //manage name of Button
  $button_value = "weiter";
  if($counter == $numScreens) {
    $button_value = "beenden";
  }

?>

<html lang="de">
  <head>
    <meta charset="UTF-8">
    <title>Umfrage</title>
    <meta name="author" content="Daniela">
    <meta name="keywords" content="Essen, Umfrage">
    <meta name="description" content="Umfrage im Rahmen des Human Information Behaviour Seminars an der Universit&auml;t Regensburg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="neogridic.css">
    <link rel="stylesheet" href="layout.css">

  </head>
  <body class="grid">
    <main class="grid">

      <div class="row c12">

        <p id="dotspans">
          <script type="text/javascript">
            //create the dots showing the progress
            var output = document.getElementById("dotspans");
            var counter = <?php echo $counter ?>;
            for (var i = 1; i <= <?php echo $numScreens ?>; i++) {
              output.innerHTML += '<span id="'+i+'" class="dot"></span>';
              if(i <= counter){
                document.getElementById(i).style.backgroundColor="#67ab49";
              }
            }
          </script>
        </p>

      </div>

      <form id="form" method="post">

        <div class="row c12">

          <p id="information" class="c6"><?php if (!empty($screen[1])) {echo "<i class='fa fa-info'></i> ";} echo $screen[1];?></p>

          <div class="c6">
            <img id="myImg" src="<?php echo "Bilder/".$screen[0]; ?>" alt="<?php echo $screen[0]; ?>">

			<div id="modal" class="modal">
				<span class="close">&times;</span>
				<img class="modal-content" id="img1">
			</div>

          </div>

		  <script>
			//https://www.w3schools.com/howto/howto_css_modal_images.asp
			var modal = document.getElementById('modal');

			var img = document.getElementById('myImg');
			var modalImg = document.getElementById("img1");

			img.onclick = function(){
				modal.style.display = "block";
				modalImg.src = this.src;
			}

			var span = document.getElementsByClassName("close")[0];

			span.onclick = function() {
			  modal.style.display = "none";
			}

		  </script>

        </div>

        <div class="c12">

          <?php echo $errorString; ?>

          <p>
            <div class="slidecontainer">
              <label><?php echo $screen[3];?></label>
              <input type="range" min="0" max="100" value="50" class="slider" name="rangeEat" id="eat" onchange="setEatValue()" onclick="setEatValue()" ontouchstart="setEatValue()">
              <label style="float: left; font-size: 9pt;">stimme gar nicht zu</label><label style="float: right; font-size: 9pt;">stimme voll zu</label>
            </div>
          </p>
          <p>
            <div class="slidecontainer">
              <label><?php echo $screen[4];?></label>
              <input type="range" min="0" max="100" value="50" class="slider" name="rangeUse" id="use" onchange="setUseValue()" onclick="setUseValue()" ontouchstart="setUseValue()">
              <label style="float: left; font-size: 9pt;">stimme gar nicht zu</label><label style="float: right; font-size: 9pt;">stimme voll zu</label>
            </div>
          </p>
          <p>
            <div class="slidecontainer">
              <label><?php echo $screen[5];?></label>
              <input type="range" min="0" max="100" value="50" class="slider" name="rangeAway" id="away" onchange="setAwayValue()" onclick="setAwayValue()" ontouchstart="setAwayValue()">
              <label style="float: left; font-size: 9pt;">stimme gar nicht zu</label><label style="float: right; font-size: 9pt;">stimme voll zu</label>
            </div>
          </p>

          <input type="hidden" id="eatSliderMoved" name="eatSliderMoved" value="">
          <input type="hidden" id="useSliderMoved" name="useSliderMoved" value="">
          <input type="hidden" id="awaySliderMoved" name="awaySliderMoved" value="">

          <input id="next-button" class='button c12' type='submit' name='next' value=<?php echo $button_value?>>

          <script>
            var slider1 = document.getElementById("eat");
            var slider2 = document.getElementById("use");
            var slider3 = document.getElementById("away");

            function setEatValue(){
              document.getElementById("eatSliderMoved").value = slider1.value;
              console.log("Eat has been set");
            }

            function setUseValue(){
              document.getElementById("useSliderMoved").value = slider2.value;
              console.log("Use has been set");
            }

            function setAwayValue(){
              document.getElementById("awaySliderMoved").value = slider3.value;
              console.log("Away has been set");
            }
          </script>

        </div>
      </form>
    </main>
  </body>
</html>
