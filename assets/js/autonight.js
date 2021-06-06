function autoDark() {
	var o = new Date;
	"off" == getCookie("autoDark") && getCookie("themeExpire") < o.getTime() && setCookie("autoDark", "on"), "on" ==
		getCookie("autoDark") && (o.getHours() <= 6 || 22 <= o.getHours() ? dark : light)()
}
var autoDarkEnabled = 1;