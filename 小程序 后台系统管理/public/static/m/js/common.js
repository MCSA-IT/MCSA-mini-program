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

    // $('.navigation .navigation__active dl').slideDown();

    // $(".navigation>ul>li").click(function() {
    //     $(this).addClass('navigation__active').siblings().removeClass('navigation__active');
    //     let oIndex = $(this).index();
    //     console.log(oIndex);
    //     let oText = $('.navigation>ul>li>a>span').eq(oIndex).text()
    //     // console.log(oText)
    //     if (oText == '+') {
    //         $(".navigation ul>.navigation__active>a>span").eq(oIndex).text('-');
    //         $(this).siblings('li').removeClass('navigation__active').children('a').children('span').text('-');
    //         $('.navigation__active dl').slideDown().parent('li').siblings().find('dl').slideUp();
    //     }

    // });

    // $(".navigation>ul>li").click(function() {
    //     $(this).addClass('navigation__active').siblings().removeClass('navigation__active');
    //     let oText = $(this).children('a').children('span').text();
    //     if (oText === '+') {
    //         $(this).children('a').children('span').text('-').parents('li').siblings().children('a').children('span').text('+');
    //         $(this).find('dl').slideDown().parent('li').siblings().children('dl').slideUp();
    //         $(this).find('dd').find('span').text('+').parent().siblings('ol').slideUp();
    //     }
    // });
    $(".navigation ul>li>a").click(function() {
        var classname = $(this).parent().attr('class');
        if(classname == 'navigation__active'){
            $(this).parent().removeClass('navigation__active');
            let oIndex = $(this).parent().index();
            let oText = $(this).children('span').text()
            if (oText == '-') {
                $(this).children('span').text('+');
                $(this).siblings("dl").slideUp();
            }
        }else{
            $(this).parent().addClass('navigation__active').siblings().removeClass('navigation__active');
            let oIndex = $(this).parent().index();
            let oText = $(this).children('span').text()
            if (oText == '+') {
                $(this).children('span').text('-');
                $(this).parent().siblings('li').removeClass('navigation__active').children('a').children('span').text('+');
                $('.navigation__active dl').slideDown().parent('li').siblings().find('dl').slideUp();
            }
        }
    });

    $('.navigation ul>li:nth-of-type(5) dd>a').click(function() {
        let oText = $('.navigation ul>li:nth-of-type(5) dd>a>span').text();
        if (oText == '+') {
            $(this).siblings('ol').slideDown();
            $(".navigation ul>li:nth-of-type(5) dd>a>span").text('-');
        } else {
            $(".navigation ul>li:nth-of-type(5) dd>a>span").text('+');
            $(this).siblings('ol').slideUp();
        }
    });

    $(".open").click(function() {
        $('.navigation').stop().slideToggle();
        $('.shadow').stop().slideToggle();
    });

});