$(document).ready(function() {

    $(".guide .guide__title li").click(function() {
        let oIndex = $(this).index();
        $(this).addClass('guide__active').siblings('').removeClass('guide__active');
        $(".guide .guide__content .guide__heap").eq(oIndex).addClass('guide__active').siblings('').removeClass('guide__active');
    });

    $(".dynamic .dynamic__title li").click(function() {
        let oIndex = $(this).index();
        $(this).addClass('dynamic__active').siblings('').removeClass('dynamic__active');
        $(".dynamic .dynamic__content .dynamic__heap").eq(oIndex).addClass('dynamic__active').siblings('').removeClass('dynamic__active');
    });

    function getTime() {
        let oTime = new Date();
        let dd = String(oTime.getDate()).padStart(2, '0');
        let mm = String(oTime.getMonth() + 1).padStart(2, '0');
        let yyyy = oTime.getFullYear();
        oTime = yyyy + '年' + mm + '月' + dd + '日';
        return oTime;
    }

    function getWeekDate() {
        var now = new Date();
        var day = now.getDay();
        var weeks = new Array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
        var week = weeks[day];
        return week;
    }

    $('#nyr').text(getTime());
    $('#z').text(getWeekDate());

    $(window).scroll(function() {
        var h_num = $(window).scrollTop();
        if (h_num > 395) {
            $('header').addClass('fixednav');
        } else {
            $('header').removeClass('fixednav');
        }
    });

    $('.navigation .navigation__active dl').slideDown();

    $(".navigation ul>li>a").click(function() {
        var classname = $(this).parent().attr('class');
        if(classname == 'navigation__active'){
            $(this).parent().removeClass('navigation__active');
            let oIndex = $(this).parent().index();
            let oText = $('.navigation ul>li>a>span').eq(oIndex).text()
            if (oText == '-') {
                $(".navigation ul>li>a>span").eq(oIndex).text('+');
                $(this).parent().find('dl').slideUp();
            }
        }else{
            $(this).parent().addClass('navigation__active').siblings().removeClass('navigation__active');
            let oIndex = $(this).parent().index();
            let oText = $('.navigation ul>li>a>span').eq(oIndex).text()
            if (oText == '+') {
                $(".navigation ul>li>a>span").eq(oIndex).text('-');
                $(this).parent().siblings('li').removeClass('navigation__active').children('a').children('span').text('+');
                $('.navigation__active dl').slideDown().parent('li').siblings().find('dl').slideUp();
            }
        }
    });

    $('.navigation ul>li:nth-of-type(4) dd>a').click(function() {
        // $(this).siblings('ol').slideDown();
        let oText = $('.navigation ul>li:nth-of-type(4) dd>a>span').text();
        // console.log(oText)
        if (oText == '+') {
            $(this).siblings('ol').slideDown();
            $(".navigation ul>li:nth-of-type(4) dd>a>span").text('-');
        } else {
            $(".navigation ul>li:nth-of-type(4) dd>a>span").text('+');
            $(this).siblings('ol').slideUp();
        }
    });

    // function oNone() {
    //     let oDisblock = $('.navigation dd>a+ol').css('display');
    //     // console.log(oDisblock)
    //     if (oDisblock == 'none') {
    //         $('.navigation dd>a+ol').css('display', "none");
    //     } else {
    //         $('.navigation dd>a+ol').css('display', "block");
    //     }
    // }

    // oNone();
    $('.list__details .list__details__right li').on('click', function(event) {
        let oClass = $(this).attr('class');
        console.log(oClass);
        let oIndex = $(this).index();
        console.log(oIndex);
        $(this).parents('.list__details__title').siblings('.list__details__content').addClass(oClass);
        if (oIndex === 1) {
            $('.list__details__content').removeClass('fz16');
            $('.list__details__content').removeClass('fz14');
        }
        if (oIndex === 2) {
            $('.list__details__content').removeClass('fz19');
            $('.list__details__content').removeClass('fz14');
        }

        if (oIndex === 3) {
            $('.list__details__content').removeClass('fz19');
            $('.list__details__content').removeClass('fz16');
        }
    });

});