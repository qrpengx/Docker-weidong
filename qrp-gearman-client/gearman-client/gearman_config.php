<?php
return [
    'gearman_server'=>'192.168.200.120',
    'gearman_port'=>4730,
    'app_url'=>'http://192.168.200.120:85/index.php/api/fileExt/getFileExtList/?fileExtName=mp4&fetchNum=1000',
    'app_update_url'=>'http://192.168.200.120:85/index.php/api/fileExt/changeFileExt/',
    'video_type'=>'MP4,RMVB,F4V,FLV,AVI,WMA,WAV',
    'video_max_num'=>10,//视频队列最大数量
    'page_size'=>20,//请求数量


];
