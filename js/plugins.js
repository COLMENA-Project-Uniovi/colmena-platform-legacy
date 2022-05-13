function loadPlugins() {
	'use strict';
	//Submenu
	loadSubmenus();
	loadAccordion();

	//graficos de Google
	loadEfectosCharts();

	//filtro de sesion
	loadFiltroSesion();
  
	//isotope
	loadIsotopeUsers();
	loadIsotopeErrors();

	//search forms
	loadSearchErroresFamilies();

	//sort table
	sortTables();

	//selector de sesiones
	loadSelectable();

	//top 3 errores animation
	loadTopErrorAnimation();

	//user's seach
	loadSearch();

	// drag&drop 
	loadDnd();

	//SkillSet
	loadSkillSet();

	// botón de info en títulos
	loadInfo();

	// círculo de coeficiente colmena
	loadProgressCircle();

	// enlaces animados
	loadAnimatedLink();


	// admin actions
	loadAdminActions();
}

function loadAdminActions(){
	var $admin_bar = $('.admin-bar, .admin-stick');
	var $admin_close = $('.admin-close');
	var $admin_content = $('.admin-content');
	$admin_bar.find('.admin-button').on('click', function(){
		var data = $(this).data();
		$.ajax({
		  type: "POST",
		  url: "lib/admin/"+ data.url +".php",
		  data: { data: data }
		})
		  .done(function( msg ) {
		    $admin_content.html(msg);
			$admin_bar.hide();
			$admin_content.toggleClass('active');
		    addCloseEvents();
		    addSendConfirmationEvent();
		    loadSelectable(true);
		  });				
	})
}
function addSendConfirmationEvent(){
	var $admin_content = $('.admin-content');
	$admin_content.find('.send-notifications').on('click', function(){		
		var data = $(this).data();
		$admin_content.find('.selectable.selected').each(function(){
			var selectable = $(this);
			var data_user = selectable.data();
			selectable.removeClass('selected');
			selectable.addClass('inactive');
			selectable.unbind('click');
			selectable.html('<span class="loading"><i class="fa fa-spinner fa-spin"></i></span>');
			$.ajax({
			  type: "POST",
			  url: "lib/admin/"+ data.url +".php",
			  data: { user_id: data_user.user }
			})
			.done(function( msg ) {
			    selectable.html(msg);
		    	loadSelectable(true);
			});
		});
	});
	$admin_content.find('.send-report').on('click', function(){		
		var data = $(this).data();
		var $parent = $(this).parent();
		var email_subject = $('.admin-email-subject').html();
		var email_body = $('.admin-email-body').html();
		$parent.html('<div class="loading"><i class="fa fa-spinner fa-spin"></i></div>');
		$.ajax({
		  type: "POST",
		  url: "lib/admin/"+ data.url +".php",
		  data: { subject: email_subject, body: email_body, to: data.user }
		})
		.done(function( msg ) {
		    $parent.html(msg);
		});
	});
}
function addCloseEvents(){
	var $admin_bar = $('.admin-bar, .admin-stick');
	var $admin_close = $('.admin-close');
	var $admin_content = $('.admin-content');
	$admin_close.on('click', function(){		
		$admin_bar.show();
		$admin_content.toggleClass('active');		
	})

	if($admin_bar.size() > 0){
		$("body").keydown(function(e) {
			if($admin_content.hasClass('active'))
				if(e.keyCode == 27 || e.keyCode == 13){  // esc
					$admin_content.removeClass('active');
					$admin_bar.show();
					
				}
		});
	}
}

function loadAnimatedLink(){
	$('a[data-href]').on('click', function(){
		var target = $(this).attr('data-href');
		$('html,body').animate({
			scrollTop: $(target).offset().top
		}, 2000);
	})
}


function loadProgressCircle(){
	window.randomize = function() {
		$('.radial-progress').each(function() {
			var transform_styles = ['-webkit-transform', '-ms-transform', 'transform'];
      		$(this).find('span.big').fadeTo('slow', 1);
			var score = $(this).data('score');
			var index = parseInt((score / 25) + 1);
			$(this).addClass('color-' + index);
			var deg = ((score) / 100) * 180;
			var rotation = deg;
			var fill_rotation = rotation;
			var fix_rotation = rotation * 2;
			for(i in transform_styles) {
				$(this).find('.circle .fill, .circle .mask.full').css(transform_styles[i], 'rotate(' + fill_rotation + 'deg)');
				$(this).find('.circle .fill.fix').css(transform_styles[i], 'rotate(' + fix_rotation + 'deg)');
			}
		});
	}
	setTimeout(window.randomize, 200); 
}
function loadInfo(){
	$('.info').each(function(){
		var info = $(this).attr('data-info');
		$(this).append('<i class="fa fa-info-circle"></i>');
		$(this).append('<span class="data-info">' + info + '</span>');
		$(this).find('i').hover(function(){
			$(this).parent().toggleClass('active');
		});
	})
}

