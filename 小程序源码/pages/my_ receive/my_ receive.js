import { request } from '../../request/index'
Page({
    data: {
        position: [
            { value: '在读生' },
            { value: '毕业生' },
        ],
        majorList: [
            'Science',
            'Education',
            'Information Technology',
            'Engineering',
            'Law',
            'Marketing',
            'Management',
            'Economics',
            'Econometrics and business Statistics',
            'Banking and Finance',
            'Business',
            'Accounting',
            'Media,Film and Journalism',
            'Design',
            'Fine Art',
            'Architecture',
            'Arts',
            'Music',
            'Media',
            'Nursing',
            'Pharmacy and pharmaceutical sciences',
            'Occupational Therapy',
            'Physiotherapy',
            'Social Network',
            'Nutrition and Dietetics',
            'Medicine',
            '其他'
        ],
        majorList_two: [
            'Science',
            'Education',
            'Information Technology',
            'Engineering',
            'Law',
            'Marketing',
            'Management',
            'Economics',
            'Econometrics and business Statistics',
            'Banking and Finance',
            'Business',
            'Accounting',
            'Media,Film and Journalism',
            'Design',
            'Fine Art',
            'Architecture',
            'Arts',
            'Music',
            'Media',
            'Nursing',
            'Pharmacy and pharmaceutical sciences',
            'Occupational Therapy',
            'Physiotherapy',
            'Social Network',
            'Nutrition and Dietetics',
            'Medicine',
            '其他',
            '无'
        ],
        params: {
            img: '', // 头像
            name: '',
            birthday: '',
            discipline: '', //专业
            discipline_two: '', //专业2
            identity: '', //身份
            professional: '', // 职业
            region: '',
            email: '',
            year: '',
            graduation: '', //毕业时间
        },
        tempFilePaths: '',
    },
    // 提交
    submit() {
        let params = this.data.params
            // for (let key in params) {
            //     if (params[key] == '' && key != 'professional' && key != 'region' && key != 'discipline_two' && key != 'graduation') {
            //         wx.showToast({
            //             title: '请完善信息',
            //             icon: 'none',
            //             duration: 1500,
            //         });
            //         return
            //     }
            // }
            // 如果是在读生的话  把 职业 、地区、毕业年份都删了
        if (this.data.params.identity == '在读生') {
            this.setData({
                'params.professional': '',
                'params.region': '',
                'params.graduation': '',
            })
        }
        wx.showModal({
            content: '是否确认，确认后将无法更改',
            success: async(result) => {
                if (result.confirm) {
                    console.log('确定');
                    let res = await request({ url: 'api/user/save', data: this.data.params })
                    wx.showToast({
                        title: '审核中~',
                        icon: 'none',
                        duration: 1500,
                    });
                    setTimeout(() => {
                        wx.navigateBack({
                            delta: 1
                        })
                    }, 1000)
                }
            },
            fail: () => {},
            complete: () => {}
        });

    },
    // 上传头像
    handleChooseImage(e) {
        wx.chooseImage({
            count: 1,
            success: async(res) => {
                // 在请求之前显示 加载中
                wx.showLoading({
                    title: '上传中',
                    mask: true,
                });

                // tempFilePath可以作为img标签的src属性显示图片
                const tempFilePaths = res.tempFilePaths[0]

                wx.uploadFile({
                    url: getApp().globalData.baseUrl + 'api/common/image',
                    filePath: tempFilePaths,
                    name: 'image',
                    formData: {
                        'token': wx.getStorageSync('token')
                    },
                    success: (result) => {
                        console.log(JSON.parse(result.data).code);
                        if (JSON.parse(result.data).code == -1) {
                            wx.clearStorageSync();
                            wx.navigateTo({
                                url: '/pages/login/login',
                            });
                            return
                        }
                        if (JSON.parse(result.data).code == 1) {
                            wx.showToast({
                                title: JSON.parse(result.data).msg,
                                icon: 'none',
                                duration: 1500,
                            });
                        }
                        if (JSON.parse(result.data).code == 0) {
                            // 获取图片路径
                            let img = JSON.parse(result.data).data
                            this.setData({
                                'params.img': img,
                                tempFilePaths
                            })
                        }
                    },
                    complete: () => {
                        wx.hideLoading()
                    }
                })
            }
        })
    },
    // input输入
    handleInput(e) {
        let { type } = e.target.dataset
        let { value } = e.detail
        switch (type) {
            case '1': //姓名
                this.setData({
                    'params.name': value
                })
                break;
            case '3': // 职业
                this.setData({
                    'params.professional': value
                })
                break;
            case '4': // 邮箱
                this.setData({
                    'params.email': value
                })
                break;


        }
    },
    // picker选择
    handlePicker(e) {
        let { type } = e.target.dataset
        let { value } = e.detail
        switch (type) {
            case '1': //出生日期
                this.setData({
                    'params.birthday': value
                })
                break;
            case '2': //身份
                let position = this.data.position[value].value
                this.setData({
                    'params.identity': position
                })
                break;
            case '3': // 地区
                let region = value[0] + value[1] + value[2]
                this.setData({
                    'params.region': region
                })
                break;
            case '4': // 入学年份
                this.setData({
                    'params.year': value
                })
                break;
            case '5': //专业
                let discipline = this.data.majorList[value]
                this.setData({
                    'params.discipline': discipline
                })
                break;
            case '6': //专业2
                let discipline_two = this.data.majorList_two[value]
                this.setData({
                    'params.discipline_two': discipline_two
                })
                break;
            case '7': //毕业年份
                this.setData({
                    'params.graduation': value
                })
                break;
        }
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
     * 生命周期函数--监听页面显示
     */
    onShow: function() {

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