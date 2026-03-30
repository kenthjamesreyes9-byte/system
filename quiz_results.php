<?php
// Check if user has permission to view results
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user quiz results
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM quiz_results WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Display quiz results
    echo '<h1>Quiz Results</h1>';
    echo '<table class="results">';
    echo '<tr><th>Quiz Title</th><th>Score</th><th>Date Taken</th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['quiz_title']) . '</td>';
        echo '<td>' . htmlspecialchars($row['score']) . '</td>';
        echo '<td>' . date('Y-m-d', strtotime($row['date_taken'])) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>No quiz results found.</p>';
}

$stmt->close();
$conn->close();
?>