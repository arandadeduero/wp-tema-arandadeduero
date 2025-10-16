jQuery(document).ready(function($){

    jQuery('.home-main-section-a').click(function (e) {
        e.preventDefault();
        var category = $(this).data('categoryid');
        $(".category-principal-sections").each(function( index ) {
            if($(this).hasClass('category-'+category)) {
                if ($('.category-'+category).css('display') != 'block'){
                    $(this).show("slow");
                } else {
                    $(this).hide("slow");
                }
            } else {
                $(this).hide("slow");
            }
        });
    });

    jQuery('#moreEspecial').click(function (e) {
        e.preventDefault();
        if($('.especial').hasClass('hide')){
            $('.especial').removeClass('hide');
            $('.especial').addClass('nohide'); 
        }else{
            $('.especial').removeClass('nohide');
            $('.especial').addClass('hide'); 
        }
    });


    jQuery('#ajax-load-more').bind('DOMSubtreeModified', function(){
        jQuery('.alm-reveal').addClass('row');
    });

    jQuery('.home-evento-descripcion').each(function(index) {    
        jQuery(this).find('.home-evento-fecha').addClass('home-evento-fecha-' + index);
        jQuery("head").append('<style>.home-evento-fecha-' + index+'::after {height: '+(jQuery(this).height() - 48)+'px;}</style>');
    
    });

    jQuery(".astm-search-menu" ).find( "a" ).append('<span class="d-none">Search</span>')


    jQuery( "#banner_streaming" ).click(function() {
        window.open("https://www.youtube.com/user/SMArandaDeDuero"); 
      });
});
    