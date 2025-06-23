<?php
include("header.php");
?>
<style>
  select.form-control {
    color: black;
  }

  textarea.form-control {
    height: 4rem;
  }
</style>


<?php
error_reporting(E_ERROR | E_PARSE);

if (isset($_POST["submit"])) {
  $errors = array();
  $file_name = $_FILES['image']['name'];
  $file_size = $_FILES['image']['size'];
  $file_tmp = $_FILES['image']['tmp_name'];
  $file_type = $_FILES['image']['type'];
  $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

  $expensions = array("jpeg", "jpg", "png");

  if (in_array($file_ext, $expensions) === false) {
    $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
  }

  if ($file_size > 2097152) {
    $errors[] = 'File size must be excately 2 MB';
  }

  if (empty($errors) == true) {
    move_uploaded_file($file_tmp, "../images/books/" . $file_name);
    echo "Success";
  } else {
    print_r($errors);
  }
  // Escape all user inputs
  $category = mysqli_real_escape_string($con, $_POST["category"]);
  $subcategory = mysqli_real_escape_string($con, $_POST["subcategory"]);
  $book = mysqli_real_escape_string($con, $_POST["book"]);
  $price = mysqli_real_escape_string($con, $_POST["price"]);
  $mrp = mysqli_real_escape_string($con, $_POST["mrp"]);
  $quantity = mysqli_real_escape_string($con, $_POST["quantity"]);
  $author = mysqli_real_escape_string($con, $_POST["author"]);
  $desc = mysqli_real_escape_string($con, $_POST["desc"]);
  $is_affiliate_enabled = isset($_POST['is_affiliate_enabled']) ? 1 : 0;
  $affiliate_commission_percent = isset($_POST['affiliate_commission_percent']) ? floatval($_POST['affiliate_commission_percent']) : 10;


  $sql = mysqli_query($con, "INSERT INTO books(image, cat_id, subcat_id, book, price, totquantity, author, description, mrp, is_affiliate_enabled, affiliate_commission_percent) 
VALUES('$file_name','$category','$subcategory','$book','$price','$quantity','$author','$desc','$mrp', '$is_affiliate_enabled', '$affiliate_commission_percent')");


  echo "<script> alert('Added Successfully')</script>";
  echo "<script>  window.location.assign('book_view.php')</script>";


}
?>


<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Add Book</h4>
            <form class="form-sample" method="post" enctype="multipart/form-data">
              <p class="card-description">

              </p>


              <div class="row">

                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Category</label>
                    <div class="col-sm-9">
                      <select name="category" class="form-control" id="category-dropdown">
                        <option value="">Select Category</option>
                        <?php
                        $result1 = mysqli_query($con, "SELECT * FROM category");
                        while ($row1 = mysqli_fetch_array($result1)) {
                          ?>
                          <option value="<?php echo $row1['category_id']; ?>"><?php echo $row1['category']; ?></option>
                          <?php
                        }
                        ?>

                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">SubCategory</label>
                    <div class="col-sm-9">
                      <select name="subcategory" class="form-control" id="sub-category-dropdown">


                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Book Name</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="book" required />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Upload Image</label>
                    <div class="col-sm-9">
                      <input type="file" class="form-control" name="image" />
                      <br>
                      Note:* Image Size Must be 443 x 621 Pixels *
                    </div>
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Price (₹)</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="price" required />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">MRP</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="mrp" required />
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Quantity</label>
                    <div class="col-sm-9">
                      <input type="number" class="form-control" name="quantity" required />
                    </div>
                  </div>
                </div>

              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Author</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="author" />

                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                      <textarea rows="10" cols="50" class="form-control" name="desc"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row ">
                <label class="col-sm-3 col-form-label">Allow Affiliate Selling</label>
                <div class="col d-flex ">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="affiliateCheck" name="is_affiliate_enabled"
                      value="1">
                    <label class="form-check-label" for="affiliateCheck">Enable</label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Affiliate Commission (%)</label>
                <div class="col-sm-9">
                  <input type="number" class="form-control" name="affiliate_commission_percent" min="0" max="100"
                    step="0.01" value="10" required />
                  <small class="form-text text-muted">Set the base affiliate commission percentage for this
                    book.</small>
                </div>
              </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-icon-text" name="submit">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function () {
    $('#category-dropdown').on('change', function () {
      var category_id = this.value;
      var category_id = this.value;
      $.ajax({
        url: "fetch-subcategory.php",
        type: "POST",
        data: {
          category_id: category_id
        },
        cache: false,
        success: function (result) {
          $("#sub-category-dropdown").html(result);
        }
      });
    });
  });
</script>
<script src="assets\tinymce\js\tinymce\tinymce.min.js"></script>
<script>
  tinymce.init({
    selector: 'textarea[name="desc"]', // Target the textarea with the name "desc"
    height: 300, // Set the editor height to 300 pixels
    menubar: false, // Disable the menu bar for a cleaner interface
    plugins: 'lists link', // Enable the "lists" plugin for bullet/numbered lists and "link" plugin for hyperlinking
    toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link', // Configure the toolbar with undo/redo, formatting, text alignment, lists, and link options
    content_css: 'default' // Use the default content CSS for styling the editor content
  });
</script>

<?php
include("footer.php");
?>