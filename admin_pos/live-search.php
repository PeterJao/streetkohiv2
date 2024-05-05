<?php
    // Add connection file
    include('connection.php');

    // Get the search term using $_GET
    $search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';
    // Transform to lowercase and remove spaces
    $search_term = trim(strtolower($search_term));

    // Search database
    $conn = $GLOBALS['conn'];
    $stmt = $conn->prepare("SELECT * FROM coffee WHERE coffee_name LIKE '%$search_term%'");
        
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ensure that $rows is always an array
    $data = is_array($rows) ? $rows : [];

    echo json_encode([
        'length' => count($data),  // Use count($data) instead of count($rows)
        'data' => $data
    ]);
?>
