function multipleSessionsCharts(){
    /** FAMILIES ERRORS COMPARING SESSIONS **/
        var labels = [];
        var datasets = [];  
        $('#compare_sessiones_families .family-title').each(function(){
            labels.push($(this).html());
        });
        $('#compare_sessiones_families .session').each(function(){
            var name = $(this).find('.session_name').html();
            var data = {
                name: name,            
            };
            var data_array = [];
            $(this).find('.total').each(function(){
                data_array.push(parseInt($(this).html()));
            });
            data.data = data_array;
            datasets.push(data);
        })
        $('#compare_sessiones_families').highcharts({  
            colors: ['#94b6b5', '#744248', '#e8576a', '#f7a967', '#F0EF4D', '#666666', '#8dba79'],
            //colors: ['#F7A967', '#FE5F55', '#7FB685', '#9CAFB7', '#55568C', '#6369D1', '#4C2E05'],
            title: {
                text: ''
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 50,
                y: 10,
                floating: true,
                borderWidth: 1,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            xAxis: {
                categories:labels
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                shared: true                
            },
            credits: {
                enabled: false
            },
            series: datasets
        });
    /** END FAMILIES ERRORS COMPARING SESSIONS **/
}
function userCharts(){


        /** EVOLUTION OF USER THROUGH SESSIONS IN SUBJECT  **/
        var color_pattern = [];
        color_pattern['color-1'] = "#8dba79";
        color_pattern['color-2'] = "#C7C640";
        color_pattern['color-3'] = "#f7a967";
        color_pattern['color-4'] = "#e8576a";

        var color = "#8dba79";
        var total_ranking =  $('.get-ranking-color');
        if(total_ranking.hasClass("color-1")){
            color = color_pattern['color-1'];
        }else if(total_ranking.hasClass("color-2")){
            color = color_pattern['color-2'];
        }else if(total_ranking.hasClass("color-3")){
            color = color_pattern['color-3'];
        }else if(total_ranking.hasClass("color-4")){
            color = color_pattern['color-4'];
        }

    /** USER IN SUBJECT - COMPILATIONS IN SESSION  **/
    $('.compilations-in-session').each(function(){
        var my_data = [];
        $(this).find('.compilation').each(function(){
            var year = parseInt($(this).find('.year').html());
            var month = parseInt($(this).find('.month').html());
            var day = parseInt($(this).find('.day').html());
            var hour = parseInt($(this).find('.hour').html());
            var minute = parseInt($(this).find('.minute').html());
            var seconds = parseInt($(this).find('.seconds').html());
            var number = parseInt($(this).find('.number').html());
            
            var time = [Date.UTC(year, month, day, hour, minute, seconds), number]; 
            my_data.push(time);
        })
        $(this).highcharts({
            colors: [color],
            chart: {
                type: 'spline'
            },
            title: {
                text: ''
            },
            xAxis: {
                title: {
                    enabled: true,
                    text: 'Hora',
                    style: {
                        fontSize: '18px',
                    }
                },
                type: 'datetime',

                dateTimeLabelFormats : {
                    hour: '%I %p',
                    minute: '%I:%M'
                }
            },
            yAxis: {
                title: {
                    text: 'Errores por compilación',
                    style: {
                        fontSize: '18px',
                    }
                },
                min: 0,
                tickInterval: 1,
            },
            tooltip: {
                formatter: function() {
                    return ''+
                            "" +
                            this.y + ' errores a las '+ Highcharts.dateFormat('%I:%M:%S', this.x);
                }
            },

            plotOptions: {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Errores del usuario',
                data: my_data
            }]
        })
    })


        var labels = [];
        var datasets = [];  
        $('#user-evolution-subject .session_name').each(function(){
            labels.push($(this).html());
        });
        
        var data = {
            name: 'Usuario',
        };
        var data_array = [];
        $('#user-evolution-subject .session_errors').each(function(){
            data_array.push(parseInt($(this).html()));
        });
        data.data = data_array;
        datasets.push(data);
        var data = {
            name: 'Media',
        };
        var data_array = [];
        $('#user-evolution-subject .average_errors').each(function(){
            data_array.push(parseInt($(this).html()));
        });
        data.data = data_array;
        datasets.push(data);
        $('#user-evolution-subject').highcharts({  
           // colors: ['#94b6b5', '#744248', '#e8576a', '#f7a967', '#F0EF4D', '#666666', '#8dba79'],
            colors: [color, '#999999'],
            title: {
                text: ''
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 50,
                y: 10,
                floating: true,
                borderWidth: 1,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            xAxis: {
                categories:labels,
                title: {
                    text: 'Sesiones',
                    style: {
                        fontSize: '18px'
                    }
                },
            },
            yAxis: {
                min: 0,
                max: 100,
                minorGridLineWidth: 0,
                gridLineWidth: 0,
                alternateGridColor: '#f1f1f1',
                tickInterval: 10,
                title: {
                    text: 'Coeficiente Colmena',
                    style: {
                        fontSize: '18px',
                    }
                },
                plotLines: [{
                    color: '#ccc',
                    width: 1,
                    value: 50,
                    dashStyle: 'dash'
                },{
                    color: '#ccc',
                    width: 1,
                    value: 100,
                    dashStyle: 'dash'
                }]
            },
            tooltip: {
                shared: true                
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.5
                }
            },
            series: datasets
        });
    /** END EVOLUTION OF USER THROUGH SESSIONS IN SUBJECT  **/

    /** SPIDERWEB USERS SUMMARY **/
    var spiderweb = $('#users-summary-spiderweb');
    var subjects_names = [];
    var total_errors = [];
    var average_errors = [];
    spiderweb.find('.family-name').each(function(){
        subjects_names.push($(this).html());
    });
    spiderweb.find('.total-errors').each(function(){
        total_errors.push(parseInt($(this).html()));
    });

    spiderweb.find('.average-errors').each(function(){
        average_errors.push(parseInt($(this).html()));
    });
    spiderweb.highcharts({

        chart: {
            polar: true,
            type: 'line'
        },
        colors: [color, '#666666'],
        //colors: ['#94b6b5', '#744248', '#e8576a', '#f7a967', '#F0EF4D', '#666666', '#8dba79'],
        
        pane: {
            size: '80%'
        },

        title: {
            text: ''
        },

        xAxis: {
            categories: subjects_names,
            tickmarkPlacement: 'on',
            lineWidth: 0
        },

        yAxis: {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
            type: 'logarithmic'
        },

        tooltip: {
            shared: true,
            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
        },

        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },

        series: [{
            name: 'Usuario',
            data: total_errors,
            pointPlacement: 'on'
        }, {
            name: 'Media',
            data: average_errors,
            pointPlacement: 'on'
        }]

    });

    /** SUBJECTS USERS BAR CHART **/        
        $('.subject-user-chart').each(function(){
            var errors = {
                data: []
            };
            var total = {
                name: 'Totales',
                data: []
            };
            var data = [];
            var error_ids = [];          
            var data_array = [];              
            var colors_array = [];              
            $(this).find('.family').each(function(){
                var serie = {};
                serie.y = parseInt($(this).html());
                
                color = '#8dba79';
                if(serie.y >= 0)
                    color = '#e8576a';
                serie.color = color;
                data_array.push(serie);
                error_ids.push(($(this).next('.family-name')).html());
            });           
            data_array.data = colors_array;
            errors.data = data_array;      
            errors.name = 'Diferencia respecto a la media';
            data.push(errors);  
             $(this).highcharts({
                chart: {
                    type: 'bar'
                },
                title: {
                    text: '',
                    style: {
                        fontSize: '14px',
                    },
                    enabled: false,
                },
                xAxis: {
                    categories: error_ids,
                    gridLineWidth : 0,                                
                    lineWidth: 0,                    
                    title: {
                        text: ''
                    },
                    tickLength: 0
                                         
                },
                yAxis: {                         
                    gridLineWidth : 0,                                
                    lineWidth: 0,
                    labels: {
                        enabled: false
                    },                
                    title: {
                        enabled: false
                    },
                    plotLines: [{
                        color: '#999',
                        width: 1,
                        value: 0,
                        label: {
                            text: '',
                            rotation: 0,
                            verticalAlign: 'bottom'
                        }
                    }]
                },
                plotOptions: {
                    bar: {                                                
                        groupPadding: 0.1,
                        borderWidth: 0,
                        pointPadding: 0
                    }
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    valueSuffix: '%'
                },
                series: data
            });    
        });
}


