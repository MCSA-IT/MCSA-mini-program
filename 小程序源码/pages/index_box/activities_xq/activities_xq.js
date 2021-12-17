import { request } from '../../../request/index'
Page({

    /**
     * 页面的初始数据
     */
    data: {
        id: '',
        obj: {},
    },
    async getDetail() {
        let res = await request({ url: 'api/activity/detail', data: { id: this.data.id } })
        this.setData({ obj: res })
    },
    // 报名
    async handleSign(e) {
        let token = wx.getStorageSync('token') || '';
        if (!token) {
            wx.navigateTo({
                url: '/pages/login/login',
            });
            return
        }
        await request({ url: 'api/forum/signup', data: { activity_id: this.data.id } })
        wx.showToast({
            title: '报名成功',
            icon: 'none',
            duration: 1500,
            success: (result) => {
                this.getDetail()
            },
        });
    },
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        this.setData({ id: options.id })
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        this.getDetail()
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function() {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {

    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {

    }
})