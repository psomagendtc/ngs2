<?php
foreach(glob(userdir().'/*/OrderInfo.txt') as $OrderInfo_filename){
    $Order_id=basename(dirname($OrderInfo_filename));
    $Order_info=[];
    if($fp=fopen($OrderInfo_filename, 'r')){
        $firstline=true;
        while($line=fgets($fp)){
            $line=trim($line);
            if($line!=''){
                if($firstline){
                    $head=explode("\t", $line);
                    $firstline=false;
                }else{
                    $body=explode("\t", $line);
                    $row=[];
                    for($i=0;$i<count($head);$i++){
                        $row[$head[$i]]=$body[$i];
                    }
                    array_push($Order_info, $row);
                }
            }
        }
        fclose($fp);
    }
    $OrderInfo=file_get_contents();
    array_push($output, ['id'=>$Order_id, 'info'=>$Order_info]);
}
?>