function subjectCharts(){

$(function () {
		Highcharts.setOptions({
		    chart: {
		        style: {
		            fontFamily: 'inherit'
		        }
		    },
            colors: ['#94b6b5', '#744248', '#e8576a', '#f7a967', '#F0EF4D', '#666666', '#8dba79'],
		});
    /** ERRORS FROM SESSION AGAINST SUBJECT BAR CHART **/        
        var session_total = parseInt($('#session_total').html());
        var subject_total = parseInt($('#subject_total').html());        
        $('#chart-session-to-subject').highcharts({
            chart: {
                type: 'bar',
                backgroundColor: 'transparent'
            },
            title: {
                text: ''
            },
            legend: {
                enabled: false
            },
            xAxis: {
                categories: ['Total errors'],
                gridLineWidth : 0,                                
                lineWidth: 0,
                gridLineColor: 'transparent',
                labels: {
                    enabled: false
                },
                title: {
                    text: ''
                }
                
            },
            yAxis: {
                min: 0,
                max: subject_total,
                           
                gridLineWidth : 0,                                
                lineWidth: 0,
                labels: {
                    enabled: false
                },
                title: {
                    enabled: false
                }
            },
            plotOptions: {
                bar: {
                    grouping:false,
                    borderWidth: 0                    
                }

            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Asignatura',
                data: [subject_total]
            },{
                name: 'Sesión',
                data: [session_total]
            }],
             tooltip: {
                formatter: function () {
                    return this.series.name + ': ' + this.y + '%';
                }
            },
        });
    /** END ERRORS FROM SESSION AGAINST SUBJECT BAR CHART **/

	/** MORE ERRORS BAR CHART **/
        var baseLink = base+'errors/';    
        $('.chart-errors').each(function(){
    		var errors = [];
            var data = [];
            var error_ids = [];
    		var error_genders = [];

    		$(this).find('.error').each(function(){
    			errors.push($(this).html());
                error_ids[$(this).html()] = ($(this).siblings('.error-id')).html();                        
                error_genders[$(this).html()] = ($(this).next('.error-gender')).html();                        
            });     
            $(this).find('.totales').each(function(){
                data.push(parseInt($(this).html()));
            });
            var i = 0;	        
            var config = {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: errors,
                    labels: {
                         formatter: function() { 
                             if (error_genders[this.value] == 'warning') {
                                return '<a href="'+ baseLink + error_ids[this.value] +'"  style="fill: #C4C43F;">' +
                                this.value +'</a>';
                             } else{
                                return '<a href="'+ baseLink + error_ids[this.value] +'" style="fill: rgb(232,87,106);">' +
                                this.value +'</a>';
                                
                             }                       
                            }
                    },
                     title: {
                        text: 'Nombre del error',
                        style: {
                            fontSize: '18px',
                        }
                    },
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Veces que se ha generado',
                        style: {
                            fontSize: '18px',
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 100,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor || '#FFFFFF'),
                    shadow: true
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Ocurrencias',
                    data: data
                }]
            };
             $(this).highcharts(config);             
        })
    });
	/** END MORE ERRORS BAR CHART **/

    var baseLink = base+'users/';    

	/** USERS/FAMILIES ERRORS COLUMN CHART **/
		var users = [];
		var families = [];
		$('#chart-column .user').each(function(){
			users.push($(this).html());
		});		
		$('#chart-column .labels').each(function(){
			var familia = $(this).html();
			
			var data = {
				name: familia,
			
			};
			var data_array = [];
			$('#chart-column .total-'+familia).each(function(){
				data_array.push(parseInt($(this).html()));
			});
			data.data = data_array;
			families.push(data);
		})			        
		$('#chart-column').highcharts({
			credits: {
				enabled: false
			},
            chart: {
                type: 'column'
            },
            colors: ['#94b6b5', '#744248', '#e8576a', '#f7a967', '#F0EF4D', '#666666', '#8dba79'],
            title: {
                text: ''
            },
            xAxis: {
                categories: users,
                labels: {
                    formatter: function() {
                        return '<a href="'+ baseLink + this.value +'">'+
                            this.value +'</a>';
                    },
                    rotation: -90,
                    x: 4
                },
                title: {
                    text: 'Usuarios',
                    style: {
                        fontSize: '18px',
                    }
                },
                tickLength: 0
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Coeficiente Colmena',
                    style: {
                        fontSize: '18px',
                    }
                },
                stackLabels: {
                    enabled: false
                },
                max: 100,
                minorGridLineWidth: 0,
                gridLineWidth: 0,
                alternateGridColor: '#f1f1f1',
                tickInterval: 10,
            },
            legend: {
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 10,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    groupPadding: 0,
                    borderWidth: 0
                }

            },
            series: families
        });
    /** END USERS/FAMILIES ERRORS COLUMN CHART **/

    /** EVOLUTION OF ERRORS LINE CHART **/
		var labels = [];
		var datasets = [];	
		$('#chart-line .labels').each(function(){
			labels.push($(this).html());
		});
		$('#chart-line .family-title').each(function(){
			var familia = $(this).html();
			
			var data = {
				name: familia,
			
			};
			var data_array = [];
			$('#chart-line .total-'+familia).each(function(){
				data_array.push(parseInt($(this).html()));
			});
			data.data = data_array;
			datasets.push(data);
		})		
		$('#chart-line').highcharts({  
            colors: ['#94b6b5', '#744248', '#e8576a', '#f7a967', '#F0EF4D', '#666666', '#8dba79'],
            title: {
                text: ''
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 80,
                y: 160,
                floating: true,
                borderWidth: 1,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            xAxis: {
                categories:labels,
                title: {
                    text: 'Sesiones',
                    style: {
                        fontSize: '18px',
                    }
                }
            },
            yAxis: {
            	min: 0,
                max: 100,
                minorGridLineWidth: 0,
                gridLineWidth: 0,
                alternateGridColor: '#f1f1f1',
                tickInterval: 10,
                title: {
                    text: 'Coeficiente Colmena',
                    style: {
                        fontSize: '18px',
                    }
                },
                plotLines: [{
                    color: '#ccc',
                    width: 1,
                    value: 50,
                    dashStyle: 'dash'
                },{
                    color: '#ccc',
                    width: 1,
                    value: 100,
                    dashStyle: 'dash'
                }]
            },
            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.5
                }
            },
            series: datasets
        });
    /** END EVOLUTION OF ERRORS LINE **/

	/** ERRORS AND WARNING BY FAMILY COLUMN CHART  **/
		var labels = [];
		var warnings = [];
		var errors = [];
		$('#chart-bar .labels').each(function(){
			labels.push($(this).html());
		});
		$('#chart-bar .warnings').each(function(){
			warnings.push(parseInt($(this).html()));
		});
		$('#chart-bar .errors').each(function(){
			errors.push(parseInt($(this).html()));
		});
		$('#chart-bar').highcharts({
			colors: ['#e8576a', '#F0EF4D'],
			credits: {
				enabled: false
			},
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: labels                    
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                headerFormat: '<p style="text-align: center; border-bottom: solid 1px black;background-color:#333; color: #fff;">{point.key}</p>',
                pointFormat: '{series.name}: <b>{point.y}<br/>',
                footerFormat: '',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Errors',
                data: errors
    
            }, {
                name: 'Warnings',
                data: warnings
    
            }]
        });
    /** END ERRORS AND WARNING BY FAMILY COLUMN CHART  **/


	/** TOTAL ERRORS AND WARNING PIE CHART  **/
        var chart_area = $('.chart-area');
        chart_area.each(function(){
    		var total_warnings = parseInt($(this).find('#total-warnings').html());
    		var total_errors = parseInt($(this).find('#total-errors').html());
                // Build the pie
            $(this).highcharts({  
                colors: ['#e8576a', '#F0EF4D'],
                credits: {
                    enabled: false
                },
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false,                       
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Percentage',
                    data: [
                        ['Errors',   total_errors],    
                        ['Warnings', total_warnings]
                    ]
                }]
            });    
        
        });
		/** END TOTAL ERRORS AND WARNING  PIE CHART  **/
    

}
function loadEfectosCharts(){
	'use strict';
	$(".user-chart-container").hide();
	$(".user-chart-container:eq(0)").fadeIn();
	$(".bloque-seleccion.graficos-familia:eq(0)").addClass("actual");

	$(".bloque-seleccion.graficos-familia").click(function(){
		$(".bloque-seleccion.graficos-familia.actual").removeClass("actual");
		var nombreGrafico = $(this).attr("id");

		if(!$(".user-chart-container:visible").hasClass(nombreGrafico)){
			$(".user-chart-container:visible").fadeOut(function(){
				
				$(".user-chart-container."+nombreGrafico).fadeIn(function(){
					
				});
				loadUserCharts();
			});
			$(this).addClass("actual");
		}
	});
}

