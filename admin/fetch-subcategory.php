<?php
require_once "config.php";
$category_id = $_POST["category_id"];
$result = mysqli_query($con,"SELECT * FROM subcategory where categoryid = $category_id");
?>
<option value="">Select SubCategory</option>
<?php
while($row = mysqli_fetch_array($result)) {
?>
<option value="<?php echo $row["subcategory_id"];?>"><?php echo $row["subcategory"];?></option>
<?php
}
?>