<?php
session_start();
require 'config/connect.php';

$user = $_SESSION['USER'];
$company_id = $user['company_id'];
$errorSearch = '';
$trips = [];
// search tours by destination 
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    if (empty($search)) {
        $errorSearch = "Please enter a keyword to search";
    } else {
        $sql = "SELECT * FROM trips where destination like '%$search%'";
        $result = $pdo->query($sql);
        $trips = $result->fetchAll();
    }
} else {
    $sql = "SELECT * FROM trips";
    $result = $pdo->query($sql);
    $trips = $result->fetchAll();
}
// Search circuits by destination
$circuits = [];
if (isset($_GET['search_circuits'])) {
    $searchCircuits = $_GET['search_circuits'];
    if (empty($searchCircuits)) {
        $errorSearch = "Please enter a keyword to search";
    } else {
        $sql = "SELECT * FROM tours where destination like '%$searchCircuits%'";
        $result = $pdo->query($sql);
        $circuits = $result->fetchAll();
    }
} else {
    $sql = "SELECT * FROM tours";
    $result = $pdo->query($sql);
    $circuits = $result->fetchAll();
}

// search vehicles by brand
$vehicles = [];
if (isset($_GET['search_vehicles'])) {
    $searchVehicles = $_GET['search_vehicles'];
    if (empty($searchVehicles)) {
        $errorSearch = "Please enter a keyword to search";
    } else {
        $sql = "SELECT * FROM vehicles where brand like '%$searchVehicles%'";
        $result = $pdo->query($sql);
        $vehicles = $result->fetchAll();
    }
} else {
    $sql = "SELECT * FROM vehicles";
    $result = $pdo->query($sql);
    $vehicles = $result->fetchAll();
}
?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <link href="/styles/output.css" rel="stylesheet"> -->
    <title>List of trips</title>
</head>

