<?php
include("../config.php");
$userid = $_SESSION['id'];
// Handle affiliate application BEFORE any output
if (isset($_POST['apply_affiliate'])) {
    mysqli_query($con, "UPDATE students SET is_affiliate=1 WHERE sid='$userid'");
    // After applying, redirect to the same page to update dashboard state and avoid output issues
    header("Location: dash.php?tab=affiliate");
    exit;
}
// Fetch user info
$user_query = mysqli_query($con, "SELECT name, referral_code, is_affiliate FROM students WHERE sid='$userid'");
$user_row = mysqli_fetch_assoc($user_query);
$is_affiliate = $user_row['is_affiliate'] ?? 0;
// Generate referral code if missing
if ($is_affiliate && empty($user_row['referral_code'])) {
    $new_code = 'EA' . $userid . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 3);
    mysqli_query($con, "UPDATE students SET referral_code='$new_code' WHERE sid='$userid'");
    $user_row = mysqli_fetch_assoc(mysqli_query($con, "SELECT referral_code FROM students WHERE sid='$userid'"));
}
$referral_code = $user_row['referral_code'];
$referral_link = "https://eadreamsupporters.com/referral.php?ref=$referral_code";
$referral_count = 0;
$total_earnings = 0;
$pending_payouts = 0;
$q1 = mysqli_query($con, "SELECT COUNT(*) as cnt FROM affiliate_referrals WHERE referrer_user_id='$userid'");
if ($row1 = mysqli_fetch_assoc($q1))
    $referral_count = $row1['cnt'];
$q2 = mysqli_query($con, "SELECT SUM(commission_amount) as total FROM affiliate_commissions WHERE referrer_user_id='$userid' AND status IN ('approved','paid')");
if ($row2 = mysqli_fetch_assoc($q2))
    $total_earnings = $row2['total'] ?? 0;
$q3 = mysqli_query($con, "SELECT SUM(commission_amount) as pending FROM affiliate_commissions WHERE referrer_user_id='$userid' AND status='pending'");
if ($row3 = mysqli_fetch_assoc($q3))
    $pending_payouts = $row3['pending'] ?? 0;
