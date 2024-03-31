<?php
require 'config/auth.php';
require 'config/connect.php';
require 'utils/menu-bar.php';

$user = $_SESSION['USER'];
$company_id = $user['company_id'];

// Initialisation des variables pour stocker les messages d'erreur
$erreurs = array();
if (isset($_POST['submit'])) {
  // Récupérer les valeurs des champs date d'arrivée et date de retour
  $date_arrivee = $_POST['departure_date'];
  $date_retour = $_POST['arrival_date'];

  // Vérifier si les champs ne sont pas vides
  if (!empty($date_arrivee) && !empty($date_retour)) {
    // Vérifier si la date de retour est inférieurà la date d'arrivée
    if ($date_retour <= $date_arrivee) {
      // Afficher un message d'erreur si la date de retour est antérieure à la date d'arrivée
      $erreurs[] = "Arrival date must be before departure date";
    }
  }
  if (empty($date_arrivee) || empty($date_retour)) {
    $erreurs[] = "Please fill in the dates";
  }

  // Vérification du programme (chaîne de caractères)
  $program = $_POST['program'];
  if (empty($program)) {
    $erreurs[] = "Program cannot be empty.";
  }

  // Vérification de la description (chaîne de caractères)
  $description = $_POST['description'];
  if (empty($description)) {
    $erreurs[] = "Description cannot be empty";
  }

  // Vérification de la destination (chaîne de caractères)
  $destination = $_POST['destination'];
  if (empty($destination)) {
    $erreurs[] = "Destination cannot be empty.";
  }

  // Vérification du nombre de places (entier)
  $number_of_seats = $_POST['number_of_seats'];
  if (!is_numeric($number_of_seats) || $number_of_seats <= 0) {
    $erreurs[] = "Number of seats must be a positive integer.";
  }
  // Vérification de l'hébergement (chaîne de caractères)
  $accomodation = $_POST['accomodation'];
  if (empty($accomodation)) {
    $erreurs[] = "Accomodation cannot be empty.";
  }

  // Vérification du type de transport (chaîne de caractères)
  $typeTransport = $_POST['transport_type'];
  if (empty($typeTransport)) {
    $erreurs[] = "Transport type cannot be empty.";
  }

  // Vérification du price (nombre réel)
  $price = $_POST['price'];
  if (!is_numeric($price) || $price <= 0) {
    $erreurs[] = "Price must be a positive number.";
  } else {

    try {
      // Les données peuvent être insérées en base de données ou traitées ici
      $sql = "INSERT INTO tours (program, description, destination, number_of_seats, departure_date, arrival_date, accomodation, transport_type, price, company_id)
        VALUES (:program, :description, :destination, :number_of_seats, :departure_date, :arrival_date, :accomodation, :transport_type, :price, :company_id)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':program', $program);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':destination', $destination);
      $stmt->bindParam(':number_of_seats', $number_of_seats);
      $stmt->bindParam(':departure_date', $date_arrivee);
      $stmt->bindParam(':arrival_date', $date_retour);
      $stmt->bindParam(':accomodation', $accomodation);
      $stmt->bindParam(':transport_type', $typeTransport);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':company_id', $company_id);

      if ($stmt->execute()) {
        echo "<script>alert('Tour added successfully!')</script>";
        echo "<script>window.location.replace('/travago/agency/circuits')</script>";
      } else {
        echo "<script>alert('Error executing query')</script>";
      }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }
}



?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Add Tour</title>
</head>

<body>
  <header class="bg-white shadow">
    <div class="flex mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-x-4">
      <a href="/travago/agency/circuits" class="text-gray-800">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="w-8 h-8 text-gray">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
      </a>
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">
        Add tour
      </h1>
    </div>
  </header>
  <div class="flex flex-col items-center justify-center w-full lg:w-1/2 mx-auto ">

    <form action="/travago/agency/circuits/add-circuit" method="post" class="w-full px-2">
      <div class="space-y-2">
        <div class="w-full border-b border-gray-900/10 pb-8">
          <h2 class="text-base font-semibold leading-7 text-gray-900">Add Tour</h2>
          <p class="mt-1 text-sm leading-6 text-gray-600">Add informations about the tour.</p>

          <?php
          if (!empty($erreurs)) {
            echo "<div class='flex flex-col'><br>";
            foreach ($erreurs as $erreur) {
              echo "<p class='text-sm text-red-500'>- $erreur<br> </p>";
            }
            echo "</div>";
          }
          ?>

          <div class="w-full mt-10 grid grid-cols-1 gap-x-2 gap-y-4 lg:gap-x-4 lg:gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <label for="program" class="block text-sm font-medium leading-6 text-gray-900">Program</label>
              <div class="mt-2">
                <input type="text" name="program"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>

            <div class="sm:col-span-4">
              <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description:</label>
              <div class="mt-2">
                <input name="description" type="text"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>

            <div class="col-span-full">
              <label for="destination" class="block text-sm font-medium leading-6 text-gray-900">Destination:</label>
              <div class="mt-2">
                <input type="text" name="destination"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>

            <div class="sm:col-span-2 sm:col-start-1">
              <label for="number_of_seats" class="block text-sm font-medium leading-6 text-gray-900">Number of
                seats</label>
              <div class="mt-2">
                <input type="text" name="number_of_seats"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>

            <div class="sm:col-span-2">
              <label for="departure_date" class="block text-sm font-medium leading-6 text-gray-900">Departure
                date</label>
              <div class="mt-2">
                <input type="date" name="departure_date"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>

            <div class="sm:col-span-2">
              <label for="arrival_date" class="block text-sm font-medium leading-6 text-gray-900">Arrival date</label>
              <div class="mt-2">
                <input type="date" name="arrival_date"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>
            <div class="sm:col-span-2 sm:col-start-1">
              <label for="accomodation" class="block text-sm font-medium leading-6 text-gray-900">Accomodation</label>
              <div class="mt-2">
                <input type="text" name="accomodation" "  class=" block w-full rounded-md border-0 py-1.5 text-gray-900
                  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                  focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>

            <div class="sm:col-span-2">
              <label for="transport_type" class="block text-sm font-medium leading-6 text-gray-900">Transport Type
              </label>
              <div class="mt-2">
                <input type="text" name="transport_type"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>

            <div class="sm:col-span-2">
              <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Price </label>
              <div class="mt-2">
                <input type="text" name="price"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>
          </div>

        </div>

        <div class="w-full mt-6 flex items-center justify-center gap-x-6">
          <button type="button" class="w-full text-sm font-semibold leading-6 text-gray-900">Cancel</button>
          <button type="submit" name="submit"
            class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
        </div>
    </form>


</body>

</html>