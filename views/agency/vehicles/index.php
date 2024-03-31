<?php
session_start();
require 'config/auth.php';
require 'config/connect.php';
require 'utils/menu-bar.php';

$user = $_SESSION['USER'];
$company_id = $user['company_id'];

////////////////////////////// ACTIVATE OR NOT 
try {
    if (isset($_POST['availability_form']) && isset($_POST['availability_id'])) {
        $id = $_POST['availability_id'];
        $sql = "UPDATE vehicles SET availability = !availability WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        if ($stmt->execute()) {
            echo "<script>alert('Availabilty modified successfully N°'. $id)</script>";
        } else {
            echo "<script>alert('Availabilty modification failed N°'. $id)</script>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

////////////////////////////// DELETE 
try {
    // Check if ID is set and valid
    if (isset($_POST['delete']) && is_numeric($_POST['delete_id'])) {
        $id = $_POST['delete_id'];

        // Prepare and execute delete statement
        $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = :id");
        $stmt->bindValue(':id', $id);

        $stmt->execute();
        echo "Record deleted successfully";

    }
} catch (PDOException $e) {
    echo "connection failed: " . $e->getMessage();
}

/////////////////////////////// GET VEHICLES
try {
    $stmt = $pdo->prepare("SELECT * FROM vehicles where company_id = :company_id");
    $stmt->bindValue(':company_id', $company_id);
    $stmt->execute();
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/travago/styles/output.css" rel="stylesheet">
    <title>Vehicles</title>
</head>

<body>
    <header class="bg-white shadow border-b">
        <div class="flex mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-x-4">
            <a href="/travago/agency/circuits" class="text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                Vehicles
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 sm:py-24">
        <div class="relative overflow-x-auto">
            <div class="p-2 w-full flex flex-row-reverse">
                <a href='/travago/agency/vehicles/add-vehicle'
                    class='rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'>
                    Add a vehicle</a>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            brand
                        </th>
                        <!-- <th scope="col" class="px-6 py-3">
                            model
                        </th> -->
                        <th>
                            number_of_seats
                        </th>
                        <th scope="col" class="px-6 py-3">
                            plate_number
                        </th>
                        <th>
                            Created at
                        </th>
                        <th>
                            availability
                        </th>
                        <th>
                        </th>
                        <th>
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($vehicles as $vehicle) {
                        $availability = $vehicle['availability'] == 1 ? 'Yes' : 'No';

                        $id = $vehicle['id'];
                        $brand = $vehicle['brand'];
                        // $model = $vehicle['model'];
                        $number_of_seats = $vehicle['number_of_seats'];
                        $plate_number = $vehicle['plate_number'];
                        $created_at = $vehicle['created_at'];
                        $company_id = $vehicle['company_id'];


                        echo " <tr
                    class='odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700'>
                    <th scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'>
                        $id
                    </th>
                    <td class='px-6 py-4'>
                        $brand
                    </td>
               
                    <td class='px-6 py-4'>
                        $number_of_seats
                    </td>
                    <td class='px-6 py-4'>
                        $plate_number
                    </td>
                    <td class='px-6 py-4'>
                        $created_at
                    </td>
                
                    <td class='px-6 py-4'>
                        $availability
                    </td>
                    <td class='px-6 py-4'>
                    <form method='POST' action='/travago/agency/vehicles'> <!-- change to ur file name -->
                            <input type='hidden' name='availability_id' value='$id'> <!-- Pass the delete_id as a hidden input -->
                            <button class='btn' name='availability_form' id='yesButton' style='color:blue;'>Change Availability</button>
                        </button>
                        </form>
                    </td>
                    <td class='px-6 py-4'>
                        <a href='edit-vehicle.php'>
                        <form method='post' action='/travago/agency/vehicles/edit-vehicle'>
                        <input type='hidden' name='Id' value='<?php echo $Id; ?>'>
                        <button class='btn' id='yesButton' style='color:green; name='edit'>Edit</button>
                        </form>
                        </a>
                    </td>
                    <td class='px-6 py-4'>
                        <form method='POST' action='/travago/agency/vehicles'> <!-- change to ur file name -->
                            <input type='hidden' name='delete_id' value='$id'> <!-- Pass the delete_id as a hidden input -->
                            <button class='btn' name='delete' id='yesButton' style='color:red;'>Delete</button>
                            </button>
                        </form>
                    </td>
                </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>