<style>
    .page__title {
        font-size: 40px;
        text-align: center;
        font-weight: bold;
    }
    .weui-btn-area {
        margin-top: 100px;
    }
    .weui-btn.weui-btn_cell-primary {
        display: inline-block;
    }
    #intro_area {
        font: 400 13.3333px Arial;
    }
    input[type="date" i] {
        font-size: inherit;
    }
    .weui-btn {
        display: inline-block;
        width: auto;
        position: relative;
        margin-top: 0px;
    }
    .weui-cells.weui-cells_form {
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
    }
    .weui-uploader__file {
        -webkit-border-radius: 15px;
        -moz-border-radius: 15px;
        border-radius: 15px;
    }
</style>
<div class="page__hd">
    <h1 class="page__title" style="text-align: center;">修改用户</h1>
</div>
<div class="page__bd">
    <div class="weui-cells__title">基本信息</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label" for="username">用户名</label></div>
            <div class="weui-cell__bd"><input class="weui-input" type="text" name="username" id="username" placeholder="请输入用户名"></div>
        </div>

        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label" for="password">密码</label></div>
            <div class="weui-cell__bd"><input class="weui-input" type="password" name="password" id="password" placeholder="请输入密码"></div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">手机号</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" type="tel" placeholder="请输入手机号" id="telephone_area"  onfocusout="checkTel(this)">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label for="" class="weui-label">生日</label></div>
            <div class="weui-cell__bd">
                <label>
                    <input id="date_area" class="weui-input" type="date" placeholder="请选择出生日期">
                </label>
            </div>
        </div>
    </div>
    <div class="weui-cells__title">个人简介</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <textarea class="weui-textarea" placeholder="请输入个人介绍" rows="3" id="intro_area"></textarea>
            </div>
        </div>
    </div>
    <div class="weui-cells__title">头像</div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <div class="weui-uploader">
                    <div class="weui-uploader__bd">
                        <ul class="weui-uploader__files" id="uploaderFilesList">
                        </ul>
                        <div class="weui-uploader__input-box">
                            <input id="uploaderFile" class="weui-uploader__input" name="uploaderFile"  type="file" accept="image/*" onchange="showThumbNail()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <button class="weui-btn weui-btn_cell-primary" id="goBack_btn" style="float: left;">返回</button>
        <button class="weui-btn weui-btn_warn" id="update_btn" style="float: right; margin-top: 0px; background-color: #ffffff;">更新</button>
    </div>
</div>
<script>
    $(function () {
        let id = "<?php echo $id ?>";
        console.log(id);
        getUserById(id);
        $("#goBack_btn").on("click",function () {
            $("#container").load("<?php echo site_url("users/list")?>");
        });
        $("#update_btn").on("click",function () {
            updateBtnClicked(id);
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
        $("#username").val(user.username);
        // $("#user_img").attr("src",user.headimg);
        createNewUploadImg($("#uploaderFilesList"),user.headimg);
        $("#telephone_area").val(setNullIfEmpty(user.telephone));
        $("#date_area").val(setNullIfEmpty(user.birth));
        $("#intro_area").text(setNullIfEmpty(user.intro));
    }

    function setNullIfEmpty(elem) {
        return elem === null || elem === '' ? "暂无" : elem;
    }
    function checkTel(num_area) {
        if (!isTelephone(num_area)) {
            alert("请输入正确的电话号");
        }
    }
    function updateBtnClicked(id) {
        let username = $("#username").val();
        let password = $("#password").val();
        let birth  = $("#date_area").val();
        let intro = $("#intro_area").val();
        let telephone = $("#telephone_area").val();
        let uploaderFile = $("#uploaderFile").get(0).files;
        console.log("file",uploaderFile);

        let formData = new FormData;
        formData.append("username",username);
        formData.append("password",password);
        formData.append("birth",birth);
        formData.append("intro",intro);
        formData.append("telephone",telephone);

        if (uploaderFile.length !== 0)  {
            formData.append("uploaderFile",uploaderFile[0]);
        }
        if (username === '' || password === '') {
            alert("用户名或密码不能为空");
            return;
        }

        $.ajax({
            url: "<?=site_url('users/update/');?>" + id,
            dataType: "json",
            data: formData,
            method: "POST",
            processData: false,
            contentType: false,
            cache: false,
            success: function (res) {
                if (res.state === 0) {
                    alert(res.message);
                    return;
                } else {
                    alert("更新成功");
                    $("#container").load("<?php echo site_url("users/list")?>");
                }
            },
            fail: function (res) {
                alert("未知错误");
            },
        })
    }

    function render() {
        $.ajax()
    }

</script>