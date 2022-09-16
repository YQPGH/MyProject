<!DOCTYPE html>
<html >
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no">
<meta name="applicable-device" content="pc,mobile">
<meta http-equiv="Cache-Control" content="no-transform ">
<script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
<title>意见反馈</title>
<link rel="stylesheet" style="text/css" href="<?= base_url()?>static/questionnaire/css/suggestionList.css">
</head>

<body>
	<div class="bg">
        <a id="close"  href="<?= base_url()?>api/Main/index"></a>
        <div id="content">
            <ul class="content_list">
                <?php foreach($list as $key=>$value):?>
                    <li>

<!--                        <span>--><?//= $value['uid']?><!--</span>-->
                        <p>意见<br>&nbsp;&nbsp;<?= $value['content']?></p>
                        <div class="reply_content">

                            <?php foreach($value['reply_content'] as $k=>$v):?>
                                <a >回复：</a>
                                <p>&nbsp;&nbsp;<?= $v['r_content']?></p>
                            <?php endforeach; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <input id="sub" name="button" type="button" value="" onclick=window.open("<?= base_url()?>api/Suggestion/suggestion")>
	</div>
	
<script type="application/javascript">

</script>

</body>
</html>