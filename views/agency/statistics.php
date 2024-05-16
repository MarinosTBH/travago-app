<?php
require 'config/auth.php';
require 'config/connect.php';
require 'utils/menu-bar.php';

$user = $_SESSION['USER'];
$user_type = $user['user_type'];

if ($user_type == 'admin') {
    $sql = "SELECT * FROM users";
} else {
    $sql = "SELECT * FROM users WHERE company_id = $company
    _id";
}
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!-- Dashboard elements -->
<link href="/styles/output.css" rel="stylesheet">
<div class="p-6 w-2/3">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-md border border-gray-100 p-6 shadow-md shadow-black/5">
            <div class="flex justify-between mb-6">
                <!-- Users -->
                <div>
                    <div class="flex items-center mb-1">
                        <div class="text-2xl font-semibold">
                            <?php
                            if ($role == 'admin') {
                                $sql = "SELECT * FROM users WHERE user_type='agency' OR user_type='user'";
                            } else {
                                $sql = "SELECT * FROM users WHERE company_id = $company_id AND user_type='user'";
                            }
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo $stmt->rowCount();
                            ?>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-400">Users</div>
                </div>
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle text-gray-400 hover:text-gray-600"><i
                            class="ri-more-fill"></i></button>
                    <ul
                        class="dropdown-menu shadow-md shadow-black/5 z-30 hidden py-1.5 rounded-md bg-white border border-gray-100 w-full max-w-[140px]">
                        <li>
                            <a href="#"
                                class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">Profile</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">Settings</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="bg-white rounded-md border border-gray-100 p-6 shadow-md shadow-black/5">
            <div class="flex justify-between mb-4">
                <div>
                    <div class="flex items-center mb-1">
                        <div class="text-2xl font-semibold">
                            <?php
                            $stmt = $pdo->query("select count(id) from companies");
                            $companies = $stmt->fetchColumn();
                            echo $companies;
                            ?>
                        </div>
                        <div
                            class="p-1 rounded bg-emerald-500/10 text-emerald-500 text-[12px] font-semibold leading-none ml-2">
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-400">Agencies</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-100 p-6 shadow-md shadow-black/5">
            <div class="flex justify-between mb-4">
                <div>
                    <div class="flex items-center mb-1">
                        <div class="text-2xl font-semibold">
                            <?php
                            $stmt = $pdo->query("select count(id) from trips");
                            $companies = $stmt->fetchColumn();
                            echo $companies;
                            ?>
                        </div>
                        <div
                            class="p-1 rounded bg-emerald-500/10 text-emerald-500 text-[12px] font-semibold leading-none ml-2">
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-400">Trips</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-100 p-6 shadow-md shadow-black/5">
            <div class="flex justify-between mb-4">
                <div>
                    <div class="flex items-center mb-1">
                        <div class="text-2xl font-semibold">
                            <?php
                            $stmt = $pdo->query("select count(id) from tours");
                            $companies = $stmt->fetchColumn();
                            echo $companies;
                            ?>
                        </div>
                        <div
                            class="p-1 rounded bg-emerald-500/10 text-emerald-500 text-[12px] font-semibold leading-none ml-2">
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-400">Circuits</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md border border-gray-100 p-6 shadow-md shadow-black/5">
            <div class="flex justify-between mb-4">
                <div>
                    <div class="flex items-center mb-1">
                        <div class="text-2xl font-semibold">
                            <?php
                            $stmt = $pdo->query("select count(id) from vehicles");
                            $companies = $stmt->fetchColumn();
                            echo $companies;
                            ?>
                        </div>
                        <div
                            class="p-1 rounded bg-emerald-500/10 text-emerald-500 text-[12px] font-semibold leading-none ml-2">
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-400">Vehicles</div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div
            class="p-6 relative flex flex-col min-w-0 mb-4 lg:mb-0 break-words bg-gray-50 w-full shadow-lg rounded">
            <div class="rounded-t mb-0 px-0 border-0">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50">Users</h3>
                    </div>
                </div>
                <!-- Users list -->
                <div class="block w-full overflow-x-auto">
                    <table class="items-center w-full bg-transparent border-collapse">
                        <thead>
                            <tr>
                                <th
                                    class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Name</th>
                                <th
                                    class="px-4 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-100 align-middle border border-solid border-gray-200 dark:border-gray-500 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($users as $user) {
                                $username = $user['username'];
                                $role = $user['user_type'];
                                echo "
                                            <tr class='text-gray-700 dark:text-gray-100'>
                                                <th
                                                    class='border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left'>
                                                    $username</th>
                                                <td
                                                    class='border-t-0 px-4 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4'>
                                                    $role
                                                </td>
                                            </tr>
                                            ";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Booking trips -->
        <div class="bg-white border border-gray-100 shadow-md shadow-black/5 p-6 rounded-md">
            <div class="flex justify-between mb-4 items-start">
                <div class="font-medium">Trip Activities</div>
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle text-gray-400 hover:text-gray-600"><i
                            class="ri-more-fill"></i></button>
                    <ul
                        class="dropdown-menu shadow-md shadow-black/5 z-30 hidden py-1.5 rounded-md bg-white border border-gray-100 w-full max-w-[140px]">
                        <li>
                            <a href="#"
                                class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">Booking
                                code</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">Status</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">User_id</a>
                                <a href="#"
                                class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">Trip destination</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="overflow-hidden">
                <table class="w-full min-w-[540px]">
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT bookings.id, bookings.status, trips.Destination, users.username FROM bookings, users, trips WHERE bookings.trip_id = trips.id AND bookings.user_id = users.id");
                        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($bookings as $booking) {
                            $booking_code = $booking['id'];
                            $status = $booking['status'];
                            $user_id = $booking['username'];
                            $destination = $booking['Destination'];
                            echo "
                                        <tr>
                                            <td class='py-2 px-4 border-b border-b-gray-50'>
                                                <div class='flex items-center'>
                                                    <a href=''
                                                        class='text-gray-600 text-sm font-medium hover:text-blue-500 ml-2 truncate'>
                                                        $booking_code
                                                        </a>
                                                </div>
                                            </td>
                                            <td class='py-2 px-4 border-b border-b-gray-50'>
                                                <span class='text-[13px] font-medium text-gray-400'>
                                                    $status
                                                </span>
                                            </td>
                                            <td class='py-2 px-4 border-b border-b-gray-50'>
                                                <span class='text-[13px] font-medium text-gray-400'>
                                                    $user_id
                                                </span>
                                            </td>
                                            <td class='py-2 px-4 border-b border-b-gray-50'>
                                                <span class='text-[13px] font-medium text-gray-400'>
                                                    $destination
                                                </span>
                                            </td>
                                        </tr>";
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>