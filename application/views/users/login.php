<style>
    .page__title {
        font-size: 40px;
        text-align: center;
        font-weight: bold;
    }
    .weui-btn-area {
        margin-top: 200px;
    }
    .weui-cells.weui-cells_form {
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
    }
</style>
<div class="page__hd">
    <h1 class="page__title" style="text-align: center;">登录</h1>
</div>
<div class="page__bd">
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label" for="username">用户名</label></div>
            <div class="weui-cell__bd"><input class="weui-input" type="text" name="username" id="username"></div>
        </div>

        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label" for="password">密码</label></div>
            <div class="weui-cell__bd"><input class="weui-input" type="password" name="password" id="password"></div>
        </div>

    </div>
    <div class="weui-btn-area">
        <button class="weui-btn weui-btn_primary" id="login_btn">登录</button>
        <button class="weui-btn weui-btn_cell-primary" id="goRegisterBtn">注册</button>
    </div>
</div>
<div class="page__ft j_bottom">
    <i class="novo_icon"></i>
</div>
<script>
    //when the window onload
    $(function () {
        // verifyUserStatus();
        $("#login_btn").on("click",loginBtnClicked);
        $("#goRegisterBtn").on("click",doRegisterUI);
    });

    function doRegisterUI() {
        $("#container").load("<?=site_url('users/doRegister');?>");
    }
    function loginBtnClicked() {
            loginBtnLoading(true,this);
            let username = $("#username").val();
            let password = $("#password").val();

            if (username == '' || password == '') {
                alert("用户名或密码不能为空");
                loginBtnLoading(false,this);
                return;
            }
            $.ajax({
                url: "<?=site_url('users/login');?>",
                dataType: "json",
                data: {
                    "username": username,
                    "password": password,
                },
                method: "POST",
                success: function (res) {
                    loginBtnLoading(false,this);
                    if (res.state === 1) {
                        $("#container").load("<?=site_url('users/list');?>");
                    } else {
                        alert("用户名或密码错误");
                        $("#username").val('');
                        $("#password").val('');
                        return;
                    }
                },
                fail: function (res) {
                    loginBtnLoading(false,this);
                    alert("未知错误500");
                },
            })
    }

    /**
     * set the loading icon start/stop
     * @param status true / false == turn on / off
     * @param btn the parent button
     */
    function loginBtnLoading(status,btn) {
        if(status) {
            let circle = $("<i class='weui-loading'></i>");
            $(btn).addClass("weui-btn_loading");
            $(btn).prepend(circle);
        } else {
            $(btn).removeClass("weui-btn_loading");
            $("i.weui-loading").remove();
        }
    }
</script>