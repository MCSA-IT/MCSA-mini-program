import { request } from '../../request/index'
Page({

    /**
     * 页面的初始数据
     */
    data: {
        userInfo: {},
        card: null,
        about: {},
        guide: {},
    },
    // 跳转到外链
    handleWeb(e) {
        let { src } = e.currentTarget.dataset
        wx.navigateTo({
            url: '/pages/webView/webView?src=' + src,
        });
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
                url: '../my_ receive/my_ receive',
            });
        }
    },
    handleGetUserInfo(e) {
        wx.getUserProfile({
            desc: '登录',
            lang: 'zh_CN',
            success: (res) => {
                console.log(res);
                wx.login({
                    success: async(result) => {
                        let { code } = result
                        let newRes = await request({
                            url: 'api/wxapp/oauth',
                            data: {
                                code,
                                avatarUrl: res.userInfo.avatarUrl,
                                nickName: res.userInfo.nickName,
                                city: res.userInfo.city,
                                country: res.userInfo.country,
                                province: res.userInfo.province,
                                gender: res.userInfo.gender,
                            }
                        })
                        let token = newRes.token
                        wx.setStorageSync('token', token);
                        if (!newRes.mobile) {
                            // 电话为空 则进行电话解密
                            console.log('未绑定手机号');
                            wx.navigateTo({
                                url: '/pages/login/login',
                            });
                        } else {
                            console.log('已经绑定了手机号');
                            let tt = await request({ url: 'api/user/info' })
                            wx.setStorageSync('userInfo', tt);
                            wx.switchTab({
                                url: '/pages/index/index',
                            });
                        }
                    },
                });
            },
            fail: (err) => {
                console.log(err);
            },
        })
    },
    /**
     * 生命周期函数--监听页面显示
     */
    onShow: async function() {
        let res = await request({ url: 'api/index/info' }) // 外部链接
        let token = wx.getStorageSync('token');
        if (token) {
            let tt = await request({ url: 'api/user/info' })
            wx.setStorageSync('userInfo', tt);
        }
        let userInfo = wx.getStorageSync('userInfo') || {};
        this.setData({
            userInfo: userInfo.info,
            card: userInfo.card,
            about: res.about,
            guide: res.guide,
        })
    },
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {

    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {

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