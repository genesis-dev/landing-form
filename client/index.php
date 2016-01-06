<?php
    header('Content-Type: application/javascript');
    $config = include __DIR__."/../config/local.php";
    if (isset($config['sites'][$_GET['siteID']])):
?>
(function() {
    var landingForm = {
        siteID: "<?= $_GET['siteID'] ?>",
        selector: ".landing-form"
    };

    window.onsubmit = function (e) {
        console.log(e);
        return false;
    }
})();
<?php
    else:
?>
alert("Invalid siteID");
<?php
    endif;
?>
