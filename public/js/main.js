/**
 * set the loading icon start/stop
 * @param status true / false == turn on / off
 * @param btn the parent button
 */
function registerBtnLoading(status, btn) {
    if(status) {
        let circle = $("<i class='weui-loading'></i>");
        $(btn).addClass("weui-btn_loading");
        $(btn).prepend(circle);
    } else {
        $(btn).removeClass("weui-btn_loading");
        $("i.weui-loading").remove();
    }
}

$(function(){
    var winH = $(window).height();
    var categorySpace = 10;

    $('.js_item').on('click', function(){
        var id = $(this).data('id');
        window.pageManager.go(id);
    });
    $('.js_category').on('click', function(){
        var $this = $(this),
            $inner = $this.next('.js_categoryInner'),
            $page = $this.parents('.page'),
            $parent = $(this).parent('li');
        var innerH = $inner.data('height');
        bear = $page;

        if(!innerH){
            $inner.css('height', 'auto');
            innerH = $inner.height();
            $inner.removeAttr('style');
            $inner.data('height', innerH);
        }

        if($parent.hasClass('js_show')){
            $parent.removeClass('js_show');
        }else{
            $parent.siblings().removeClass('js_show');

            $parent.addClass('js_show');
            if(this.offsetTop + this.offsetHeight + innerH > $page.scrollTop() + winH){
                var scrollTop = this.offsetTop + this.offsetHeight + innerH - winH + categorySpace;

                if(scrollTop > this.offsetTop){
                    scrollTop = this.offsetTop - categorySpace;
                }

                $page.scrollTop(scrollTop);
            }
        }

        var winH = $(window).height();
        var $foot = $('body').find('.page__ft');
        if($foot.length < 1) return;

        if($foot.position().top + $foot.height() < winH){
            $foot.addClass('j_bottom');
        }else{
            $foot.removeClass('j_bottom');
        }
    });
});


// function verifyUserStatus() {
//     let user = sessionStorage.getItem("username");
//     if (user !== null && user === '') {
//         loginBtnLoading(false,this);
//         $("#container").load("<?=site_url('users/list');?>");
//     }
// }
function showThumbNail() {
    let input_File = $("#uploaderFile");
    let file = input_File.get(0).files[0];
    if (file == null) {
        alert("图片错误,请重新上传图片");
        input_File.val("");
    }
    let src = window.URL.createObjectURL(file);
    console.log("src",src);
    createNewUploadImg($("#uploaderFilesList"),src);
}

function createNewUploadImg(fatherElem,src) {
    let newImg = $("<li class='weui-uploader__file'></li>");
    newImg.attr({
        style: 'background-image:' + 'url(' + src + ')',
        width : 100 + '%',
        height : 100 + '%',
    });
    fatherElem.empty();
    fatherElem.append(newImg);
}

function isTelephone(num_area) {
    let telStr = /^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/;
    let num = $(num_area).val();
    return telStr.test(num);
}