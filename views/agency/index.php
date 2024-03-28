<?php
require 'config/auth.php';

define('BASEPATH', true);
require 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/styles/output.css" rel="stylesheet">
    <title>My Agency</title>
</head>

<body>
<?php require 'menu-bar.php';?>

    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <dl class="grid grid-cols-1 lg:grid lg:grid-cols-4 gap-x-8 gap-y-16 text-center">
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-600">
                        Total Companies
                    </dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                        <?php
                        try {
                            $sql = "SELECT COUNT(id) AS num FROM companies";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            $agencyCount = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo $agencyCount['num'] . ' companies';
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }

                        ?>
                    </dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-600">Total Users</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                        <?php
                        try {
                            $sql = "SELECT * FROM users WHERE user_type='agency' OR user_type='customer'";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo $stmt->rowCount() . ' users';
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }

                        ?>
                    <dt class="text-base leading-7 text-gray-600">
                        <!-- in which how many admin and how many customers -->
                        <?php
                        // admin count 
                        $adminCount = 0;
                        $customerCount = 0;
                        foreach ($users as $user) {
                            if ($user['user_type'] == 'agency') {
                                $adminCount++;
                            } else if ($user['user_type'] == 'customer') {
                                $customerCount++;
                            }
                        }
                        echo $adminCount . ' agencies and ' . $customerCount . ' customers';

                        ?>
                    </dt>
                    </dd>

                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-600">Trips</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">0 Trips</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-600">Trips</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">0 Trips</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-600">Trips</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">0 Trips</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-600">Trips</dt>
                    <dd class="order-first text-3xl font-semibold tracking-tight text-gray-900 sm:text-5xl">0 Trips</dd>
                </div>
            </dl>
        </div>
    </div>

</body>

</html>