import { request } from '../../request/index'
Page({
    data: {
        banner: [],
        guide: {}, //指南
        activity: [],
        month: new Date().getMonth() + 1,
        day: new Date().getDate(),
        week: new Date().getDay(),
        current: 0,
    },
    handleSwiper(e) {
        let current = e.detail.current
        this.setData({ current })
    },
    // 跳转到外链
    handleWeb(e) {
        let { src } = e.currentTarget.dataset
        wx.navigateTo({
            url: '/pages/webView/webView?src=' + src,
        });
    },
    onShow: async function() {
        let res = await request({ url: 'api/index/index' })

        this.setData({
            banner: res.banner,
            guide: res.guide,
            activity: res.activity,
        })
    },
    onHide: function() {

    },
    onUnload: function() {

    },
    onPullDownRefresh: function() {

    },
    onReachBottom: function() {

    },
    onShareAppMessage: function() {

    },
    onPageScroll: function() {

    },
    //item(index,pagePath,text)
    onTabItemTap: function(item) {

    }
});