<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFSP Client GUI</title>
    <link rel='stylesheet' id='mfsp-bundle-css-css' href='<?php echo plugins_url('../public/css/jstree/default/jstree.style.css', __FILE__) . "?ver=" . time(); ?>' media='all' />
    <link rel='stylesheet' id='fontawesome-css-css' href='<?php echo plugins_url('../public/css/fontawesome.min.css', __FILE__) . "?ver=" . time(); ?>' media='all' />
    <link rel='stylesheet' id='mfsp-bundle-css' href='<?php echo plugins_url('../public/bundle/mfsp-bundle.css', __FILE__) . "?ver=" . time(); ?>' media='all' />
    <link rel='stylesheet' id='custom-css' href='<?php echo plugins_url('../public/css/custom.css', __FILE__) . "?ver=" . time(); ?>' media='all' />
    <link rel='stylesheet' id='sweetalert2-css' href='<?php echo plugins_url('../public/css/sweetalert2.css', __FILE__) . "?ver=" . time(); ?>' media='all' />
</head>

<body>
    <div id="mfsp_clientgui"></div>
</body>

<script id='mfsp-bundle-js-js-extra'>
    var MfspApiConfig = {
        "api_base_url": <?php echo json_encode(get_rest_url()); ?>,
        "nonce": "<?php echo wp_create_nonce('wp_rest'); ?>"
    };
</script>
<script src='<?php echo plugins_url('../public/bundle/mfsp-bundle.js', __FILE__) . "?ver=" . time(); ?>' id='mfsp-bundle-js-js'></script>

</html>