<style>
    #user_img {
        width: 100px;
        height: 100px;
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        display: block;
    }
    #user_intro_area {
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
    }
    #user_intro {
        font: 400 13.3333px Arial;
    }
</style>
<div class="page panel js_show">
    <div class="page__hd">
        <h1 class="page__title">详情</h1>
    </div>
    <div class="page__bd">
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__hd" style="position: relative;margin-right: 10px;">
                    <img  id="user_img">
<!--                    <span class="weui-badge" style="position: absolute;top: -.4em;right: -.4em;">8</span>-->
                </div>
                <div class="weui-cell__bd">
                    <p id="user_name"></p>
                </div>
            </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label" for="username">创建人</label></div>
                        <div class="weui-cell__bd"><input class="weui-input" type="text" name="username" id="user_createdUser" readonly="readonly"></div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd">
                            <label class="weui-label">手机号</label>
                        </div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" id="user_tel" >
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label for="" class="weui-label">生日</label></div>
                        <div class="weui-cell__bd">
                            <label>
                                <input id="user_birth" class="weui-input" readonly="readonly">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="weui-cells__title">个人简介</div>
                <div class="weui-cells weui-cells_form" id="user_intro_area">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <textarea class="weui-textarea" rows="3" id="user_intro" readonly="readonly"></textarea>
                        </div>
                    </div>
                </div>
        </div>
        <button class="weui-btn weui-btn_cell-primary" id="goBack_btn">返回</button>
</div>
<script>
    $(function () {
        let id = "<?php echo $id ?>";
        console.log(id);
        getUserById(id);
        $("#goBack_btn").on("click",function () {
            $("#container").load("<?php echo site_url("users/list")?>");
        })
    });

    function getUserById(id) {
        $.ajax({
            url: "<?php echo site_url("users/") ?>" + id,
            dataType: "json",
            method: "get",
        }).done(function (res) {
            renderUserDetail(res.data[0]);
        })
    }

    function renderUserDetail(user) {
        console.log(user);
        $("#user_name").text(user.username);
        $("#user_img").attr("src",user.headimg);
        $("#user_tel").attr("href","tel:" + user.telephone);
        $("#user_tel").val(user.telephone);
        $("#user_birth").val(user.birth);
        $("#user_createdUser").val(user.createdUser);
        $("#user_intro").text(user.intro);
    }

</script>