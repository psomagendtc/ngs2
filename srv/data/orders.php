<?php
foreach(glob(userdir().'/*') as $pathname){
	if(is_dir($pathname)){
	    $Order_id=basename($pathname);
	    $Order_info=[];
	    if($fp=fopen("{$pathname}/OrderInfo.txt", 'r')){
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
	    array_push($output, ['id'=>$Order_id, 'info'=>$Order_info]);
	}
}
?>