<?php
include "db.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_name = trim($_POST['bus_name']);
    $source = trim($_POST['source']);
    $destination = trim($_POST['destination']);
    $travel_date = trim($_POST['travel_date']);
    $available_seats = intval($_POST['available_seats']);

    // Validate inputs
    if ($bus_name && $source && $destination && $travel_date && $available_seats > 0) {
        $sql = "INSERT INTO buses (bus_name, source, destination, travel_date, available_seats) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $bus_name, $source, $destination, $travel_date, $available_seats);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>✅ Bus details added successfully!</p>";
        } else {
            echo "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color:red;'>❌ Please fill in all fields correctly.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Bus Details</title>
</head>
<body>
    <h2>Enter Bus Details</h2>
    <form method="POST" action="">
        <label>Bus Name:</label><br>
        <input type="text" name="bus_name" required><br><br>

        <label>Source:</label><br>
        <input type="text" name="source" required><br><br>

        <label>Destination:</label><br>
        <input type="text" name="destination" required><br><br>

        <label>Travel Date:</label><br>
        <input type="date" name="travel_date" required><br><br>

        <label>Available Seats:</label><br>
        <input type="number" name="available_seats" min="1" required><br><br>

        <button type="submit">Add Bus</button>
    </form>
</body>
</html>
