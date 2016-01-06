<?php
    header('Content-Type: application/javascript');
    $config = include __DIR__."/../config/local.php";
    if (isset($config['sites'][$_GET['siteID']])):
?>
(function($) {
    var landingForm = {
        siteID: "<?= $_GET['siteID'] ?>"
    };
    $(document).ready(function() {
        $('[data-landing-form]').append('<input type="hidden" name="site-id" value="<?= $_GET['siteID'] ?>">');
        $(document).on('submit', '[data-landing-form]', function() {
            var data = $(this).serialize();
            $.post($(this).attr('action'), data, function(r) {
                console.log(e);
            }, 'jsonp');
            return false;
        });
    });

})(jQuery);
<?php
    else:
?>
alert("Invalid siteID");
<?php
    endif;
?>
