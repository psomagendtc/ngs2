<?php
foreach(glob(userdir().'/*/*') as $pathname){
    if(is_dir($pathname)&&count(glob("{$pathname}/*"))){
        $project=basename(dirname($pathname));
        $sample=basename($pathname);
        if(!array_key_exists($project, $output)){
            $output[$project]=[];
        }
        $output[$project][$sample]=true;
    }
}
?>