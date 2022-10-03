<?php
foreach(glob(userdir().'/*/*') as $pathname){
    if(is_dir($pathname)){
        $project=basename(dirname($pathname));
        $sample=basename($pathname);
        if(!array_key_exists($project, $output)){
            $output[$project]=[];
        }
        array_push($output[$project], $sample);
    }
}
?>