<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);

        echo "<h2>Form Submitted Successfully</h2>";
        echo "<p><strong>Name:</strong> $name</p>";
        echo "<p><strong>Email:</strong> $email</p>";
    } else {
        echo "<p>No data submitted.</p>";
    }
?>