/*SKILLSET */

function loadSkillSet(){
	$(".skillset.main-skillset").each(function(){
		var skillset = $(this);

		var skillsetObject = [];
		var skillBlockArray = [];

		var skillsetData = skillset.next(".main-skillset-data");
		
		skillsetData.children(".skillset-block").each(function(){
			var skillSetBlock = $(this);
			var skillBlockArray = 
			{
				'headline' : skillSetBlock.find(".legend").html() + " (" + skillSetBlock.find(".total-errors").html() + "/100) - " + skillSetBlock.find(".family-average").html() ,
				'value' : skillSetBlock.find(".total-errors").html(),
				'average' : skillSetBlock.find(".average-errors").html(),
				'length' : 100
			}
			skillsetObject.push(skillBlockArray);
		});
		
		skillset.skillset({

			object:skillsetObject,
			duration:40

		});
	});

	$(".skillset.secondary-skillset, .skillset.single-skillset").each(function(){
		var skillset = $(this);
		
		var skillsetObject = [];
		var skillBlockArray = [];

		var skillsetData = skillset.next(".skillset-data");
		
		skillsetData.children(".skillset-block").each(function(){
			var skillsetBlock = $(this);
			var skillBlockArray = 
			{
				'headline' : skillsetBlock.find(".legend").html() ,
				'value' : skillsetBlock.find(".total-errors").html(),
				'average' : skillsetBlock.find(".average-errors").html(),
				'length' : 100
			}
			skillsetObject.push(skillBlockArray);
		});
		
		skillset.skillset({

			object:skillsetObject,
			duration:0

		});
	});
}

/*SUBMENU*/

function loadSubmenus(){
	'use strict';
	$("#menu-navegacion .desplegable").hover(function(){
		var elemento = $(this);
		var posicion = elemento.position();
		var height = elemento.outerHeight();
		var alto = posicion.top + height;
		var left = posicion.left;
		
		var submenu = elemento.children(".submenu");
		submenu.css("top",alto);
	
		submenu.show();
	}, function(){
		$(this).children(".submenu").hide();
	});
}

function loadAccordion(){
	'use strict'
	var parent = $('#accordion');
	parent.find('.title').click(function(){
		var title = $(this);
		$(this).next('.content').slideToggle(function(){
			title.toggleClass('active');
		});
	})
}

function sortTables(){
	var id_user = $('#ajax-result #id_user').val();
	var subjects_tables = $('#ajax-result #subjects_tables').val();
	$('table.sortable th[data-sort]').on('click', function(){		
		var url = $('table.sortable').attr('data-url');		
		var sortby = $(this).attr('data-sort');
		var desc = parseInt($(this).attr('data-desc'));
		if ($(this).hasClass('current'))
			desc = desc ? 0 : 1;
		$.ajax({
		  type: "POST",
		  url: "lib/sort/"+ url +".php",
		  data: { sortby: sortby, id: id_user, subjects_tables: subjects_tables, desc: desc }
		})
		  .done(function( msg ) {
		    $('#ajax-result').html(msg);
		  });
	})
}

function loadSelectable(multiple){
	multiple || ( multiple = false ); // asignar a multiple el valor por defecto 'false'	
	var selector = $('#selector');
	var selectables = selector.find('.selectable').not('.inactive');
	var select_all = selector.find('.select-all');
	var select_no_notified = selector.find('.select-no-notified');
	var hiddenData = selector.find('.hidden-data');
	var submitButton = selector.find('.submit-button');
	var compareButton = selector.find('.select-button span');
	
	function updateSubmitSelected(value){
		var selecteds = selector.find('.selected');		
		if(selecteds.size() > 0){
			var sessions_ids = [];
			selecteds.each(function(){
				sessions_ids.push($(this).attr('data-id'));
			});
			var ids = sessions_ids.join(',');
			if(selecteds.size() == 1){
				submitButton.html('<a href="./subjects-session/'+ ids + '"">Ver sesión</a>');
			}
			if(value != 0){
				compareButton.html('<a href="./subjects-multiple-sessions/' + ids + ',' + value + '"">Compara con</a>');
			}
			hiddenData.addClass('show');
		} else{
			hiddenData.removeClass('show');
		}

	}
	if(selectables.size() > 0){
		selectables.on('click', function(){
			if (!multiple)
				selectables.removeClass('selected');
			$(this).toggleClass('selected');
			updateSubmitSelected(0);
		});
		$('select').on('change', function(){			
			updateSubmitSelected($(this).val());
		});
	}
	if(select_all.size() > 0){
		select_all.on('click', function(){
			selectables.addClass('selected');
			$('.admin-inner').animate({
				scrollTop: $('.send-notifications').position().top
			}, 2000);
		});
	}
	if(select_no_notified.size() > 0){
		select_no_notified.on('click', function(){
			selectables.removeClass('selected');
			selectables.not('.notified').addClass('selected');
			$('.admin-inner').animate({
				scrollTop: $('.send-notifications').position().top
			}, 2000);
		});
	}
}

