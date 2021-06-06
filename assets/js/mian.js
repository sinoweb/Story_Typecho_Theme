mdui.$(function() {
	window.onscroll = function() {
		var viewH = document.documentElement.clientHeight;
		var scroH = document.documentElement.scrollTop || document.body.scrollTop;
		if (scroH == 0) {
			mdui.$('#top').addClass('mdui-fab-hide');
		}
		if (scroH != 0) {
			mdui.$('#top').removeClass('mdui-fab-hide');
		}
		var contH = document.getElementsByClassName('container')[0].clientHeight;
		if (contH - viewH < scroH) {
			mdui.$('#top').addClass('mdui-fab-hide');
		}
	};
	mdui.$('.to-top').on('click', function(e) {
		var time = setInterval(function() {
			var Top = document.documentElement.scrollTop || document.body.scrollTop;
			if (Top === 0) {
				clearInterval(time);
			} else {
				window.scrollTo(0, top - 100);
			}
		}, 10);
	});
});
