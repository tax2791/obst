<?php
include "db.php";

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$booking_id = intval($_GET['id']);

// Fetch booking details
$sql = "SELECT b.id AS booking_id, b.passenger_name, b.seats, b.booking_time, b.travel_date,
               buses.bus_name, buses.source, buses.destination
        FROM bookings b
        JOIN buses ON b.bus_id = buses.id
        WHERE b.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

// Fetch payment details
$sql2 = "SELECT card_number, card_holder, amount, payment_date 
         FROM payments WHERE booking_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $booking_id);
$stmt2->execute();
$payment = $stmt2->get_result()->fetch_assoc();

if (!$booking) {
    die("Booking not found.");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Ticket #<?php echo $booking['booking_id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        .ticket {
            width: 500px;
            border: 2px solid #000;
            padding: 15px;
            margin: auto;
        }
        h2 { text-align: center; }
        table { width: 100%; margin-top: 10px; border-collapse: collapse; }
        td { padding: 5px; border-bottom: 1px solid #ddd; }
        .btn-print {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body onload="window.print()">

<div class="ticket">
    <h2>Bus Ticket</h2>
    <table>
        <tr><td><b>Booking ID:</b></td><td><?php echo $booking['booking_id']; ?></td></tr>
        <tr><td><b>Passenger Name:</b></td><td><?php echo $booking['passenger_name']; ?></td></tr>
        <tr><td><b>Seats:</b></td><td><?php echo $booking['seats']; ?></td></tr>
        <tr><td><b>Bus Name:</b></td><td><?php echo $booking['bus_name']; ?></td></tr>
        <tr><td><b>From:</b></td><td><?php echo $booking['source']; ?></td></tr>
        <tr><td><b>To:</b></td><td><?php echo $booking['destination']; ?></td></tr>
        <tr><td><b>Travel Date:</b></td><td><?php echo $booking['travel_date']; ?></td></tr>
        <tr><td><b>Booking Time:</b></td><td><?php echo $booking['booking_time']; ?></td></tr>
    </table>

    <h3>Payment Details</h3>
    <?php if ($payment) { ?>
    <table>
        <tr><td><b>Card Number:</b></td><td><?php echo "**** **** **** " . substr($payment['card_number'], -4); ?></td></tr>
        <tr><td><b>Card Holder:</b></td><td><?php echo $payment['card_holder']; ?></td></tr>
        <tr><td><b>Amount:</b></td><td><?php echo "₹" . number_format($payment['amount'], 2); ?></td></tr>
        <tr><td><b>Payment Date:</b></td><td><?php echo $payment['payment_date']; ?></td></tr>
    </table>
    <?php } else { ?>
        <p><i>No payment details found.</i></p>
    <?php } ?>
</div>



</body>
</html>