function loadFiltroSesion(){
	'use strict';
	$('input:checkbox').removeAttr('checked');
	
	$(".selector-filtro").click(function(){
/*
		var stringQueryBase = "./subjects/multiple-sessions/";
		var query = $(".boton-enlace a").attr("href");
		var endQuery= query.split("&").pop().split("=").pop();
		if(endQuery === stringQueryBase){
			endQuery = "";
		}
		console.log(endQuery);
		var stringQuery = "";
		
		var arrayIds = new Array();

		$(".selector-filtro:checked").each(function(){
			arrayIds.push($(this).val());
		})

		stringQuery += arrayIds.join();

		$(".boton-enlace a").attr("href", stringQueryBase+stringQuery+"/" + endQuery);
*/
		var cantidadSeleccionados = $(".selector-filtro:checked").length;
		if(cantidadSeleccionados >= 1){
			$(".boton-enlace").fadeIn();
			$(".bloque-limite-sesiones").fadeIn();
		}else{
			$(".boton-enlace").fadeOut();
			$(".bloque-limite-sesiones").fadeOut();
		}
	
	});
/*
	$("#limite-sesiones").change(function(){
		var query = $(".boton-enlace a").attr("href");

		var endQuery=query.split("/").pop();

		query = query.replace("/" + endQuery,"");

		var filterQuery = "/" + $(this).val();
		var newQuery = query + filterQuery;
		$(".boton-enlace a").attr("href", newQuery);

	});
*/
	var parent = $('.selector-multiples-sesiones');
	var stringQueryBase = "./subjects/multiple-sessions/";
	parent.find('.boton-enlace a').click(function(){
		var filtro = '';
		parent.find('.selector-filtro:checked').each(function(){
			filtro += $(this).val() + ',';
		})
		filtro = filtro.substring(0, filtro.length-1);
		var limite = parent.find("#limite-sesiones").val();
		var stringTotal = stringQueryBase + filtro + '/' + limite;

		$(this).attr('href',  stringTotal);

		return true;
	});
}

