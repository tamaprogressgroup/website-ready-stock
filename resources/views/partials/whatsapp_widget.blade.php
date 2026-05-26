<?php
$utm = session('utm_parameters') ? session('utm_parameters') : null;
$wa_widget = session('wa_widget') ? session('wa_widget') : null;
$wa_no_channel = session('wa_no_channel') ? session('wa_no_channel') : $wa_widget['wa_no'];

$source = '';
  if (!is_null($utm)) {
      $source = isset($utm['utm_source']) ? strtolower($utm['utm_source']) : null;

  }


?>

<script>
    (function () {

        var options = {
            greeting : true,
            greeting_message : <?php echo json_encode($wa_widget['greeting']) ?>,
            whatsapp:  <?php echo json_encode($wa_no_channel) ?>,
            call_to_action: <?php echo json_encode($wa_widget['cta_message']) ?>,
            button_color: "#4dc247",
            position: "right",
            pre_filled_message: <?php echo json_encode($wa_widget['pre_filled_message']) ?>,
            shift_vertical : 20,
            shift_horizontal : 20,
            // greeting_message_delay : 10,
            company_logo_url: <?php echo json_encode($wa_widget['logo_url']) ?>,
        };
        var proto = document.location.protocol, host = "beta.paradise.co.id", url = proto + "//" + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/build/js/waplugin.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
</script>