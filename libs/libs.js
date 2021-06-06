$(document).ready(function() {
	$('#wmd-button-row').append(
		'<li class="wmd-button" id="post-button" title="引入文章">引入文章</li>' +
		'<li class="wmd-button" id="pinyin-button" title="拼音注解">拼音注解</li>'
	);
	if ($('#wmd-button-row').length !== 0) {
		$('#post-button').click(function() {
			var rs = '[cid="文章的cid"]';
			var myField = $('#text')[0];
			insertAtCursor(myField, rs);
		});
		$('#pinyin-button').click(function() {
			var rs = '{{拼音注解写法:pinyin}}';
			var myField = $('#text')[0];
			insertAtCursor(myField, rs);
		});
	}

	function insertAtCursor(myField, myValue) {
		//IE 浏览器  
		if (document.selection) {
			myField.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
			sel.select();
		}
		//FireFox、Chrome等  
		else if (myField.selectionStart || myField.selectionStart == '0') {
			var startPos = myField.selectionStart;
			var endPos = myField.selectionEnd;
			// 保存滚动条  
			var restoreTop = myField.scrollTop;
			myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos,
				myField.value.length);
			if (restoreTop > 0) {
				myField.scrollTop = restoreTop;
			}
			myField.focus();
			myField.selectionStart = startPos + myValue.length;
			myField.selectionEnd = startPos + myValue.length;
		} else {
			myField.value += myValue;
			myField.focus();
		}
	}

	$('#custom-field-expand').before('<div id="tdk" style="float: right;cursor: pointer;"><a>添加额外字段</a></div>');

	function attachDeleteEvent(el) {
		$('button.btn-xs', el).click(function() {
			if (confirm('确认要删除此字段吗?')) {
				$(this).parents('tr').fadeOut(function() {
					$(this).remove();
				});

				$(this).parents('form').trigger('field');
			}
		});
	}
	$('#tdk').click(function() {
		// 展开字段
		if ($('#custom-field').hasClass('fold')) {
			$('#custom-field').removeClass('fold');
			var btn = $('i', '#custom-field-expand');
			if (btn.hasClass('i-caret-right')) {
				btn.removeClass('i-caret-right').addClass('i-caret-down');
			}
		}

		//隐藏typecho的默认字段
		$("#custom-field").find("input[type='text']").each(function() {
			if ($(this).val() == "") {
				$(this).parent().parent().hide();
			}
		});
		//插入预设字段    
		var html = '<tr><td><label class="text-s w-100">文章描述</label></td>' +
			'<td><select name="fieldTypes[]" id="">' +
			'<option value="str">字符</option>' +
			'</select></td>' +
			'<td><textarea name="fields[d]" placeholder="文章描述" class="text-s w-100" rows="1"></textarea><p class="description">此处输入文章描述，用于展示给搜索引擎</p></td>' +
			'<td><button type="button" class="btn btn-xs">删除</button></td></tr>' +
			'<tr><td><label class="text-s w-100">文章关键词</label></td>' +
			'<td><select name="fieldTypes[]" id="">' +
			'<option value="str">字符</option>' +
			'</select></td>' +
			'<td><textarea name="fields[k]" placeholder="文章关键词" class="text-s w-100" rows="4"></textarea><p class="description">此处输入文章关键词，用于展示给搜索引擎</p></td>' +
			'<td><button type="button" class="btn btn-xs">删除</button></td></tr>',
			el = $(html).hide().appendTo('#custom-field table tbody').fadeIn();
		$(':input', el).bind('input change', function() {
			$(this).parents('form').trigger('field');
		});
		attachDeleteEvent(el);
	});
});