function loadSubjectCharts(){
	'use strict';
    loadFamilyErrorPieChart();
    loadMostErrorsUserBarChart();
    loadEvolutionAllSessionsLineChart();
}

function loadMultipleSessionsCharts(){
	'use strict';
    loadFamilyErrorPieChart();
    loadMostErrorsUserBarChart();
    loadEvolutionAllSessionsLineChart();
}

function loadSessionCharts(){
	'use strict';
    loadFamilyErrorPieChart();
    loadMostErrorsUserBarChart();
}

function loadUserCharts(){
	'use strict';
    loadEvolutionByFamilyLineChart();
    loadSessionFamilyErrorPieChart();
}

function loadEvolutionByFamilyLineChart(){
	'use strict';
	$(".datos-grafico-usuario.datos-familia").each(function(){
		var nombreContenedor = $(this).find(".user-chart").attr("id");
		
		var datos = [];

		var nombreSerie = $(this).find(".nombre-familia").html();

		var familias = ['Sesión', 'Media de asignatura',  'Usuario ' + nombreSerie + ' errores en esta asignatura'];

		datos.push(familias);

			

		$(this).children(".datos-grafico").each(function(){
			var errores = [];
	    	var nombre = $(this).find(".nombre-sesion-grafico p").html();
	    	var total = parseInt($(this).find(".totales-sesion-grafico p").html());
	    	var totalMedia = parseInt($(this).find(".media-totales-sesion-grafico p").html());
	    	errores[0] = nombre;
 	    	errores[1] = totalMedia;
 	    	errores[2] = total;
 	    	datos.push(errores);
	    });
		

	  	pintarGraficoLineas(datos, nombreContenedor, ['gray', 'green']);
	 });
}


