<?php
require 'config/auth.php';
require 'config/connect.php';
require 'utils/menu-bar.php';

// get id 
$tripId = $_GET['tripId'];

try {
    if (isset($_POST['validate']) && $_POST['status'] != 'validated') {
        $tripId = $_POST['tripId'];
        $userId = $_POST['userId'];

        // update booking
        $sql = "UPDATE bookings SET status = 'validated' WHERE trip_id = $tripId AND user_id = $userId";
        $pdo->exec($sql);
    }
} catch (\Throwable $th) {
    echo "Error: " . $th->getMessage();
}

// get trip details
$sql = "SELECT * FROM trips WHERE id = $tripId";
$result = $pdo->query($sql);
$trip = $result->fetch();

$sql = "SELECT bookings.payment_method, bookings.created_at, bookings.user_id, bookings.status,
    users.username, users.email, users.id

    FROM bookings
    INNER JOIN users ON bookings.user_id = users.id
    WHERE trip_id = $tripId";
$result = $pdo->query($sql);
$bookings = $result->fetchAll();

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/styles/output.css" rel="stylesheet">
    <title>Manage seats</title>
</head>

<body> <header class="bg-white shadow border-b">
        <div class="flex mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-x-4">
            <a href="/agency" class="text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                Trips
            </h1>
        </div>
    </header>
    <div class="flex flex-col items-center justify-center w-full lg:w-1/2 mx-auto ">

        <div class="w-full border-b border-gray-900/10 pb-8">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Destination :<?php echo $trip['Destination'] ?>
            </h2>

            <!-- users that booked the trip through table bookings -->
            <table class="w-full mt-8">
                <thead>
                    <tr>
                        <th class="text-left">ID</th>
                        <th class="text-left">Name</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Payment Method</th>
                        <th class="text-left">Date</th>
                        <th class="text-left">Status</th>
                        <th class="text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['id'] ?></td>
                            <td><?php echo $booking['username'] ?></td>
                            <td><?php echo $booking['email'] ?></td>
                            <td><?php echo $booking['payment_method'] ?></td>
                            <td><?php echo
                                date('d/m/Y', strtotime($booking['created_at'])) ?></td>
                            <!-- status -->
                            <td>
                                <p style="color:green">
                                    <?php
                                    if (isset($booking['status']) && $booking['status'] == 'validated') {
                                        echo "Validated";
                                    } ?>
                                </p>
                                <p style="color:violet">
                                    <?php
                                    if (isset($booking['status']) && $booking['status'] == 'pending') {
                                        echo "Pending";
                                    }
                                    ?>
                                </p>
                                <!-- action validate -->
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="tripId" value="<?php echo $tripId ?>">
                                    <input type="hidden" name="status" value="<?php echo $booking['status'] ?>">
                                    <input type="hidden" name="userId" value="<?php echo $booking['user_id'] ?>">
                                    <button type="submit" name="validate" style="color:blue;"><?php
                                    if (isset($booking['status']) && $booking['status'] == 'validated') {
                                        echo '-';
                                    } else {
                                        echo 'Validate';
                                    }
                                    ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>



</body>

</html>