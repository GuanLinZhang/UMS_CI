
<div class="page panel js_show">
    <div class="page__hd">
        <h1 class="page__title">用户列表</h1>
    </div>
    <div class="weui-search-bar" id="searchBar">
        <form class="weui-search-bar__form" onsubmit="return false">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required="" onsearch="search()">
                <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
            </div>
            <label class="weui-search-bar__label" id="searchText" style="transform-origin: 0px 0px; opacity: 1; transform: scale(1, 1);">
                <i class="weui-icon-search"></i>
                <span>搜索</span>
            </label>
        </form>
        <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
    </div>
    <div class="page__bd" id="users_container">

    </div>
    <div>
        <div class="weui-mask" id="iosMask" style="display: none"></div>
        <div class="weui-actionsheet" id="iosActionsheet" style="border-top-left-radius: 2em;border-top-right-radius:2em;">
            <div class="weui-actionsheet__title" style="border-top-left-radius: 2em;border-top-right-radius:2em;">
                <p class="weui-actionsheet__title-text">操作</p>
            </div>
            <div class="weui-actionsheet__menu">
                <div class="weui-actionsheet__cell" id="detail_btn">查看此用户</div>
                <div class="weui-actionsheet__cell" id="update_btn">更新资料</div>
                <div class="weui-actionsheet__cell" style="color: red" id="del_btn">删除</div>
            </div>
            <div class="weui-actionsheet__action">
                <div class="weui-actionsheet__cell" id="iosActionsheetCancel">取消</div>
            </div>
        </div>
    </div>
    <div class="weui-btn-area" style="margin-top: 0px;">
        <button class="weui-btn weui-btn_cell-primary" id="goBack_btn">返回</button>

    </div>