function loadEvolutionAllSessionsLineChart(){
	'use strict';
	var datos = [];

	var familias = ['Session', 'Constructor', 'Field', 'Import', 'Internal', 'Method', 'Syntax', 'Type' /*, 'Javadoc',*/ ];

	datos.push(familias);

	$(".datos-grafico-sesion").each(function(){
		var errores = [];
		errores.push($(this).attr("id"));
		errores.push(0);
		errores.push(0);
		errores.push(0);
		errores.push(0);
		errores.push(0);
		errores.push(0);
		errores.push(0);
		//errores.push(0);
		
		var indices = [];
		indices['Constructor Related'] = 1;
		indices['Field Related'] = 2;
		indices['Import Related'] = 3;
		indices['Internal'] = 4;
	  indices['Method Related'] = 5;
		indices['Syntax'] = 6;
		indices['Type Related'] = 7;
    //indices['Javadoc'] = 8;
    
		$(this).children(".datos-errores-familias-juntas-grafico").each(function(){
	    	var nombre = $(this).children(".nombre-familias-juntas-grafico").html();
	   		var indice = indices[nombre];
	    	var total = parseInt($(this).children(".total-familias-juntas-grafico").html());
	    	errores[indice] = total;
	    });
		datos.push(errores);
	});

	pintarGraficoLineas(datos, 'evolucion_errores_familias_juntas_chart_div', false);

}

