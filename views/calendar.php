<!DOCTYPE html>
<html>
  <head>
    <link rel='stylesheet' href='app.css'>
    <script src='vendor/jquery.js'></script>
  </head>
  <body>
    <table can-edit='<?=$canEdit ? 'y' : 'n'; ?>'>
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th>Monday</th>
          <th>Tuesday</th>
          <th>Wednesday</th>
          <th>Thursday</th>
          <th>Friday</th>
          <th>Saturday</th>
          <th>Sunday</th>
        </tr>
      </thead>
      <tbody>
<?php foreach ($hours as $hour => $hourInfo): ?>
        <tr>
          <td>
            <?=str_pad($hour, 2, '0', STR_PAD_LEFT); ?>:00
          </td>
  <?php for ($i = 0; $i <= 6; $i++): ?>
          <td>
            <span onclick='claim(this, <?=$hour; ?>, <?=$i; ?>)'><?=$hourInfo[$i]; ?></span>
          </td>
  <?php endfor; ?>
        </tr>
<?php endforeach; ?>
      </tbody>
    </table>
    <script>
      var claim = function (el, hour, day) {
        var can_edit = <?=$canEdit ? 'true' : 'false'; ?>;
        var user_encrypted = '<?=$userEncrypted; ?>';
        var user = '<?=$user; ?>';
        var level = <?=$level; ?>;

        // If they can't edit, then let's do nothing.
        if (can_edit === false) {
          return;
        }

        var param_list = {
          day: day,
          hour: hour,
          level: level,
          user: user_encrypted,
        };

        $.post('claim.php', $.param(param_list))
          .done(function(data) {
            switch (data) {
              case 'D:':
                alert('There was a server error!');

                break;
              case ':(':
                alert('Someone else has already claimed that hour.');

                break;
              case ':|':
                $(el).html('--');

                break;
              case ':)':
                $(el).html(user);

                break;
            }
          });
      };
    </script>
  </body>
</html>
