var Menu = {
    menuSlided: false,
    slideMenuIn: function () {
        var translateLength=document.getElementById('sidemenu').clientWidth+'px';
        document.getElementById("sidemenu").style.transform = "translate("+translateLength+", 0px)";
        document.getElementById("sidemenu").style["-webkit-transform"] = "translate("+translateLength+", 0px)";

        document.getElementById("main").style.transform = "translate("+translateLength+", 0px)";
        document.getElementById("main").style["-webkit-transform"] = "translate("+translateLength+", 0px)";
        //transform: translateX(380px);
        this.menuSlided = true;
    },
    slideMenuOut: function () {
        document.getElementById("sidemenu").style.transform = "translate(0px, 0px)";
        document.getElementById("sidemenu").style["-webkit-transform"] = "translate(0px, 0px)";

        document.getElementById("main").style.transform = "translate(0px, 0px)";
        document.getElementById("main").style["-webkit-transform"] = "translate(0px, 0px)";
        this.menuSlided = false;
    },
    slideMenuToggle: function () {
        if (this.menuSlided)
            this.slideMenuOut();
        else
            this.slideMenuIn()
    }
};

window.addEventListener('load', function () {
    var touch = false;
    var startX = 0;

    // $('.menu-main .menu-item-with-submenu').each(function () {
    //     $(this).on('click', function () {
    //         var currentClass = $(this).attr('class');
    //         if (currentClass.indexOf('menu-opened') == -1) {
    //             $(this).addClass('menu-opened');
    //         } else {
    //             $(this).removeClass('menu-opened');
    //         }
    //     });
    // });

    // document.getElementsByClassName('page-sliding-aside-handle')[0].addEventListener('click', function () {
    //     Menu.slideMenuToggle();
    //     return false;
    // });

    document.addEventListener("touchmove", function (event) {
        var touches = event.touches;
        if (touch) {
            if ((touches[0].pageX - startX) < -100) {
                Menu.slideMenuOut();
            }
        }
    }, false);

    document.addEventListener("touchstart", function (event) {
        touch = true;
        startX = event.touches[0].pageX;
    }, false);

    document.addEventListener("touchend", function (event) {
        touch = false;
    }, false);

    [].forEach.call(document.getElementsByClassName('flickity-slider-enable'), function (a) {
        new Flickity(a, {
            contain: true,
            cellAlign: 'left',
            freeScroll: true,
            prevNextButtons: false,
            pageDots: false
        });
    });

    new Flickity(document.getElementsByClassName('slidertop')[0], {
        contain: true,
        wrapAround: true,
        prevNextButtons: false,
        autoPlay: true,
        pageDots: false
    });

});

