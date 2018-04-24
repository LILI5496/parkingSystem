<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="李丽20145496"/>
    <meta name="description" content="李丽20145496"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>李丽20145496</title>
    <base href="<?php echo site_url(); ?>">
    <link rel="apple-touch-icon-precomposed" href="">
    <link rel="Shortcut Icon" type="image/x-icon" href="">
    <link rel="stylesheet" href="css/mint-ui.min.css">
    <link rel="stylesheet" href="css/about.css">
    <script src="js/vue.min.js"></script>
    <script src="js/mintUI.min.js"></script>
</head>
<body>

<div id="app" class="page-welcome">
    <mt-header title="关于停车场设计系统">
        <mt-button @click="javascript:window.history.go(-1);" icon="back" slot="left">返回</mt-button>
    </mt-header>
    <div style="height: 150px;">
        <mt-swipe :show-indicators="true" :auto="2500">
            <mt-swipe-item><img src="images/about/1.jpg" alt="">
            </mt-swipe-item>
            <mt-swipe-item><img src="images/about/2.jpg" alt="">
            </mt-swipe-item>
            <mt-swipe-item><img src="images/about/3.jpg" alt="">
            </mt-swipe-item>
        </mt-swipe>
    </div>
    <article>
        <div class="photo"><img src="images/about/4.jpg"></div>
        <div class="text">
            <h2>周边车位 一目了然</h2>
            <p>
                共建和谐小区停车场系统
            </p>
        </div>
    </article>

    <article>
        <div class="photo"><img src="images/about/5.jpg"></div>

    </article>

    <article class="list">
        <h2 class="title"><span>生活的艺术</span></h2>
        <div class="box">
            <div class="img"><img src="images/about/3.jpg" alt="">
            </div>
            <div class="txt">
                <ol>
                    <li>传统停车格局缺乏创新,单调</li>
                    <li>体会小区的温暖如家</li>
                </ol>
                <div class="comment">
                    <img src="images/about/2.jpg">
                    <div class="">
                        <p><span>王先生</span>2018-03-04</p>
                        <q>停车更便捷了,比传统的方式省时,操作简单,强力推荐</q>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <article class="list">
        <h2 class="title"><span>新奇、有趣、非常规</span></h2>
        <div class="box">
            <div class="img"><img src="https://staticfile.tujia.com/mobile/images/h5/promotion/yhjy/tujia2.jpg" alt="">
            </div>
            <div class="txt">
                <ol>
                    <li>对停车场而言，统计准确无误，避免逃费、露费等</li>
                    <li>对车主而言，有效解决排队缴费烦恼，一卡通行，畅通无阻</li>
                    <li>对物业而言，提升管理效率，业主满意度，也为物业品牌树立良好口碑</li>
                    <li>对城市环境而言，节能减排，降低空气、噪音的污染。</li>
                </ol>
                <div class="comment">
                    <img src="https://staticfile.tujia.com/mobile/images/h5/promotion/yhjy/user2.png">
                    <div>
                        <p><span>李女士</span>2017-03-18</p>
                        <q>很好,特别好</q>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <article class="list">
        <h2 class="title"><span>周租月租更省钱</span></h2>
        <div class="box">
            <div class="img"><img src="https://staticfile.tujia.com/mobile/images/h5/promotion/yhjy/tujia3.jpg" alt="">
            </div>
            <div class="txt">
                <ol>
                    <li>优雅的设计，减少生活的压力</li>
                    <li>便利的车位选择，节省出行时间</li>
                    <li>周租月租超低折扣，区别于传统停车场，越租越省钱</li>
                </ol>
                <div class="comment">
                    <img src="https://staticfile.tujia.com/mobile/images/h5/promotion/yhjy/user3.png">
                    <div>
                        <p><span>易先生</span>2017-04-21</p>
                        <q>unpredictable</q>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <article class="list">
        <h2 class="title"><span>平均花费便宜50%</span></h2>
        <div class="box">
            <div class="img"><img src="images/about/6.jpg" alt="">
            </div>
            <div class="txt">
                <ol>
                    <li>比传统停车节约一倍时间，更加减少人力资源</li>
                    <li>更多车位满足您的需求，高性价比让人印象深刻</li>
                    <li>新用户注册即送100元礼品卡，更有APP专享价等您预订</li>
                </ol>
                <div class="comment">
                    <img src="https://staticfile.tujia.com/mobile/images/h5/promotion/yhjy/user4.png">
                    <div>dockform-bg-div
                        <p><span>周女士</span>2014-08-01</p>
                        <q>很实惠，这么好的小区停车场，总之总体感觉很好，下次还会选择停在这里。</q>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <footer class="about-yueju">

        <div class="info">
            <div class="logo"><img src="images/logo.png" alt="停车场-logo"></div>
            <div class="name">XXX有限公司</div>
            <div class="hotphone">客服热线： <a href="tel:20134200">123456</a></div>
            <div class="link">联系我们：123456</div>
        </div>
    </footer>

    <a class="tool-search" href="<?php echo site_url(); ?>">查找<br/>车位</a>
</div>

<script>
    new Vue({
        el: '#app'
    });
</script>
</body>
</html>