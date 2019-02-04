//Copy paste this
//<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
//<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
//<script src="js/loader.js"></script>
//after <body>
//<div class="loader"></div>

  $(window).on("load", function() {
    // weave your magic here.
    $(".loaderCustom").fadeOut("slow");;
});

$(window).on("beforeunload", function() {
    $('.loaderCustom').fadeIn();
});
