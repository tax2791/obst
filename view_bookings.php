<?php
include "db.php";

$sql = "SELECT b.id AS booking_id, b.passenger_name, b.seats, b.booking_time, b.travel_date, 
               buses.bus_name, buses.source, buses.destination
        FROM bookings b
        JOIN buses ON b.bus_id = buses.id
        ORDER BY b.booking_time DESC";

$result = $conn->query($sql);

echo "<h2>All Bookings</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8'>";
    echo "<tr>
            <th>Booking ID</th>
            <th>Passenger Name</th>
            <th>Seats</th>
            <th>Bus Name</th>
            <th>From</th>
            <th>To</th>
            <th>Travel Date</th>
            <th>Booking Time</th>
            <th>Action</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['booking_id']}</td>
                <td>{$row['passenger_name']}</td>
                <td>{$row['seats']}</td>
                <td>{$row['bus_name']}</td>
                <td>{$row['source']}</td>
                <td>{$row['destination']}</td>
                <td>{$row['travel_date']}</td>
                <td>{$row['booking_time']}</td>
                <td><a href='print_ticket.php?id={$row['booking_id']}' target='_blank'>Print</a></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No bookings found.";
}
?>
<a href="http://localhost:8080/obst/bus_details.php">Bus Details</a>

<a href="http://localhost:8080/obst/search.php">Available Buses</a>
