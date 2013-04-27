/************************************************
 *
 * Amysql Framework
 * Amysql.com 
 * @param Object AmysqlPluginManage 管理插件的插件
 *
 */

// ************************** 预设函数与配置 **************************

if(window.AmysqlMainObject)
{
	var AmysqlPluginManageTempData = [];
	AmysqlMainObject.AmysqlExtend.push({
		'_ExtendInfo':{
			'ExtendId':'AmysqlPluginManage',
			'PlugName':'插件管理',
			'PlugAbout':'管理查看您系统已安装的插件，随时关闭或开启插件。Notice:保证插件能拥有最高的管理权限请第一个引用本插件。',
			'Sort':'Menu',
			'Version':'2.00',
			'Date':'2012-03-14',
			'WebSite':'http://amysql.com',
			'PoweredBy':'Amysql'
		},

		'_AmysqlExtendControllerConfig':function ()
		{
			if (window._AmysqlTag)
			{
				if(Cookies.get('AmysqlPluginManage') == null ) 
				{
					var exp = new Date();
					exp.setTime(exp.getTime()*1.1);
					Cookies.set('AmysqlPluginManage', ' ', exp, '/');
				}

				// Copy data
				for (var k in AmysqlMainObject.AmysqlExtend )
					AmysqlPluginManageTempData[k] = AmysqlMainObject.AmysqlExtend[k];

				// 清空Deactivate插件数据
				for (var k in AmysqlMainObject.AmysqlExtend )
				{
					if(Cookies.get('AmysqlPluginManage').indexOf('|' + AmysqlMainObject.AmysqlExtend[k]._ExtendInfo.ExtendId + '|') != -1)
						AmysqlMainObject.AmysqlExtend[k] = [];
				}
			}
		},

		'_AmysqlLNIJson':[{
			'order':5,
			'id':'PlugAmysql', 
			'name':'系统插件管理',
			'PlugIn':true,
			'url':'js/PlugIn/PluginManage/AmysqlPluginManage.js'
		}]

	});
}

// ************************** 标签打开后运行 **************************

if(window.ExtendContent)
{
	var AmysqlTagWindow = parent.parent.window.frames.AmysqlTag;
	var AmysqlParentWindow = parent.window;
	var AmysqlRootWindow = parent.parent.window;

	// 创建子条项
	var CreateItem = function (Item)
	{
		if(!Item) return;
		var div = C('div', {'className':'item','href':'javascript:'});
		div.PlugName = C('h3', 'In', Item.ExtendId + '<i>' + Item.Version + '<font>' + Item.Sort + ' ' + Item.Date + '</font></i>');
		div.PlugAbout = C('p', 'In', Item.PlugName + ': ' + Item.PlugAbout);
		div.WebSite = C('em', 'In', Item.WebSite);
		div.WebSite.onclick = function ()
		{
			AmysqlRootWindow.OpenWindow('Activate', Item.ExtendId, Item.PoweredBy, Item.WebSite);
		}
		div.PoweredBy = C('i', 'In', 'Powered By:' + Item.PoweredBy);

		div.button = C('a', {'id':'Deactivate', 'className':'button','href':'javascript:'});
		div.button.PlugCookie = AmysqlRootWindow.Cookies.get('AmysqlPluginManage').indexOf(Item.ExtendId) == -1 ? null:1;
		C(div.button, {'innerHTML': (div.button.PlugCookie == null) ? 'Deactivate' : 'Activate'});
		div.button.onclick = function ()
		{
			var exp = new Date();
			exp.setTime(exp.getTime()*1.1);
			if(this.PlugCookie == null)
			{
				this.innerHTML = 'Activate';
				this.PlugCookie = 1;
				AmysqlRootWindow.Cookies.set('AmysqlPluginManage', AmysqlRootWindow.Cookies.get('AmysqlPluginManage') + '|' + Item.ExtendId + '|', exp, '/');
			}
			else
			{
				this.innerHTML = 'Deactivate';
				this.PlugCookie = null;
				var exp = new Date();
				exp.setTime(exp.getTime()*1.1);
				AmysqlRootWindow.Cookies.set('AmysqlPluginManage', AmysqlRootWindow.Cookies.get('AmysqlPluginManage').replace('|' + Item.ExtendId + '|', '') , exp, '/');
			}
		}

		div.onmouseover = function ()
		{
			this.className = 'item_hover';
		}
		div.onmouseout = function ()
		{
			this.className = 'item';
		}

		if(Item.ExtendId == 'AmysqlPluginManage') div.button = C('u');
		return C(div, 'In', [div.PlugName, div.PlugAbout, div.PoweredBy, div.WebSite, div.button]);
	}

	var GoShow = function ()
	{
		this.AmysqlPlugList = C('div', {'id':'AmysqlPlugList','className':'_c'});
		this.SumDiv = null;
		this.TempPlug = [];
		this.TempPlugItem = [];
		this.ItemSum = 0;

		for (var key in AmysqlRootWindow.AmysqlPluginManageTempData)
		{
			this.TempPlug[key] = [AmysqlRootWindow.AmysqlPluginManageTempData[key].Sort, AmysqlRootWindow.AmysqlPluginManageTempData[key]._ExtendInfo];	// 存临时数组
		}
		// 排序
		this.TempPlug.sort();

		for (key in this.TempPlug )
		{
			++this.ItemSum;

			var k = this.TempPlugItem.length;
			var Item = CreateItem(this.TempPlug[key][1]);
			if(Item)
			{
				this.TempPlugItem[k] = Item;
				this.AmysqlPlugList.appendChild(this.TempPlugItem[k]);		// 增加至列表
			}
		}
		
		this.SumDiv = C('DIV', {'className':'SumDiv'});
		C(this.SumDiv, 'In', [C('font','In','总共有: '),C('B', 'In', this.ItemSum),C('font','In','个插件。<b>Notice:</b>设置后重新载入系统才生效。')]);

		document.body.innerHTML = '';
		C(document.body, 'In', [
			C('link',{'type':'text/css','rel':'stylesheet','href':'js/PlugIn/PluginManage/style.css'}),
			C('h1','In', '系统插件管理'),
			this.AmysqlPlugList, this.SumDiv
		]);
	}

	document.body.innerHTML = '<div id="LoadingBlock">&nbsp; Loading...<div id="loading"></div></div>';
	setTimeout(
		function () {
				var GoShowObject = new GoShow();
		}, 50
	);
}