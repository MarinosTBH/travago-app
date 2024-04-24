<?php
session_start();
require 'config/connect.php';

if (!isset($_SESSION['USER'])) {
    header("Location: /login");
    exit;
}

if (!isset($_GET['id'])) {
    echo "no id provided";
    die;
}

// declae vars
$user = $_SESSION['USER'];
$id = $_GET['id'];
$error = "";
$success = "";

// get the trip with id
$sql = "SELECT * FROM trips WHERE id = $id";
$result = $pdo->query($sql);
$trip = $result->fetch();

if (!$trip) {
    die('Trip not found');
}
// get the booking with id
$sql = "SELECT * FROM bookings WHERE trip_id = $id AND user_id = {$user['id']}";
$result = $pdo->query($sql);
$booking = $result->fetchAll();

if (isset($booking['user_id']) && $booking['user_id'] == $user['id']) {
    die("You already booked this trip");

}

$destination = $trip['Destination'];
$departure_date = $trip['Departure_date'];
$price = $trip['price'] ?? 1000;
$availability = $trip['Number_of_seats'] - ($trip['number_of_booked_seats'] ?? 0);
$status = $departure_date > date('Y-m-d') ? 'Upcoming' : 'Completed';
$availability_status = $availability > 0 ? 'Available' : 'Full';
$description = $trip['description'] ?? 'No description available';
$userId = $user['id'];

// METHOD
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $credit_card = $_POST['credit_card'];
    if ($credit_card === 'credit_card') {

        $name_on_card = $_POST['name_on_card'];
        $card_number = $_POST['card_number'];
        $expiration_month = $_POST['expiration_month'];
        $expiration_year = $_POST['expiration_year'];
        $security_code = $_POST['security_code'];
        if (
            empty($credit_card) ||
            empty($name_on_card) ||
            empty($card_number) ||
            empty($expiration_month) ||
            empty($expiration_year) ||
            empty($security_code)
        ) {
            $error = "Please fill all the payment form";
        } else {
            $expiration_date = "01" + $expiration_month + $expiration_year;
            $sql = "INSERT INTO bookings (trip_id, user_id, payment_method, name_on_card, card_number, expiration_date, security_code) 
            VALUES ($id, $userId, 'credit_card', '$name_on_card', '$card_number', '$expiration_date', '$security_code')";
            $pdo->exec($sql);
            $success = "Successfully booked with credit card payment method";
        }
    } else {
        $sql = "INSERT INTO bookings (trip_id, user_id, payment_method) VALUES ($id, {$user['id']}, 'cash')";
        $pdo->exec($sql);
        $success = "Successfully booked with cash payment method";
    }
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <link href="/styles/output.css" rel="stylesheet"> -->
    views/trips/index.php    <title>Booking</title>
</head>

