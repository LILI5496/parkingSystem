<?php

session_start();//开启session，处于程序的最顶部

$image=imagecreatetruecolor(100,30);//创建底图


$bgcolor=imagecolorallocate($image,255,255,255);//设置颜色
imagefill($image,0,0,$bgcolor);//填充颜色

//下方是数字与字母的混合体
$captch_code="";//存储返回的字符
for($i=0;$i<4;$i++)
{
    $fontsize=6;
    $fontcolor=imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));//设置数字颜色，颜色要深
    $data='qwertyuiopkjhgfdsazxcvbnm123456789';//设置字典数据
    $fontcontent=substr($data,rand(0,strlen($data)),1);//取一个字典中的数据

    $captch_code.=$fontcontent;//将取出的数据合取到captch_code中
    $x=($i*100/4)+rand(5,10);
    $y=rand(5,10);
    imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);
}
//echo $captch_code;
$_SESSION['authcode']=$captch_code;

//设置点的干扰元素
for($i=0;$i<200;$i++)
{
    $pointcolor=imagecolorallocate($image,rand(50,200),rand(50,200),rand(50,200));//设置点的颜色，颜色要浅
    imagesetpixel($image,rand(1,99),rand(1,29),$pointcolor);//显示资源
}
//增加线的干扰元素
for($i=0;$i<3;$i++)
{
    $linecolor=imagecolorallocate($image,rand(80,220),rand(80,220),rand(80,220));
    imageline($image,rand(1,99),rand(1,29),rand(1,99),rand(1,29),$linecolor);
}
header('content-type:image/png');
imagepng($image);//显示图片
imagedestroy($image);//销毁资源


