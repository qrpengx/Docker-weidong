<?php
/*
 * Gearman PHP Extension
 *
 * Copyright (C) 2008 James M. Luedke (jluedke@jamesluedke.com)
 *                           Eric Day (eday@oddments.org)
 * All rights reserved.
 *
 * Use and distribution licensed under the PHP license.  See
 * the LICENSE file in this directory for full text.
 */
$gmw= new GearmanWorker();
$gmw->addServer('192.168.200.120',4730);

# optional config paramsj
//$args;

$gmw->addFunction("convert_video", "convert_video", NULL);

while($gmw->work())
{
    switch ($gmw->returnCode())
    {
        case GEARMAN_SUCCESS:
            break;
        default:
            echo "ERROR RET: " . $gmc->returnCode() . "\n";
            exit;
    }
}
echo "DONE\n";

/* simple function to resize an image
 * Requires the Imagick extension */
function convert_video($job, $args)
{
    $wrk= $job->workload();
    $data= unserialize($wrk);    
    echo $job->handle() . " - 正在处理视频：".$data['rowkey']."\n";
    sleep(rand(5,15));
    $job->sendStatus(1, 1);
    $result['rowKey']=$data['rowkey'];
    $result['status']=1;
    $result['convert_url']='new_url.mp4';
    $result['convert_size']=rand(200,500);
    return $result;
}
?>
