<?php

function getUsernameHTML($userid, $mysqli)
{
    if ($result = $mysqli->query("SELECT username, namecolor_fg, namecolor_bg, namecolor_bg2, xtra FROM users WHERE id='" . $userid . "'")) {
        while ($row = $result->fetch_assoc()) {
            $filteredname = str_replace("<", "&lt;", $row['username']);
            $filteredname = str_replace(">", "&gt;", $filteredname);

            $xtrasecond = $row['namecolor_bg2'] == "" ? $row['namecolor_bg'] : $row['namecolor_bg2'];
            $xtragradient = $row['xtra'] == 1 ? "background-image: linear-gradient(to right, " . $row['namecolor_bg'] . ", " . $xtrasecond . "  );" : "background-color: " . $row['namecolor_bg'];
            $html_message = " <b class='user-name' style='color: " . $row["namecolor_fg"] . "; " . $xtragradient . "'>" . $filteredname . "</b>";
            return $html_message;
        }
    }
}
