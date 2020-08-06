<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<link rel="stylesheet" href="{!! asset('/css/all.min.css') !!}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya:700">
<link rel="stylesheet" href="https://paysonutah.org/template_main/css/bootstrap.min.css">
<!--[if lt IE 9]>
<script src="https://paysonutah.org/template_main/js/html5shiv.js"></script>
<script src="https://paysonutah.org/template_main/js/respond.min.js"></script>
<![endif]-->
<title>Payson – Experience the Many Layers</title>
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
<link rel="dns-prefetch" href="//maxcdn.bootstrapcdn.com">
<link rel="dns-prefetch" href="//s.w.org">

@if($fb_pixel = \AnchorCMS\Copy::_getVerbiageforAPageByName('pixels', 'facebook'))
    {!! $fb_pixel !!}
@endif

<style type="text/css">
    img.wp-smiley,
    img.emoji {
        display: inline !important;
        border: none !important;
        box-shadow: none !important;
        height: 1em !important;
        width: 1em !important;
        margin: 0 .07em !important;
        vertical-align: -0.1em !important;
        background: none !important;
        padding: 0 !important;
    }
</style>
<link rel="stylesheet" id="foundation-icons-css" href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css?ver=1.8.1" type="text/css" media="all">
<link rel="stylesheet" id="jquery-bxslider-css" href="https://paysonutah.org/template/ext/bb-plugin/css/jquery.bxslider.css?ver=1.8.1" type="text/css" media="all">
<link rel="stylesheet" id="font-awesome-css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css?ver=1.8.1" type="text/css" media="all">
<link rel="stylesheet" id="fl-builder-layout-7-css" href="https://paysonutah.org/storage/bb-plugin/cache/7-layout.css?ver=3aa732626d12444ce6cd4b0daf49c893" type="text/css" media="all">
<link rel="stylesheet" id="mono-social-icons-css" href="https://paysonutah.org/template_main/css/mono-social-icons.css?ver=1.5.1" type="text/css" media="all">
<link rel="stylesheet" id="jquery-magnificpopup-css" href="https://paysonutah.org/template/ext/bb-plugin/css/jquery.magnificpopup.css?ver=1.8.1" type="text/css" media="all">
<script type="text/javascript" src="https://paysonutah.org/template/lib/js/jquery/jquery.js?ver=1.12.4"></script>
<script type="text/javascript" src="https://paysonutah.org/template/lib/js/jquery/jquery-migrate.min.js?ver=1.4.1"></script>
<link rel="https://api.w.org/" href="https://paysonutah.org/wp-json/">
<link rel="alternate" type="application/json+oembed" href="https://paysonutah.org/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fpaysonutah.org%2F">
<link rel="alternate" type="text/xml+oembed" href="https://paysonutah.org/wp-json/oembed/1.0/embed?url=https%3A%2F%2Fpaysonutah.org%2F&amp;format=xml">
<style type="text/css">
    .calnk a:hover {
        background-position:0 0;
        text-decoration:none;
        color:#000000;
        border-bottom:1px dotted #000000;
    }
    .calnk a:visited {
        text-decoration:none;
        color:#000000;
        border-bottom:1px dotted #000000;
    }
    .calnk a {
        text-decoration:none;
        color:#000000;
        border-bottom:1px dotted #000000;
    }
    .calnk a span {
        display:none;
    }
    .calnk a:hover span {
        color:#333333;
        background:#F6F79B;
        display:block;
        position:absolute;
        margin-top:1px;
        padding:5px;
        width:150px;
        z-index:100;
        line-height:1.2em;
    }
    .calendar-table {
        border:0 !important;
        width:100% !important;
        border-collapse:separate !important;
        border-spacing:2px !important;
    }
    .calendar-heading {
        height:25px;
        text-align:center;
        background-color:#E4EBE3;
    }
    .calendar-next {
        width:20%;
        text-align:center;
        border:none;
    }
    .calendar-prev {
        width:20%;
        text-align:center;
        border:none;
    }
    .calendar-month {
        width:60%;
        text-align:center;
        font-weight:bold;
        border:none;
    }
    .normal-day-heading {
        text-align:center;
        width:25px;
        height:25px;
        font-size:0.8em;
        border:1px solid #DFE6DE;
        background-color:#EBF2EA;
    }
    .weekend-heading {
        text-align:center;
        width:25px;
        height:25px;
        font-size:0.8em;
        border:1px solid #DFE6DE;
        background-color:#EBF2EA;
        color:#FF0000;
    }
    .day-with-date {
        vertical-align:text-top;
        text-align:left;
        width:60px;
        height:60px;
        border:1px solid #DFE6DE;
    }
    .no-events {

    }
    .day-without-date {
        width:60px;
        height:60px;
        border:1px solid #E9F0E8;
    }
    span.weekend {
        color:#FF0000;
    }
    .current-day {
        vertical-align:text-top;
        text-align:left;
        width:60px;
        height:60px;
        border:1px solid #BFBFBF;
        background-color:#E4EBE3;
    }
    span.event {
        font-size:0.75em;
    }
    .kjo-link {
        font-size:0.75em;
        text-align:center;
    }
    .calendar-date-switcher {
        height:25px;
        text-align:center;
        border:1px solid #D6DED5;
        background-color:#E4EBE3;
    }
    .calendar-date-switcher form {
        margin:2px;
    }
    .calendar-date-switcher input {
        border:1px #D6DED5 solid;
        margin:0;
    }
    .calendar-date-switcher input[type=submit] {
        padding:3px 10px;
    }
    .calendar-date-switcher select {
        border:1px #D6DED5 solid;
        margin:0;
    }
    .calnk a:hover span span.event-title {
        padding:0;
        text-align:center;
        font-weight:bold;
        font-size:1.2em;
        margin-left:0px;
    }
    .calnk a:hover span span.event-title-break {
        width:96%;
        text-align:center;
        height:1px;
        margin-top:5px;
        margin-right:2%;
        padding:0;
        background-color:#000000;
        margin-left:0px;
    }
    .calnk a:hover span span.event-content-break {
        width:96%;
        text-align:center;
        height:1px;
        margin-top:5px;
        margin-right:2%;
        padding:0;
        background-color:#000000;
        margin-left:0px;
    }
    .page-upcoming-events {
        font-size:80%;
    }
    .page-todays-events {
        font-size:80%;
    }
    .calendar-table table,tbody,tr,td {
        margin:0 !important;
        padding:0 !important;
    }
    table.calendar-table {
        margin-bottom:5px !important;
    }
    .cat-key {
        width:100%;
        margin-top:30px;
        padding:5px;
        border:0 !important;
    }
    .cal-separate {
        border:0 !important;
        margin-top:10px;
    }
    table.cat-key {
        margin-top:5px !important;
        border:1px solid #DFE6DE !important;
        border-collapse:separate !important;
        border-spacing:4px !important;
        margin-left:2px !important;
        width:99.5% !important;
        margin-bottom:5px !important;
    }
    .minical-day {
        background-color:#F6F79B;
    }
    .cat-key td {
        border:0 !important;
    }
