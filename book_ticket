<?php
include "db.php";
session_start();

$id = $_GET['id'];

// Fetch bus details
$sql = "SELECT * FROM buses WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();

if (!$bus) {
    die("Bus not found.");
}

// STEP 1: BOOKING PREVIEW
if (isset($_POST['preview'])) {
    $_SESSION['booking'] = [
        'bus_id' => $id,
        'bus_name' => $bus['bus_name'],
        'name' => $_POST['name'],
        'seats' => (int) $_POST['seats'],
        'travel_date' => $_POST['travel_date'],
        'amount' => (int) $_POST['seats'] * 500 // dummy price per seat
    ];
}

// STEP 2: PAYMENT PROCESS
if (isset($_POST['pay'])) {
    $booking = $_SESSION['booking'];
    $card_number = $_POST['card_number'];
    $card_holder = $_POST['card_holder'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];

    // Save booking
    $insert = $conn->prepare("INSERT INTO bookings (bus_id, passenger_name, seats, travel_date) VALUES (?, ?, ?, ?)");
    $insert->bind_param("isis", $booking['bus_id'], $booking['name'], $booking['seats'], $booking['travel_date']);
    $insert->execute();
    $booking_id = $insert->insert_id;

    // Update seats
    $update = $conn->prepare("UPDATE buses SET available_seats = available_seats - ? WHERE id = ?");
    $update->bind_param("ii", $booking['seats'], $booking['bus_id']);
    $update->execute();

    // Save payment
    $payment = $conn->prepare("INSERT INTO payments (booking_id, card_number, card_holder, expiry, amount) VALUES (?, ?, ?, ?, ?)");
    $payment->bind_param("isssi", $booking_id, $card_number, $card_holder, $expiry, $booking['amount']);
    $payment->execute();

    // Show confirmation
    echo "<h2>🎫 Ticket Confirmation</h2>";
    echo "<div id='ticket' style='font-family:Arial;max-width:500px;border:1px solid #333;padding:15px;border-radius:8px;'>";
    echo "<h3 style='text-align:center;'>Bus Ticket</h3>";
    echo "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
    echo "<tr><th>Bus</th><td>{$booking['bus_name']}</td></tr>";
    echo "<tr><th>Passenger</th><td>{$booking['name']}</td></tr>";
    echo "<tr><th>Seats</th><td>{$booking['seats']}</td></tr>";
    echo "<tr><th>Travel Date</th><td>{$booking['travel_date']}</td></tr>";
    echo "<tr><th>Amount Paid</th><td>₹{$booking['amount']}</td></tr>";
    echo "</table><br>";

    echo "<h3>💳 Payment Details</h3>";
    echo "<table border='1' cellpadding='8' cellspacing='0' width='100%'>";
    echo "<tr><th>Card Holder</th><td>{$card_holder}</td></tr>";
    echo "<tr><th>Card Number</th><td>**** **** **** " . substr($card_number, -4) . "</td></tr>";
    echo "<tr><th>Expiry</th><td>{$expiry}</td></tr>";
    echo "</table></div><br>";

    echo "<button onclick='printTicket()'>🖨️ Print Ticket</button>";

    echo "<script>
        function printTicket() {
            var ticketContent = document.getElementById('ticket').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = ticketContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>";

    session_destroy();
    exit;
}
?>

<h2>Booking for: <?= htmlspecialchars($bus['bus_name']) ?></h2>

<?php if (!isset($_SESSION['booking'])): ?>
<!-- STEP 1: BOOKING FORM -->
<form method="POST">
    <label>Name:</label><br />
    <input type="text" name="name" required /><br /><br />

    <label>Seats:</label><br />
    <input type="number" name="seats" required min="1" max="<?= $bus['available_seats'] ?>" /><br /><br />

    <label>Travel Date:</label><br />
    <input type="date" name="travel_date" value="<?= $bus['travel_date'] ?>" required /><br /><br />

    <button type="submit" name="preview">Preview Booking</button>
</form>

<?php elseif(isset($_SESSION['booking']) && !isset($_POST['pay'])): 
    $b = $_SESSION['booking']; ?>
<!-- STEP 2: PREVIEW & PAYMENT FORM -->
<h3>Booking Preview</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr><th>Bus</th><td><?= htmlspecialchars($b['bus_name']) ?></td></tr>
    <tr><th>Passenger</th><td><?= htmlspecialchars($b['name']) ?></td></tr>
    <tr><th>Seats</th><td><?= $b['seats'] ?></td></tr>
    <tr><th>Travel Date</th><td><?= $b['travel_date'] ?></td></tr>
    <tr><th>Total Amount</th><td>₹<?= $b['amount'] ?></td></tr>
</table><br>

<h3>Enter Payment Details</h3>
<form method="POST">
    <label>Card Number:</label><br />
    <input type="text" name="card_number" maxlength="16" required /><br /><br />

    <label>Card Holder:</label><br />
    <input type="text" name="card_holder" required /><br /><br />

    <label>Expiry (MM/YY):</label><br />
    <input type="text" name="expiry" required /><br /><br />

    <label>CVV:</label><br />
    <input type="password" name="cvv" maxlength="3" required /><br /><br />

    <button type="submit" name="pay">Pay & Confirm</button>
</form>
<?php endif; ?>
