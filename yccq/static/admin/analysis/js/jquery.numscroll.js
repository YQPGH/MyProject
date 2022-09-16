/*!
 * jquery.numscroll.js -- 鏁板瓧婊氬姩绱姞鍔ㄧ敾鎻掍欢  (Digital rolling cumulative animation)
 * version 1.0.0
 * 2018-09-22
 * author: KevinTseng < 921435247@qq.com@qq.com >
 * 鏂囨。:  https://github.com/chaorenzeng/jquery.numscroll.js.git
 * QQ浜ゆ祦缇�: 739574382
 */

(function($) {
	
	function isInt(num) {
		//浣滅敤:鏄惁涓烘暣鏁�
		//杩斿洖:true鏄� false鍚�
		var res = false;
		try {
			if(String(num).indexOf(".") == -1 && String(num).indexOf(",") == -1) {
				res = parseInt(num) % 1 === 0 ? true : false;
			}
		} catch(e) {
			res = false;
		}
		return res;
	}

	function isFloat(num) {
		//浣滅敤:鏄惁涓哄皬鏁�
		//杩斿洖:灏忔暟浣嶆暟(-1涓嶆槸灏忔暟)
		var res = -1;
		try {
			if(String(num).indexOf(".") != -1) {
				var index = String(num).indexOf(".") + 1; //鑾峰彇灏忔暟鐐圭殑浣嶇疆
				var count = String(num).length - index; //鑾峰彇灏忔暟鐐瑰悗鐨勪釜鏁�
				if(index > 0) {
					res = count;
				}
			}
		} catch(e) {
			res = -1;
		}
		return res;
	}

	$.fn.numScroll = function(options) {
		
		var settings = $.extend({
			'time': 1500,
			'delay': 0
		}, options);
		
		return this.each(function() {
			var $this = $(this);
			var $settings = settings;
			
			var num = $this.attr("data-num") || $this.text(); //瀹為檯鍊�
			var temp = 0; //鍒濆鍊�
			$this.text(temp);
			var numIsInt = isInt(num);
			var numIsFloat = isFloat(num);
			var step = (num / $settings.time) * 10; //姝ラ暱
			
			setTimeout(function() {
				var numScroll = setInterval(function() {
					if(numIsInt) {
						$this.text(Math.floor(temp));
					} else if(numIsFloat != -1) {
						$this.text(temp.toFixed(numIsFloat));
					} else {
						$this.text(num);
						clearInterval(numScroll);
						return;
					}
					temp += step;
					if(temp > num) {
						$this.text(num);
						clearInterval(numScroll);
					}
				}, 1);
			}, $settings.delay);
			
		});
	};

})(jQuery);