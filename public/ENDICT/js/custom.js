(function ($) {
    'use strict';

    $(document).on('ready', function () {
        
        // -----------------------------
        //  Screenshot Slider
        // -----------------------------
        $('.speaker-slider').slick({
            slidesToShow: 3,
            centerMode: true,
            infinite: true,
            autoplay: true,
            arrows:true,
            responsive: [
                {
                    breakpoint: 1440,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
         });
        // -----------------------------
        //  Count Down JS
        // -----------------------------
        $('.timer').syotimer({
            year: 2019,
            month: 8,
            day: 28,
            hour: 8,
            minute: 0,
            lang: "por"
        });
        // -----------------------------
        // To Top Init
        // -----------------------------
        $('.to-top').click(function() {
          $('html, body').animate({ scrollTop: 0 }, 'slow');
          return false;
        });
     

        // -----------------------------
        // Instahistory
        // -----------------------------
        feed();
       
    });
    function feed() {
        $('#feed').empty();
        $('#feed').instahistory({
            get: '#endict',
            imageSize: 640,
            limit: 30,
            template: '<div class="carousel-item"><img class="d-block" src="{{image}}"><p class="caption">{{caption}}</p></div>'
        });
    }
    setInterval(function(){
        feed()
    }, 60000);
})(jQuery);