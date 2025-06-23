<?php
include('config.php'); // connect to DB

$cat_id = isset($_GET['cat_id']) ? mysqli_real_escape_string($con, $_GET['cat_id']) : '';
$query = isset($_GET['query']) ? mysqli_real_escape_string($con, $_GET['query']) : '';

$sql = "SELECT * FROM books WHERE 1=1";

// If category filter applied
if (!empty($cat_id)) {
    $sql .= " AND cat_id = '$cat_id'";
}

// If search term provided
if (!empty($query)) {
    $sql .= " AND (book LIKE '%$query%' OR author LIKE '%$query%' OR description LIKE '%$query%')";
}

$result = mysqli_query($con, $sql);
?>

<h2>Search Results</h2>
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="book-item">
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['book']); ?>" width="100">
            <h3><?php echo htmlspecialchars($row['book']); ?></h3>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
            <p><strong>Price:</strong> ₹<?php echo $row['price']; ?> <del>₹<?php echo $row['mrp']; ?></del></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        </div>
        <hr>
        <?php
    }
} else {
    echo "<p>No books found matching your search.</p>";
}
?>
