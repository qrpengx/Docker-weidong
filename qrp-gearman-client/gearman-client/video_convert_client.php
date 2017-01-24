<?php
require("DB/Db.class.php");
require("Lib/Curl.php");
$gearman_config=require('gearman_config.php');
$url=$gearman_config['app_url'];
$url.='?fileExtName='.$gearman_config['video_type'].'&fetchNum='.$gearman_config['page_size'].'';
//初始化分布式任务
/* create our object */
$gmc= new GearmanClient();
/* add the default server */
$gmc->addServer($gearman_config['gearman_server'],$gearman_config['gearman_port']);
/* set a few callbacks */
//$gmc->setCreatedCallback("thumb_created"); //开始创建任务回调函数
$gmc->setCompleteCallback("video_convert_complete");//任务执行成功后的回调函数
$gmc->setFailCallback("video_convert_fail");//任务执行失败时的回调函数
$cl=new  Curl();
do
{
    $data= $cl->get($url);
	
    if(is_array($data))
    {
        foreach ($data as $item) {
            $key=$item->rowKey;
            //判断该值是否存在
            $db = new Db();
            $rowData= $db->row("SELECT id FROM wd_video_convert WHERE rowkey = :rowkey", array("rowkey"=>$key)); 
           if(!$rowData) //数据不存在 就插入
            {
		//获得当前空余数
                $workCountArray= $db->row("select count(id) as row_count from wd_video_convert WHERE convert_status=0");
                $workCount= $workCountArray['row_count'];
//            //超出了系统规定最大处理数后会下次继续请求进入
                if($workCount<$gearman_config['video_max_num'])
                {
                    $queryCount= $db->query("insert into wd_video_convert(rowkey,sourse_url,createtime) values(:rowkey,:sourse_url,:createtime)",array("rowkey"=>$key,"sourse_url"=>$item->fileId,"createtime"=>time()));
                    echo 'insert key'.$key;
                    if($queryCount>0)
                    {
                        $taskData['rowKey']=$key;
                        $taskData['fileId']=$item->fileId;
                        //$gmc->addTask("convert_video", serialize($taskData));
			$gmc->addTask("convert_video", json_encode($taskData));
                        echo '新分配了任务:'.$key.'\n';
                    }
                }
                else
                {
                   echo '任务数已超出限制,等待处理中';
                    break;
                }

            }
            else //数据已存在 并且转码成功就不处理，目前数据存在就不做任何处理了
            {
                //更改数据转码状态为未转码
                //$db->query("update wd_video_convert set convert_status=0 where rowKey=:rowkey",array("rowkey"=>$key));
            }
        }
    }
}
while($gmc->returnCode() != GEARMAN_SUCCESS);

if (! $gmc->runTasks())
{
    echo "ERROR RET:" . $gmc->error() . "\n";
    exit;
}
function video_convert_complete($task)
{
    $result=json_decode($task->data());
    $key= $result->rowKey;
    $status=$result->status;
    $new_url=$result->convert_url;
    $convert_size=$result->convert_size;
    $db = new Db();
    $db->query("update wd_video_convert set convert_url=:convert_url,convert_size=:convert_size, convert_status=:convert_status where rowKey=:rowkey",array("rowkey"=>$key,"convert_url"=>$new_url,"convert_size"=>$convert_size,"convert_status"=>$status));
    //调用接口修改转码状态
//?rowKey=1100002000000000280&fileId=group1/M00/01/40/wKgGbVXqmlqABonVAAAByDzs87g596.swf&fileSize=456&fileExtName=SWF&tableName=T_JYX_ZYXX_WJZY&tb=group1/M00/01/40/wKgGbVXqmlqABonVAAAByDzs87g596_1.png&token=Gm.uLeT0JWAYUSB0KZ4Q59VIoKWrPB
    $cl=new  Curl();
    $gearman_config=require('gearman_config.php');
    $postData['rowKey']=$key;
    $postData['fileId']=$new_url;
    $postData['fileSize']=$convert_size;
    $cl->post($gearman_config['app_update_url'],$postData);
    echo $key.'---转码成功';
}
function video_convert_fail($task)
{
    $result=json_decode($task->data());
    $key= $result->rowKey;
    $status=$result->status;
    $db = new Db();
    $db->query("update wd_video_convert set convert_status=:convert_status where rowKey=:rowkey",array("rowkey"=>$key,"convert_status"=>$status));
    //调用接口修改转码状态
    $cl=new  Curl();
    $gearman_config=require('gearman_config.php');
    $postData['rowKey']=$key;
    $postData['fileId']='';
    $postData['fileSize']=0;
    $cl->post($gearman_config['app_update_url'],$postData);
    echo $key.'---转码失败';
}
?>

