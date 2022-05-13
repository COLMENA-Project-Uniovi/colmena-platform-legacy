
function loadIsotopeErrors(){
	 var $container = $('.bloques-errores');
      
      $container.isotope({
        sortBy : 'times',
        sortAscending : false,
        itemSelector : '.bloque-error.show',
        getSortData : {
          name : function( $elem ) {
           return $elem.find(".nombre-error").text();
          },
          message : function( $elem ) {
           	return $elem.find(".mensaje-error").text();
          },
          times : function( $elem ) {
          	
            return parseInt( $elem.find('.parametro-bloque-error.times').find('.cantidad-parametro-bloque-error ').text(), 10 );
          },
          timesuser : function( $elem ) {
            
            return parseInt( $elem.find('.parametro-bloque-error.timesuser').find('.cantidad-parametro-bloque-error ').text(), 10 );
          },
           users : function( $elem ) {
           
			return parseInt( $elem.find('.parametro-bloque-error.users').find('.cantidad-parametro-bloque-error ').text(), 10 );
          },
           subjects : function( $elem ) {
           
            return parseInt( $elem.find('.parametro-bloque-error.subjects').find('.cantidad-parametro-bloque-error ').text(), 10 );}
        }
      });
      
      
      var $optionSets = $('#options .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        
        var $this = $(this);
        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  
        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
         if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
          // changes in layout modes need extra logic
          changeLayoutMode( $this, options )
        } else {
          // otherwise, apply new options
          $container.isotope( options );
           console.log(key);
          if(key=="sortBy"){
           $container.find('.parametro-bloque-error').removeClass("current-sorting");
         	 $container.find('.parametro-bloque-error .'+value).addClass("current-sorting");
        	}
       }
        
        return false;
      });
}

function loadIsotopeUsers(){
	 var $container = $('.bloques-usuario');
      
      $container.isotope({
        sortBy : 'timestamp',
        sortAscending : false,
        itemSelector : '.bloque-usuario',
        getSortData : {
          name : function( $elem ) {
            return $elem.find(".nombre-usuario").text();
          },
          timestamp : function( $elem ) {
            return $elem.find(".timestamp-usuario").text();
          },
          constructor_related : function( $elem ) {
            return parseInt( $elem.find('.familia-bloque-usuario.constructor_related').find(".cantidad-familia-bloque-usuario").text(), 10 );
          },
           field_related : function( $elem ) {

            return parseInt( $elem.find('.familia-bloque-usuario.field_related').find(".cantidad-familia-bloque-usuario").text(), 10 );
          },
           import_related : function( $elem ) {
            return parseInt( $elem.find('.familia-bloque-usuario.import_related').find(".cantidad-familia-bloque-usuario").text(), 10 );
          },
           internal : function( $elem ) {
            return parseInt( $elem.find('.familia-bloque-usuario.internal').find(".cantidad-familia-bloque-usuario").text(), 10 );
          },
           javadoc : function( $elem ) {
            return parseInt( $elem.find('.familia-bloque-usuario.javadoc').find(".cantidad-familia-bloque-usuario").text(), 10 );
          },
           method_related : function( $elem ) {
            return parseInt( $elem.find('.familia-bloque-usuario.method_related').find(".cantidad-familia-bloque-usuario").text(), 10 );
          },
           syntax : function( $elem ) {
            return parseInt( $elem.find('.familia-bloque-usuario.syntax').find(".cantidad-familia-bloque-usuario").text(), 10 );
          },
           type_related : function( $elem ) {
            return parseInt( $elem.find('.familia-bloque-usuario.type_related').find(".cantidad-familia-bloque-usuario").text(), 10 );
          }
        }
      });
      
      
      var $optionSets = $('#options .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = $(this);
        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  
        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
         if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
          // changes in layout modes need extra logic
          changeLayoutMode( $this, options )
        } else {
          // otherwise, apply new options
          $container.isotope( options );
          console.log(key);
          if(key=="sortBy"){
        	 $container.find('.familia-bloque-usuario').removeClass("current-sorting");
         	 $container.find('.familia-bloque-usuario.'+value).addClass("current-sorting");
        	}
        }
        
        return false;
      });
}