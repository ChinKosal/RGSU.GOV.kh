$(document).ready(function () {
    $(".switch-lange").click(function (e) {
        e.stopPropagation(); // Prevent the click event from reaching the body
        $(".sub-language").toggle(); // Toggle the visibility of sub-language
    });
    $(".dropdown-activi").click(function (e) {
        e.stopPropagation(); // Prevent the click event from reaching the body
        $(".item-drop").toggle(); // Toggle the visibility of sub-language
    });

    // Close sub-language when clicking outside of it
    $("body").click(function () {
        if ($(".sub-language").is(":visible")) {
            $(".sub-language").hide();
        }
        if ($(".item-drop").is(":visible")) {
            $(".item-drop").hide();
        }
    });
});
