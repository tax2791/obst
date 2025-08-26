<?php
include "db.php";

// Fetch all buses
$sql = "SELECT id, bus_name, source, destination, travel_date, available_seats FROM buses";
$result = $conn->query($sql);

echo "<h2>Available Buses</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>
            <tr>
                <th>ID</th>
                <th>Bus Name</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Travel Date</th>
                <th>Available Seats</th>
                <th>Action</th>
            </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['bus_name']) . "</td>
                <td>" . htmlspecialchars($row['source']) . "</td>
                <td>" . htmlspecialchars($row['destination']) . "</td>
                <td>" . htmlspecialchars($row['travel_date']) . "</td>
                <td>" . htmlspecialchars($row['available_seats']) . "</td>
                <td><a href='book.php?id=" . urlencode($row['id']) . "'>Book</a></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No buses found.</p>";
}

$conn->close();
?>