</style>
<link rel="icon" href="https://paysonutah.org/storage/2016/05/cropped-Logo125-1-1-32x32.png" sizes="32x32">
<link rel="icon" href="https://paysonutah.org/storage/2016/05/cropped-Logo125-1-1-192x192.png" sizes="192x192">
<link rel="apple-touch-icon-precomposed" href="https://paysonutah.org/storage/2016/05/cropped-Logo125-1-1-180x180.png">
<meta name="msapplication-TileImage" content="https://paysonutah.org/storage/2016/05/cropped-Logo125-1-1-270x270.png">
<link rel="stylesheet" href="https://paysonutah.org/storage/bb-theme/skin-5eb4d6e7783f0.css">
<style id="fl-theme-custom-css">
    .fl-page-header-container>.fl-page-header-row>.fl-page-nav-col
    {
        width: 95%;
    }
    .fl-page-header-container>.fl-page-header-row>.fl-page-logo-col
    {
        width:5%;
    }
    header.fl-page-header
    {
        position: absolute;
        z-index: 999;
        width: 100%;
        background: rgba(255,255,255, .9);
    }

    /*Editor Panel code*/
    .e-panel
    {
        background: rgba(255,255,255, .5);
    }
    #d_pages
    {
        display: inline-block;
    }
    #d_pages>ul
    {
        display: none;
        position: absolute;
        background-color: white;
    }
    #d_pages:hover ul
    {
        padding: 10px;
        display: block;
        z-index: 9999;
        list-style-position: inside;
    }
    #a_page_div, #d_pages, #set_title_div
    {
        display: inline-block;
        padding: 0 5px;
    }
    #a_page_div>div, #set_title_div>div
    {
        position: absolute;
        display: none;
        background: white;
    }
    #a_page_div:hover>div, #set_title_div:hover>div
    {
        padding: 10px;
        display: block;
        z-index: 9999;
    }

    /*edit link remove*/
    .post-edit-link
    {
        display: none;
    }</style>

@if($ga_pixel = \AnchorCMS\Copy::_getVerbiageforAPageByName('pixels', 'ga'))
    {!! $ga_pixel !!}
@endif

