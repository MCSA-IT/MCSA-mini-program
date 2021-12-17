var myBehavior = require('../../lib/mixin')
import { request } from '../../request/index'
Page({

    behaviors: [myBehavior],
    data: {
        url: 'api/forum/announcement',
        menuList: [
            { text: '社区公告' },
            { text: '校友论坛' },
        ],
        params: {
            keyword: '', //搜索
        },
        current: 0,
        flag: false,
        searchValue: '',
        publishFlag: false,
        publishValue: '',
        publishOrReplay: false, // true是回复  false是发布
        qrList: [], //二维码列表
        moreFlag: false,
        forumId: '', //论坛id
        replayList: [], //评论列表
        replayId: '', //回复id
        showFlag: false,
        focus: false, //获取焦点
        // replayHeight: 0, //输入框高度
    },
    // 搜索
    handleSearch(e) {
        this.setData({
            'params.keyword': this.data.searchValue,
            list: [],
        })
        this.pullDownRefresh()
    },
    // 发布
    async publish(e) {
        if (this.data.publishOrReplay) {
            // 回复
            let { replayId } = this.data
            let res = await request({ url: 'api/forum/forumhfsave', data: { content: this.data.publishValue, forum_id: replayId } })
                // 刷新评论
            this.handleMore()
        } else {
            // 发布
            let res = await request({ url: 'api/forum/forumsave', data: { content: this.data.publishValue } })
        }
        this.setData({
            list: [],
            publishValue: '',
        })
        this.pullDownRefresh()
        console.log(this.data.publishValue);
    },
    // 切换至发布
    globalPublish(e) {
        this.setData({
            publishOrReplay: false,
            publishValue: '',
            focus: false,
        })
    },
    // 切换至回复
    handleReplay(e) {
        let forum_id = e.currentTarget.dataset.forumid
        this.setData({
            publishOrReplay: true,
            publishValue: '',
            replayId: forum_id,
            focus: true,
        })
    },
    // 查看更多（评论列表）
    async handleMore(e) {
        if (!e) {
            this.setData({
                replayList: result.data
            })
        } else {

            let forum_id = e.currentTarget.dataset.forumid
            let res = await request({ url: 'api/forum/search_hf', data: { forum_id } })
            this.setData({
                moreFlag: true,
                forumId: forum_id,
                replayList: res.data
            })
        }
    },
    handleMenu(e) {
        let { index } = e.target.dataset
        if (index == this.data.current) { return }
        if (index == 0) {
            this.setData({
                url: 'api/forum/announcement',
            })
        } else if (index == 1) {
            this.setData({
                url: 'api/forum/search',
            })
        }
        this.setData({
            current: index,
            list: [],
            'params.keyword': '',
        })
        this.pullDownRefresh()
    },
    handlePublish(e) {
        let { value } = e.detail
        if (value.length) {
            this.setData({
                publishFlag: true,
                publishValue: value
            })
        } else {
            this.setData({
                publishFlag: false
            })
        }
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
    async getList() {
        let { url, params } = this.data
        let res = await request({
            url,
            data: params
        })
        let list = [...this.data.list, ...res.data]
        this.setData({
            list,
            qrList: res.ewm_list,
            totalPages: res.total
        })
    },
    // 跳转到外链
    handleWeb(e) {
        let { src } = e.currentTarget.dataset
        wx.navigateTo({
            url: '/pages/webView/webView?src=' + src,
        });
    },

    onShow: function() {
        let token = wx.getStorageSync('token') || '';
        if (!token) {
            return
        }
        this.setData({ showFlag: true })

        this.setData({
            current: 0,
            url: 'api/forum/announcement',
        })
        this.pullDownRefresh()
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
        this.pullDownRefresh()
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {
        this.reachBottom()
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {

    }
})