<script type="text/javascript">

    if(typeof jQuery == 'undefined' || typeof jQuery.fn.on == 'undefined') {
        document.write('<script src="https://paysonutah.org/template/ext/bb-plugin/js/jquery.js"><\/script>');
        document.write('<script src="https://paysonutah.org/template/ext/bb-plugin/js/jquery.migrate.min.js"><\/script>');
    }

</script>

<script type="text/html" id="tmpl-fl-node-template-block">
    <span class="fl-builder-block fl-builder-block-saved-@{{data.type}}<# if ( data.global ) { #> fl-builder-block-global<# } #>" data-id="@{{data.id}}">
		<span class="fl-builder-block-title">@{{data.name}}</span>
		<# if ( data.global ) { #>
		<div class="fl-builder-badge fl-builder-badge-global">
			Global		</div>
		<# } #>
		<span class="fl-builder-node-template-actions">
			<a class="fl-builder-node-template-edit" href="@{{data.link}}" target="_blank">
				<i class="fa fa-wrench"></i>
			</a>
			<a class="fl-builder-node-template-delete" href="javascript:void(0);">
				<i class="fa fa-times"></i>
			</a>
		</span>
	</span>
</script>
<!-- #tmpl-fl-node-template-block --><script type="text/javascript" src="https://paysonutah.org/template/ext/bb-plugin/js/jquery.easing.1.3.js?ver=1.3"></script>
<script type="text/javascript" src="https://paysonutah.org/template/ext/bb-plugin/js/jquery.fitvids.js?ver=1.8.1"></script>
<script type="text/javascript" src="https://paysonutah.org/template/ext/bb-plugin/js/jquery.bxslider.min.js?ver=1.8.1"></script>
<script type="text/javascript" src="https://paysonutah.org/storage/bb-plugin/cache/7-layout.js?ver=3aa732626d12444ce6cd4b0daf49c893"></script>
<script type="text/javascript" src="https://paysonutah.org/template_main/js/jquery.throttle.min.js?ver=1.5.1"></script>
<script type="text/javascript" src="https://paysonutah.org/template/ext/bb-plugin/js/jquery.magnificpopup.min.js?ver=1.8.1"></script>
<script type="text/javascript" src="https://paysonutah.org/template_main/js/bootstrap.min.js?ver=1.5.1"></script>
<script type="text/javascript" src="https://paysonutah.org/template_main/js/theme.js?ver=1.5.1"></script>
<script type="text/javascript" src="https://paysonutah.org/template/lib/js/wp-embed.min.js?ver=4.7.18"></script>
<script>
    function user_logout() {
        var data = {action: 'u_logout'};
        jQuery.post('https://paysonutah.org/ajax', data, function(response) {
            location.reload(true);
        });
    }
    function add_page()
    {
        var pName = document.getElementById('p_name_box').value;
        var pId = document.getElementById('add-p-b').getAttribute("p_id");
        var data = {
            action: 'add_new_page',
            p_name: pName,
            parent: pId
        };
        jQuery.post('https://paysonutah.org/ajax', data, function(response) {
            location.reload(true);
        });

    }
    function delete_page()
    {
        var pId = document.getElementById('d-p-b').getAttribute("p_id");
        var data = {
            action: 'delete_page',
            page: pId
        };
        jQuery.post('https://paysonutah.org/ajax', data, function(response) {
            location.assign(response);
        });

    }
    function set_page_name()
    {
        var pName = document.getElementById('set_name_box').value;
        var pId = document.getElementById('set_name_b').getAttribute("p_id");

        var data = {
            action: 'set_page_name',
            page: pId,
            p_name: pName
        };
        jQuery.post('https://paysonutah.org/ajax', data, function(response) {
            location.assign(response);
        });

    }

    jQuery('.fl-slide-content a').on('pointerdown', function(){return false});
</script>
