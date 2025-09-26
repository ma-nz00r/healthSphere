<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$server   = "localhost";
$username = "root";
$password = "";
$dbname   = "healthsphere";

$conn = new mysqli($server, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $rating = intval($_POST['rating']);
    $review = trim($_POST['review']);

    if (!empty($name) && !empty($email) && !empty($rating) && !empty($review)) {
        $stmt = $conn->prepare("INSERT INTO reviews (`name`, `email`, `rating`, `review`) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("SQL Error in prepare(): " . $conn->error);
        }

        $stmt->bind_param("ssis", $name, $email, $rating, $review);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>✅ Thank you! Your review has been submitted.</p>";
        } else {
            $message = "<p style='color:red;'>❌ Execute Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $message = "<p style='color:orange;'>⚠ Please fill in all fields.</p>";
    }
}

// Fetch all reviews
$reviews = [];
$result = $conn->query("SELECT name, rating, review FROM reviews ORDER BY id DESC");
if ($result && $result->num_rows > 0) {
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Reviews</title>
  <link rel="stylesheet" href="reviews.css">
</head>
<body>
  <div class="container">
    <h1 class="title">What Our Patients Say</h1>
    <p class="subtitle">Real experiences from patients who trusted our hospital.</p>

    <!-- Show feedback message -->
    <?php if (!empty($message)) echo $message; ?>

    <div class="reviews">
      <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $r): ?>
          <div class="review-card">
            <div class="review-content">
              <div class="stars">
                <?php echo str_repeat("★", $r['rating']) . str_repeat("☆", 5 - $r['rating']); ?>
              </div>
              <p class="review-text">
                "<?php echo htmlspecialchars($r['review']); ?>"
              </p>
              <p class="reviewer">- <?php echo htmlspecialchars($r['name']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No reviews yet. Be the first to share your experience!</p>
      <?php endif; ?>
    </div>

    <!-- Review Submission Form -->
    <div class="review-form">
      <h2>Share Your Experience</h2>
      <form action="reviews.php" method="post">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        
        <select name="rating" required>
          <option value="">Select Rating</option>
          <option value="5">★★★★★ - Excellent</option>
          <option value="4">★★★★☆ - Good</option>
          <option value="3">★★★☆☆ - Average</option>
          <option value="2">★★☆☆☆ - Poor</option>
          <option value="1">★☆☆☆☆ - Very Bad</option>
        </select>
        
        <textarea name="review" rows="5" placeholder="Write your review here..." required></textarea>
        <button type="submit">Submit Review</button>
      </form>
    </div>
  </div>
</body>
</html>
