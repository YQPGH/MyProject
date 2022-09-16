<?php

/**
 * 长文章分页，分段显示
 * by tangjian 2015-9-28
 */
class ArticlePage
{
    private $article;  // 文章内容
    private $word_number;  // 每页显示多少个字
    private $split_word;  // 分隔字符 默认是段落

    function __construct($article, $word_number = 1000, $split_word = "</p>")
    {
        $this->article = trim($article);
        $this->word_number = $word_number;
        $this->split_word = $split_word;
    }

    // 显示
    function get_article()
    {
        $result = "";

        $strlen = mb_strlen( $this->article, 'UTF-8');
        if ($_GET['view'] == "all" || $strlen <= $this->word_number) {
            $result .= $this->article;
        } else {
            $page = !empty($_GET['per_page']) ? $_GET['per_page'] : 1;
            $detailContent = $this->article_addpage();
            $detailContent = explode('[nextpage]', $detailContent);
            $result .= $detailContent[$page - 1];
            $result .= "<br />";
            $cutepage = $this->article_fenpage($detailContent, $page);
            $result .= $cutepage;
        }

        return $result;
    }

    /**
     * 长文章分段
     * @param string $article 文章内容
     * @param number $word_number 文章字节限制
     * @return array
     */
    function article_addpage()
    {
        $has_table = strpos($this->article, "<table");
        if ($has_table !== false) {
            return $this->article;  //如果包含了表格就跳过，不进行分页处理
        } else {
            $return_art = "";
            $word_num = 0;
            $art_arr = explode($this->split_word, $this->article);//根据字符串确定段
            for ($i = 0; $i < count($art_arr); $i++) {
                $strlen = mb_strlen($art_arr[$i], 'UTF-8');
                if ($strlen == 0) return $return_art;
                $word_num += $strlen;  //得到字数
                if ($word_num <= $this->word_number) {
                    $return_art .= $art_arr[$i] . $this->split_word;
                } else { // 超过指定字数的 加上 [nextpage]
                    $return_art .= $art_arr[$i] . "{$this->split_word}[nextpage]";
                    $word_num = 0;
                }
            }

            return $return_art;
        }
    }


    /**
     * 文章分页
     * @param string $article 文章内容
     * @param number $id 默认第一页
     * @param string $page 当前第几页
     * @return string
     */
    function article_fenpage($article_arr, $page)
    {
        $fenyedh = '<div class="pages">';
        foreach ($article_arr as $key=>$one) {
            $i = $key+1;
            if ($page == $i) {
                $fenyedh .= "<strong> $i </strong>";
            } else {
                $fenyedh .= "<a href='?per_page=$i'>$i</a> ";
            }
        }
        $fenyedh .= '<a href="?view=all" title="全文浏览" class="inline_b">全文浏览</a></div>';

        return $fenyedh;
    }

}
