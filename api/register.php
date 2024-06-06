<?php
include("connect.php");

$name = $_POST['name'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$address = $_POST['address'];
$image = $_FILES['photo']['name'];
$tmp_name = $_FILES['photo']['tmp_name'];
$role = $_POST['role'];

if ($password === $cpassword) {
    // Check if there was an error during file upload
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        echo '
        <script>
            alert("Error uploading file.");
            window.location= "../routes/register.html";
        </script>
        ';
        exit();
    }

    // Ensure the uploads directory exists and is writable
    $uploads_dir = "../uploads/";
    if (!is_dir($uploads_dir) || !is_writable($uploads_dir)) {
        echo '
        <script>
            alert("Uploads directory is not writable.");
            window.location= "../routes/register.html";
        </script>
        ';
        exit();
    }

    // Move the uploaded file
    if (move_uploaded_file($tmp_name, $uploads_dir . $image)) {
        // Use prepared statements to prevent SQL injection
        $insert = $connect->prepare("INSERT INTO user (name, mobile, address, password, photo, role, status, votes) VALUES (?, ?, ?, ?, ?, ?, 0, 0)");
        $insert->bind_param("ssssss", $name, $mobile, $address, $password, $image, $role);

        if ($insert->execute()) {
            echo '
            <script>
                alert("Registration successful!");
                window.location= "../";
            </script>
            ';
        } else {
            echo '
            <script>
                alert("Some error occurred!");
                window.location= "../routes/register.html";
            </script>
            ';
        }

        $insert->close();
    } else {
        echo '
        <script>
            alert("Failed to move uploaded file.");
            window.location= "../routes/register.html";
        </script>
        ';
    }
} else {
    echo '
    <script>
        alert("Password and Confirm password do not match");
        window.location= "../routes/register.html";
    </script>
    ';
}
?>
