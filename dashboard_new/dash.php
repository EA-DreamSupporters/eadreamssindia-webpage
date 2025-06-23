<?php
session_start();
if (!isset($_SESSION['id'])) {
  // Redirect to login page or show an error
  header("Location: ../login.php");
  exit();
}
include("dashheader.php");
include("../config.php"); // Ensure DB connection

$userid = $_SESSION['id'];
$user_query = mysqli_query($con, "SELECT name, photo, referral_code, is_affiliate, points FROM students WHERE sid='$userid'");
$user_row = mysqli_fetch_assoc($user_query);
$is_affiliate = $user_row['is_affiliate'] ?? 0;
$profile_name = $user_row['name'] ?? 'Profile';
$profile_photo = $user_row['photo'] ? '../images/' . $user_row['photo'] : '../images/profile.jpg';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/scss/components/dashnew.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<?php
$userid = $_SESSION['id'];
$sql = "SELECT * from orders WHERE userId='$userid'";

if ($result = mysqli_query($con, $sql)) {

  // Return the number of rows in result set
  $rowcount = mysqli_num_rows($result);

}

$sql1 = "SELECT * from subscriptions WHERE user_id='$userid'";

if ($result1 = mysqli_query($con, $sql1)) {

  // Return the number of rows in result set
  $rowcount1 = mysqli_num_rows($result1);

}
$sql2 = "SELECT * from rentals WHERE userid='$userid'";

if ($result2 = mysqli_query($con, $sql2)) {

  // Return the number of rows in result set
  $rowcount2 = mysqli_num_rows($result2);

}

date_default_timezone_set("Asia/Kolkata");
$date = date("Y-m-d");
$sql3 = "SELECT * from orders WHERE userId='$userid' AND orderDate='$date'";

if ($result3 = mysqli_query($con, $sql3)) {

  // Return the number of rows in result set
  $rowcount3 = mysqli_num_rows($result3);

}
?>

<style>
  /* --- Affiliate Dashboard Styles --- */
  .tab-btn {
    padding: 10px 28px;
    background: #f3f3f3;
    color: #292929;
    font-weight: 600;
    text-decoration: none;
    font-family: 'Outfit', sans-serif;
    font-size: 1.1rem;
    transition: background 0.2s, color 0.2s;
    border: none;
    outline: none;
    cursor: pointer;
  }

  .tab-btn.active,
  .tab-btn:hover {
    background: #BE0D8F;
    color: #fff;
  }

  body.dark-mode .tab-btn {
    background: #292929;
    color: #fff;
  }

  body.dark-mode .tab-btn.active,
  body.dark-mode .tab-btn:hover {
    background: #BE0D8F;
    color: #ffF;
  }

  /* Add more affiliate-specific styles here if needed */
</style>

<script>
  // --- Affiliate Dashboard Scripts (if any) ---
  // Example: Copy referral link to clipboard (already inline in button onclick)
  // Add more affiliate-specific JS here if needed
</script>

<?php
$tab = $_GET['tab'] ?? 'dashboard';

if ($tab === 'affiliate') {
  include 'dash_affiliate.php';
} else {
  ?>
  <div class="dashboard-main-container" id="dashboard-content">
    <div class="dashboard-welcome-box">
      <div style="display:flex;align-items:center;gap:24px;width:100%;">
        <div>
          <div style="font-size:2rem;font-weight:700;font-family:'Outfit',sans-serif;line-height:1.2;">
            Welcome, <?php echo htmlspecialchars($profile_name); ?> ! <span style="font-size:2rem;">👋🏻</span>
          </div>
          <div style="font-size:1.1rem;color:#5a6df5;font-weight:500;margin-top:4px;">
            Your learning journey is going great. Keep it up and unlock more rewards!</div>
        </div>

      </div>
      <div class="dashboard-stats-row">
        <div class="dashboard-stat-card" style="background: #ffb1ea;">
          <div style="font-size:1rem;color:#dc31af;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-shopping-cart" style="color: #ff00bb;"></i>
            <span style="font-family:'Outfit',sans-serif;color:#292929 ; font-weight: 700;">Orders</span>
          </div>
          <div style="font-size:2rem;font-weight:700;color:#292929 ;margin-top:6px;"><?php echo $rowcount ?? 0; ?></div>
        </div>
        <div class="dashboard-stat-card" style="background:#b4bcfc;">
          <div style="font-size:1rem;color:#5a6df5;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-book" style="color:rgb(0, 30, 255);"></i>
            <span style="font-family:'Outfit',sans-serif;color:#292929; font-weight: 700;">Courses</span>
          </div>
          <div style="font-size:2rem;font-weight:700;color:#292929;margin-top:6px;"><?php echo $rowcount1 ?? 0; ?></div>
        </div>
        <div class="dashboard-stat-card" style="background:#ffd294;">
          <div style="font-size:1rem;color:#ff9800;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-pen" style="color:#ff9500;"></i>
            <span style="font-family:'Outfit',sans-serif;color:#292929; font-weight: 700;">Tests Attended</span>
          </div>
          <div style="font-size:2rem;font-weight:700;color:#292929;margin-top:6px;"><?php echo $rowcount2 ?? 0; ?></div>
        </div>
        <div class="dashboard-stat-card" style="background:#8cf190; ">
          <div style="font-size:1rem;color:#4caf50;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-star" style="color:#4caf50;"></i>
            <span style="font-family:'Outfit',sans-serif;color:#292929; font-weight: 700;">Points</span>
          </div>
          <div style="font-size:2rem;font-weight:700;color:#292929;margin-top:6px;">
            <?php echo isset($user_row["points"]) ? $user_row["points"] : 0; ?>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- Close .dashboard-main-container -->
  <?php
}
?>