<link rel="stylesheet" href="https://paysonutah.org/template/main.css">
<style id="fit-vids-style">.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style><style type="text/css">.fb_hidden{position:absolute;top:-10000px;z-index:10001}.fb_reposition{overflow:hidden;position:relative}.fb_invisible{display:none}.fb_reset{background:none;border:0;border-spacing:0;color:#000;cursor:auto;direction:ltr;font-family:"lucida grande", tahoma, verdana, arial, sans-serif;font-size:11px;font-style:normal;font-variant:normal;font-weight:normal;letter-spacing:normal;line-height:1;margin:0;overflow:visible;padding:0;text-align:left;text-decoration:none;text-indent:0;text-shadow:none;text-transform:none;visibility:visible;white-space:normal;word-spacing:normal}.fb_reset>div{overflow:hidden}@keyframes fb_transform{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}.fb_animate{animation:fb_transform .3s forwards}
    .fb_dialog{background:rgba(82, 82, 82, .7);position:absolute;top:-10000px;z-index:10001}.fb_dialog_advanced{border-radius:8px;padding:10px}.fb_dialog_content{background:#fff;color:#373737}.fb_dialog_close_icon{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 0 transparent;cursor:pointer;display:block;height:15px;position:absolute;right:18px;top:17px;width:15px}.fb_dialog_mobile .fb_dialog_close_icon{left:5px;right:auto;top:5px}.fb_dialog_padding{background-color:transparent;position:absolute;width:1px;z-index:-1}.fb_dialog_close_icon:hover{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 -15px transparent}.fb_dialog_close_icon:active{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 -30px transparent}.fb_dialog_iframe{line-height:0}.fb_dialog_content .dialog_title{background:#6d84b4;border:1px solid #365899;color:#fff;font-size:14px;font-weight:bold;margin:0}.fb_dialog_content .dialog_title>span{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/yd/r/Cou7n-nqK52.gif) no-repeat 5px 50%;float:left;padding:5px 0 7px 26px}body.fb_hidden{height:100%;left:0;margin:0;overflow:visible;position:absolute;top:-10000px;transform:none;width:100%}.fb_dialog.fb_dialog_mobile.loading{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/ya/r/3rhSv5V8j3o.gif) white no-repeat 50% 50%;min-height:100%;min-width:100%;overflow:hidden;position:absolute;top:0;z-index:10001}.fb_dialog.fb_dialog_mobile.loading.centered{background:none;height:auto;min-height:initial;min-width:initial;width:auto}.fb_dialog.fb_dialog_mobile.loading.centered #fb_dialog_loader_spinner{width:100%}.fb_dialog.fb_dialog_mobile.loading.centered .fb_dialog_content{background:none}.loading.centered #fb_dialog_loader_close{clear:both;color:#fff;display:block;font-size:18px;padding-top:20px}#fb-root #fb_dialog_ipad_overlay{background:rgba(0, 0, 0, .4);bottom:0;left:0;min-height:100%;position:absolute;right:0;top:0;width:100%;z-index:10000}#fb-root #fb_dialog_ipad_overlay.hidden{display:none}.fb_dialog.fb_dialog_mobile.loading iframe{visibility:hidden}.fb_dialog_mobile .fb_dialog_iframe{position:sticky;top:0}.fb_dialog_content .dialog_header{background:linear-gradient(from(#738aba), to(#2c4987));border-bottom:1px solid;border-color:#043b87;box-shadow:white 0 1px 1px -1px inset;color:#fff;font:bold 14px Helvetica, sans-serif;text-overflow:ellipsis;text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0;vertical-align:middle;white-space:nowrap}.fb_dialog_content .dialog_header table{height:43px;width:100%}.fb_dialog_content .dialog_header td.header_left{font-size:12px;padding-left:5px;vertical-align:middle;width:60px}.fb_dialog_content .dialog_header td.header_right{font-size:12px;padding-right:5px;vertical-align:middle;width:60px}.fb_dialog_content .touchable_button{background:linear-gradient(from(#4267B2), to(#2a4887));background-clip:padding-box;border:1px solid #29487d;border-radius:3px;display:inline-block;line-height:18px;margin-top:3px;max-width:85px;padding:4px 12px;position:relative}.fb_dialog_content .dialog_header .touchable_button input{background:none;border:none;color:#fff;font:bold 12px Helvetica, sans-serif;margin:2px -12px;padding:2px 6px 3px 6px;text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0}.fb_dialog_content .dialog_header .header_center{color:#fff;font-size:16px;font-weight:bold;line-height:18px;text-align:center;vertical-align:middle}.fb_dialog_content .dialog_content{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/y9/r/jKEcVPZFk-2.gif) no-repeat 50% 50%;border:1px solid #4a4a4a;border-bottom:0;border-top:0;height:150px}.fb_dialog_content .dialog_footer{background:#f5f6f7;border:1px solid #4a4a4a;border-top-color:#ccc;height:40px}#fb_dialog_loader_close{float:left}.fb_dialog.fb_dialog_mobile .fb_dialog_close_button{text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0}.fb_dialog.fb_dialog_mobile .fb_dialog_close_icon{visibility:hidden}#fb_dialog_loader_spinner{animation:rotateSpinner 1.2s linear infinite;background-color:transparent;background-image:url(https://static.xx.fbcdn.net/rsrc.php/v3/yD/r/t-wz8gw1xG1.png);background-position:50% 50%;background-repeat:no-repeat;height:24px;width:24px}@keyframes rotateSpinner{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
    .fb_iframe_widget{display:inline-block;position:relative}.fb_iframe_widget span{display:inline-block;position:relative;text-align:justify}.fb_iframe_widget iframe{position:absolute}.fb_iframe_widget_fluid_desktop,.fb_iframe_widget_fluid_desktop span,.fb_iframe_widget_fluid_desktop iframe{max-width:100%}.fb_iframe_widget_fluid_desktop iframe{min-width:220px;position:relative}.fb_iframe_widget_lift{z-index:1}.fb_iframe_widget_fluid{display:inline}.fb_iframe_widget_fluid span{width:100%}
</style>