function loadTopErrorAnimation(){
	var tables_container = $('.two-tables');
	if( tables_container.size() > 0 ){
		tables_container.each(function(){
			var container = this;
			$(container).find('tr').hover(function(){
				var error = $(this).attr('data-error');				
				$(container).find('[data-error=' + error + ']').addClass('selected');
			}, function(){
				$(container).find('tr').removeClass('selected');
			})
		})
	}
}

function loadSearch(){
	var search = $('.search');
	if(search.size() > 0){
		var nav = $('nav');
		var top_search = nav.offset().top + nav.height();
		search.css('top', top_search);

		$(window).scroll(function(){			
			if($(window).scrollTop() >= top_search){
				search.css('position', 'fixed');
				search.css('top', 0);
			} else {
				search.css('position', 'absolute');
				search.css('top', top_search);
			}
		})
		
		search.find('.title').on( 'click' , function(){	
			search.toggleClass('active');		
			$(this).next('.content').toggleClass('active');
			$(this).next('.content').find('input').focus();

		})
		search.find('input').on( 'keyup', function(){
			var value = formatString(this.value.trim().toLowerCase());
			if(value.length > 2){
				$('.all-users').find('.user').fadeOut();
				$('.all-users').find('.user[class*='+value+']').each(function(){
					$(this).fadeIn();
				})
			} else {
				$('.all-users').find('.user').fadeIn();				
			}
		})
		$("body").keydown(function(e) {
			if($(search).find('.content').hasClass('active'))
				if(e.keyCode == 27 || e.keyCode == 13)  // esc
					$(search).find('.content').removeClass('active');
		});
	}
}

// Drag&drop auxiliar functions
function drag_start(event) {
    var style = window.getComputedStyle(event.target, null);
    if(style.getPropertyValue("top") == 'auto')
    	var top_data = (parseInt(style.getPropertyValue("height"),10) - event.clientY);
    else
    	var top_data = (parseInt(style.getPropertyValue("top"),10) - event.clientY);
    event.dataTransfer.setData("text/plain",
    top_data + ',' + event.target.id);
    // do droppable zones reachable
    $('.droppable').addClass('active');
} 
function drag_over(event) { 
    event.preventDefault(); 
    $(event.target).addClass('dragover');
    return false; 
} 
function drag_leave(event) { 
    event.preventDefault(); 
    $(event.target).removeClass('dragover');    
    return false; 
} 
function drop(event) { 
    var offset = event.dataTransfer.getData("text/plain").split(',');
    var dm = document.getElementById(offset[1]);
    dm.style.bottom = 'auto';
    dm.style.top = (event.clientY + parseInt(offset[0],10)) + 'px';
    event.preventDefault();
    return false;
} 
function drop_on_target(event) {
	var offset = event.dataTransfer.getData("text/plain").split(',');
    var dm = document.getElementById(offset[1]);
    dm.style.bottom = 'auto';    
    dm.style.top = event.target.offsetTop;
    $(dm).css('top', event.target.offsetTop)
    

    // hide droppable zones
    $(event.target).removeClass('dragover');
    $('.droppable').removeClass('active');
    event.preventDefault();
    return false;	
}

// load drag&drop
function loadDnd(){
	var draggable = $('*[draggable]'); 
	var droppable = $('.droppable'); 
	if(draggable.size() > 0){
		draggable.each(function(){
			this.addEventListener('dragstart',drag_start,false); 
		})
		if(droppable.size() > 0){
			droppable.each(function(){
				$(this).height(draggable.height());
				this.addEventListener('dragover',drag_over,false); 
				this.addEventListener('dragleave',drag_leave,false); 
				this.addEventListener('drop',drop_on_target,false);
			})
		} else {
			document.body.addEventListener('dragover',drag_over,false); 
			document.body.addEventListener('drop',drop,false);
		}
		 document.addEventListener("dragend", function( event ) {
	      // hide droppable zones
	      $('.droppable').removeClass('active');
	  }, false);
	}
}

function formatString(text) {
    var acentos = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüû";
    var original = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuu";
    for (var i=0; i<acentos.length; i++) {
        text = text.replace(acentos.charAt(i), original.charAt(i));
    }
    return text;
}

