var navigationMenu = {
    $window: $('#main-content'),
    $contentFront: $('#content-front'),
    $hamburger: $('.hamburger'),
    offset: 1800,
    pageHeight: $('#content-front').outerHeight(),
    open: function () {
    	document.getElementById('main-content').scrollTop = 0;
        this.$window.addClass('tilt');
        this.$hamburger.off('click');
        $('#container, .hamburger').on('click', this.close.bind(this));
        this.hamburgerFix(true);
        console.log('opening...');
    },
    close: function () {
        this.$window.removeClass('tilt');
        $('#container, .hamburger').off('click');
        this.$hamburger.on('click', this.open.bind(this));
        this.hamburgerFix(false);
        console.log('closing...');
    },
    updateTransformOrigin: function () {
        scrollTop = this.$window.scrollTop();
        equation = (scrollTop + this.offset) / this.pageHeight * 100;
        this.$contentFront.css('transform-origin', 'center ' + equation + '%');
    },
    hamburgerFix: function (opening) {
        if (opening) {
            $('.hamburger').css({
                position: 'fixed',		/*fixed to move down*/
            });
        } else {
            setTimeout(function () {
                $('.hamburger').css({
                    position: 'fixed', 	/*fixed/absolute to move down*/
                });
            }, 300);
        }
    },
    bindEvents: function () {
        this.$hamburger.on('click', this.open.bind(this));
        $('.close-nav').on('click', this.close.bind(this));
        this.$window.on('scroll', this.updateTransformOrigin.bind(this));
    },
    init: function () {
        this.bindEvents();
        this.updateTransformOrigin();
    }
};
navigationMenu.init();