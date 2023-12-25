const height = $('.header').height();
$('.header-fixed').height(height);

$('#menuSidebarOpen').click(function () {
    menuToggle();
});

$('#menuSidebarClose').click(function () {
    menuToggle();
});

$('#menuSidebarCover').click(function () {
    menuToggle();
});

$('#searchToggleOpen').click(function () {
    searchToggle();
});

$('#searchToggleClose').click(function () {
    searchToggle();
});


const menu = document.querySelector('.menu-responsive-content');

var tl = gsap.timeline();

tl.to(menu, {
    right: 0,
    duration: 0.2,
    opacity: 1
});

tl.reverse();

function menuToggle() {
    tl.reversed(!tl.reversed());
    $('.menu-sidebar').toggleClass('active');
    $('.menu-responsive-cover').toggleClass('active');
    if ($('.menu-sidebar').hasClass('active')) {
        $('body').css('overflow', 'hidden');
    } else {
        $('body').css('overflow', 'unset');
    }
}

var tl2 = gsap.timeline();
tl2.to('.search-dialog', {
    top: 0,
    duration: 0.2,
    opacity: 1
});
tl2.reverse();

function searchToggle() {

    tl2.reversed(!tl2.reversed());
    $('.search-dialog').toggleClass('active');
    if ($('.search-dialog').hasClass('active')) {
        $('body').css('overflow', 'hidden');
    } else {
        $('body').css('overflow', 'unset');
    }
}
