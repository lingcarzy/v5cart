/************************************************
 *
 * Amysql Framework
 * Amysql.com 
 * @param AmysqlConfig 系统配置
 *
 */
var _HttpPath = '';
var _AmysqlContent;						// 框架内容
var _AmysqlTag;							// 框架标签
var _AmysqlLeft;						// 框架左栏
var _AmysqlContentLoad = false;
var _AmysqlTagLoad = false;
var _AmysqlLeftLoad = false;


// 设置默认打开的标签列表
var _AmysqlTabJson = [];

// 设置默认小标签列表
var _AmysqlTabMinJson = [];


// 设置左栏下拉菜单数据
var _AmysqlLNIJson = [];

// 设置左栏列表数据
var _AmysqlLeftListJson = [];