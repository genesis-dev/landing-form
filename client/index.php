<?php
    header('Content-Type: application/javascript');
    $config = include __DIR__."/../config/local.php";
    if (isset($config['sites'][$_GET['siteID']]) && $config['sites'][$_GET['siteID']]['host'] == $_SERVER['REMOTE_HOST']):
?>
(function($) {
    var landingForm = {
        siteID: "<?= $_GET['siteID'] ?>"
    };
    $(document).ready(function() {
        $('[data-landing-form]').append('<input type="hidden" name="siteID" value="<?= $_GET['siteID'] ?>">');
        $(document).on('submit', '[data-landing-form]', function() {
            var data = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                data: data,
                dataType: 'jsonp',
                success: function(r) {
                    console.log(r);
                }
            });

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