<!-- Top Centered Search Bar with Language, Theme, Notification, and Profile Avatar -->
<div class="dash-topbar" id="dash-topbar">
  <div class="dash-search-bar">
    <button class="search-btn" title="Search"><i class="fas fa-search"></i></button>
    <input type="text" class="search-input" placeholder="Search">

    <button class="icon-btn lang-btn" title="Language"><iconify-icon icon="bx:globe"></iconify-icon></button>
    <button class="icon-btn theme-btn" id="theme-toggle" title="Toggle dark/light mode"><iconify-icon id="theme-icon"
        icon="bx:moon"></iconify-icon></button>
    <button class="icon-btn notif-btn" title="Notifications"><iconify-icon icon="bx:bell"></iconify-icon></button>
    <div class="profile-avatar-wrapper" title="<?php echo htmlspecialchars($profile_name); ?>"
      id="profile-avatar-wrapper">
      <img src="<?php echo $profile_photo; ?>" alt="Profile" class="profile-avatar">
      <span class="profile-badge"></span>
      <span class="profile-name" style="margin-left:8px;font-weight:500;vertical-align:middle;"></span>

      <!-- Profile Dropdown -->
      <div id="profile-dropdown">
        <div class="profile-dropdown-header">
          <div class="profile-img-wrapper">
            <img src="<?php echo $profile_photo; ?>" alt="Profile">
            <span class="profile-status"></span>
          </div>
          <div>
            <div class="profile-name"><?php echo htmlspecialchars($profile_name); ?></div>
            <div class="profile-role">Admin</div>
          </div>
        </div>
        <a href="../profile.php">
          <iconify-icon icon="bx:user" style="font-size:18px;"></iconify-icon>My Profile
        </a>
        <a href="#">
          <iconify-icon icon="bx:cog" style="font-size:18px;"></iconify-icon>Settings
        </a>
        <a href="#">
          <iconify-icon icon="bx:credit-card" style="font-size:18px;"></iconify-icon>Billing Plan
        </a>
        <hr>
        <a href="#">
          <iconify-icon icon="bx:dollar" style="font-size:18px;"></iconify-icon>Pricing Plan
        </a>
        <a href="../faq.php">
          <iconify-icon icon="bx:help-circle" style="font-size:18px;"></iconify-icon>FAQ
        </a>
        <hr>
        <a href="../logout.php">
          <iconify-icon icon="bx:power-off" style="font-size:18px;"></iconify-icon>Sign Out
        </a>
      </div>
    </div>
  </div>
</div> <!-- Close .dash-topbar -->

<script>
  // Theme toggle logic for topbar
  const themeToggle = document.getElementById('theme-toggle');
  const themeIcon = document.getElementById('theme-icon');
  if (themeToggle && themeIcon) {
    themeToggle.addEventListener('click', function () {
      document.body.classList.toggle('dark-mode');
      if (document.body.classList.contains('dark-mode')) {
        themeIcon.setAttribute('icon', 'bx:sun');
      } else {
        themeIcon.setAttribute('icon', 'bx:moon');
      }
    });
  }

  // Profile dropdown logic
  const avatarWrapper = document.getElementById('profile-avatar-wrapper');
  const dropdown = document.getElementById('profile-dropdown');
  if (avatarWrapper && dropdown) {
    avatarWrapper.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', function (e) {
      if (!avatarWrapper.contains(e.target)) {
        dropdown.style.display = 'none';
      }
    });
  }

  // Example: Set wallet points (replace with real value from backend)
  document.getElementById('user-wallet-points').innerText = '<?php echo isset($user_row["points"]) ? $user_row["points"] : 0; ?>';
  // Instant transfer button (stub)
  document.getElementById('wallet-transfer-btn').onclick = function () {
    alert('Instant transfer feature coming soon!');
  };
  // Badge share buttons (stub)
  document.querySelectorAll('.badge-share-btn').forEach(function (btn) {
    btn.onclick = function () {
      alert('Shareable badge feature coming soon!');
    };
  });

  // If you have an affiliate tab/button in dashheader.php, add this JS to load affiliate dashboard via AJAX
  const affiliateTab = document.getElementById('affiliate-tab-btn');
  if (affiliateTab) {
    affiliateTab.addEventListener('click', function (e) {
      e.preventDefault();
      fetch('dashboard_new/dash_affiliate.php')
        .then(res => res.text())
        .then(html => {
          document.getElementById('dashboard-content').innerHTML = html;
        });
    });
  }
