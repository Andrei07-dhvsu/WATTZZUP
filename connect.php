<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

// Current date and time (server)
$current_day = date('l');          // e.g. "Sunday"
$current_time = date('H:i:s');     // e.g. "20:58:03"

// Example data for 15 slots (you can replace this with DB fetch)
$slots = [
    [
        "slot_id" => 7,
        "medicine_name" => "BioFule",
        "dispenseTime" => "20:58:03",
        "dispenseDays" => ["Monday", "Tuesday", "Wednesday", "Saturday", "Sunday"]
    ],
    [
        "slot_id" => 9,
        "medicine_name" => "Metformin",
        "dispenseTime" => "19:00:00",
        "dispenseDays" => ["Tuesday", "Friday"]
    ],
    // Add more up to slot 15...
];

// Wrap in a top-level JSON object
$response = [
    "current_day" => $current_day,
    "current_time" => $current_time,
    "slots" => $slots
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>