<?php

  session_start();
  $session = session_id();

  $action = "";

  //Variablen
  $sex = NULL;
  $age = NULL;
  $profession = NULL;
  $living = NULL;
  $allergies = NULL;
  $cooking = NULL;
  $instruction = "<h3>Bitte die demographischen Daten ausfüllen.</h3>*erforderliche Angaben";


  if(isset($_POST['submit'])){

    if(isset($_POST['sex'])){
      $sex = $_POST['sex'];
    }
	if(!$_POST['age']==""){
      $age = htmlspecialchars($_POST['age']);
    }
    if(!$_POST['profession']==""){
      $profession = htmlspecialchars($_POST['profession']);
    }
    if(isset($_POST['cooking'])){
      $cooking = $_POST['cooking'];
    }
    if(!$_POST['allergies']==""){
      $allergies = htmlspecialchars($_POST['allergies']);
    }
    if(!$_POST['living']==""){
      $living = htmlspecialchars($_POST['living']);
    }

    //if everything is set and button is clicked, demographic data goes into CSV
    if(isset($sex, $age, $profession, $living, $cooking)) {
      $daten = array($session, $sex, $age, $profession, $living, $cooking, $allergies);
      $fp = fopen('demographics.csv', 'a');
      fputcsv($fp, $daten);
      fclose($fp);

      // Unset all of the session variables.
      $_SESSION = array();

      // If it's desired to kill the session, also delete the session cookie.
      // Note: This will destroy the session, and not just the session data!
      if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
        );
      }
      // Finally, destroy the session.
      session_destroy();

      header('Location:final.php');
      exit;

    } else {
      $instruction = "<h3 style='color:red;'>Bitte zuerst alle erforderlichen Angaben ausfüllen.</h3>";
    }
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

	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <script src="jquery-3.2.1.min.js" type="text/Javascript"></script>
    <link rel="stylesheet" href="neogridic.css">
    <link rel="stylesheet" href="layout.css">
  </head>

  <body class="grid w960">

    <main class="grid w960">

      <form method="POST">

        <p class="c12"><?php echo $instruction ?></p>

        <p class="c12">
          <ul>
            <label><strong>Geschlecht*</strong></label><br>
            <li><input type="radio" name="sex" value="1" <?php if($sex == "1") { echo 'checked';} ?>/> männlich </li>
  					<li><input type="radio" name="sex" value="2" <?php if($sex == "2") { echo 'checked';} ?>/> weiblich </li>
  					<li><input type="radio" name="sex" value="3" <?php if($sex == "3") { echo 'checked';} ?>/> sonstiges </li>
          </ul>
        </p>

        <p class="c12">
          <ul>
            <label><strong>Alter*</strong></label><br>
			<li><input type="number" name="age" value="<?php echo $age ?>" min="18" max="100" placeholder="18"></li></ul>
        </p>

        <p class="c12">
          <ul>
            <label><strong>Beruf*</strong></label><br>
            <li><input type="text" name="profession" value="<?php echo $profession ?>" maxlength="30"></li>
          </ul>
        </p>

        <p class="c12">
          <ul>
            <label><strong>Wie viele Personen wohnen in Ihrem Haushalt*</strong></label><br>
            <li><input type="text" name="living" value="<?php echo $living ?>" maxlength="50" placeholder="alleine, mit Partner, ..."></li>
          </ul>
        </p>

        <p class="c12">
          <ul>
            <label><strong>Wie gut können Sie kochen?*</strong></label><br>
            <li><input type="radio" name="cooking" value="1" <?php if($cooking == "1") { echo 'checked';} ?>/> Kaum oder gar nicht.</li>
            <li style="list-style-position:outside;"><input type="radio" name="cooking" value="2" <?php if($cooking == "2") { echo 'checked';} ?>/> Ich weiß genug, um zurecht zu kommen, kann aber keine
              gro&szlig;e Vielfalt an Gerichten.</li>
            <li style="list-style-position:outside;"><input type="radio" name="cooking" value="3" <?php if($cooking == "3") { echo 'checked';} ?>/> Ich kann eine einfache Mahlzeit ohne gro&szlig;e Probleme zubereiten.</li>
            <li style="list-style-position:outside;"><input type="radio" name="cooking" value="4" <?php if($cooking == "4") { echo 'checked';} ?>/> Ich kann viele Gerichte zubereiten und experimentiere
              gern mit neuen Rezepten.</li>
          </ul>
        </p>

        <p class="c12">
          <ul>
            <label><strong>Allergien gegen soeben gezeigte Lebensmittel</strong></label><br>
            <li><input type="text" name="allergies" value="<?php echo $allergies ?>" maxlength="50"; ></li>
          </ul>
        </p>

        <input class="button c12"type='submit' name='submit' value='Abschließen'>

      </form>
    </main>
  </body>
</html>
