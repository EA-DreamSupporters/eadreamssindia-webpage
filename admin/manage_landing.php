<?php
include("header.php");
$result = mysqli_query($con, "SELECT button_status, schedule_date FROM settings LIMIT 1");
$row = mysqli_fetch_assoc($result);
$button_status = $row['button_status'];
$schedule_date = $row['schedule_date'];
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script>
  function show_confirm() {
    return confirm("Are you sure you want to remove this?");
  }
</script>
<style>
  .table td img,
  .table td video {
    max-width: 150px;
    /* Adjust the width as needed */
    max-height: 150px;
    /* Adjust the height as needed */
    border-radius: 5px;
    object-fit: cover;
    /* Ensures that the image or video maintains its aspect ratio */
  }

  h4.card-title {
    margin-bottom: 0 !important;
  }

  #banner {
    width: 400px;
    height: 200px;
  }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Landing Page Manager</h4>
        
        <div class="card mt-4 mb-4 shadow-sm border-0" style="border-radius: 10px;">
          <div class="card-header text-white"
            style="background-color: rgb(26, 22, 104); border-top-left-radius: 10px; border-top-right-radius: 10px;">
            <h4 class="card-title mb-0 py-2 px-3" style="color:white; border-bottom: none;"> New Page Activator</h4>
          </div>
          <div class="card-body"
            style="background-color: #f9f9fc; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
            <form action="save_button_state.php" method="post">

              <div class="form-group mb-4">
                <label class="form-label fw-semibold d-block mb-3">Choose Status:</label>
                <div class="form-check ps-3 mb-2">
                  <input class="form-check-input me-2" type="radio" name="status" id="statusDisabled" value="disabled"
                    <?php if ($button_status == 'disabled')
                      echo 'checked'; ?>>
                  <label class="form-check-label" for="statusDisabled">❌ Disabled</label>
                </div>
                <div class="form-check ps-3 mb-2">
                  <input class="form-check-input me-2" type="radio" name="status" id="statusActive" value="active_now"
                    <?php if ($button_status == 'active_now')
                      echo 'checked'; ?>>
                  <label class="form-check-label" for="statusActive">✅ Activate Now</label>
                </div>
                <div class="form-check ps-3 mb-2">
                  <input class="form-check-input me-2" type="radio" name="status" id="statusScheduled" value="scheduled"
                    <?php if ($button_status == 'scheduled')
                      echo 'checked'; ?>>
                  <label class="form-check-label" for="statusScheduled">🕒 Schedule</label>
                </div>
              </div>

              <div class="form-group mb-4">
                <label for="scheduleDate" class="form-label mb-2">📅 Schedule Date:</label>
                <input type="date" name="schedule_date" id="scheduleDate" class="form-control w-50 ps-3"
                  value="<?php echo htmlspecialchars($schedule_date); ?>">
              </div>

              <button type="submit" class="btn btn-outline-primary px-4 py-2">💾 Save</button>
            </form>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table id="employee_data" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Position</th>
                    <th>Landing Page Image</th>
                    <th>Delete</th>
                  </tr>
                </thead>
                <?php
                $x = 1;
                $query = "SELECT * FROM landing";
                $result = mysqli_query($con, $query);
                while ($row = mysqli_fetch_array($result)) {
                  ?>
                  <tr>
                    <td><?php echo $x; ?></td>
                    <td>
                      <?php if ($row["Position"] == 'bb') {
                        echo "Big Banner";
                      } elseif ($row['Position'] == '1b') {
                        echo "1st Banner";
                      } elseif ($row['Position'] == '2b') {
                        echo "2nd Banner";
                      } elseif ($row['Position'] == '3b') {
                        echo "3rd Banner";
                      } else {
                        echo "More Banner";
                      } ?>
                    </td>

                    <td>
                      <?php if ($row['Type'] === 'image') { ?>
                        <img src="uploads/<?php echo $row['Banner']; ?>" id="banner">
                      <?php } elseif ($row['Type'] === 'video') { ?>
                        <video controls>
                          <source src="uploads/<?php echo $row['Banner']; ?>" type="video/mp4">
                          Your browser does not support the video tag.
                        </video>
                      <?php } ?>
                    </td>

                    <td><a href="delete_landing.php?id=<?php echo $row['LP_ID']; ?>" class=""
                        onclick='return show_confirm();'><img src='images/delete.png' id='delete'></a></td>
                  </tr>
                  <?php
                  $x++;
                }
                ?>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->
  <script>
    $(document).ready(function () {
      $('#employee_data').DataTable();
    });  
  </script>
  <?php
  include("footer.php");
  ?>