</script>






<!-- Self Study India Section (separated from Dashboard container) -->
<!-- <div class="dashboard-selfstudy-section">
  <div
    style="font-size:3rem;font-weight:600;margin-bottom:12px;color:hsl(0, 0.00%, 7.80%);display:flex;align-items:center;gap:10px; font-family:'Outfit',sans-serif;">
    Self Study India
  </div>
  <div style="display:flex;flex-wrap:wrap;gap:32px;align-items:center;">
    <div style="flex:1 1 220px;min-width:200px;display:flex;flex-direction:column;gap:6px;">
      <span style="font-size:1rem;color:#888;">Time Spent Watching</span>
      <span style="font-size:1.3rem;font-weight:700;color:#384551;">--:--</span>
    </div>
    <div style="flex:1 1 320px;min-width:220px;display:flex;flex-direction:column;gap:6px;">
      <span style="font-size:1rem;color:#888;">Last Video Watched</span>
      <span style="font-size:1.1rem;font-weight:600;color:#384551;">No video watched yet</span>
    </div>
    <div style="flex:1 1 220px;min-width:200px;display:flex;flex-direction:column;gap:6px;">
      <span style="font-size:1rem;color:#888;">Continue Watching?</span>
      <button
        style="margin-top:2px;padding:7px 18px;background:#5a6df5;color:#fff;border:none;border-radius:6px;font-weight:600;font-size:1rem;cursor:pointer;opacity:0.85;transition:opacity 0.2s;">Continue</button>
    </div>
  </div>
  <div style="margin-top:18px;display:flex;flex-wrap:wrap;gap:32px;align-items:flex-start;">
    <div style="flex:2 1 340px;min-width:260px;">
      <span style="font-size:1rem;color:#888;">Latest Free YouTube Video</span>
      <div style="margin-top:8px;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px #0001;max-width:400px;"> -->

<!-- Example YouTube embed, replace src with dynamic video later -->

<!-- <iframe width="100%" height="215" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="YouTube video player"
          frameborder="0" allowfullscreen></iframe>
      </div>
    </div>
    <div style="flex:1 1 220px;min-width:200px;">
      <span style="font-size:1rem;color:#888;">Your Self Notes</span>
      <div
        style="margin-top:8px;padding:12px 14px;background:#fff;border-radius:8px;min-height:60px;box-shadow:0 1px 4px #0001;color:#384551;font-size:1rem;">
        <em>Coming soon: Save your own notes for each video!</em>
      </div>
    </div>
    <div style="flex:1 1 220px;min-width:200px;">
      <span style="font-size:1rem;color:#888;">AI Video Summary</span>
      <div
        style="margin-top:8px;padding:12px 14px;background:#fff;border-radius:8px;min-height:60px;box-shadow:0 1px 4px #0001;color:#384551;font-size:1rem;">
        <em>Coming soon: Smart video notes and summaries!</em>
      </div>
    </div>
  </div>
</div> -->
<!-- End Self Study India Section -->

<?php
// --- AFFILIATE REFERRAL LOGIC FOR EXISTING USERS ---
// If a user visits with ?ref=...&product=... in the URL, store in session for later purchase
if (isset($_GET['ref']) && isset($_GET['product'])) {
  $_SESSION['affiliate_ref'] = $_GET['ref'];
  $_SESSION['affiliate_product'] = $_GET['product'];
}
// On successful purchase (in your buy or order logic), check if these session variables exist
// If yes, record the referral commission for the referrer, then unset the session variables
// Example usage (to be placed in your purchase logic):
// if (isset($_SESSION['affiliate_ref']) && isset($_SESSION['affiliate_product'])) {
//     // Process commission for $_SESSION['affiliate_ref'] and $_SESSION['affiliate_product']
//     unset($_SESSION['affiliate_ref']);
//     unset($_SESSION['affiliate_product']);
// }
// --- END AFFILIATE REFERRAL LOGIC ---