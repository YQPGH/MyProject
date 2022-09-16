<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 过滤词库
 * Date: 2020/1/17
 * Time: 10:10
 */
class Filterwords_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * $string 要过滤的内容
     */
    function getMain($string)
    {
        $f = file(site_url('filterWords/filterWords.txt'));//载入敏感词文件
        $words = array();
        foreach ($f as &$w)
        {
            $w = str_replace(",",'',$w);
            $words[] = preg_quote(trim($w), '/');
        }


        $result = $this->sensitive($words, $string);

        return $result;

    }

    function sensitive($words, $string)
    {

        $count = 0; //违规词的个数
        $sensitiveWord = '';  //违规词
        $stringAfter = $string;  //替换后的内容
        $pattern = "/".implode("|",$words)."/i"; //定义正则表达式

        if(preg_match_all($pattern, $string, $matches))
        { //匹配到了结果
            $patternList = $matches[0];  //匹配到的数组

            $count = count($patternList);
            $sensitiveWord = implode(',', $patternList); //敏感词数组转字符串
            $replaceArray = array_combine($patternList,array_fill(0,count($patternList),'*')); //把匹配到的数组进行合并，替换使用
            $stringAfter = strtr($string, $replaceArray); //结果替换
        }

        if($count!=0)
        {

            $content = "{$stringAfter}";
        }
        else
        {
            $content = $string;
        }

        return $content;
    }



    function testAction()
    {

        $f = file(site_url('filterWords.txt'));

        $words = array();
        foreach ($f as $w)
        {
            $words[] = preg_quote(trim($w), '/');
        }

        $text = file_get_contents(site_url('filterWords.txt'));
        $start = microtime(true);
        $reg = '/' . implode('|', $words) . '/S';
        preg_match_all($reg, $text, $m);
        $result = array();
        $total = 0;
        foreach ($m[0] as $w)
        {
            if (!isset($result[$w]))
            {
                $result[$w] = 1;
            }
            else
            {
                $result[$w]++;
            }
            $total++;
        }
        $end = microtime(true);
        echo $end - $start, "\n";
        echo $total, "\n";
        print_r($result);
    }


}
