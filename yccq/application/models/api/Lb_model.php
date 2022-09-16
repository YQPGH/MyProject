<?php
if (! defined('BASEPATH'))  exit('No direct script access allowed');
    
// 龙币模型
	
class Lb_model extends CI_Model
{
	public  $ip  = '0';
	private $query_key ;//= '0efd1cb3e33a618f92f43f220e5d3301';//密钥 
	private $recharge_url = 'http://10.1.32.14:80/api/dcurrency/recharge.do';//充值url
	private $consume_url  = 'http://10.1.32.14:80/api/dcurrency/consume.do';//消耗url
	private $query_url    = 'http://10.1.32.14:80/api/dcurrency/query.do';//查询url
	private $sid_arr;// = array('119.29.87.142' => 100111, '119.29.56.43' => 100112);
	private $query_key_arr;// = array('119.29.87.142' => '0ekh1cb9j33a548f92f43f220a6d8977', '119.29.56.43' => '0efd1cb3e55a338f92f43f220i9d8976');//密钥
	private $code_table_arr = array('119.29.87.142' => 'zy_two_code', '119.29.56.43' => 'zy_three_code');
	private $sid = '';
	private $rechargetype = 0;
	private $consumetype = 0;
    function __construct ()
    {
        parent::__construct();
		$this->ip = config_item('ip');
		//$this->sid = $this->sid_arr[config_item('ip')];
    }
    
    // 查询龙币
    function get_lb_num($openid, $ActiveID, $ChannelID, $RoomID, $gameid = 0)
    {				
		if(!$openid || !$ChannelID || !$ActiveID || !$RoomID){
			return false;
		}
		$this->init_info($ActiveID);
		$data['sid'] = $this->sid;//设备编号
        $data['seq'] = $this->sid . date("YmdHis", time()) . $this->get_seq();//流水号
        $data['useraccount'] = $openid;//用户账号
        $data['accounttype'] = 2;////accounttype=1，useraccount=手机号码     accounttype=2，useraccount=微信openId  accounttype=3，useraccount=微信unionId
        $data['timestamp'] = time() - strtotime('2000-01-01 00:00:00');//时间戳，即从2000-01-01 00:00:00到该请求失效时间的间隔秒数，正整数  
		//数据有效性签名   MD5(sid + seq + useraccount + accounttype  + timestamp + key)输出32位小写字符，其中Key是约定的密匙
		$data['skey'] = md5($data['sid'] . $data['seq'] . $data['useraccount'] . $data['accounttype'] . $data['timestamp'] .$this->query_key );			
        $return = $this->https_request($this->query_url, $data);
		
		//var_dump( $return );
		//存入数据库
		$data_log['addtime'] = time();
		$data_log['postdata'] = json_encode($data);
		$data_log['returndata'] = json_encode($return);
		$data_log['gameid'] = $gameid;
		$data_log['ChannelID'] = $ChannelID; 		
		$data_log['ActiveID'] =  $ActiveID; 
		$data_log['RoomID'] = $RoomID;
		$data_log['ip'] = $this->ip;
		$data_log['openid'] =  $openid;
		$data_log['postUrl'] =  $this->query_url;
		$data_log['status'] =  $return['returncode'] == '000000' ? 1 : 0;
		$this->db->insert('zy_game_bl_trade_log',$data_log);
		
		return $return;
    }
	
	 //消耗龙币
    function consume_lb($lbNum, $consumetype, $openid, $ActiveID, $ChannelID, $RoomID, $gameid){
		if(!$lbNum || !$consumetype || !$openid || !$ChannelID || !$ActiveID || !$RoomID || !$gameid){
			return false;
		}
		$this->init_info($ActiveID);
  		$data['sid'] = $this->sid;//设备编号
        $data['seq'] = $this->sid . date("YmdHis", time()) . $this->get_seq();//流水号
        $data['useraccount'] = $openid;//用户账号
		$data['consumetype'] = $this->consumetype;//消耗类别ID
        $data['consumeamount'] = $lbNum;//消耗金额（龙币）-正整数
        $data['accounttype'] = 2;////accounttype=1，useraccount=手机号码     accounttype=2，useraccount=微信openId  accounttype=3，useraccount=微信unionId
        $data['timestamp'] = time() - strtotime('2000-01-01 00:00:00');//时间戳，即从2000-01-01 00:00:00到该请求失效时间的间隔秒数，正整数  
		//数据有效性签名MD5(sid + seq + useraccount + accounttype  + consumetype + consumeamount + timestamp + key)输出32位小写字符，其中Key是约定的密匙
		$data['skey'] = md5($data['sid'] . $data['seq'] . $data['useraccount'] . $data['accounttype'] . $data['consumetype'] . $data['consumeamount'] . $data['timestamp'] .$this->query_key );			
        $return = $this->https_request($this->consume_url, $data);
	  	
		//存入数据库
		$data_log['addtime'] = time();
		$data_log['postdata'] = json_encode($data);
		$data_log['returndata'] = json_encode($return);
		$data_log['gameid'] = $gameid;
		$data_log['ChannelID'] = $ChannelID; 		
		$data_log['ActiveID'] =  $ActiveID; 
		$data_log['RoomID'] = $RoomID;
		$data_log['ip'] = $this->ip;
		$data_log['openid'] =  $openid;
		$data_log['postUrl'] =  $this->consume_url;
		$data_log['status'] =  $return['returncode'] == '000000' ? 1 : 0;
		$this->db->insert('zy_game_bl_trade_log',$data_log);
   		return $return;
   
    }
	
