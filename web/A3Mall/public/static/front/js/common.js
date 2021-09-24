$(function (){
    $("img.lazy").lazyload({ effect:"fadeIn" });

    if($('.swiper-container')[0]){
        var swiper = new Swiper('.swiper-container', {
          slidesPerView: 1,
          spaceBetween: 30,
          loop: true,
          autoplay: {
            delay: 5000,
            disableOnInteraction: false
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true
          }
        });
    }

    var navPos = $(".navigation-wrap").offset();
    $(".welcome-notice").css({
        "left": (navPos.left + 1200 - $(".welcome-notice").width()) + "px",
        "top": (navPos.top + 38) + "px"
    }).show();

    $(".list-wares ul li,.sales-ranking-text ul li").on({
        mouseover: function (){
            $(".group-layer-box",this).stop(false,false).fadeIn();
        },
        mouseout: function (){
            $(".group-layer-box",this).stop(false,false).fadeOut();
        }
    });
    
    $(".product-categories").on("mouseover",function(){
        $(".category-menu-box").show();
    }).on("mouseout",function(){
        $(".category-menu-box").hide();
    });
    
    $(".wares-cent").on("mouseover",function(){
        $(this).find(".carte-combobox").show();
    }).on("mouseout",function(){
        $(this).find(".carte-combobox").hide();
    });
    
    $(".settlement-cart").on("mouseover",function (){
        $(".shopping-drop").show();
    }).on("mouseout",function (){
        $(".shopping-drop").hide();
    });
    
    $(".top-left li").on("mouseover",function (){
        var index = $(".top-left li").index($(this));
        $(".top-left li").removeClass("curren").eq(index).addClass("curren");
        $(".top-dropdown",this).show();
    }).on("mouseout",function (){
        $(".top-left li").removeClass("curren");
        $(".top-dropdown",this).hide();
    });
    
    $(".cosy-cent").on("mouseover",function (){
        $(".share-details").show();
    }).on("mouseout",function (){
        $(".share-details").hide();
    });
    
    $(".goods-tab-box").first().show();
    $(".product-commodity-title li").first().addClass("descri-curren");
    $(".product-commodity-title li").on("click",function (){
        var index = $(".product-commodity-title li").index($(this));
        $(".goods-tab-box").hide().eq(index).show();
        $(".product-commodity-title li").removeClass("descri-curren").eq(index).addClass("descri-curren");
    });
    
    $('.product-left-details').myZoom();
});


;(function ($) {

    $.fn.myZoom = function (){
        if ($(this).size() <= 0) return;
        $(".image-zoom").elevateZoom({
            scrollZoom: false, cursor: 'pointer',easing: true,
            zoomWindowFadeIn: 500, 
            zoomWindowFadeOut: 500, 
            zoomWindowWidth: 420,
            zoomWindowHeight: 420,
            borderSize: 0,lensBorderSize: 0,
            lensFadeIn: 200, 
            lensFadeOut: 200 
        });
       
        $('.details-small li').each(function (){
            $(this).on('mouseover',function (){
                var smallImage = $('img',this).attr('data-image');
                var largeImage = $('img',this).attr('data-zoom-image');
                var ez = $('.image-zoom').data('elevateZoom');
                ez.swaptheimage(smallImage, largeImage);
            });
        });
        $('.details-small').showImage();
    };
    
    $.fn.showImage = function (){
        if($(this).size() <= 0) return ;
        var pssListUl = $('ul',this);
        var pssListLi = $('ul li',this);
        var prev = $('.prev-btn');
        var next = $('.next-btn');
        var sWidth = pssListLi.outerWidth(true);
        var iNow=0;
        pssListUl.width(pssListLi.size() * (sWidth));
        
        var move = function(index) {
            pssListUl.stop().animate({
                left: -index * (sWidth)
            }, 450);
        };
       
        prev.click(function (){
            if(pssListLi.size() <= 5){
                 iNow--;
             }else if (iNow <= 0) {
                 return false;
             } else {
                 iNow--;
                 move(iNow);
             }
             return false;
         });
        
        next.click(function (){
            if (iNow == pssListLi.size() - 5) {
                return false;
            } else if(pssListLi.size() <= 5){
                return false;
            }else{
                iNow++;
            }
            move(iNow);
            return false;
        });
        
    };
    
})(jQuery);