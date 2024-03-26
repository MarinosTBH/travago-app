<?php
require ("config/auth.php");
echo ("User " . $_SESSION['USER']['username'] . " Role:" . $_SESSION['USER']['user_type']);