function pintarGraficoLineas(datos, id, colores){
	'use strict';
	
	 // Set chart options
    var options = {
		'legend'			: {position: 'top', textStyle: {fontSize: 10}},
		'width'				: 750,
   	'height'			: 330,
   	//'curveType'			: 'function',
   	'fontName'			: 'Conv_DINPro-Regular',
 		'pointSize'			: 5,
 		'vAxis'				: {title: 'Errores'},
 		'hAxis'				: {title: 'Nombre de la sesión'},
   	'chartArea'			: {width: 600}
      };

	if(colores != false){
		options = {
			'legend'			: {position: 'top', textStyle: {fontSize: 10}},
			'width'				: 750,
     	'height'			: 330,
     	//'curveType'			: 'function',
     	'fontName'			: 'Conv_DINPro-Regular',
     	'pontSize'			: 10,
     	'colors'			: colores,
     	'pointSize'			: 5,
     	'vAxis'				: {title: 'Errores'},
 			'hAxis'				: {title: 'Nombre de la sesión'},
     	'chartArea'			: {width: 600}
        };
	}

    var data = google.visualization.arrayToDataTable(datos);

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.LineChart(document.getElementById(id));

    chart.draw(data, options);
}

function loadFamilyErrorPieChart(){
	'use strict';
	var datos = [];
    datos.push(['Family', 'Number of Errors'])
    $(".datos-errores-familia-grafico").each(function(){
    	var nombre = $(this).children(".nombre-familia-grafico").html().replace("Related", "");
    	var total = parseInt($(this).children(".total-familia-grafico").html());
    	var encontrado = false;

    	for(var i =0 ; i<datos.length && !encontrado; i++){
    		if(datos[i][0] === nombre){
    			datos[i][1] += total;
    			encontrado = true;
    		}
    	}
    	if(!encontrado){
    		datos.push([nombre, total]);	
    	}
    });

	datos.sort(function(a,b) {
	    return a[0] - b[0];
	});
 
  	var data = google.visualization.arrayToDataTable(datos);

    // Set chart options
    var options = {
    				'legend'			: {position: 'top', textStyle: {fontSize: 10}},
    				'width'				: 750,
                   	'height'			: 230,
                   	'fontName'			: 'Conv_DINPro-Regular',
                   	'chartArea'			: {width: 650}
                  };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.ColumnChart(document.getElementById('errores_familia_chart_div'));
    chart.draw(data, options);
}

