<?php defined('BASEPATH') OR exit('No direct script access allowed ');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/24
 * Time: 10:04
 */

define('IA_ROOT',$_SERVER['DOCUMENT_ROOT'] .'/yccq/');
define('IMAGES',$_SERVER['DOCUMENT_ROOT'] .'/yccq/static/');
//define('QRCODE',IMAGES.'qrcode/');
define('FONTS',$_SERVER['DOCUMENT_ROOT'] .'/yccq/static/msyh.ttf');

class Createsharepng_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

    }

//方法二

    /**
     * 该方法是对十六进制(#FFB400)转为RGB(255,180,0)
     * 进行转换，把后台的自定义的颜色改变为RGB，如后台采用RGB方法存储也可以，还未尝试，产生的BUG不确定性
     * 十六进制转RGB
     * @param $color
     * @return array|bool
     */
    private function hex2rgb($color)
    {
        $hexColor = str_replace('#', '', $color);
        $lens = strlen($hexColor);
        if ($lens != 3 && $lens != 6) {
            return false;
        }
        $newcolor = '';
        if ($lens == 3) {
            for ($i = 0; $i < $lens; $i++) {
                $newcolor .= $hexColor[$i] . $hexColor[$i];
            }
        } else {
            $newcolor = $hexColor;
        }
        $hex = str_split($newcolor, 2);
        $rgb = [];
        foreach ($hex as $key => $vls) {
            $rgb[] = hexdec($vls);
        }
        return $rgb;
    }
    /**
     * 微信头像进行切割为圆形
     * 该方法是对方形进行圆形的切割
     * 处理成圆图片,如果图片不是正方形就取最小边的圆半径,从左边开始剪切成圆形
     * @param $url ：图片url路径
     * @param $w ：图片宽度
     * @param $h ：图片高度
     * @return false|resource
     */
    private function tangential($url,$w,$h)
    {
        $w   = min($w, $h);
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r  = $w / 2; //圆半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($url, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        return $img;
    }
    /**
     * 该方法为接口请求数据
     * GET ：$post 必须为空
     * POST：$post 如参数不为空['data'=>123]
     * @param $url
     * @param string $post
     * @param int $timeout
     * @return bool|string
     */
    public function http_request( $url, $post = '', $timeout = 30 )
    {
        $curl = curl_init();// 初始化一个 cURL 对象
        curl_setopt($curl, CURLOPT_URL, $url);// 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_HEADER, false);// 设置header是否一并显示
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);// https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if( $post != '' && !empty( $post )  ){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($post)));
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($curl);// 运行cURL，请求网页
        curl_error($curl); // 错误调试
        curl_close($curl);// 关闭URL请求
        return $result;
    }
    /**
     * 使用url获取图片并创建图片
     * @param $url
     * @return false|resource
     */
    public function createImage($url)
    {
        $resp = $this->http_request($url);
        return imagecreatefromstring($resp);
    }
    /**
     * 删除文件夹及文件
     * @param $dir
     * @return bool
     */
    public function deleteDir($dir)
    {
        if (!$handle = @opendir($dir)) {
            return false;
        }
        while (false !== ($file = readdir($handle))) {
            if ($file !== "." && $file !== "..") {       //排除当前目录与父级目录
                $file = $dir . '/' . $file;
                if (is_dir($file)) {
                    $this->deleteDir($file);
                } else {
                    @unlink($file);
                }
            }
        }
        @rmdir($dir);
    }

    /**
     * 该方法直接获取拼接的海报二维码
     * @param array $data
     * @return bool
     */
    public function getQrcode($data=array())
    {


        ob_clean();
        if(!empty($data)){
            header("Content-type: image/png");//图片输出
            $font = FONTS;//字体文件
            $target = imagecreatetruecolor(400, 740);//画布的大小
            $bc = imagecolorallocate($target, 255, 255, 255);//创建750*1206的图想
            $nickcolor = $this->hex2rgb('#ffffff');
            $annocolor = $this->hex2rgb('#e69138');
            $promcolor = $this->hex2rgb('#b7b7b7');
            $nc = imagecolorallocate($target, $nickcolor[0], $nickcolor[1], $nickcolor[2]);
            $yc = imagecolorallocate($target, $annocolor[0], $annocolor[1], $annocolor[2]);
            $hc = imagecolorallocate($target, $promcolor[0], $promcolor[1], $promcolor[2]);
            if( !empty($data['background']) )
            {
                //$bg = $this->createImage(tomedia($data['background']));//获取云服务器照片

                $bg = imagecreatefrompng($data['background']);
                //        背景图     拷贝图   X位置    Y位置     X缩小     Y缩小   图片宽度     图片高度
                imagecopy($target, $bg, 0, 0, 0, 0, 400, 740);
                //       背景图      图片宽度  图片高度 背景图
                imagefill($target, 400, 74, $bc);
                imagedestroy($bg);
            }
            if( !empty($data['posters']) )
            {
                //$images = createImage(tomedia($data['posters']));
                $images = imagecreatefrompng($data['posters']);
                $w = imagesx($images);//获取x轴的宽度
                $h = imagesy($images);//获取y轴的宽度
                //               背景图    拷贝图         X位置    Y位置     X缩小     Y缩小   图片宽度     图片高度
                imagecopyresized($target, $images, 62, 118, 0, 0, 626, 898, $w, $h);
                imagedestroy($images);
            }
            if( !empty($data["head_img"]) )
            {
                //$avatar = preg_replace("/\\/0\$/i", "/96", $data["avatar"]);
                $head = imagecreatefromjpeg($data['head_img']);
                //$head = createImage($avatar);
                $w = imagesx($head);//获取x轴的宽度
                $h = imagesy($head);//获取y轴的宽度
                //方形头像切割为圆形
                $h_img = $this->tangential($head,$w,$h);
                //               背景图    拷贝图         X位置    Y位置     X缩小     Y缩小   图片宽度     图片高度
                imagecopyresized($target, $h_img, 110, 44, 0, 0, 150, 150, $w, $h);
//                print_r($w);exit;
                imagedestroy($h_img);
            }
//            if( !empty($data['qrcode']) )
//            {
//                //$thumb = createImage($data['qrcode']);
//                $thumb = imagecreatefromjpeg($data['qrcode']);
//                $w = imagesx($thumb);
//                $h = imagesy($thumb);
//                imagecopyresized($target, $thumb, 136, 328, 0, 0, 480, 480, $w, $h);
//                imagedestroy($thumb);
//            }
            //像素单位的字体大小    text 将被度量的角度大小   TrueType 字体文件的文件名   要度量的字符串
//            $box = imagettfbbox(30, 0, $font, $data["nickname_content"]);
            //计算字体间距
//            $width = $box[4] - $box[6];
            //          背景图      字体大小  角度大小  文字内容x轴距离  文字内容y轴距离  字体颜色 字体  字体内容
//            imagettftext($target, 30, 0, (400-$width)/2 + 1.5, 250, $nc, $font, $data["nickname_content"]);
            $this->textcl($target,$nc,$data["nickname_content"],20,$font, 250,'');
            $announ = imagettfbbox(25, 0, $font, $data["announ_content"]);
            $width1 = $announ[4] - $announ[6];
            imagettftext($target, 25, 0, (400-$width1)/2 + 1.5, 500, $yc, $font, $data["announ_content"]);
            $prompt = imagettfbbox(20, 0, $font, $data["prompt_content"]);
            $width2 = $prompt[4] - $prompt[6];
            imagettftext($target, 20, 0, (400-$width2)/2 + 1.5, 560, $hc, $font, $data["prompt_content"]);

            imagepng($target,$data['filename']);

//            $save_path = 'uploads/shareimage/';
//            if (!file_exists($save_path)) { // 创建文件夹
//                mkdir($save_path);
//            }
//
//            $save_path .= date("Ymd") . "/";
//
//            if (!file_exists($save_path)) {
//                mkdir($save_path);
//            }
            imagedestroy($target);

            return true;
        }else{
            return false;
        }
    }

    //自动文字换行计算
    function textcl($img,$_text_color,$str,$fontSize,$fontpath,$Y,$before){

        for ($i=0;$i<mb_strlen($str);$i++) {
            $letter[] = mb_substr($str, $i, 1,'utf-8');
        }
        $content=$before;
        foreach ($letter as $l) {
            $teststr = $content." ".$l;
            $fontBox = imagettfbbox($fontSize, 0, $fontpath, $teststr);
            if (($fontBox[2] > 300) && ($content !== "")) {
                $content .= "\n";
            }
            $content .= $l;
        }
        imagettftext($img, $fontSize, 0, ceil((400 - $fontBox[2]) / 2), $Y, $_text_color, $fontpath, $content );

        return $img;
    }

}