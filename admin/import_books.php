<?php
include("config.php"); // Database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['import']) && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $mimeType = $_FILES['csv_file']['type'];
    $allowedMimes = ['text/csv', 'application/vnd.ms-excel', 'application/csv'];

    // Check file type
    if (!in_array($mimeType, $allowedMimes)) {
        echo "<script>
            alert('Invalid file type. Please upload a valid CSV.');
            window.location.href = 'book_view.php';
        </script>";
        exit;
    }

    if ($_FILES['csv_file']['size'] > 0) {
        $handle = fopen($file, "r");
        $rowCount = 0;

        fgetcsv($handle); // Skip header

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) < 11) {
                continue; // Skip incomplete rows
            }

            // Now: [book_id, category, subcategory, ...]
            list($book_id, $category_name, $subcategory_name, $book, $image, $price, $totquantity, $author, $description, $mrp, $time) = $data;

            // Sanitize input
            $category_name = mysqli_real_escape_string($con, trim($category_name));
            $subcategory_name = mysqli_real_escape_string($con, trim($subcategory_name));
            $book = mysqli_real_escape_string($con, $book);
            $author = mysqli_real_escape_string($con, $author);
            $description = mysqli_real_escape_string($con, $description);
            $image = mysqli_real_escape_string($con, $image);

            // CATEGORY: Get or insert
            $cat_res = mysqli_query($con, "SELECT category_id FROM category WHERE category = '$category_name' LIMIT 1");
            if ($cat_row = mysqli_fetch_assoc($cat_res)) {
                $cat_id = $cat_row['category_id'];
            } else {
                mysqli_query($con, "INSERT INTO category (category) VALUES ('$category_name')");
                $cat_id = mysqli_insert_id($con);
            }

            // SUBCATEGORY: Get or insert (needs category id)
            $subcat_res = mysqli_query($con, "SELECT subcategory_id FROM subcategory WHERE subcategory = '$subcategory_name' AND categoryid = '$cat_id' LIMIT 1");
            if ($subcat_row = mysqli_fetch_assoc($subcat_res)) {
                $subcat_id = $subcat_row['subcategory_id'];
            } else {
                mysqli_query($con, "INSERT INTO subcategory (categoryid, subcategory) VALUES ('$cat_id', '$subcategory_name')");
                $subcat_id = mysqli_insert_id($con);
            }

            // Check if the book already exists
            $check = mysqli_query($con, "SELECT book_id FROM books WHERE book_id = '$book_id'");

            if (mysqli_num_rows($check) > 0) {
                // Update existing book
                $query = "UPDATE books SET 
                    cat_id='$cat_id', 
                    subcat_id='$subcat_id', 
                    book='$book', 
                    image='$image', 
                    price='$price', 
                    totquantity='$totquantity', 
                    author='$author', 
                    description='$description', 
                    mrp='$mrp', 
                    time='$time' 
                    WHERE book_id='$book_id'";
            } else {
                // Insert new book
                $query = "INSERT INTO books 
                    (book_id, cat_id, subcat_id, book, image, price, totquantity, author, description, mrp, time) 
                    VALUES 
                    ('$book_id', '$cat_id', '$subcat_id', '$book', '$image', '$price', '$totquantity', '$author', '$description', '$mrp', '$time')";
            }

            mysqli_query($con, $query);
            $rowCount++;
        }

        fclose($handle);

        echo "<script>
            alert('Books imported successfully. ($rowCount rows updated/inserted)');
            window.location.href = 'book_view.php';
        </script>";

    } else {
        echo "<script>
            alert('The uploaded file is empty.');
            window.location.href = 'book_view.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Please select a valid CSV file to import.');
        window.location.href = 'book_view.php';
    </script>";
}
?>