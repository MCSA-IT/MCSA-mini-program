var myBehavior = require('../../lib/mixin')
Page({
    behaviors: [myBehavior],
    /**
     * 页面的初始数据
     */
    data: {
        flag: false,
        searchValue: '',
        userInfo: {},
        card: null,
        params: {
            title: '', //搜索
        },
        url: 'api/merchant/search',
        showFlag: false
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
    // 搜索
    handleSearch(e) {
        console.log(this.data.searchValue);
        this.setData({
            'params.title': this.data.searchValue
        })
        this.pullDownRefresh()
    },
    handleInput(e) {
        let { value } = e.detail
        if (value.length) {
            this.setData({
                flag: true,
                searchValue: value
            })
        } else {
            this.setData({
                flag: false
            })
        }
    },
    onTabItemTap() {
        console.log(2222);
        let token = wx.getStorageSync('token') || '';
        if (!token) {
            return
        }
        this.setData({ showFlag: true })
        let info = wx.getStorageSync('userInfo') || {}
        this.setData({
            userInfo: info.info,
            card: info.card,
            searchValue: '',
            'params.title': '',
        })
        this.pullDownRefresh()
    },
    onShow() {
        let token = wx.getStorageSync('token') || '';
        if (!token) {
            return
        }
        this.setData({ showFlag: true })
        let info = wx.getStorageSync('userInfo') || {}
        this.setData({
            userInfo: info.info,
            card: info.card,
            searchValue: '',
            'params.title': '',
        })
    },

    onPullDownRefresh: function() {
        this.pullDownRefresh()
    },

    onReachBottom: function() {
        this.reachBottom()
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {

    }
})