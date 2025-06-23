<?php
include("header.php");
include('config.php');
?>

<?php
// Define the allowed file types and maximum file size
$allowedImageTypes = ['image/jpeg', 'image/png'];
$allowedVideoTypes = ['video/mp4'];
$maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

// Check if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $position = $_POST['position'];
    $fileType = $_POST['file_type']; // Get the file type from the select box

    // Check if there was an error with the file upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Error uploading file.";
        exit;
    }

    // Validate file size
    if ($file['size'] > $maxFileSize) {
        echo "File size exceeds 2 MB.";
        exit;
    }

    // Validate file type based on selection
    if ($fileType === 'image' && !in_array($file['type'], $allowedImageTypes)) {
        echo "Only JPG and PNG files are allowed.";
        exit;
    } elseif ($fileType === 'video' && !in_array($file['type'], $allowedVideoTypes)) {
        echo "Only MP4 files are allowed.";
        exit;
    }

    // Sanitize file name to prevent directory traversal attacks
    $fileName = basename($file['name']);
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . $fileName;

    // Create the uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Move the file to the uploads directory
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        // Insert the file details into the database including the file type
        $insert = mysqli_query($con,"INSERT INTO landing (Position, Banner, Type) VALUES ('$position', '$fileName', '$fileType')");
    }
    
    if ($insert) {
        echo "<script>alert('File uploaded successfully.')</script>";
        echo "<script>window.location.assign('manage_landing.php')</script>";
    } else {
        echo "<script>alert('File not uploaded!')</script>";
        echo "<script>window.location.assign('manage_landing.php')</script>";
    }
}
?>

<!-- partial -->
<div class="main-panel">        
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Landing Page Manager</h4>
                        <form class="form-sample" method="post" enctype="multipart/form-data">
                            <p class="card-description"></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Banner Position</label>
                                        <div class="col-sm-9">
                                            <select name="position" class="form-control">
                                                <option value="">Select Position</option>
                                                <option value="bb">Big Banner</option>
                                                <option value="1b">1st Banner</option>
                                                <option value="2b">2nd Banner</option>
                                                <option value="3b">3rd Banner</option>
                                                <option value="mb">More Banner</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">File Type</label>
                                        <div class="col-sm-9">
                                            <select name="file_type" class="form-control" id="file_type">
                                                <option value="">Select File Type</option>
                                                <option value="image">Image</option>
                                                <option value="video">Video</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="file_input_container" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Upload File</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" name="file" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary btn-icon-text">
                                            <i class="ti-save btn-icon-prepend"></i>
                                            Save
                                        </button>
                                        <button type="reset" class="btn btn-warning btn-icon-text">
                                            <i class="ti-reload btn-icon-prepend"></i>           
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
<?php
include("footer.php");
?>

<script>
document.getElementById('file_type').addEventListener('change', function() {
    var fileInputContainer = document.getElementById('file_input_container');
    if (this.value) {
        fileInputContainer.style.display = 'block';
    } else {
        fileInputContainer.style.display = 'none';
    }
});
</script>
