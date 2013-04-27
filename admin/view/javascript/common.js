var isIE = (document.all && window.ActiveXObject && !window.opera) ? true : false;
var isGecko = navigator.userAgent.indexOf('WebKit') != -1;

function $$(id) {
	return document.getElementById(id);
}

/**
 * function mycallback(status) {
 *	if (status == 0) //...  NO
 *  if (status == 1) // ... YES
 * }
 * 
 * function test() {
 * 	dConfirm('Sure?', mycallback);
 * }
 */
function dConfirm(code, callback, width) {
	if(!code) return;
	var width = width ? width : 350;
	code = code +'<p style="margin-top:5px;"><input type="button" class="btn" value="OK" onclick="closeDialog(1)"/>&nbsp;&nbsp;<input type="button" class="btn" value="Cancel" onclick="closeDialog(0);"/></p>';
	makeDialog('', code, '', width, 0, 0, 0, callback);
}

function dAlert(title, code, width, second) {
	if(!code) return;
	var second = second ? second : 0;
	code = code + '<p style="margin-top:5px;text-align:center;"><input type="button" class="btn" value="OK" onclick="closeDialog();"/></p>';
	makeDialog(title, code, '', width);
	if(second) window.setTimeout(function(){closeDialog();}, second * 1000);
}

function dInput(title, code, callback, width) {
	if(!code) return;
	code = code + '<p style="margin-top:5px;"><input type="button" class="btn" value="OK" onclick="closeDialog(1);"/></p>';
	makeDialog(title, code, '', width, 0, 0, 0, callback);
}

/**
 * title - dialog title
 * code - html code of the dialog
 * url - iframe src url
 * width - width of the dialog
 * height - iframe height
 * px, py - Postion: X point, Y point
 * callback - callback function, called when dialog closed
 */
function makeDialog(title, code, url, width, height, px, py, callback) {
	var w = width ? width : 400;
	var h = height ? height : 300;
	var u = url ? url : '';
	var c = code ? code : (u ? '<iframe src="'+u+'" width="'+(w-25)+'" height="' + height + '" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="yes"></iframe>' : '');
	var t = title ? title : 'Tip';
	var s = s ? s : 0;
	var px = px ? px : 0;
	var py = py ? py : 0;
	var cw, ch;
	var body = document.body;
	// if (isGecko) {
		// cw = document.documentElement.clientWidth;
		// ch = document.documentElement.clientHeight;
		
	// } else {
		// cw = body.clientWidth;
		// ch = body.clientHeight;
	// }
	cw = _clientWidth();
	ch = _clientHeight();
	var sl = px ? px : _scrollLeft() + parseInt((cw-w)/2);
	var st = py ? py : _scrollTop() + parseInt(ch/2) - 100;
	if (height) st = st - height / 2;
	if (st < 20) st = 20;
	var DIALOG = document.createElement("div");	
	with(DIALOG.style){zIndex = 999; position = 'absolute'; width = w+'px'; left = sl+'px'; top = st+'px'; if(isIE){filter = " Alpha(Opacity=0)";}else{opacity = 0;}}
	DIALOG.id = 'DIALOG';
	if (callback) DIALOG.callback = callback;
	document.body.appendChild(DIALOG);
	$$('DIALOG').innerHTML = '<div class="dbody"><div class="dhead" ondblclick="closeDialog();" onmousedown="dragstart(\'DIALOG\', event);"  onmouseup="dragstop(event);" onselectstart="return false;"><span onclick="closeDialog();">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;'+t+'</div><div class="dbox">'+c+'</div></div>';
	showDialog('DIALOG', 100, '+');
}

function closeDialog(status) {showDialog('DIALOG', 100,  '-', status);}

