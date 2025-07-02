<!-- notification -->

CREATE TABLE notifications (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT, -- or NULL for global notifications
message VARCHAR(255),
is_read TINYINT(1) DEFAULT 0,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

<!-- Create a loader, e.g. /load/loadnotifications.php: -->

<?php
session_start();
include('../config/db.php');
$userid = $_SESSION['userid'];
$query = "SELECT * FROM notifications WHERE (user_id = $userid OR user_id IS NULL) AND is_read = 0 ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode(['data' => $data]);
?>

<!-- 3. Frontend: Poll for Notifications
Add a notification icon in your header (e.g., a bell), and use AJAX to poll for new notifications: -->

<!-- In your header.php, add: -->
<span id="notifBell" style="cursor:pointer;position:relative;">
    <i class="fa fa-bell"></i>
    <span id="notifCount" class="badge bg-danger" style="position:absolute;top:-8px;right:-8px;display:none;">0</span>
</span>
<div id="notifDropdown" class="dropdown-menu" style="max-height:200px;overflow:auto;"></div>

// Add this in your
<script> section in header.php
    function loadNotifications() {
        $.ajax({
            url: '../load/loadnotifications.php',
            method: 'GET',
            dataType: 'json',
            success: function (res) {
                let count = res.data.length;
                if (count > 0) {
                    $('#notifCount').text(count).show();
                } else {
                    $('#notifCount').hide();
                }
                let html = '';
                res.data.forEach(function (n) {
                    html += `<a class="dropdown-item small" href="#">${n.message}</a>`;
                });
                $('#notifDropdown').html(html);
            }
        });
    }

    $(document).ready(function () {
        loadNotifications();
        setInterval(loadNotifications, 10000); // every 10 seconds

        $('#notifBell').on('click', function () {
            $('#notifDropdown').toggle();
        });

        // Optional: Hide dropdown when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#notifBell').length) {
                $('#notifDropdown').hide();
            }
        });
    });

    //     4. Mark Notifications as Read (Optional)
    // Add an endpoint to mark notifications as read when the dropdown is opened or a notification is clicked.

    //         Summary
    // Store notifications in a table.
    // Poll for unread notifications via AJAX.
    // Show a badge and dropdown in your header.
    //     Mark as read when viewed / clicked.
    // This is simple, scalable, and fits your current structure!
    // Let me know if you want the code for marking as read or more advanced features(like push notifications).