function Calendar(id, year, month) {
    var lastDay = new Date(year, month + 1, 0).getDate();
    var lastMounthDays = new Date(year, month, 0).getDate();
    var lastDayFull = new Date(year, month, lastDay);
    var lastDayMonth = new Date(lastDayFull.getFullYear(), lastDayFull.getMonth(), lastDay).getDay();
    var firstDayMonth = new Date(lastDayFull.getFullYear(), lastDayFull.getMonth(), 1).getDay();
    var calendar = '';
    var month = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль","Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];

    if (firstDayMonth != 0) {
        for(var i = 1; i < firstDayMonth; i++) calendar += (`<div class="form_radio_btn"><input id="last-${lastMounthDays + i + 1 - firstDayMonth}" type="radio" name="radio" value=""><label for="last-${lastMounthDays + i + 1 - firstDayMonth}">` + (lastMounthDays + i + 1 - firstDayMonth) + "</label></div>");
    } else {
        for(var i = 0; i < 6; i++) calendar += (`<div class="form_radio_btn"><input id="last-${lastMounthDays + i - 5}" type="radio" name="radio" value=""><label for="last-${lastMounthDays + i - 5}">` + (lastMounthDays + i - 5) + "</label></div>");
    }

    for(var i = 1; i <= lastDay; i++) {
        if (i == new Date().getDate() && lastDayFull.getFullYear() == new Date().getFullYear() && lastDayFull.getMonth() == new Date().getMonth()) {
            calendar += '<div class="today">' + i + '</div>';
        } else {
            calendar += (`<div class="form_radio_btn"><input id="now-${i}" type="radio" name="radio" value=""><label for="now-${i}">` + i + "</label></div>");
        }
    }

    for(var i = 1; i <= 7 - lastDayMonth; i++) {
        calendar += (`<div class="form_radio_btn"><input id="prev-${i}" type="radio" name="radio" value=""><label for="prev-${i}">` + i + "</label></div>");
    };

    document.querySelector('#' + id + ' .calendar-registration-title .calendar-days').innerHTML = calendar;

    document.querySelector('#' + id + ' .calendar-title-mounth .calendar-mounth').innerHTML = month[lastDayFull.getMonth()] + ' ' + lastDayFull.getFullYear();

    document.querySelector('#' + id + ' .calendar-title-mounth .calendar-mounth').dataset.month = lastDayFull.getMonth();

    document.querySelector('#' + id + ' .calendar-title-mounth .calendar-mounth').dataset.year = lastDayFull.getFullYear();

    // if (document.querySelectorAll('#' + id +' tbody tr').length < 6) {
    //     document.querySelector('#' + id + ' tbody').innerHTML += '<tr><td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;';
    // }
}

Calendar("calendar", new Date().getFullYear(), new Date().getMonth());



document.querySelector('#calendar .calendar-registration-title .calendar-title-mounth .last-mounth-btn').onclick = function() {
  Calendar("calendar", document.querySelector('#calendar .calendar-registration-title .calendar-title-mounth .calendar-mounth').dataset.year, parseFloat(document.querySelector('.calendar-registration-title .calendar-title-mounth .calendar-mounth').dataset.month) - 1);
}

let v = parseFloat(document.querySelector('#calendar .calendar-registration-title .calendar-title-mounth .calendar-mounth').dataset.month);


document.querySelector('#calendar .calendar-registration-title .calendar-title-mounth .next-mounth-btn').onclick = function() {
  Calendar("calendar", document.querySelector('.calendar-registration-title .calendar-title-mounth .calendar-mounth').dataset.year, parseFloat(document.querySelector('#calendar .calendar-registration-title .calendar-title-mounth .calendar-mounth').dataset.month) + 1);
}

