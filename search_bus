<?php
include "db.php";

// Handle search
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $travel_date = $_POST['travel_date'];

    $sql = "SELECT * FROM buses WHERE source=? AND destination=? AND travel_date=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $source, $destination, $travel_date);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Available Buses</h2>";

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='8'>";
        echo "<tr>
                <th>Bus Name</th>
                <th>From</th>
                <th>To</th>
                <th>Available Seats</th>
                <th>Action</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['bus_name']}</td>
                    <td>{$row['source']}</td>
                    <td>{$row['destination']}</td>
                    <td>{$row['available_seats']}</td>
                    <td><a href='book_ticket.php?id={$row['id']}'>Book Now</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No buses found for your selection.</p>";
    }
}

// Dropdown options from buses table
$sources = $conn->query("SELECT DISTINCT source FROM buses");
$destinations = $conn->query("SELECT DISTINCT destination FROM buses");
?>

<h2>Search Bus</h2>
<form method="POST">
    <label>From:</label>
    <select name="source" required>
        <option value="">-- Select Source --</option>
        <?php while ($s = $sources->fetch_assoc()) {
            echo "<option value='{$s['source']}'>{$s['source']}</option>";
        } ?>
    </select><br /><br />

    <label>To:</label>
    <select name="destination" required>
        <option value="">-- Select Destination --</option>
        <?php while ($d = $destinations->fetch_assoc()) {
            echo "<option value='{$d['destination']}'>{$d['destination']}</option>";
        } ?>
    </select><br /><br />

    <label>Travel Date:</label>
    <input type="date" name="travel_date" required><br /><br />

    <button type="submit">Search</button>
</form>