	//充值龙币
    function recharge_lb($lbNum, $rechargetype, $openid, $ActiveID, $ChannelID, $RoomID, $gameid){
		if(!$lbNum || !$rechargetype || !$openid || !$ChannelID || !$ActiveID || !$RoomID || !$gameid){
			return false;
		}
		$this->init_info($ActiveID);
        $data['sid'] = $this->sid;//设备编号
        $data['seq'] = $this->sid . date("YmdHis", time()) . $this->get_seq();//流水号
        $data['useraccount'] = $openid;//用户账号
        $data['accounttype'] = 2;//accounttype=1，useraccount=手机号码     accounttype=2，useraccount=微信openId  accounttype=3，useraccount=微信unionId
        $data['rechargetype'] = $this->rechargetype;//充值类别ID
        $data['rechargeamount'] = $lbNum;//充值金额（龙币）-正整数
        $data['timestamp'] = time() - strtotime('2000-01-01 00:00:00');//时间戳，即从2000-01-01 00:00:00到该请求失效时间的间隔秒数，正整数
         //MD5(sid + seq + useraccount + accounttype  + rechargetype + rechargeamount + timestamp + key)输出32位小写字符，其中Key是约定的密匙
		$data['skey'] = md5($data['sid'] . $data['seq'] . $data['useraccount'] . $data['accounttype'] . $data['rechargetype'] . $data['rechargeamount'] . $data['timestamp'] .$this->query_key );			
        $return = $this->https_request($this->recharge_url, $data);

        //存入数据库
        $data_log['addtime'] = time();
		$data_log['postdata'] = json_encode($data);
		$data_log['returndata'] = json_encode($return);
		$data_log['gameid'] = $gameid;
		$data_log['ChannelID'] = $ChannelID; 		
		$data_log['ActiveID'] =  $ActiveID; 
		$data_log['RoomID'] = $RoomID;
		$data_log['ip'] = $this->ip;
		$data_log['openid'] =  $openid;
		$data_log['postUrl'] =  $this->recharge_url;
		$data_log['status'] =  $return['returncode'] == '000000' ? 1 : 0;
        $this->db->insert('zy_game_bl_trade_log',$data_log);
        return $return;
    }
	
	function init_info($ActiveID){
		$query = $this->db->get_where ('zy_active_main', array('ActiveID'=>$ActiveID),1 );
		$value = $query->row_array ();
		if($value){
			$server_data = string2array($value['ServerData']);
			foreach($server_data as $k=>$v){
				$this->sid_arr[$k] = $v[0];
				$this->query_key_arr[$k] = $v[1];
			}
			$this->sid = trim( $this->sid_arr[config_item('ip')] );
			$this->query_key = trim( $this->query_key_arr[config_item('ip')] );
			$this->rechargetype = trim( $value['ApiRechargeTypeID'] );
			$this->consumetype = trim( $value['ApiConsumeTypeID'] );
			
			
			$this->recharge_url = $this->getApiListForID($value['ApiRechargeID']);
			$this->consume_url  = $this->getApiListForID($value['ApiConsumeID']);
			$this->query_url    = $this->getApiListForID($value['ApiQueryID']);
			
			
		}else{
			exit(0);
		}
		
	}
	
	public function getApiListForID($ID){
		if(empty($ID)) return array();
		$query = $this->db->get_where('zy_channel_api', array('ChannelApiID' => $ID),1);
		$row = $query->row_array();
		return $row['ApiUrl'];
		
	}
	
	function getUserApiByAID($ActiveID) {
		
		$query = $this->db->get_where ('zy_active_main', array('ActiveID'=>$ActiveID),1 );
		$value = $query->row_array();		
		if($value && $value['ApiUserInfoID']){
			$query = $this->db->query ("SELECT * FROM zy_channel_api  WHERE `ChannelApiID` = '".$value['ApiUserInfoID']."' LIMIT 1"  );			
			$row = $query->row_array ();
			return $row['ApiUrl'];
		}	
		return false;
		
	}
	
	/**
	 * 根据条件，获取记录条数
	 *
	 * @param string $where        	
	 * @return array 二维数组
	 */
	function get_seq() {	
		$ip = config_item('ip');
		$table = $this->code_table_arr[$ip];
		$this->db->insert($table,array('addtime'=>time()));
		$new_id = $this->db->insert_id();
		$seq   = sprintf("%08d", $new_id);//生成8位数,不足前面补0
		return $seq;
	}
	
  /**
     * 模拟POST提交数据
     * @param string $url 链接地址
     * @param array $data 数组
     */
    public function https_request($url,$data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output,true);
    }
	
	function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
	}

    
    
}
