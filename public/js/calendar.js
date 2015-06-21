var Calendar = function () {
};

Calendar.prototype.can_edit = false;
Calendar.prototype.can_edit =  false,
Calendar.prototype.level =  0,
Calendar.prototype.user =  '',
Calendar.prototype.user_encrypted =  '',
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
  var info = $.get('view.php?week=' + week, function (data) {
    data = $.parseJSON(data);

    $.each(data, function (day, hours) {
      $.each(hours, function (hour, dj) {
        var selector = $('#' + day + '-' + hour);

        if (dj.length == 0) {
          $(selector).html('--');
        } else {
          $(selector).html(dj);
        }
      });
    });
  });
}
