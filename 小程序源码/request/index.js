// 同时发送异步代码的次数
let ajaxTimes = 0
export const request = (params) => {

    ajaxTimes++
    // 在请求之前显示 加载中
    wx.showLoading({
        title: '加载中',
        mask: true,
    });

    // 定义公共的url
    const baseUrl = getApp().globalData.baseUrl
    let token = wx.getStorageSync('token') || '';

    // 参数与token合并
    let data = Object.assign({ token }, params.data)

    return new Promise((resolve, reject) => {
        wx.request({
            ...params, // 对象的解构赋值
            data,
            header: {
                'content-type': 'application/x-www-form-urlencoded'
            },
            method: 'POST',
            url: baseUrl + params.url,
            success: (result) => {
                if (result.data.code == 0) {
                    if (result.data.count || result.data.count == 0) { resolve(result.data) } // 有条目数 ，需要分页
                    else { resolve(result.data.data) }
                }
                if (result.data.code == 1) {
                    wx.showToast({
                        title: result.data.msg,
                        icon: 'none',
                        duration: 1500,
                    });
                }
                // 登录失效
                if (result.data.code == -1) {
                    console.log(result.data.code);
                    wx.clearStorageSync();
                    wx.navigateTo({
                        url: '/pages/login/login',
                    });
                }
            },
            fail: (err) => {
                reject(err)
            },
            complete: () => {
                ajaxTimes--
                if (ajaxTimes === 0) {
                    // 无论请求成功或失败  都关闭  加载中
                    wx.hideLoading()
                }
                wx.stopPullDownRefresh() // 关闭下拉刷新
            }
        });
    })
}