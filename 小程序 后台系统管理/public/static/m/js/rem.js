(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var html = document.documentElement;
        var windowWidth = html.clientWidth;
        html.style.fontSize = windowWidth / 12.80 + 'px';
        // 12.42 就是设计图的宽度然后除以100  
        // 等价于html.style.fontSize = windowWidth / 640 * 100 + 'px';
    }, false);
})();