import { request } from '../request/index'
module.exports = Behavior({
    data: {
        list: [],
        // url: '/project/list',  // 引用该文件的 文件中要有的
        params: {
            page: 1,
            limit: 20,
        },
        totalPages: 1, // 总页数
    },
    methods: {
        async getList() {
            let { url, params } = this.data
            let res = await request({
                url,
                data: params
            })
            let list = [...this.data.list, ...res.data]
            this.setData({
                list,
                totalPages: res.total
            })
        },
        // 下拉刷新
        pullDownRefresh() {
            console.log('刷新');
            this.setData({
                'params.page': 1,
                list: [],
            })
            this.getList()
        },
        // 触底加载下一页
        reachBottom() {
            console.log('触底');
            let { page } = this.data.params
            let { totalPages } = this.data
            if (page >= totalPages) {
                return
            } else {
                this.setData({
                    'params.page': ++page
                })
                this.getList()
            }
        },
    }
})