<body>

    <form action="" method="post">
        <p class="text-sm text-red-500 font-semibold text-center">
            <?php if (isset($error)) {
                echo $error;
            } ?>
        </p>
        <p class="text-sm text-green-500 font-semibold text-center">
            <?php if (isset($success)) {
                echo $success;
            } ?>
        </p>
        <div class="min-w-screen min-h-screen flex items-center justify-center px-5 pb-10 pt-16">
            <!-- error -->
            <!-- Trip information -->
            <div class="w-full mx-auto rounded-lg bg-white shadow-lg p-5 text-gray-700"
                style="min-height: 600px;max-width: 600px">
                <div id='$id'
                    class='col-span-4 md:col-span-1 w-full lg:min-w-72 max-w-sm border rounded-lg shadow bg-gray-800 border-gray-700 p-2'
                    style="min-height: 600px;max-width: 600px" ;>
                    <div class='px-5 pb-5'>
                        <div class='flex flex-row items-center justify-between'>
                            <div>
                                <h5 class='text-xl font-semibold tracking-tight text-white'><?php echo $destination ?>
                                </h5>
                                <span class='text-sm text-white'>
                                    <?php echo $departure_date ?>
                                </span>
                            </div>
                            <div class='flex flex-col space-y-1'>
                                <span
                                    class='bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-200 dark:text-green-800 ms-auto'>
                                    <?php echo $status ?>
                                </span>
                                <span
                                    class='bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-red-200 dark:text-red-800 ms-auto'>
                                    <?php echo $availability_status ?>
                                </span>
                            </div>

                        </div>
                        <div class='flex items-center mt-2.5 mb-5'>
                            <div class='flex items-center space-x-1 rtl:space-x-reverse'>
                                <svg class='w-4 h-4 text-yellow-300' aria-hidden='true'
                                    xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                    <path
                                        d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                                </svg>
                                <svg class='w-4 h-4 text-yellow-300' aria-hidden='true'
                                    xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                    <path
                                        d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                                </svg>
                                <svg class='w-4 h-4 text-yellow-300' aria-hidden='true'
                                    xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                    <path
                                        d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                                </svg>
                                <svg class='w-4 h-4 text-yellow-300' aria-hidden='true'
                                    xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                    <path
                                        d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                                </svg>
                                <svg class='w-4 h-4 text-gray-600' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                    fill='currentColor' viewBox='0 0 22 20'>
                                    <path
                                        d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                                </svg>
                            </div>
                            <span
                                class='text-xs font-semibold px-2.5 py-0.5 rounded bg-blue-200 text-blue-800 ms-3'>5.0</span>
                        </div>
                        <p class='text-sm text-gray-100 py-2'>
                            <?php echo $description ?>
                        </p>
                        <!-- select payment method -->
                        <select id="credit_card" name="credit_card"
                            class='w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer'>
                            <option value='paypal'>Cash</option>
                            <option value='credit_card'>Credit Card</option>
                        </select>


                        <div class='flex items-center justify-between'>
                            <span class='text-3xl font-bold text-gray-900 text-white'>TND
                                <?php echo $price ?>
                            </span>
                            <button href="/trips/booking?id=<?php echo $id ?>" type="submit" id="submit_cash"
                                class='text-white bg-blue-700 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800'>
                                Book
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Credit Card payment -->
            <div id="payment-form" class="w-full mx-auto rounded-lg bg-white shadow-lg p-5 text-gray-700"
                style="max-width: 600px">
                <div class="w-full pt-1 pb-5">
                    <div
                        class="bg-indigo-500 text-white overflow-hidden rounded-full w-20 h-20 -mt-16 mx-auto shadow-lg flex justify-center items-center">
                        <i class="mdi mdi-credit-card-outline text-3xl"></i>
                    </div>
                </div>
                <div class="mb-10">
                    <h1 class="text-center font-bold text-xl uppercase">Secure payment info</h1>
                </div>
                <div class="mb-3 flex -mx-2">
                    <div class="px-2">
                        <label for="type1" class="flex items-center cursor-pointer">
                            <input type="radio" class="form-radio h-5 w-5 text-indigo-500" name="type" id="type1"
                                checked>
                            <img src="https://leadershipmemphis.org/wp-content/uploads/2020/08/780370.png"
                                class="h-8 ml-3">
                        </label>
                    </div>
                    <div class="px-2">
                        <label for="type2" class="flex items-center cursor-pointer">
                            <input type="radio" class="form-radio h-5 w-5 text-indigo-500" name="type" id="type2">
                            <img src="https://www.sketchappsources.com/resources/source-image/PayPalCard.png"
                                class="h-8 ml-3">
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="font-bold text-sm mb-2 ml-1">Name on card</label>
                    <div>
                        <input name="name_on_card"
                            class="w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                            placeholder="John Smith" type="text" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="font-bold text-sm mb-2 ml-1">Card number</label>
                    <div>
                        <input name="card_number"
                            class="w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                            placeholder="0000 0000 0000 0000" type="text" />
                    </div>
                </div>
                <div class="mb-3 -mx-2 flex items-end">
                    <div class="px-2 w-1/2">
                        <label class="font-bold text-sm mb-2 ml-1">Expiration date</label>
                        <div>
                            <select name="expiration_month"
                                class="form-select w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer">
                                <option value="01">01 - January</option>
                                <option value="02">02 - February</option>
                                <option value="03">03 - March</option>
                                <option value="04">04 - April</option>
                                <option value="05">05 - May</option>
                                <option value="06">06 - June</option>
                                <option value="07">07 - July</option>
                                <option value="08">08 - August</option>
                                <option value="09">09 - September</option>
                                <option value="10">10 - October</option>
                                <option value="11">11 - November</option>
                                <option value="12">12 - December</option>
                            </select>
                        </div>
                    </div>
                    <div class="px-2 w-1/2">
                        <select name="expiration_year"
                            class="form-select w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                        </select>
                    </div>
                </div>
                <div class="mb-10">
                    <label class="font-bold text-sm mb-2 ml-1">Security code</label>
                    <div>
                        <input name="security_code"
                            class="w-32 px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                            placeholder="000" type="text" />
                    </div>
                </div>
                <div>
                    <button type="submit" name="submit_credit_card"
                        class="block w-full max-w-xs mx-auto bg-indigo-500 hover:bg-indigo-700 focus:bg-indigo-700 text-white rounded-lg px-3 py-3 font-semibold"><i
                            class="mdi mdi-lock-outline mr-1"></i> PAY NOW</button>
                </div>

            </div>
        </div>
    </form>
    <script>
        const paymentForm = document.getElementById('payment-form');
        const cashButton = document.getElementById('submit_cash');

        paymentForm.style.display = 'none';
        const select = document.querySelector('select');
        select.addEventListener('change', (e) => {
            if (e.target.value === 'credit_card') {
                paymentForm.style.display = 'block';
                cashButton.style.display = 'none';
            } else {
                paymentForm.style.display = 'none';
                cashButton.style.display = 'block';
            }
        });

    </script>

</body>

</html>