$books = mysqli_query($con, "SELECT * FROM books WHERE is_affiliate_enabled=1");
$courses = mysqli_query($con, "SELECT * FROM course WHERE is_affiliate_enabled=1");
$tests = mysqli_query($con, "SELECT * FROM test WHERE is_affiliate_enabled=1");
?>
<style>
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
</style>
<div class="dashboard-main-container">
    <div class="dashboard-welcome-box">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:2rem;font-weight:700;font-family:'Outfit',sans-serif;line-height:1.2;">
                Affiliate Dashboard <span style="font-size:2rem;">💸</span>
            </div>
        </div>
        <div style="margin-top:18px;font-size:1.1rem;display:flex;align-items:flex-start;gap:48px;">
            <div style="flex:1;min-width:260px;">
                <strong>Your Referral Link:</strong><br>
                <input type="text" value="<?php echo $referral_link; ?>" readonly
                    style="width:100%;padding:8px 12px;border-radius:6px;border:1px solid #ccc;font-size:1rem;">
                <button onclick="navigator.clipboard.writeText('<?php echo $referral_link; ?>')"
                    style="margin-top:8px;padding:7px 18px;background:#BE0D8F;color:#fff;border:none;border-radius:6px;font-weight:600;font-size:1rem;cursor:pointer;">Copy
                    Link</button>
            </div>
            <div style="flex:1;min-width:220px;margin-left:32px;">
                <strong>Affiliate Stats:</strong>
                <ul style="margin:10px 0 0 18px;font-size:1rem;">
                    <li>Total Referrals: <b>
                            <?php echo $referral_count; ?>
                        </b></li>
                    <li>Total Earnings: <b>₹<?php echo number_format($total_earnings, 2); ?></b></li>
                    <li>Pending Payouts: <b>₹<?php echo number_format($pending_payouts, 2); ?></b></li>
                </ul>
            </div>
        </div>
        <div style="margin-top:32px;">
            <strong>Affiliate Products You Can Promote:</strong>
            <div style="margin-top:12px;">
                <div
                    style="width:950px;background:#fff;padding:24px 18px 18px 18px;border-radius:12px;box-shadow:0 2px 12px #0001;width:855px;margin:auto;">
                    <div
                        style="font-size:1.1rem;font-weight:600;margin-bottom:18px;font-family:'Outfit',sans-serif;color:#222;">
                        All Eligible Products</div>
                    <?php
                    $hasProducts = false;
                    while ($b = mysqli_fetch_assoc($books)) {
                        $hasProducts = true;
                        $commission_percent = $b['affiliate_commission_percent'] ?? 10;
                        $commission_amount = round($b['price'] * ($commission_percent / 100), 2);
                        ?>
                        <div
                            style="display:flex;align-items:center;gap:18px;background:#fafafa;border:1px solid #eee;padding:18px 16px 18px 16px;border-radius:12px;box-shadow:0 1px 4px #0001;margin-bottom:18px;position:relative;">
                            <span
                                style="display:inline-block;background:#5a6df5;color:#fff;font-size:0.85rem;font-weight:600;padding:2px 10px;border-radius:12px 0 0 0;margin-bottom:6px;position:absolute;left:0;top:0;margin-top:0px;margin-left:0px;transform:none;">Book</span>
                            <div style="flex-shrink:0;">
                                <img src="../images/<?php echo $b['image']; ?>" alt="
                    <?php echo htmlspecialchars($b['book']); ?>"
                                    style="width:70px;height:95px;object-fit:cover;border-radius:4px;display:block;margin-top:6px;">
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;color:#222;">📚
                                    <?php echo htmlspecialchars($b['book']); ?>
                                </div>
                                <div style="font-size:0.95rem;color:#666;">By <?php echo htmlspecialchars($b['author']); ?>
                                </div>
                                <div style="margin-top:2px;font-size:1rem;color:#222;">₹<?php echo $b['price']; ?></div>
                                <div style=" margin-top:2px;font-size:0.98rem;color:#2e7d32;">Affiliate Commission:
                                    <b>
                                        <?php echo $commission_percent; ?>%
                                    </b> (You get
                                    <b>₹
                                        <?php echo $commission_amount; ?>
                                    </b> per sale)
                                </div>
                                <div style="margin-top:8px;display:flex;gap:6px;align-items:center;">
                                    <input type="text"
                                        value="<?php echo $referral_link . '&product=book_' . $b['book_id']; ?>" readonly
                                        style="width:100%;font-size:0.95rem;padding:6px 8px;border-radius:4px;border:1px solid #ccc;">
                                    <button
                                        onclick="navigator.clipboard.writeText('<?php echo $referral_link . '&product=book_' . $b['book_id']; ?>')"
                                        style="padding:6px 14px;font-size:0.95rem;background:#5a6df5;color:#fff;border:none;border-radius:4px;">Copy</button>
                                </div>
                            </div>
                        </div>
                    <?php }
                    while ($c = mysqli_fetch_assoc($courses)) {
                        $hasProducts = true;
                        $commission_percent = $c['affiliate_commission_percent'] ?? 10;
                        $commission_amount = round($c['csprice'] * ($commission_percent / 100), 2);
                        ?>
                        <div
                            style="display:flex;align-items:center;gap:18px;background:#fafafa;border:1px solid #eee;padding:18px 16px 18px 16px;border-radius:12px;box-shadow:0 1px 4px #0001;margin-bottom:18px;position:relative;">
                            <span
                                style="display:inline-block;background:#BE0D8F;color:#fff;font-size:0.85rem;font-weight:600;padding:2px 10px;border-radius:12px 0 0 0;margin-bottom:6px;position:absolute;left:0;top:0;margin-top:0px;margin-left:0px;transform:none;">Course</span>
                            <div style="flex-shrink:0;">
                                <img src="../images/<?php echo $c['image']; ?>" alt="
            <?php echo htmlspecialchars($c['ctitle']); ?>"
                                    style="width:100px;height:70px;object-fit:contain;background:#f3f3f3;border-radius:4px;display:block;margin-top:6px;aspect-ratio:10/7;">
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;color:#222;">🎓
                                    <?php echo htmlspecialchars($c['ctitle']); ?>
                                </div>
                                <div style="font-size:0.95rem;color:#666;">By <?php echo htmlspecialchars($c['ins']); ?>
                                </div>
                                <div style="margin-top:2px;font-size:1rem;color:#222;">₹<?php echo $c['csprice']; ?></div>
                                <div style=" margin-top:2px;font-size:0.98rem;color:#2e7d32;">Affiliate Commission:
                                    <b>
                                        <?php echo $commission_percent; ?>%
                                    </b> (You get
                                    <b>₹
                                        <?php echo $commission_amount; ?>
                                    </b> per sale)
                                </div>
                                <div style="margin-top:8px;display:flex;gap:6px;align-items:center;">
                                    <input type="text" value="<?php echo $referral_link . '&product=course_' . $c['id']; ?>"
                                        readonly
                                        style="width:100%;font-size:0.95rem;padding:6px 8px;border-radius:4px;border:1px solid #ccc;">
                                    <button
                                        onclick="navigator.clipboard.writeText('<?php echo $referral_link . '&product=course_' . $c['id']; ?>')"
                                        style="padding:6px 14px;font-size:0.95rem;background:#BE0D8F;color:#fff;border:none;border-radius:4px;">Copy</button>
                                </div>
                            </div>
                        </div>
                    <?php }
                    while ($t = mysqli_fetch_assoc($tests)) {
                        $hasProducts = true;
                        $commission_percent = $t['affiliate_commission_percent'] ?? 10;
                        $commission_amount = round($t['sprice'] * ($commission_percent / 100), 2);
                        ?>
                        <div
                            style="display:flex;align-items:center;gap:18px;background:#fafafa;border:1px solid #eee;padding:18px 16px 18px 16px;border-radius:12px;box-shadow:0 1px 4px #0001;margin-bottom:18px;position:relative;">
                            <span
                                style="display:inline-block;background:#D9E545;color:#292929;font-size:0.85rem;font-weight:600;padding:2px 10px;border-radius:12px 0 0 0;margin-bottom:6px;position:absolute;left:0;top:0;margin-top:0px;margin-left:0px;transform:none;">Test
                                Pack</span>
                            <div style="flex-shrink:0;">
                                <img src="../images/<?php echo $t['image']; ?>" alt="
    <?php echo htmlspecialchars($t['title']); ?>"
                                    style="width:70px;height:70px;object-fit:cover;border-radius:4px;display:block;margin-top:6px;">
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;color:#222;">📝
                                    <?php echo htmlspecialchars($t['title']); ?>
                                </div>
                                <div style="margin-top:2px;font-size:1rem;color:#222;">₹<?php echo $t['sprice']; ?></div>
                                <div style=" margin-top:2px;font-size:0.98rem;color:#2e7d32;">Affiliate Commission:
                                    <b>
                                        <?php echo $commission_percent; ?>%
                                    </b> (You get
                                    <b>₹
                                        <?php echo $commission_amount; ?>
                                    </b> per sale)
                                </div>
                                <div style="margin-top:8px;display:flex;gap:6px;align-items:center;">
                                    <input type="text" value="<?php echo $referral_link . '&product=test_' . $t['id']; ?>"
                                        readonly
                                        style="width:100%;font-size:0.95rem;padding:6px 8px;border-radius:4px;border:1px solid #ccc;">
                                    <button
                                        onclick="navigator.clipboard.writeText('<?php echo $referral_link . '&product=test_' . $t['id']; ?>')"
                                        style="padding:6px 14px;font-size:0.95rem;background:#D9E545;color:#292929;border:none;border-radius:4px;">Copy</button>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if (!$hasProducts) { ?>
                        <div style="text-align:center;padding:32px 0;color:#888;font-size:1.1rem;">No affiliate-enabled
                            products available at the moment.</div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div style="margin-top:40px;">
            <strong>Your Referral & Commission History:</strong>
            <div style="margin-top:12px;">
                <table
                    style="width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 1px 8px #0001;font-size:0.98rem;">
                    <thead style="background:#dfdfdf;">
                        <tr>
                            <th style="padding:10px 8px;border-bottom:1px solid #eee;">Date</th>
                            <th style="padding:10px 8px;border-bottom:1px solid #eee;">Product</th>
                            <th style="padding:10px 8px;border-bottom:1px solid #eee;">Buyer</th>
                            <th style="padding:10px 8px;border-bottom:1px solid #eee;">Commission</th>
                            <th style="padding:10px 8px;border-bottom:1px solid #eee;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $history = mysqli_query($con, "SELECT ac.*, ar.product_type, ar.product_id, ar.buyer_user_id, ar.created_at as referral_date, s.name as buyer_name FROM affiliate_commissions ac LEFT JOIN affiliate_referrals ar ON ac.referral_id = ar.id LEFT JOIN students s ON ar.buyer_user_id = s.sid WHERE ac.referrer_user_id='$userid' ORDER BY ac.created_at DESC LIMIT 50");
                        if (mysqli_num_rows($history) > 0) {
                            while ($h = mysqli_fetch_assoc($history)) {
                                $date = date('d M Y', strtotime($h['referral_date'] ?? $h['created_at']));
                                $product = '-';
                                if (!empty($h['product_type']) && !empty($h['product_id'])) {
                                    $ptype = $h['product_type'];
                                    $pid = $h['product_id'];
                                    if ($ptype === 'book') {
                                        $p = mysqli_query($con, "SELECT book FROM books WHERE book_id='$pid'");
                                        $prow = mysqli_fetch_assoc($p);
                                        $product = $prow ? 'Book: ' . htmlspecialchars($prow['book']) : 'Book';
                                    } elseif ($ptype === 'course') {
                                        $p = mysqli_query($con, "SELECT ctitle FROM course WHERE id='$pid'");
                                        $prow = mysqli_fetch_assoc($p);
                                        $product = $prow ? 'Course: ' . htmlspecialchars($prow['ctitle']) : 'Course';
                                    } elseif ($ptype === 'test') {
                                        $p = mysqli_query($con, "SELECT title FROM test WHERE id='$pid'");
                                        $prow = mysqli_fetch_assoc($p);
                                        $product = $prow ? 'Test: ' . htmlspecialchars($prow['title']) : 'Test';
                                    }
                                }
                                $buyer = $h['buyer_name'] ? substr($h['buyer_name'], 0, 2) . str_repeat('*', max(0, strlen($h['buyer_name']) - 2)) : 'User#' . $h['buyer_user_id'];
                                $commission = '₹' . number_format($h['commission_amount'], 2);
                                $status = ucfirst($h['status']);
                                echo "<tr>\n";
                                echo "<td style='padding:8px 6px;border-bottom:1px solid #f6f6f6;'>$date</td>\n";
                                echo "<td style='padding:8px 6px;border-bottom:1px solid #f6f6f6;'>$product</td>\n";
                                echo "<td style='padding:8px 6px;border-bottom:1px solid #f6f6f6;'>$buyer</td>\n";
                                echo "<td style='padding:8px 6px;border-bottom:1px solid #f6f6f6;'>$commission</td>\n";
                                echo "<td style='padding:8px 6px;border-bottom:1px solid #f6f6f6;'>$status</td>\n";
                                echo "</tr>\n";
                            }
                        } else {
                            echo "<tr><td colspan='5' style='padding:18px;text-align:center;color:#888;'>No referral or commission history yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin-top:24px;">
            <em>More affiliate features coming soon!</em>
        </div>
    </div>
</div>