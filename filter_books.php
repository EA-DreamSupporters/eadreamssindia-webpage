<?php
include('config.php'); // Database connection

// Get the filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';
$author = isset($_GET['author']) ? $_GET['author'] : '';

// Build the query based on the filters
$query = "SELECT * FROM books WHERE 1";

if ($category) {
    $query .= " AND cat_id = '$category'";
}

if ($subcategory) {
    $query .= " AND subcat_id = '$subcategory'";
}

if ($author) {
    $query .= " AND author = '$author'";
}

// Execute the query
$result = mysqli_query($con, $query);

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
    // Loop through the results and display the books
    while ($row = mysqli_fetch_array($result)) {
        ?>
        <div class="col-book style-2">
            <div class="dz-shop-card style-1">
                <div class="dz-media">
                    <a href="book_details.php?id=<?php echo $row['book_id']; ?>"><img
                            src="images/books/<?php echo $row['image']; ?>"></a>
                </div>
                <div class="bookmark-btn style-2">
                    <input class="form-check-input" type="checkbox" id="flexCheckDefault1">
                </div>
                <div class="dz-content">
                    <h5 class="title"><a
                            href="book_details.php?id=<?php echo $row['book_id']; ?>"><?php echo $row['book']; ?></a></h5>
                    <ul class="dz-tags">
                        <span class="price-num">₹<?php echo $row['price']; ?></span>
                    </ul>
                    <div class="book-footer">
                        <div class="price">
                            <span class="price-num">₹<?php echo $row['price']; ?></span>
                            <del>₹ <?php echo $row['mrp']; ?></del>
                        </div>
                        <a href="book_details.php?id=<?php echo $row['book_id']; ?>"
                            class="btn btn-secondary box-btn btnhover2">View Details</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>No books found with the selected filters.</p>";
}
?>