<?php
require 'config/auth.php';
require 'config/connect.php';
require 'utils/menu-bar.php';

if (isset($_POST['submit'])) {

  //recuperation 
  $d = @$_POST['desti'];
  $f = @$_POST['flight'];
  $s = @$_POST['seats'];
  $p = @$_POST['plan'];
  $de = @$_POST['dep'];
  $a = @$_POST['arrival'];
  $h = @$_POST['hotel'];
  $user = $_SESSION['USER'];
  $company_id = $user['company_id'];

  if (is_numeric($_POST['desti']) || is_numeric($_POST['flight']) == FALSE || is_numeric($_POST['seats']) == FALSE || is_numeric($_POST['plan']) || is_numeric($_POST['hotel'])) {
    $o = "Please verify your informations type!";
  } else {
    $q = $pdo->prepare("INSERT INTO trips(Destination, Flight_number, Number_of_seats, Plan, Departure_date, Arrival_date, Hotel, company_id) 
    VALUES (:Destination, :Flight_number, :Number_of_seats, :Plan, :Departure_date, :Arrival_date, :Hotel, :company_id)");
    $q->bindParam(':Destination', $d);
    $q->bindParam(':Flight_number', $f);
    $q->bindParam(':Number_of_seats', $s);
    $q->bindParam(':Plan', $p);
    $q->bindParam(':Departure_date', $de);
    $q->bindParam(':Arrival_date', $a);
    $q->bindParam(':Hotel', $h);
    $q->bindParam(':company_id', $company_id);


    try {
      if ($q->execute()) {
        echo "<script>alert('Trip added successfully!')</script>";
        echo "<script>window.location.replace('/travago/agency/trips')</script>";
      } else {
        echo "<script>alert('Error executing query')</script>";
      }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }


    $el = "Trip added successfully!";
  }
}
?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--<script src="https://cdn.tailwindcss.com"></script>-->
  <title>Add trips</title>
  <link rel="stylesheet" href="style.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      -webkit-font-smoothing: antialiased;
      -moz-font-smoothing: antialiased;
      -o-font-smoothing: antialiased;
      text-rendering: optimizeLegibility;
    }

    body {
      font-family: "Open Sans", Helvetica, Arial, sans-serif;
      font-weight: 400;
      font-size: 16px;
      line-height: 30px;
      /* color: rgb(32, 32, 32); */
      /* background: rgb(20, 37, 53); */
    }

    .container {
      max-width: 900px;
      width: 100%;
      margin: 0 auto;
      position: relative;
    }

    #contact input,
    textarea,
    button {
      font: 400 16px "Open Sans", Helvetica, Arial, sans-serif;
    }

    #contact {
      background: #e7e7e7;
      padding: 25px;
      margin: 50px 0;
      border-radius: 12px;
    }

    #contact h3 {
      color: rgb(0, 0, 0);
      display: block;
      font-size: 30px;
      font-weight: 700;
      margin-left: 20px;
    }

    #contact h4 {
      margin: 5px 0 15px;
      display: block;
      color: rgb(31, 31, 31);
      font-size: 13px;
      margin-left: 20px;
    }

    fieldset {
      border: medium none !important;
      margin: 0 0 10px;
      min-width: 100%;
      padding: 0;
      width: 100%;
    }

    #contact input {
      width: 50%;
      border: 1px solid #CCC;
      border-radius: 12px;
      background: #FFF;
      margin: 0 0 5px;
      padding: 10px;
    }

    #contact input:hover {
      -webkit-transition: border-color 0.3s ease-in-out;
      -moz-transition: border-color 0.3s ease-in-out;
      transition: border-color 0.3s ease-in-out;
      border: 1px solid rgb(134, 134, 134);

    }

    #contact button {
      cursor: pointer;
      width: 20%;
      border: none;
      background: rgb(0, 0, 0);
      color: #FFF;
      margin: 0 0 5px 20px;
      padding: 10px;
      font-size: 15px;
      border-radius: 142px;

    }

    #contact button:hover {
      background: rgb(33, 164, 240);
      -webkit-transition: background 0.3s ease-in-out;
      -moz-transition: background 0.3s ease-in-out;
      transition: background-color 0.3s ease-in-out;
    }

    #contact button[type="submit"]:active {
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    #contact input:focus {
      outline: 0;
      border: 1px solid rgb(82, 81, 81);
    }

    ::-webkit-input-placeholder {
      color: rgb(66, 66, 66);
    }


    .row {
      display: flex;
      width: 100% !important;
    }

    .row .col {
      width: 50%;
      margin: 20px;
    }

    input[type="radio"] {
      width: 10% !important;
    }

    #contact .row .radio {
      border: 1px solid rgb(97, 97, 97) !important;
      background-color: rgb(255, 255, 255);
      margin-bottom: 5px !important;
    }

    .success {
      color: green;
      font-weight: 700;
      padding: 5px;
      text-align: center;
    }

    .failed {
      color: red;
      font-weight: 700;
      padding: 5px;
      text-align: center;
    }

    /*body {
    
        /*width: 50%;*/
    /* padding: 20px;
      /*}*/
    #add {
      color: black;
      font-size: 45px;
      text-align: center;
    }

    #info {
      font-size: 25px;
    }

    #button {
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      margin-top: -5px;
      margin-bottom: -5px;

    }

    .can:hover {
      background: red;

    }

    #i {
      font-size: 25px;
      color: green;
      text-align: center;
      font-family: "Open Sans", Helvetica, Arial, sans-serif;
      font-weight: bold;
      margin-top: 12px;
    }

    #o {
      font-size: 20px;
      color: red;
      text-align: center;
      font-family: verdana;

    }

    #text {

      border: 1px solid #CCC;
      border-radius: 12px;
      background: #FFF;
      margin: 0 0 5px;
      padding: 10px;
    }

    #co {
      width: 100%;
    }
  </style>
