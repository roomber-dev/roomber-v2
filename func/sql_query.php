<?php

function sql_query($mysqli, $query, $params) {
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $params); // 's' specifies the variable type => 'string'
   
    $stmt->execute();
   
    $result = $stmt->get_result();

    return $result;
}