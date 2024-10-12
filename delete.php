<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testing";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID parameter is set and not empty
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Select image file names from database for the given ID
    $sql = "SELECT image, image1, image2 FROM card WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['image'];
        $image1 = $row['image1'];
        $image2 = $row['image2'];

        // Delete image files from the uploads directory
        unlink("uploads/$image");
        unlink("uploads/$image1");
        unlink("uploads/$image2");

        // Delete record from the database
        $sql = "DELETE FROM card WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "No record found with the given ID";
    }
} else {
    echo "ID parameter is missing or empty";
}

$conn->close();
?>
