<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php
    /******************************************************************
       View.php
       This file is where the user interacts only and checks all the POST actions
       PHP can be combined with HTML codes.
       ******************************************************************/

    include("Common.php");

    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['userpassword'])) {
        $user = getUser($_POST['username'], $_POST['userpassword']);
        if (is_array($user)) {
            header("location: admin.php");
        } else {
            $error = "Your Username or Password is invalid!";
        }
    }

    if ($error) {
        echo sprintf("<div class='alert alert-error shadow-lg' style='border-radius: 0 !important;'> <div>
        <svg xmlns='http://www.w3.org/2000/svg' class='stroke-current flex-shrink-0 h-6 w-6' fill='none' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>
        <span>%s</span>
      </div></div>", $error);
    }

    echo "<div class='container my-16 px-6 mx-auto'>";
    echo "<div class='navbar text-neutral-content grid justify-center items-center'>";
    echo "<p class='font-bold'>KFC Ordering System</p>";
    echo "</div>";

    echo "<div class='pt-16 grid justify-center items-center'>";

    // Guest Button
    echo "<form class='mb-8' action=view.php method='post'>";
    echo "<input type='hidden' name='action'><input class='btn btn-success w-full max-w-xs' type='submit' value='Self-Order' />";
    echo "</form>";

    echo "<div class='divider'>OR</div>";

    // Admin Log In 
    echo "<form class='mt-8' method='post'>";
    echo "<input class='input input-bordered input-primary w-full max-w-xs' type='text' name='username' placeholder='Username' required><br><br>";
    echo "<input class='input input-bordered input-primary w-full max-w-xs' type='password' name='userpassword' placeholder='Password' required><br><br>";
    echo "<input type='hidden' name='login'><input class='btn btn-primary w-full max-w-xs' type='submit' value='Staff Login' />";
    echo "</form>";

    echo "</div>";
    echo "</div>";
    ?>
</body>

</html>