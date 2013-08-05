$(document).ready(function() {
	
	tinymce.init({
	    selector: ".xml-field",
	    content_css: "/gamescom/src/assets/bootstrap/css/bootstrap.css",
	    width: '90%',
	    height: '400px'
	 });
	
	$('.dateField').datepicker(
	{
		format: 'yyyy-mm-dd',
		weekStart: 1
	});
	
	$('.has-tooltip').tooltip();
	
	$('.btn-confirm').on('click', function(event) {
		event.preventDefault();
		href = $(this).attr('href');
		if(confirm($(this).data('confirm'), 'Warning!')) {
			$(this).removeClass('btn-confirm');
			$.get(href, function (data, status) {
				location.reload();
			});
		}
	});
	
	$('.adminContentMenu').sortable({
		connectWith: ".adminContentMenu",
		update: sortUpdateCallback,
		start: function(event, ui) {
			$('.adminContentMenu').addClass('targetMenu');
		},
		stop: function(event, ui) {
			$('.adminContentMenu').removeClass('targetMenu');
		},
		placeholder: "ui-state-highlight",
	}).disableSelection();
	
	$('.collapse-toggle').on('click', function(event) {
		$($(this).data('target')+" > li.menuItem").toggle();
		var icon = $(this).find('i');
		changeToggleIcon(icon);
	});
	
	$('a.browser').on('click', function(event) {
		event.preventDefault();
		var browser = $(this).data('browser');
		
		var width = 450;
		var offset = width/2;
		$('#browser').css('width', width);
		$('#browser').css('margin-left', -offset);
		if(browser == 'page') {
			$('#browser').removeData('modal');
			$('#browser').modal({remote: '/gamescom/Browser/page'});
		} else {
			$('#browser').removeData('modal');
			$('#browser').modal({remote: $(this).attr('href')});
		}
	});
});

var sortUpdateCallback = function(event, ui) {
	var menu = {
			parent: $(this).attr('id'),
			menu: $(this).sortable('toArray')
	};
	
	console.log($($(this).parent()).find('i').removeClass('icon-minus'));
	
	var icon = $($(this).parent()).find('i');
	
	if(menu.menu.length > 0) {
		icon.removeClass('icon-minus');
		changeToggleIcon(icon);
	} else {
		icon.removeClass('icon-folder-open').removeClass('icon-folder-close');
		icon.addClass('icon-minus');
	}
	
	$.post('/gamescom/AdminNav/editContentTreeNav', menu, function(data) {
		console.log(data);
	});
};


var changeToggleIcon = function(icon) {
	if(!icon.hasClass('icon-minus')) {
		if(icon.hasClass('icon-folder-close')) {
			icon.removeClass('icon-folder-close').addClass('icon-folder-open');
		} else if(icon.hasClass('icon-folder-open')) {
			icon.removeClass('icon-folder-open').addClass('icon-folder-close');
		} else {
			icon.addClass('icon-folder-open');
		}
	}
}

var menuUpdateSuccess = function(data, status, header) {
	console.log(data);
}