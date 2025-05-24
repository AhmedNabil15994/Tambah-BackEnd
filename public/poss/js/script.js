
(function ($) {
    'use strict';
    var $window = $(window);

    /*======================================
     Site Header
     ======================================*/

    $('.navbar-nav .nav-item .dropdown-item').on("click", function (e) {
        $('.navbar-collapse').removeClass('show');
    });
    $('.navbar-nav .nav-item a').on("click", function (e) {
        $('.navbar-collapse').removeClass('show');
    });
    $('.navbar-toggler').on("click", function (e) {
        $('.header-area').addClass('sticky');
    });
    $('.close-blk').on("click", function (e) {
        $('.alert').hide('slow');
    });
    $('.res-search').on("click", function (e) {
        $('.home-search').toggleClass('active');
    });
    $('.resmenu-btn').on("click", function (e) {
        $('.menu-header').toggleClass('active');
    });
//    $('.product-blk').on("click", function (e) {
//        swal({
//            title: "Good",
//            text: "this item is added Sucessfully",
//            type: "success",
//            showCancelButton: false,
//            closeOnConfirm: false,
//            animation: false,
//            customClass: {
//                popup: 'animated tada'
//            }
//
//        })
//    });
    $('.remove-item').on("click", function (e) {
        // swal({
        //     title: "",
        //     text: 'Are you sure removing this item?',
        //     type: "warning",
        //     showCancelButton: true,
        //     confirmButtonClass: "btn-danger",
        //     closeOnConfirm: false,
        //     animation: false,
        //     customClass: {
        //         popup: 'animated tada'
        //     }
        // })
    });
    // :: Sticky Active Code
    $window.on('scroll', function () {
        if ($window.scrollTop() > 0) {
            $('.header-area').addClass('sticky');
        } else {
            $('.header-area').removeClass('sticky');
        }
    });

    /*======================================
     ScrollIT
     ======================================*/
    $.scrollIt({
        upKey: 60, // key code to navigate to the next section
        downKey: 40, // key code to navigate to the previous section
        easing: 'linear', // the easing function for animation
        scrollTime: 600, // how long (in ms) the animation takes
        activeClass: 'active', // class given to the active nav element
        onPageChange: null, // function(pageIndex) that is called when page is changed
        topOffset: -70 // offste (in px) for fixed top navigation
    }
    );

    /*======================================
     WOW Animation
     ======================================*/
    var wow = new WOW({
        boxClass: 'wow', // animated element css class (default is wow)
        animateClass: 'animated', // animation css class (default is animated)
        offset: 0, // distance to the element when triggering the animation (default is 0)
        mobile: false, // trigger animations on mobile devices (default is true)
        live: true, // act on asynchronously loaded content (default is true)
        callback: function (box) {
        }
        , scrollContainer: true // optional scroll container selector, otherwise use window
    }
    );
    wow.init();

    $('.nice-select').niceSelect();
    $('.select').select2();
//     $(document).on('click', '.quantity .plus, .quantity .minus', function (e) {
// // Get values
//         var $qty = $(this).closest('.quantity').find('.qty'),
//                 currentVal = parseFloat($qty.val()),
//                 max = parseFloat($qty.attr('max')),
//                 min = parseFloat($qty.attr('min')),
//                 step = $qty.attr('step');
//         // Format values
//         if (!currentVal || currentVal === '' || currentVal === 'NaN')
//             currentVal = 0;
//         if (max === '' || max === 'NaN')
//             max = '';
//         if (min === '' || min === 'NaN')
//             min = 0;
//         if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN')
//             step = 1;
//         // Change the value
//         if ($(this).is('.plus')) {
//             if (max && (max == currentVal || currentVal > max)) {
//                 $qty.val(max);
//             } else {
//                 $qty.val(currentVal + parseFloat(step));
//             }
//         } else {
//             if (min && (min == currentVal || currentVal < min)) {
//                 $qty.val(min);
//             } else if (currentVal > 0) {
//                 $qty.val(currentVal - parseFloat(step));
//             }
//         }
// // Trigger change event
//         $qty.trigger('change');
//         e.preventDefault();
//     });
    $('#example').DataTable({
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    });
    $('[data-toggle="tooltip"]').tooltip();

})(jQuery);
$(document).ready(function () {
    $(".add-product").click(function () {
        $("#myToast").toast({delay: 4000});
        $("#myToast").toast({animation: true});
        $("#myToast").toast('show');
    });
});
$('.color').on('click', function (e) {
    $('.color').not(this).removeClass('selected');
    $(this).toggleClass("selected");
});
var elem = document.getElementById("displayfullscree");
function openFullscreen() {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) { /* Safari */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE11 */
        elem.msRequestFullscreen();
    }
}
// $('.choose-debit').on('click', function () {
//     $('.payment-option-content').removeClass('active');
//     $('.payment-option-content.debit-details').addClass('active');
// });
// $('body').on('click',".choose-bank", function () {
//     $('.payment-option-content').removeClass('active');
//     $('.payment-option-content.bank-details').addClass('active');
// });
// $("body").on('click',"'.choose-visa'", function () {
//     $('.payment-option-content').removeClass('active');
//     $('.payment-option-content.visa-details').addClass('active');
// });

// $('body').on('click', ".choose-paypal",function () {
//     $('.payment-option-content').removeClass('active');
//     $('.payment-option-content.paypal-details').addClass('active');
// });

$('body').on('click',".choose-cash", function () {
    $('.payment-option-content').removeClass('active');
});
$(document).ready(function () {

//success toast



    var options = {
        autoClose: true,
        progressBar: true,
        enableSounds: true,
        sounds: {
            info: "https://res.cloudinary.com/dxfq3iotg/video/upload/v1557233294/info.mp3",
// path to sound for successfull message:
            success: "https://res.cloudinary.com/dxfq3iotg/video/upload/v1557233524/success.mp3",
// path to sound for warn message:
            warning: "https://res.cloudinary.com/dxfq3iotg/video/upload/v1557233563/warning.mp3",
// path to sound for error message:
            error: "https://res.cloudinary.com/dxfq3iotg/video/upload/v1557233574/error.mp3",
        },
    };

    toast = new Toasty(options);
    toast.configure(options);

    $("body").on("click",'.add-product' ,function () {
       
        toast.success();

    });

    $('#infotoast').click(function () {

        toast.info();

    });

    $('#warningtoast').click(function () {

        toast.warning();

    });

    $('#errortoast').click(function () {

        toast.error();

    });

});

$(".modal").click(function(e){
    if(e.target != this) return;
    $('.modal').modal('hide');
  });