<?php
require_once "config.php";
$subjectid = $_POST["subjectid"];
$result = mysqli_query($con,"SELECT * FROM topic where subject_id = $subjectid");
?>
<option value="">Select Topic</option>
<?php
while($row = mysqli_fetch_array($result)) {
?>
<option value="<?php echo $row["topicid"];?>"><?php echo $row["topic"];?></option>
<?php
}
?>