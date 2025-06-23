<?php
include("config.php"); // Ensure this includes your database connection

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=books_export.csv');
$output = fopen("php://output", "w");

// Column headers (now with category/subcategory names)
fputcsv($output, ['Book ID', 'Category', 'Subcategory', 'Book Name', 'Image', 'Price', 'Quantity', 'Author', 'Description', 'MRP', 'Time']);

// Fetch data with category/subcategory names
$query = mysqli_query($con, "SELECT b.book_id, c.category, s.subcategory, b.book, b.image, b.price, b.totquantity, b.author, b.description, b.mrp, b.time FROM books b LEFT JOIN category c ON b.cat_id = c.category_id LEFT JOIN subcategory s ON b.subcat_id = s.subcategory_id");
while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['book_id'],
        $row['category'],
        $row['subcategory'],
        $row['book'],
        $row['image'],
        $row['price'],
        $row['totquantity'],
        $row['author'],
        $row['description'],
        $row['mrp'],
        $row['time']
    ]);
}

fclose($output);
exit;
exit;
