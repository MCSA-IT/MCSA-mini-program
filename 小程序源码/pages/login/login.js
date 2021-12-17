import { request } from '../../request/index'
Page({
    data: {
        flag: false,
    },
    // 登录
    handleGetUserInfo(e) {
        wx.getUserProfile({
            desc: '登录',
            lang: 'zh_CN',
            success: (res) => {
                wx.setStorageSync('userInfo', res.userInfo);
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
                            this.setData({ flag: true })
                        } else {
                            console.log('已经绑定手机号');
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
    // 绑定手机号
    async handleGetPhone(e) {
        if (e.detail.errMsg == "getPhoneNumber:fail user deny") {
            console.log("用户拒绝授权");
            wx.showToast({
                title: '请绑定手机号',
                icon: 'none',
                duration: 1500,
                mask: false,
            });
            return
        } else {
            // 允许授权
            console.log("手机号", e);
            let userInfo = await request({
                url: 'api/common/gettel',
                data: {
                    encryptedData: e.detail.encryptedData,
                    iv: e.detail.iv,
                }
            })
            wx.setStorageSync('userInfo', userInfo);
            wx.switchTab({
                url: '/pages/index/index',
            });
        }
    },
    //options(Object)
    onLoad: function(options) {

    },
    onReady: function() {

    },
    onShow: function() {
        let token = wx.getStorageSync('token');
        if (token) {
            // 说明已经登陆了  要绑定手机号
            this.setData({ flag: true })
        }
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