<!DOCTYPE html>
<html>
  <head>
    <link rel='stylesheet' href='app.css'>
    <script src='vendor/jquery.js?<?=filemtime('vendor/jquery.js')?>'></script>
    <script src='js/calendar.js?<?=filemtime('js/calendar.js')?>'></script>
  </head>
  <body>
    <h3 id='week-header'>
      <span href='#' onclick='cal.weekPrevious();'>&larr;</span> <span id='header-info'></span> <span href='#' onclick='cal.weekNext();'>&rarr;</span>
    </h3>
    <table border='1' can-edit='<?=$canEdit ? 'y' : 'n'; ?>'>
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
<?php for ($i = 0; $i <= 23; $i++): ?>
  <?php if ($currentHour == $i): ?>
        <tr class='active-hour'>
  <?php else: ?>
        <tr>
  <?php endif; ?>
          <td>
            <?=str_pad($i, 2, '0', STR_PAD_LEFT); ?>:00
          </td>
  <?php for ($j = 0; $j <= 6; $j++): ?>
    <?php if ($currentHour == $i && $currentDay == $j): ?>
          <td id='<?=$i?>-<?=$j?>' class='current-hour'>
    <?php else: ?>
          <td id='<?=$i?>-<?=$j?>'>
    <?php endif; ?>
          </td>
  <?php endfor; ?>
        </tr>
<?php endfor; ?>
      </tbody>
    </table>
<?php if ($canEditAll): ?>
    <div class='center-block'>
      <button onclick='cal.clear();'>
        Clear Timetable
      </button>
    </div>
<?php endif; ?>
    <script>
      var cal = new Calendar();
      cal.can_edit = <?=$canEdit ? 'true' : 'false'?>;
      cal.level = <?=$level?>;
      cal.timestamp = <?=time()?>;
      cal.user = '<?=$user?>';
      cal.user_encrypted = '<?=$userEncrypted?>';
      cal.week = <?=$week?>;

      cal.view(cal.week);

      cal.update_interval = setInterval(function () {
        cal.view(cal.week);
      }, 300000);
    </script>
  </body>
</html>
