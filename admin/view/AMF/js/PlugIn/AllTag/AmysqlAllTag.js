/************************************************
 *
 * Amysql Framework
 * Amysql.com 
 * @param Object AmysqlAllTag 全部标签列表插件
 *
 */

// ************************** 预设函数与配置 **************************

if(window.AmysqlMainObject)
{
	AmysqlMainObject.AmysqlExtend.push({
		'_ExtendInfo':{
			'ExtendId':'AmysqlAllTag',
			'PlugName':'全部标签列表插件',
			'PlugAbout':'排序统计显示您已打开的标签，方便您查看与关闭。',
			'Sort':'Menu',
			'Version':'1.10',
			'Date':'2011-05-19',
			'WebSite':'http://amysql.com',
			'PoweredBy':'Amysql'
		},

		'_AmysqlExtendInitialConfig':function ()
		{
			if (window._AmysqlTag)
			{
				var ShowItem = _AmysqlTag.AmysqlTabObject.GetMin('AmysqlAllShow');
				ShowItem.span.onmouseover = function ()
				{
					this.id = 'ShowHover';
				}
				ShowItem.span.onmouseout = function ()
				{
					this.id = 'AmysqlAllShow';
				}
			}
		},

		'_AmysqlTabMinJson':[{
			'order':9,
			'id':'AmysqlAllShow', 
			'name':'Tag List',
			'PlugIn':true,
			'url':'view/AMF/js/PlugIn/AllTag/AmysqlAllTag.js'
		}],

		'_AmysqlLNIJson':[{
			'order':3,
			'id':'AmysqlAllShow', 
			'name':'Tag List',
			'PlugIn':true,
			'url':'view/AMF/js/PlugIn/AllTag/AmysqlAllTag.js'
		}]

	});
}

// ************************** 标签打开后运行 **************************

if(window.ExtendContent)
{
	var AmysqlTagWindow = parent.parent.window.frames.AmysqlTag;
	var AmysqlParentWindow = parent.window;

	// 创建子条项
	var CreateItem = function (Item)
	{
		var DIV = C('DIV', {'className':'item'});
		var A = C('A', {'className':'item_list', 'href':'javascript:;'});

		// 详细位置
		var TagID = '<b>' + Item.id + '</b>';
		var I = C('I', {'innerHTML':TagID + ' &nbsp; - <em>' + Item.command + '</em>'});
		
		// 标题
		var SPAN = C('SPAN', {'innerHTML':Item.text});

		A.appendChild(I);
		A.appendChild(SPAN);

		A.onclick = function ()
		{
			// 我们使用add方法激活 标签是已存在 不会再插入新标签 同时处理标签所在位置
			// AmysqlTagWindow.AmysqlTabObject.TagOnclick(Item);	// 也可以这样激活标签 但不会跳到当前标签位置
			Item.type = 'Activate';
			AmysqlParentWindow.AmysqlContentObject.LoadRefresh = false;	// 不刷新
			AmysqlTagWindow.AmysqlTabObject.add(Item);	// 激活标签
		}
		// *************************************


		// 关闭
		var U = C('u', {'className':'close'});
		var X = C('a', {'className':'ico2 ico_del2','title':'Close Tag', 'innerHTML':'Delete'});
		X.href = 'javascript:;';
		X.onclick = function ()
		{

			var RunClick = true;	// 是否关闭时 同时执行点击事件 更新标签 
			AmysqlTagWindow.AmysqlTabObject.GoLocation(false, AmysqlTagWindow.AmysqlTabObject.LastClickItem.TagListKey);			// 位置
			AmysqlTagWindow.AmysqlTabObject.CloseTagFun(Item, RunClick);				// 关闭标签

			var GoShowObject = new GoShow();	// 删除后key都变了。重新来一次吧~
			
			// AmysqlTagList.removeChild(DIV);
			// --ItemSum;
			// GoShowObject.ItemSumB.innerHTML = ItemSum;
		}
		U.appendChild(X);
		// *************************************

		DIV.appendChild(A);
		if(Item.id != AmysqlTagWindow.AmysqlTabObject.NumberOneId ) 
			DIV.appendChild(U);		// 第一个标签不显示关闭
		return DIV;
	}

	var GoShow = function ()
	{
		this.AmysqlTagList = C('div', {'id':'AmysqlTagList'});
		this.ItemSumB = null;
		this.SumDiv = null;
		this.TempTag = new Array();
		this.ItemSum = 0;

		this.AmysqlTagList.innerHTML = '';

		for (key in AmysqlTagWindow.AmysqlTabObject.Item)
		{
			this.TempTag[key] = [AmysqlTagWindow.AmysqlTabObject.Item[key].id, key];	// 存临时数组
		}
		// 排序
		this.TempTag.sort();

		for (key in this.TempTag )
		{
			++this.ItemSum;
			// 增加至列表
			this.AmysqlTagList.appendChild(CreateItem(AmysqlTagWindow.AmysqlTabObject.Item[this.TempTag[key][1]]));
		}
		
		this.SumDiv = C('DIV');
		this.ItemSumB = C('B');
		this.ItemSumB.innerHTML = this.ItemSum;

		SumText = document.createTextNode('Total: ');
		SumText2 = document.createTextNode(' tags');
		this.SumDiv.className = 'SumDiv';
		this.SumDiv.appendChild(SumText);
		this.SumDiv.appendChild(this.ItemSumB);
		this.SumDiv.appendChild(SumText2);

		this.AmysqlTagList.appendChild(this.SumDiv);
		document.body.innerHTML = '';
		C(document.body, 'In', [
			C('link',{'type':'text/css','rel':'stylesheet','href':'view/AMF/js/PlugIn/AllTag/style.css'}),
			C('h1','In', 'All Opened Tags'), 
			this.AmysqlTagList]
		);
	}

	document.body.innerHTML = '<div id="LoadingBlock">&nbsp; Loading...<div id="loading"></div></div>';
	setTimeout(
		function () {
				var GoShowObject = new GoShow();
		}, 50
	);
}