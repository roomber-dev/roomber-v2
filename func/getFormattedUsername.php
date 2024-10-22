<?php
function getFormattedUsername($mysqli) {
if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg FROM users WHERE id='" . $_SESSION['id'] . "' LIMIT 1")) {
    while ($row = $result->fetch_assoc()) {
        $filtered = str_replace("<", "&lt;", $row['username']);
        $filtered = str_replace(">", "&gt;", $filtered);
        echo '<b class="user" style="background:' . $row['namecolor_bg'] . '; color:' . $row['namecolor_fg'] . ';">' . $filtered . '</b>';
    }
    $result->free();
}
}
