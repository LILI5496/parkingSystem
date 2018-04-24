<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>收藏</title>
    <base href="<?php echo site_url(); ?>"/>
    <link rel="stylesheet" href="css/reset.css"/>
    <link rel="stylesheet" href="css/collect.css"/>
    <link rel="stylesheet" href="css/mint-ui.min.css">


    <!-- 引入组件库 -->
    <script src="js/vue.min.js"></script>
    <script src="js/axios.min.js"></script>
    <script src="js/rem.js"></script>
</head>
<body>
    <div id="header">
    <a class="back" id="leftTopBtn" href="javascript:goBack()">&lt;&nbsp;停车场设计系统</a>
    <div class="title">
        收藏
    </div>
    <a href="javascript:;" class="menu-btn">
    </a>
    <div id="menu-top">
        <ul>
            <li class="menu-home">
                <i class="iconfont icon-home"></i>
                <a href="welcome/index" data-stat-label="首页">
                    首页
                </a>
            </li>
            <li class="menu-user">
                <i class="iconfont icon-about"></i>
                <a href="user"  data-stat-label="我的停车场">
                    我的停车场
                    <?php
                    $login_user = $this->session->userdata('userinfo');
                    if ($login_user) { ?>
                        (<em class="h-light">已登录</em>)
                    <?php } else { ?>
                        (<em class="h-light">未登录</em>)
                    <?php } ?>
                </a>
            </li>
            <li class="menu-favorites">
                <i class="iconfont icon-collect"></i><a href="welcome/collection_manage" data-stat-label="收藏">收藏</a>
            </li>
            <li class="menu-msg">
                <i class="iconfont icon-message"></i><a href="Welcome/message" data-stat-label="消息">消息</a>
                <span class="msg">&nbsp;</span>
            </li>
        </ul>
    </div>
</div>
    <div class="pro-container" id="app">
        <div class="pro-item" v-for='(park,index) in park_collection'>
            <a class="pro-box clearfix" :href="'park/detail/'+ park.park_id">
                <div class="fl p-img">
<!--                    --><?php //echo ADMINPATH?>
                    <img :src="'<?php echo ADMINPATH?>'+park.img_thumb_src" alt=""/>
                </div>
                <div class="fr p-info">
                    <h2 class="p-title">{{park.title}}</h2>
                    <div class="p-style p-address">地址：{{park.city}},{{park.region}},{{park.street}}</div>
                    <div class="p-style p-type">
                        车位：
<!--                        {{park.bedroom}}室{{park.livingroom}}厅{{park.lavatory}}卫-->
                        <span>一字型停车位</span>
                    </div>
                    <div class="p-btns-box">
                        <span class="p-btns p-collect" @click="delCollect(park.collect_id,index)">取消收藏</span>
                    </div>
                </div>
            </a>
            <div class="pro-date">
                收藏时间：{{park.collect_time}}
            </div>
        </div>
        <!--confirm弹框-->
        <div v-show="isShow">
            <div class="mint-msgbox-wrapper" style="position: absolute; z-index: 1011;"><div class="mint-msgbox"><div class="mint-msgbox-header"><div class="mint-msgbox-title">提示</div></div> <div class="mint-msgbox-content"><div class="mint-msgbox-message">确定取消收藏?</div> <div class="mint-msgbox-input" style="display: none;"><input placeholder="" type="text"> <div class="mint-msgbox-errormsg" style="visibility: hidden;"></div></div></div> <div class="mint-msgbox-btns"><button class="mint-msgbox-btn mint-msgbox-cancel " @click="isConfirm(false)">取消</button> <button class="mint-msgbox-btn mint-msgbox-confirm " @click="isConfirm(true)">确定</button></div></div></div>
            <div class="v-modal" style="z-index: 1010;"></div>
        </div>
    </div>


    <script src="js/zepto.js"></script>
    <script src="js/header.js"></script>
    <script>

        var vm = new Vue({
            el: '#app',
            data: {
                park_collection:<?php echo $park_collection?>,
                isShow:false,
                collectId:'',
                index:''
            },
            methods: {
                delCollect: function (collectId,index) {
                    var _this = this;
                    _this.collectId = collectId;
                    _this.index = index;
                    _this.isShow = true;
                },
                isConfirm:function(isGo){
                    var _this = this;
                    if(isGo){
                        axios.get('Welcome/delete_collection', {
                            params: {
                                orderType: _this.collectId
                            }
                        }).then(function (res) {
                            var result = res.data;
                            if(result == 'success'){
                                _this.park_collection.splice(_this.index,1);
                                _this.isShow = false;
                            }else{
                            }
                        });
                    }else{
                        _this.isShow = false;
                    }
                }
            }
        });

    </script>


</body>
</html>