<?php

/**
 * 日期时间类库
 * User: tangjian
 * Date: 2017/5/17
 * Time: 17:18
 */
class Time
{
    // 现在时间
    function now()
    {
        return date('Y-m-d H:i:s');
    }
    // 今天
    function today()
    {
        return date('Y-m-d');
    }
    // 昨天
    function yesterday()
    {
        return date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
    }
    // 明天
    function tomorrow()
    {
        return date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
    }

    // 根据时间返回年月日
    function day($time)
    {
        return date('Y-m-d', strtotime($time));
    }

    // 上个月
    function last_month()
    {
        return date('Y-m', mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
    }

    // 返回时间
    function time_add_hour($hour)
    {
        return date('Y-m-d H:i:s', mktime(date("H") + $hour));
    }

    // 返回两个时间相差几天
    function days_between($start_day, $stop_day)
    {
        return floor((strtotime($stop_day)-strtotime($start_day))/86400);;
    }


}