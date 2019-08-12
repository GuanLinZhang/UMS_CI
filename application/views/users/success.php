<div class="page msg_success js_show">
    <div class="weui-msg">
        <div class="weui-msg__icon-area"><i class="weui-icon-success weui-icon_msg"></i></div>
        <div class="weui-msg__text-area">
            <h2 class="weui-msg__title">注册成功</h2>
        </div>
        <div class="weui-msg__opr-area">
            <p class="weui-btn-area">
                <div class="weui-btn weui-btn_primary" id="doListUI_Btn">3秒后跳转</div>
            </p>
        </div>

    </div>
</div>
<script>
    $(function() {
        document.title = "跳转中...";
        $("#doListUI_Btn").on("click",function () {
            $("#container").load("list");
        });

        var times = 3;
        /**
         * 实现倒计时
         */
        let intervalID = setInterval(tickTock,1000);
        function tickTock() {
            if (times > 1) {
                $("#doListUI_Btn").text(--times + "秒后跳转");
            } else {
                clearInterval(intervalID);
                $("#container").load("list");
            }
        }

    });

</script>
