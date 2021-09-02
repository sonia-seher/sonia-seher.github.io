/*product-thumbs Product-detail-Homepage1*/

var zoomConfig = {
    galleryActiveClass: "active"
}
 var galleryTop_h1 = new Swiper('.gallery-top', {
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        spaceBetween: 10,
        loop:true,
        loopedSlides: 3, //looped slides should be the same
        onSlideChangeEnd: function (swiper) {
            var zoomImage = $('.gallery-top .swiper-slide:eq('+swiper.activeIndex+') .zoom_01');
            // Remove old instance od EZ
            $('.zoomContainer').remove();
            zoomImage.removeData('elevateZoom');
            zoomImage.data('zoom-image', zoomImage.data('zoom-image'));
            zoomImage.elevateZoom();
        }
    });
    var galleryThumbs_h1 = new Swiper('.gallery-thumbs', {
        spaceBetween: 10,
        slidesPerView: 3,
        touchRatio: 0.2,
        loop:true,
        loopedSlides: 3, //looped slides should be the same
        slideToClickedSlide: true,
        direction: 'vertical',
        height: $('.gallery-thumbs').parent().height()
    });
    galleryTop_h1.params.control = galleryThumbs_h1;
    galleryThumbs_h1.params.control = galleryTop_h1;