function showDialog(i, v, t, status) {
	if(t == '+') {
		if(isIE) {$$(i).style.filter = 'Alpha(Opacity='+v+')';} else {$$(i).style.opacity = v/100;}
		if(v == 100) {ElemHide(); return;}
		if(v+25 < 100) {window.setTimeout(function(){showDialog(i, v+25, t);}, 1);} else {showDialog(i, 100, t);}
	} else {
		try {
			$$(i).style.display = 'none';
			if ($$(i).callback) $$(i).callback(status);
			document.body.removeChild($$('DIALOG'));
			ElemShow();
		} catch(e){
			
		}
	}
}

function ElemHide(t) {
	var t = t ? t : 'select';
	if(isIE) {
		var arVersion = navigator.appVersion.split("MSIE"); var IEversion = parseFloat(arVersion[1]);		
		if(IEversion >= 7 || IEversion < 5) return;
		var ss = document.body.getElementsByTagName(t);					
		for(var i=0;i<ss.length;i++) {ss[i].style.visibility = 'hidden';}
	}
}

function ElemShow(t) {
	var t = t ? t : 'select';
	if(isIE) {
		var arVersion = navigator.appVersion.split("MSIE"); var IEversion = parseFloat(arVersion[1]);		
		if(IEversion >= 7 || IEversion < 5) return;
		var ss = document.body.getElementsByTagName(t);					
		for(var i=0;i<ss.length;i++) {ss[i].style.visibility = 'visible';}
	}
}

function dragstart(i, e) {
	dgDiv = $$(i);
	if(!e) {e = window.event;}
	dgX = e.clientX - parseInt(dgDiv.style.left);
	dgY = e.clientY - parseInt(dgDiv.style.top);
	document.onmousemove = dragmove;
}

function dragmove(e) {
	if(!e) {e = window.event;}
	dgDiv.style.left = (e.clientX - dgX) + 'px';
	dgDiv.style.top = (e.clientY - dgY) + 'px';
}

function dragstop() {
	dgX = dgY = 0;
	document.onmousemove = null;
}

function submit2Iframe(target, formid) {
	var iframe;  
    try {  
		iframe = document.createElement('<iframe name="' + target + '">');  
		} catch (ex) {  
		iframe = document.createElement('iframe');  
    }   
    iframe.id = target;  
    iframe.name = target;
    iframe.width = 0;  
    iframe.height = 0;  
    iframe.marginHeight = 0;  
    iframe.marginWidth = 0; 
	document.body.appendChild(iframe);
	$$(formid).submit();
}

function _clientWidth() {
	return _filterResults (
		window.innerWidth ? window.innerWidth : 0,
		document.documentElement ? document.documentElement.clientWidth : 0,
		document.body ? document.body.clientWidth : 0
	);
}
function _clientHeight() {
	return _filterResults (
		window.innerHeight ? window.innerHeight : 0,
		document.documentElement ? document.documentElement.clientHeight : 0,
		document.body ? document.body.clientHeight : 0
	);
}
function _scrollLeft() {
	return _filterResults (
		window.pageXOffset ? window.pageXOffset : 0,
		document.documentElement ? document.documentElement.scrollLeft : 0,
		document.body ? document.body.scrollLeft : 0
	);
}
function _scrollTop() {
	return _filterResults (
		window.pageYOffset ? window.pageYOffset : 0,
		document.documentElement ? document.documentElement.scrollTop : 0,
		document.body ? document.body.scrollTop : 0
	);
}
function _filterResults(n_win, n_docel, n_body) {
	var n_result = n_win ? n_win : 0;
	if (n_docel && (!n_result || (n_result > n_docel)))
		n_result = n_docel;
	return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
}

function formatUrl() {
	url = $('#seo_url').val();
	if (url != '') {
		url = url.replace(/[^a-z0-9\-\ ]/gi, '', url);
		url = $.trim(url);
		url = url.replace(/\s+/g, '-', url);
				
		$('#seo_url').val(url.toLowerCase());
	}
}

function image_upload(field, thumb, token) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=' + token + '&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: 'Image Manager',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=' + token + '&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};