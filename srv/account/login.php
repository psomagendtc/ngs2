<?php
$id=$input['id'];
$pw=$input['pw'];
if(login($id, $pw)){
    $output=true;
}
?>