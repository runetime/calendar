var Calendar = function () {
};

Calendar.prototype.can_edit = false;
Calendar.prototype.can_edit = false,
Calendar.prototype.level = 0;
Calendar.prototype.timestamp = 0;
Calendar.prototype.user = '';
Calendar.prototype.user_encrypted = '';
Calendar.prototype.week = 0;

Calendar.prototype.claim = function (el, hour, day) {
  // If they can't edit, then let's do nothing.
  if (this.can_edit === false) {
    return;
  }

  var param_list = {
    day: day,
    hour: hour,
    level: this.level,
    user: this.user_encrypted,
    timestamp: this.timestamp,
    week: this.week
  };

  var self = this;

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
          $(el).html(self.user);

          break;
      }
    });
};

Calendar.prototype.view = function (week) {
  var info = $.get('view.php?timestamp=' + this.timestamp + '&week=' + week, function (data) {
    data = $.parseJSON(data);
    var info = data.info;

    $.each(data.hours, function (day, hours) {
      $.each(hours, function (hour, dj) {
        var selector = $('#' + day + '-' + hour),
            cell_name = '--';

        if (dj.length > 0) {
          cell_name = dj;
        }

        var cell = "<span onclick='cal.claim(this, " + day + ", " + hour + ")'>";
        cell += cell_name;
        cell += "</span>";

        $(selector).html(cell);

        // Week of <?=$mondayMonth?> <?=$monday?> - <?=$sundayMonth?> <?=$sunday?>, <?=$year?>
        var header = 'Week of ' + info.monday_month + ' ' + info.monday;
        header += ' - ';
        header += info.sunday_month + ' ' + info.sunday;
        header += ', ' + info.year;

        $('#header-info').html(header);
      });
    });
  });
};

Calendar.prototype.weekPrevious = function () {
  this.timestamp -= (86400 * 7);
  this.week -= 1;
  this.view(this.week);
};

Calendar.prototype.weekNext = function () {
  this.timestamp += (86400 * 7);
  this.week += 1;
  this.view(this.week);
};
