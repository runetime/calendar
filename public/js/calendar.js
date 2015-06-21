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

Calendar.prototype.clear = function () {
  // If they can't edit, then let's do nothing.
  if (this.can_edit === false) {
    return;
  }

  var results = confirm('Are you sure you want to clear this week?');

  if (!results) {
    return;
  }

  var param_list = {
    level: this.level,
    user: this.user_encrypted,
    week: this.week
  };

  var self = this;

  $.post('clear.php', $.param(param_list))
    .done(function(data) {
      switch (data) {
        case ':)':
          self.view(self.week);
          alert('This week was successfully cleared.');

          break;
        case ':(':
          alert('There was an error clearing this week!');

          break;
      }
    });
};

Calendar.prototype.view = function (week) {
  $.get('view.php?timestamp=' + this.timestamp + '&week=' + week, function (data) {
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

        var header = 'Week of ' + info.monday + ' of ' + info.monday_month;
        header += ' - ';
        header += info.sunday + ' of ' + info.sunday_month;
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
