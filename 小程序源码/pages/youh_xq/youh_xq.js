import { request } from '../../request/index'
Page({

    data: {
        id: '',
        obj: {},
        userInfo: {},
        card: null,
    },
    // 跳转到vip页面
    goVip() {
        let token = wx.getStorageSync('token') || '';
        if (!token) {
            wx.navigateTo({
                url: '/pages/login/login',
            });
            return
        }
        // 判断vip是不是在审核中
        if (this.data.card) {
            // vip在审核中
            wx.showModal({
                content: '您的会员卡正在审核中，请勿重复领取~',
                success: (result) => {
                    if (result.confirm) {
                        return
                    }
                },
            });
        } else {
            wx.navigateTo({
                url: '/pages/my_ receive/my_ receive',
            });
        }
    },
    handlePhone(e) {
        let { tel } = e.currentTarget.dataset
        wx.makePhoneCall({
            phoneNumber: String(tel)
        });
    },

    onLoad: function(options) {
        this.setData({
            id: options.id
        })
    },

    onShow: async function() {
        let res = await request({ url: 'api/merchant/detail', data: { id: this.data.id } })
        this.setData({ obj: res })
        let info = wx.getStorageSync('userInfo') || {}
        this.setData({
            userInfo: info.info,
            card: info.card,
        })
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