</head>

<body>
  <header class="bg-white shadow border-b w-full">
    <div class="flex mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-x-4">
      <a href="/travago/agency/circuits" class="text-gray-800">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="w-8 h-8 text-gray">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
      </a>
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Trips
      </h1>
    </div>
  </header>
  <div class="container">
    <form id="contact" method="post" action="/travago/agency/trips/add-trip">
      <h2 class="text-base font-semibold leading-7 text-gray-900" id="add">Edit a trip</h2>
      <!--<p class="mt-1 text-sm leading-6 text-gray-600" id="info">                      </p>-->
      <?php

      $i = @$el;
      echo "<div id='i'>$i</div>";
      $oo = @$o;
      echo "<div id='o'>$oo</div>";


      ?>

      <div class="row">
        <div class="col">
          <fieldset>
            <label for="first-name">Destination
              <?php echo " <font color='red'> * </font>"; ?>
            </label>
          </fieldset>
          <fieldset>
            <input type="text" name="desti" autocomplete="given-name" required>
          </fieldset>

          <fieldset>
            <label for="street-address">Flight Number
              <?php echo " <font color='red'> * </font>"; ?>
            </label>
          </fieldset>
          <fieldset>
            <input type="text" name="flight" autocomplete="street-address" required>
          </fieldset>
          <fieldset>
            <label for="street-address">Number of seats
              <?php echo " <font color='red'> * </font>"; ?>
            </label>
          </fieldset>
          <fieldset>
            <input type="text" name="seats" autocomplete="street-address" required>
          </fieldset>
        </div>



        <div class="col">
          <table>
            <tr>
              <fieldset>
                <td><label for="email">Plan
                    <?php echo " <font color='red'> * </font>"; ?>
                  </label>
                  <textarea id="text" name="plan" rows="6" cols="40" autocomplete="email" required></textarea>
                </td>
              </fieldset>
            </tr>
            <tr>
              <fieldset>
                <td> <label for="last-name">Departure Date
                    <?php echo " <font color='red'> * </font>"; ?>
                  </label>
                  <input type="date" name="dep" autocomplete="family-name" id="co" required>
                </td>
              </fieldset>
            </tr>
            <tr>

              <fieldset>
                <td> <label for="street-address">Arrival Date
                    <?php echo " <font color='red'> * </font>"; ?>
                  </label>
                  <input type="date" name="arrival" autocomplete="street-address" id="co" required>
                </td>
              </fieldset>
            </tr>

            <fieldset>
              <td><label for="city">Hotel
                  <?php echo " <font color='red'> * </font>"; ?>
                </label>
                <input type="text" name="hotel" autocomplete="address-level2" required>
              </td>
            </fieldset>
          </table>

        </div>
      </div>




      <div id="button">
        <fieldset>
          <button type="reset" id="contact-submit" class="can">Cancel</button>
          <button type="submit" name="submit" id="contact-submit">Save</button>
        </fieldset>
      </div>

    </form>
  </div>


</body>

</html>