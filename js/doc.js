jQuery(document).ready(function() {
    function add_favorite(dir, post_id, title){
        jQuery.ajax({
            url:docAjaxObject.url,
            type: 'POST',
            data:{
                nonce: docAjaxObject.nonce,
                action: 'doc_favorite_add',
                post_id:post_id,
                title:title,
                dir:dir
            },
            success: function(response) {
                if(response.success){
                    header_alert('success', response.data.message);
                }else{
                    header_alert('danger', response.data.message);
                }
            },
            error:function(){
                header_alert('danger', '操作失败, 请稍后重试!');
            }
        });
    }

    jQuery('#btn_favorite').click(function(event){
        let btn = jQuery(event.target);
        let title = btn.attr('doc-title');
        let post_id = btn.attr('doc-id');
        add_favorite('', post_id, title);
    });
    jQuery('a[name="btn_favorite_sub"]').click(function(event){
        let btn = jQuery(event.target);
        let dir = btn.attr('favorite-dir');
        let title = btn.attr('doc-title');
        let post_id = btn.attr('doc-id');
        add_favorite(dir, post_id, title);
    });
    
    jQuery('#btn_favorite_add_dir').click(function(event){
        let dir_name = jQuery('#input_favorite_add_dir').val();
        jQuery.ajax({
            url:docAjaxObject.url,
            type: 'POST',
            data:{
                nonce: docAjaxObject.nonce,
                action: 'doc_favorite_add_dir',
                dir:dir_name
            },
            success: function(response) {
                jQuery('#input_favorite_add_dir').val('');
                jQuery('#exampleModal').modal('hide');
                if(response.success){
                    header_alert('success', response.data.message);
    
                    let child = '';
                    child += '<div class="card">';
                    child +=     '<div class="card-header" id="headingOne">';
                    child +=         '<h2 class="mb-0">';
                    child +=             '<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">';
                    child +=                 dir_name;
                    child +=             '</button>';
                    child +=         '</h2>';
                    child +=     '</div>';
                    child +=     '<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#favorites_dir">';
                    child +=         '<div class="card-body">';
                    child +=         '</div>';
                    child +=     '</div>';
                    child += '</div>';
                    jQuery('#favorites_dir').append(child);
                }else{
                    header_alert('danger', response.data.message);
                }
            },
            error:function(){
                header_alert('danger', '操作失败, 请稍后重试!');
            }
        });
    });
    
    /**加载收藏文件夹 
    jQuery('button[name="my-account-tab"]').on('show.bs.tab', function (event) {
        if(event.target.id === 'v-pills-favorites-tab'){
            jQuery.ajax({
                url:docAjaxObject.url,
                type: 'POST',
                data:{
                    nonce: docAjaxObject.nonce,
                    action: 'doc_favorite_dir_list'
                },
                success: function(response) {
                    console.log(response);
                }
            });
        };
    });
    */
    
    var alertId = 1;
    function header_alert(type, msg){
        alertId ++;
        jQuery('.header-alert-box').html('<div class="alert alert-' + type + ' fade show header-alert-' + alertId + '">' + msg + '</div>');
        
        setTimeout(function(){
            jQuery('.header-alert-' + alertId).alert('close')
        }, 3000);
    }
    
    jQuery('#download_doc_download_btn').on('click', function() {
        let post_id = jQuery('#download_doc_download_btn').attr('doc-id');
        jQuery.ajax({
            url:docAjaxObject.url,
            type: 'POST',
            data:{
                nonce: docAjaxObject.nonce,
                action: 'doc_download',
                post_id:post_id
            }
        });
    });

    jQuery('a[name=header-search-logo]').on('click', function(){
        jQuery('a[name=header-search-logo]').attr('style', 'display: none;');
        jQuery('.header-search-sm').attr('style', 'display: block;');
    });

    jQuery('#single_print_btn').click(function(){
        jQuery('iframe[class=pdfjs-iframe]')[0].contentWindow.print();
    });

    jQuery('[data-toggle="popover"]').popover();
    
    /*
    jQuery('iframe[class=pdfjs-iframe]').contents().find('#download').on('click', function() {
        let post_id = jQuery('#btn_favorite').attr('doc-id');
        jQuery.ajax({
            url:docAjaxObject.url,
            type: 'POST',
            data:{
                nonce: docAjaxObject.nonce,
                action: 'doc_download',
                post_id:post_id
            }
        });
    });
    */
});


