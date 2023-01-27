<?php
$project=$input['project'];
$sample=$input['sample'];
$linkroot=_CONFIGS('linkroot');
foreach(glob(userdir()."/{$project}/{$sample}/*") as $filename){
    if(is_file($filename)){
        array_push($output, ['name'=>basename($filename), 'size'=>filesize($filename)]);
    }
}
?>