var $ = mdui.$;

function setCookie(e, o) {
	var t = new Date;
	t.setTime(t.getTime() + 6048e5), document.cookie = e + "=" + o + ";SameSite=None;Secure;path=/;/expires=" + t.toGMTString()
}

function getCookie(e) {
	for (var o = e + "=", t = document.cookie.split(";"), a = 0; a < t.length; a++) {
		var r = t[a].trim();
		if (0 == r.indexOf(o)) return r.substring(o.length, r.length)
	}
	return !1
}

function light() {
	setCookie("darkMode", "off"), 
	$("#darktoggle_icon").html("brightness_4"), 
	$("body").removeClass("mdui-theme-layout-dark"),
	$("#nav-direction .mdui-card-content").addClass("mdui-text-color-black").removeClass("mdui-text-color-white")
}

function dark() {
	setCookie("darkMode", "on"), 
	$("#darktoggle_icon").html("brightness_5"), 
	$("body").addClass("mdui-theme-layout-dark"), 
	$("#nav-direction .mdui-card-content").removeClass("mdui-text-color-black").addClass("mdui-text-color-white")
}

function darkToggle() {
	if (("on" != getCookie("darkMode") ? dark : light)(), "undefined" != typeof autoDarkEnabled) {
		setCookie("autoDark", "off");
		var e = new Date;
		if (6 < e.getHours() && e.getHours() < 20) {
			var o = new Date(e.getFullYear(), e.getMonth(), e.getDate(), 20);
			setCookie("themeExpire", o.getTime())
		} else setCookie("themeExpire", (o = 20 <= e.getHours() ? new Date(e.getFullYear(), e.getMonth(), e.getDate() + 1, 7) :
			new Date(e.getFullYear(), e.getMonth(), e.getDate(), 7)).getTime())
	}
}

$(function() {
	getCookie("darkMode") || setCookie("darkMode", "off"), "undefined" != typeof autoDarkEnabled && (getCookie("autoDark") || setCookie("autoDark", "on"), autoDark()), "on" == getCookie("darkMode") && dark()
});