function loadSessionFamilyErrorPieChart(){
	'use strict';
	$(".datos-usuario-errores-familia-sesion").each(function(){
		var datos = [];
  		datos.push(['Family', 'Errors'])
	
		var contenedor = $(this).find(".pie-session-chart");
		var nombreContenedor = contenedor.attr("id");
		
	    $(this).children(".datos-errores-familia-grafico").each(function(){
	    	var nombre = $(this).children(".nombre-familia-grafico").html().replace("Related", "");
	    	var total = parseInt($(this).children(".total-familia-grafico").html());
	    	var encontrado = false;

	    	for(var i =0 ; i<datos.length && !encontrado; i++){
	    		if(datos[i][0] === nombre){
	    			datos[i][1] += total;
	    			encontrado = true;
	    		}
	    	}
	    	if(!encontrado){
	    		datos.push([nombre, total]);	
	    	}
	    });

		datos.sort(function(a,b) {
		    return a[0] - b[0];
		});
	 
	  	var data = google.visualization.arrayToDataTable(datos);

	    // Set chart options
	    var options = {
	    				'legend'			: {position: 'top', textStyle: {fontSize: 10}},
	    				'width'				: 230,
	                   	'height'			: 230,
	                   	'fontName'			: 'Conv_DINPro-Regular',
	                   	'chartArea'			: {width: 200}
	                  };

	    // Instantiate and draw our chart, passing in some options.
	    var chart = new google.visualization.PieChart(document.getElementById(nombreContenedor));
	    chart.draw(data, options);

	});    

}

function loadMostErrorsUserBarChart(){
	'use strict';
	var datos = [];
    datos.push(['User', 'Errors'])
    $(".datos-errores-usuario-grafico").each(function(){
    	var nombre = $(this).children(".nombre-usuario-grafico").html();
    	var total = parseInt($(this).children(".total-errores-grafico").html());
    	var encontrado = false;

    	for(var i =0 ; i<datos.length && !encontrado; i++){
    		if(datos[i][0] === nombre){
    			datos[i][1] += total;
    			encontrado = true;
    		}
    	}
    	if(!encontrado){
    		datos.push([nombre, total]);	
    	}    		
    });

 	datos.sort(function(a,b) {
	    return b[1] - a[1];
	});

  	var data = google.visualization.arrayToDataTable(datos);
  	
    // Set chart options
    var options = {
    				'legend'			: {position: 'top', textStyle: {fontSize: 10}},
    				'width'				: 230,
    				'fontName'			: 'Conv_DINPro-Regular',
                   	'height'			: 400
    				
                  };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.BarChart(document.getElementById('errores_usuario_chart_div'));
    chart.draw(data, options);
    

    var handler = function(e) {
		var sel = chart.getSelection();
    	sel = sel[0];

    if (sel && sel['row']+1 && sel['column']) {
      var link = datos[sel['row']+1][0];
      window.location.href = './users/' + link;
    }
  
  }
  google.visualization.events.addListener(chart, 'select', handler);
}
