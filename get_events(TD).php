<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';

try {
    // Return only needed columns (adjust these to match your table)
    $sql = "SELECT id, title, description, location, event_date 
            FROM events 
            ORDER BY event_date ASC";

    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events, JSON_UNESCAPED_UNICODE);

    $stmt->close();

} catch (Throwable $e) {
    // Never expose internal errors to client
    error_log("get_events error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Server error"]);
}