<body>
    <!-- Cover image -->
    <img class="z-0 w-full h-full fixed opacity-[80%]" src="public/travago-cover.png" alt="banner">
    <!-- Header  -->
    <header class="absolute inset-x-0 top-0 z-50">
        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
            <!-- Logo -->
            <div class="flex lg:flex-1">
                <a href="/home" class="-m-1.5 p-1.5 flex  
                    ">
                    <span class="sr-only">Your Agency</span>
                    <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                        alt="logo">
                    <!-- if user is connected, show profile link else show nothing-->
                    <!-- Company name -->
                    <?php
                    if (isset($_SESSION['USER']) && isset($_SESSION['USER']['id'])) {
                        $companyId = $_SESSION['USER']['company_id'];
                        // get company name
                        $sql = "SELECT * from companies WHERE id=:id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':id', $companyId);
                        $stmt->execute();
                        $company = $stmt->fetch(PDO::FETCH_ASSOC);
                        $companyName = $company['name'];

                        if ($_SESSION['USER']['user_type'] !== 'user') {
                            echo ("<a href='/agency'
                                style='color: #000668;font-weight: bold;display:flex;align-items:center;gap:0.5rem;'
                                >$companyName
                                </a>");
                        } else if (isset($_SESSION['USER']['username'])) {
                            echo ("<a href='/trips'
                                style='color: #000668;font-weight: bold;display:flex;align-items:center;gap:0.5rem;'
                                >$companyName
                                </a>");
                        }

                    } else {
                        echo ("<a href='/home'
                            style='color: #000668;font-weight: bold;display:flex;align-items:center;gap:0.5rem;'
                            >TravaGo
                            </a>");

                    }
                    ?>

                </a>
            </div>
            <!-- Desktop menu -->
            <div class="flex lg:hidden">
                <button type="button"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                    onclick="toggleMenu()">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-12">
                <a href="/trips" class="text-sm font-semibold leading-6 text-gray-900">Trips</a>
                <?php
                if (isset($_SESSION['USER']) && $_SESSION['USER']['user_type'] !== 'user') {

                    echo "<a href='/agency' class='text-sm font-semibold leading-6 text-gray-900'>Agency</a>";
                }
                ?>
                <a href="/about" class="text-sm font-semibold leading-6 text-gray-900">About us</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <?php
                if (isset($_SESSION['USER']) && isset($_SESSION['USER']['username'])) {
                    $username = $_SESSION['USER']['username'];
                    echo ("<a href='/profile' 
                                    style='color: #000668;font-weight: bold;display:flex;align-items:center;gap:0.5rem;'
                                    >Connected as $username
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' />
                                    </svg>
                                    </a>");
                    //disconnect button
                    echo ("<a href='utils/logout.php' class='mx-3 block rounded-lg px-2 py-2 text-base font-semibold leading-7 text-red-500'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15' />
                                    </svg>
                                    </a>");
                } else {
                    // Desktop
                    echo ("<a href='login' class='-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50'>Log in</a>");
                }
                ?>
            </div>
        </nav>
        <!-- Mobile menu, show/hide based on menu open state. -->

        <div id="travago-mobile-menu" class="hidden lg:hidden" role="dialog" aria-modal="true">
            <!-- Background backdrop, show/hide based on slide-over state. -->
            <div class="fixed inset-0 z-50"></div>
            <div
                class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex items-center justify-between">
                    <a href="" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Agency</span>
                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                            alt="">
                    </a>
                    <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" onclick="toggleMenu()">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                            <a href="/trips"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Trips</a>
                            <?php
                            if (isset($_SESSION['USER']) && $_SESSION['USER']['user_type'] !== 'user') {

                                echo "<a href='/agency' class='-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50'>Agency</a>";
                            } ?> <a href="/about"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">About
                                us</a>
                        </div>
                        <div class="py-6">
                            <?php
                            if (isset($_SESSION['USER']['username'])) {
                                $username = $_SESSION['USER']['username'];
                                echo ("<a href='/profile' 
                                    style='color: #000668;font-weight: bold;display:flex;align-items:center;gap:0.5rem;'
                                    >Connected as $username
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963  0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' />
                                    </svg>
                                    </a>");
                                echo "<a href='utils/logout.php' class='mx-3 block rounded-lg px-2 py-2 text-base font-semibold leading-7 text-red-500'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15' />
                                    </svg>
                                    </a>";
                            } else {
                                // Mobile
                                echo ("<a href='login' class='-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50'>Log in</a>");
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <!-- Search trips -->
    <div class="absolute top-32 mx-auto z-50 w-full items-center justify-center p-2 gap-4">
        <form action="/trips" method="GET"
            class="mx-auto col-span-4 md:col-span-1 w-full lg:min-w-96 max-w-sm border rounded-lg shadow bg-gray-800 border-gray-700 p-2">
            <div class="flex items-center justify-between">
                <input type="text" name="search" placeholder="Search tours by destination"
                    class="w-full bg-gray-800 text-white border-none focus:outline-none"
                    value="<?php echo $_GET['search'] ?? ''; ?>" />
                <?php
                if (isset($_GET['search'])) {
                    echo "<a href='/trips' 
                    class='rounded-md bg-red-500 px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600'
                    >Clear</a>"
                    ;
                    if (!empty($_GET['search'])) {
                        echo "<p class='text-green-500 text-sm'><span class='text-sm text-green-500'>Destination: {$_GET['search']}</span></p>";

                    }
                } else {
                    echo "<button type='submit'
                        class='hidden lg:block rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'>
                        Search</button>";
                }
                if ($errorSearch) {
                    echo "<p class='text-xs text-red-500'>$errorSearch</p>";
                }

                ?>
            </div>
        </form>
    </div>
    <!-- Trips Cards -->
    <div
        class="absolute left-0 top-64 z-50 flex flex-row w-full p-2 gap-4 overflow-x-auto border bg-[#000668] bg-opacity-50">
        <!-- Card  -->
        <?php
        if (empty($trips)) {
            echo "<div class='w-full mx-auto text-center text-lg'>No trips found</div>";
        } else {
            foreach ($trips as $trip) {
                $destination = $trip['Destination'];
                $departure_date = $trip['Departure_date'];
                $price = $trip['price'] ?? 1000;
                $availability = $trip['Number_of_seats'] - ($trip['number_of_booked_seats'] ?? 0);
                $status = $departure_date > date('Y-m-d') ? 'Upcoming' : 'Completed';
                $availability_status = $availability > 0 ? 'Available' : 'Full';
                $description = $trip['Plan'];
                $id = $trip['Id'];
                $tour_id = $trip['tour_id'] ?? '';
                $vehicle_id = $trip['vehicle_id'] ?? '';
                // tour and vehicle
                $circuit = 'No destination';
                $brand = 'No brand';
                if ($tour_id) {
                    $sql = "SELECT * FROM tours WHERE id = $tour_id";
                    $result = $pdo->query($sql);
                    $tour = $result->fetch();
                    $circuit = $tour['destination'] ?? 'No destination';
                }
                if ($vehicle_id) {
                    $sql = "SELECT * FROM vehicles WHERE id = $vehicle_id";
                    $result = $pdo->query($sql);
                    $vehicle = $result->fetch();
                    $brand = $vehicle['brand'] ?? 'No brand';
                }

                print ("<div id='$id' class='col-span-4 md:col-span-1 w-full lg:min-w-72 max-w-sm border rounded-lg shadow bg-gray-800 border-gray-700 p-2'>
                <div class='px-5 pb-5'>
                    <div class='flex flex-row items-center justify-between'>
                        <div>
                            <h5 class='text-xl font-semibold tracking-tight text-white'>$destination
                            </h5>
                            <span class='text-sm text-white'>$departure_date</span>
                        </div>
                        <div class='flex flex-col space-y-1'>
                            <span
                                class='bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-200 dark:text-green-800 ms-auto'>
                                $status
                            </span>
                            <span
                                class='bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-red-200 dark:text-red-800 ms-auto'>
                                $availability_status
                            </span>
                        </div>

                    </div>
                    <div class='flex items-center mt-2.5 mb-5'>
                        <div class='flex items-center space-x-1 rtl:space-x-reverse'>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-gray-600' aria-hidden='true'
                                xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                        </div>
                        <span
                            class='text-xs font-semibold px-2.5 py-0.5 rounded bg-blue-200 text-blue-800 ms-3'>5.0</span>
                    </div>
                    <p class='text-sm text-gray-100 py-2'>$description</p>
                    <div class='flex items-center justify-between'>
                        <span class='text-3xl font-bold text-gray-900 text-white'>TND $price</span>
                        <a href='/trips/booking?id=$id'
                            class='text-white bg-blue-700 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800'>
                            Book
                        </a>
                    </div>
                </div>
            </div>
            ");
            }
        }
        ?>
    </div>

    <!-- search circuits -->
    <div class="absolute top-[550px] mx-auto z-50 w-full items-center justify-center p-2 gap-4">
        <form action="/trips" method="GET"
            class="mx-auto col-span-4 md:col-span-1 w-full lg:min-w-96 max-w-sm border rounded-lg shadow bg-gray-800 border-gray-700 p-2">
            <div class="flex items-center justify-between">
                <input type="text" name="search_circuits" placeholder="Search circuits by destination"
                    class="w-full bg-gray-800 text-white border-none focus:outline-none"
                    value="<?php echo $_GET['search_circuits'] ?? ''; ?>" />
                <?php
                if (isset($_GET['search_circuits'])) {
                    echo "<a href='/trips' 
                    class='rounded-md bg-red-500 px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600'
                    >Clear</a>"
                    ;
                    if (!empty($_GET['search_circuits'])) {
                        echo "<p class='text-green-500 text-sm'><span class='text-sm text-green-500'>Destination: {$_GET['search_circuits']}</span></p>";

                    }
                } else {
                    echo "<button type='submit'
                        class='hidden lg:block rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'>
                        Search</button>";
                }
                if ($errorSearch) {
                    echo "<p class='text-xs text-red-500'>$errorSearch</p>";
                }

                ?>
            </div>
        </form>
    </div>
    <!-- Circuits cards -->
    <div
        class="absolute left-0 top-[650px] z-50 flex flex-row w-full p-2 gap-4 overflow-x-auto border bg-[#000668] bg-opacity-50">
        <!-- Card  -->
        <?php
        if (empty($circuits)) {
            echo "<div class='w-full mx-auto text-center text-lg'>No circuits found</div>";
        } else {
            foreach ($circuits as $circuit) {
                $program = $circuit['program'];
                $description = $circuit['description'];
                $destination = $circuit['destination'];
                $number_of_seats = $circuit['number_of_seats'] - ($circuit['number_of_booked_seats'] ?? 0);
                $departure_date = $circuit['departure_date'];
                $status = $departure_date > date('Y-m-d') ? 'Upcoming' : 'Completed';
                $arrival_date = $circuit['arrival_date'];
                $accomodation = $circuit['accomodation'];
                $transport_type = $circuit['transport_type'];
                $availability_status = $availability > 0 ? 'Available' : 'Full';
                $price = $circuit['price'] ?? 500;
                $id = $circuit['id'];
                $tour_id = $circuit['tour_id'] ?? '';
                $vehicle_id = $circuit['vehicle_id'] ?? '';
                // tour and vehicle
                // $circuit = 'No destination';
                // $brand = 'No brand';
                // if ($tour_id) {
                //     $sql = "SELECT * FROM tours WHERE id = $tour_id";
                //     $result = $pdo->query($sql);
                //     $tour = $result->fetch();
                //     $circuit = $tour['destination'] ?? 'No destination';
                // }
                // if ($vehicle_id) {
                //     $sql = "SELECT * FROM vehicles WHERE id = $vehicle_id";
                //     $result = $pdo->query($sql);
                //     $vehicle = $result->fetch();
                //     $brand = $vehicle['brand'] ?? 'No brand';
                // }
        
                print ("<div id='$id' class='col-span-4 md:col-span-1 w-full lg:min-w-72 max-w-sm border rounded-lg shadow bg-gray-800 border-gray-700 p-2'>
                <div class='px-5 pb-5'>
                    <div class='flex flex-row items-center justify-between'>
                        <div>
                            <h5 class='text-xl font-semibold tracking-tight text-white'>$destination
                            </h5>
                            <span class='text-sm text-white'>$departure_date</span>
                        </div>
                        <div class='flex flex-col space-y-1'>
                            <span
                                class='bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-200 dark:text-green-800 ms-auto'>
                                $status
                            </span>
                            <span
                                class='bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-red-200 dark:text-red-800 ms-auto'>
                                $availability_status
                            </span>
                        </div>

                    </div>
                    <div class='flex items-center mt-2.5 mb-5'>
                        <div class='flex items-center space-x-1 rtl:space-x-reverse'>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-gray-600' aria-hidden='true'
                                xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                        </div>
                        <span
                            class='text-xs font-semibold px-2.5 py-0.5 rounded bg-blue-200 text-blue-800 ms-3'>5.0</span>
                    </div>
                    <p class='text-sm text-gray-100 py-2'>$description</p>
                        <div class='flex flex-col items-start justify-between'>
                        </div>
                    <div class='flex items-center justify-between'>
                        <span class='text-3xl font-bold text-gray-900 text-white'>TND $price</span>
                        
                    </div>
                </div>
            </div>
            ");
            }
        }
        ?>
    </div>

    <!-- search vehicles -->
    <div class="absolute top-[950px] mx-auto z-50 w-full items-center justify-center p-2 gap-4">
        <form action="/trips" method="GET"
            class="mx-auto col-span-4 md:col-span-1 w-full lg:min-w-96 max-w-sm border rounded-lg shadow bg-gray-800 border-gray-700 p-2">
            <div class="flex items-center justify-between">
                <input type="text" name="search_vehicles" placeholder="Search vehicles by brand"
                    class="w-full bg-gray-800 text-white border-none focus:outline-none"
                    value="<?php echo $_GET['search_vehicles'] ?? ''; ?>" />
                <?php
                if (isset($_GET['search_vehicles'])) {
                    echo "<a href='/trips' 
                    class='rounded-md bg-red-500 px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600'
                    >Clear</a>"
                    ;
                    if (!empty($_GET['search_vehicles'])) {
                        echo "<p class='text-green-500 text-sm'><span class='text-sm text-green-500'>Destination: {$_GET['search_vehicles']}</span></p>";

                    }
                } else {
                    echo "<button type='submit'
                        class='hidden lg:block rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'>
                        Search</button>";
                }
                if ($errorSearch) {
                    echo "<p class='text-xs text-red-500'>$errorSearch</p>";
                }

                ?>
            </div>
        </form>
    </div>
    <!-- Vehicles cards -->
    <div
        class="absolute left-0 top-[1050px] z-50 flex flex-row w-full p-2 gap-4 overflow-x-auto border bg-[#000668] bg-opacity-50">
        <!-- Card  -->
        <?php
        if (empty($circuits)) {
            echo "<div class='w-full mx-auto text-center text-lg'>No circuits found</div>";
        } else {
            foreach ($vehicles as $vehicle) {
                $brand = $vehicle['brand'];
                $model = $vehicle['model'];
                $number_of_seats = $vehicle['number_of_seats'] - ($vehicle['number_of_booked_seats'] ?? 0);
                $plate_number = $vehicle['plate_number'];
                $created_at = $vehicle['created_at'];
                $availability_status = $availability > 0 ? 'Available' : 'Full';
                $id = $vehicle['id'];
                // tour and vehicle
                // $vehicle = 'No destination';
                // $brand = 'No brand';
                // if ($tour_id) {
                //     $sql = "SELECT * FROM tours WHERE id = $tour_id";
                //     $result = $pdo->query($sql);
                //     $tour = $result->fetch();
                //     $vehicle = $tour['destination'] ?? 'No destination';
                // }
                // if ($vehicle_id) {
                //     $sql = "SELECT * FROM vehicles WHERE id = $vehicle_id";
                //     $result = $pdo->query($sql);
                //     $vehicle = $result->fetch();
                //     $brand = $vehicle['brand'] ?? 'No brand';
                // }
        
                print ("<div id='$id' class='col-span-4 md:col-span-1 w-full lg:min-w-72 max-w-sm border rounded-lg shadow bg-gray-800 border-gray-700 p-2'>
                <div class='px-5 pb-5'>
                    <div class='flex flex-row items-center justify-between'>
                        <div>
                            <h5 class='text-xl font-semibold tracking-tight text-white'>$brand
                            </h5>
                        </div>
                        <div class='flex flex-col space-y-1'>
                            <span
                                class='bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-red-200 dark:text-red-800 ms-auto'>
                                $availability_status
                            </span>
                        </div>

                    </div>
                    <div class='flex items-center mt-2.5 mb-5'>
                        <div class='flex items-center space-x-1 rtl:space-x-reverse'>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-yellow-300' aria-hidden='true' xmlns='http://www.w3.org/2000/svg'
                                fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                            <svg class='w-4 h-4 text-gray-600' aria-hidden='true'
                                xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                <path
                                    d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                            </svg>
                        </div>
                        <span
                            class='text-xs font-semibold px-2.5 py-0.5 rounded bg-blue-200 text-blue-800 ms-3'>5.0</span>
                    </div>
                    <p class='text-sm text-gray-100 py-2'>
                        <span class='text-sm font-semibold text-gray-100'>Model: $model </span>
                        <span class='text-sm font-semibold text-gray-100'>Plate number: $plate_number</span>
                    </p>
                        <div class='flex flex-col items-start justify-between'>
                        </div>
                    <div class='flex items-center justify-between'>
                        <span class='text-3xl font-bold text-gray-900 text-white'>
                            <span class='text-sm font-semibold text-gray-100'>Number of seats: $number_of_seats </span>
                        </span>
                    </div>
                </div>
            </div>
            ");
            }
        }
        ?>
    </div>

</body>

</html>