</div>
<script>

    $(function(){
        getUsers();
        let $searchBar = $('#searchBar'),
            $searchResult = $('#searchResult'),
            $searchText = $('#searchText'),
            $searchInput = $('#searchInput'),
            $searchClear = $('#searchClear'),
            $searchCancel = $('#searchCancel');
            $("#goBack_btn").on("click",function () {
                $("#container").load("<?php echo site_url("")?>");
            });
        //
        // $(document).on("click",'.weui-cell.weui-cell_access.weui-cell_link',
        //     function(){
        //         // var target = event.currentTarget;
        //         console.log($(event.currentTarget).text());
        //
        //         // showActionSheet(target);
        //     }
        //  );

        function hideSearchResult(){
            $searchResult.hide();
            $searchInput.val('');
        }


        function cancelSearch(){
            hideSearchResult();
            $searchBar.removeClass('weui-search-bar_focusing');
            $searchText.show();
        }

        $searchText.on('click', function(){
            $searchBar.addClass('weui-search-bar_focusing');
            $searchInput.focus();
        });
        $searchInput
            .on('blur', function () {
                if(!this.value.length) cancelSearch();
            })
            .on('input', function(){
                if(this.value.length) {
                    $searchResult.show();
                } else {
                    $searchResult.hide();
                }
            })
        ;
        $searchClear.on('click', function(){
            hideSearchResult();
            $searchInput.focus();
        });
        $searchCancel.on('click', function(){
            cancelSearch();
            $searchInput.blur();
        });
    });

    function getUsers() {
        $.ajax({
            url: "<?php echo site_url('users')?>",
            dataType: "json",
            method: "get",
        }).done(function (res) {
            if (res.state === 0) {
                let p_msg = $("<p class='page_desc'></p>");
                p_msg.text("暂无用户");
                $(".page__hd").append(p_msg);
                return;
            }
            renderList(res.data);
        }).fail(function (res) {
            alert("服务器错误");
        })
    }

    function doDelete(id) {
        console.log("id",id);
        $.ajax({
            url: "<?php echo site_url('users/delete')?>",
            dataType: "json",
            method: "get",
            data: {
                id: id,
            },
        }).done(function (res) {
            console.log(res);
            if (res.state === 1)  {
                alert("删除成功");
                getUsers();
            }
        }).fail(function (res) {
            alert("服务器错误500");
        });
        hideActionSheet();
    }

    function search() {
        let search_name = $("#searchInput").val();
        $.ajax({
            url: "<?php echo site_url('users/fuzzy')?>",
            method: "get",
            data: {
                "username": search_name,
            },
        }).done(function (res) {
            renderList(res.data);
        }).fail(function (res) {
            alert("服务器错误");
        });

    }

    function renderList(users) {
        let container = $("#users_container");
        if (users.length === 0) {
            container.text("暂无用户");
        }
        container.empty();
        users.forEach(function (user) {
            let weui_panel = $("<div class='weui-panel weui-panel_access'></div>");
            weui_panel.attr("id",user.id);

            let panel_hd = $("<div class='weui-panel__hd'></div>");
            panel_hd.text(user.username);
            weui_panel.append(panel_hd);

            let panel_bd = $("<div class='weui-panel__bd'></div>");
            let media_box = $("<a class='weui-media-box weui-media-box_appmsg'></a>");

            let media_box_hd = $("<div class='weui-media-box__hd'></div>");
            let media_box_thumb = $("<img class='weui-media-box__thumb'/>");
            media_box_thumb.attr("src",user.headimg);
            media_box_hd.append(media_box_thumb);
            media_box.append(media_box_hd);

            let media_box_bd = $("<div class='weui-media-box__bd'></div>");
            let media_box_desc = $("<div class='weui-media-box__desc'></div>");
            media_box_desc.text(user.intro);
            media_box_bd.append(media_box_desc);
            media_box.append(media_box_bd);
            panel_bd.append(media_box);
            weui_panel.append(panel_bd);

            let panel_footer = $("<div class='weui-panel__ft'></div>");
            let panel_footer_a = $("<a class='weui-cell weui-cell_access weui-cell_link'>");
            panel_footer_a.on("click",function () {
                let id = $(this).parent().parent().attr("id");
                showActionSheet(id);
            });
            let panel_footer_a_bd = $("<div class='weui-cell__bd'></div>");
            panel_footer_a_bd.text("创建时间: " + user.birth);
            panel_footer_a.append(panel_footer_a_bd);
            let panel_footer_a_hd = $("<div class='weui-cell__hd'>操作</div>");
            panel_footer_a.append(panel_footer_a_hd);
            let panel_footer_a_span = $("<span class='weui-cell__ft'></span>");
            panel_footer_a.append(panel_footer_a_span);
            panel_footer.append(panel_footer_a);
            weui_panel.append(panel_footer);
            container.append(weui_panel);
        })
    }
</script>
<script type="text/javascript" class="actionsheet js_show">
    // ios
        var $iosActionsheet = $('#iosActionsheet');
        var $iosMask = $('#iosMask');

        function hideActionSheet() {
            $iosActionsheet.removeClass('weui-actionsheet_toggle');
            $iosMask.fadeOut(200);
        }

        $iosMask.on('click', hideActionSheet);
        $('#iosActionsheetCancel').on('click', hideActionSheet);

        function showActionSheet(id) {
            console.log(id);
            $iosActionsheet.addClass('weui-actionsheet_toggle');
            $iosMask.fadeIn(200);
            let del_btn = $("#del_btn");
            let detail_btn = $("#detail_btn");
            let update_btn = $("#update_btn");

            del_btn.unbind("click");
            del_btn.on("click",function () {
                doDelete(id);
            });

            detail_btn.unbind("click");
            detail_btn.on("click",function () {
                $("#container").load("<?=site_url('users/detail/');?>" + id);
            });

            update_btn.unbind("click");
            update_btn.on("click",function () {
                $("#container").load("<?=site_url('users/doUpdate/');?>" + id);
            })


        }
</script>