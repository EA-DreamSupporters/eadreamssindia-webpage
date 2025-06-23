<?php
include('config.php'); // Database connection

// Get the filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';
$author = isset($_GET['author']) ? $_GET['author'] : '';

// Build the query based on the filters
$query = "SELECT a.*,b.* FROM rentalbooks As a,category As b WHERE 1 AND  a.rcat_id=b.category_id ";

if ($category) {
    $query .= " AND a.rcat_id = '$category'";
}

if ($subcategory) {
    $query .= " AND a.rsubcat_id = '$subcategory'";
}

if ($author) {
    $query .= " AND a.rauthor = '$author'";
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
										<a href="rentalbook_details.php?id=<?php echo $row['rid']; ?>"><img src="images/<?php echo $row['rimage']; ?>">	</a>					
									</div>
								
									<div class="dz-content">
										<h5 class="title"><a href="rentalbook_details.php?id=<?php echo $row['rid']; ?>"><?php  echo $row['rbook'];?></a></h5>
										<ul class="dz-tags">
											<li><a href=""><?php  echo $row['category'];?></a></li>
										</ul>
										
										<div class="book-footer">
											<a href="rentalbook_details.php?id=<?php echo $row['rid']; ?>" class="btn btn-secondary box-btn btnhover2"> View Details</a>
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
