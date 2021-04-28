if(VIEW_ONLY == 0){
  var init = (min = 10, max = 100) => {
    if($('#id-data').text() == '') {
      return {
      'id': '.app-timer',
      'min': min,
      'max': max,
      'seek': 0
      }
    }else {
      return {
      'id': $('#id-data').text(),
      'min': parseInt($('#min-time-data').text()),
      'max': parseInt($('#max-time-data').text()),
      'seek': parseInt($('#seek-data').text())
      }
    } 
  }

  var TIME_LIMIT = init()['max'];
  var MIN_TIME = init()['min'];
  var FULL_DASH_ARRAY = 283;
  var WARNING_THRESHOLD = TIME_LIMIT * 0.6;
  var ALERT_THRESHOLD = TIME_LIMIT * 0.2;
  var ID = init()['id']; 
  var html_id = '';

  var COLOR_CODES = {
    info: {
      color: "green"
    },
    warning: {
      color: "orange",
      threshold: WARNING_THRESHOLD
    },
    alert: {
      color: "red",
      threshold: ALERT_THRESHOLD
    }
  };
  var timePassed = init()['seek'];
  var timeLeft = TIME_LIMIT;
  var timerInterval = null;
  var remainingPathColor = COLOR_CODES.info.color;
  var TIMEUP = false;

  var start_nutshell = (target) => {  

    $('.start').css({
      'background': 'gray',
      'transition': 'background 0.5s'
    });
    
    $('.start').not(target).toggleClass('ttt icon-lock');
    $('.start').not(target).removeClass('start');
    
    startTimer();
    $(target).toggleClass('icon-play icon-square');
    $(target).css({
      'transform': 'scale(0.75)',
      'transition': 'transform 0.5s',
      'background': ' rgb(65, 184, 131)'
    });
    $(target).toggleClass('start stop')
    EL = target;

    if(ID == '.app-timer'){
      html_id = $(target).parents('.base-timer').parent().attr('id');
    }

  }

  if(ID == '.app-timer'){
    var EL = {};
  }else{
    start_nutshell($(ID).find('.start'));
  }

  function stopTimer() {
    clearInterval(timerInterval);
  }


  function startTimer() {
    timerInterval = setInterval(() => {
      timePassed = timePassed += 1;
      timeLeft = TIME_LIMIT - timePassed;

      if(ID == '.app-timer'){
        $(EL).parents('.base-timer').siblings('.base-timer-label').html(formatTime(timeLeft));
        $(EL).parents('.base-timer').siblings('.not-started').remove();
        
      }else{
        $(ID).find(".base-timer-label").html(formatTime(timeLeft));
      }
      
      setCircleDasharray();
      setRemainingPathColor(timeLeft);

      if (timeLeft < 0) {
        TIMEUP = true;
      }
    }, 1000);
  }

  function formatTime(time) {
    let time_var = Math.abs(parseInt(time / 60));
    let minutes = (time_var > 59) ? (time_var - (60 * Math.floor( time_var/60 ))) : time_var;
    let hours = parseInt(time_var/60);
    let seconds = time % 60;
    seconds = Math.abs(seconds);
    minutes = Math.abs(minutes);
    hours = Math.abs(hours);
    
    if (seconds < 10) {
      seconds = '0' + String(seconds);
    }
    if(minutes < 10){
      minutes = '0' + String(minutes);
    }
    if(hours < 10){
      hours = '0' + String(hours);
    }
    if(TIMEUP){
      return `<br>Overdue: <b style="color:red">${hours} : ${minutes} : ${seconds}</b>`;
    }else{
      return `<br>Countdown: <b>${hours} : ${minutes} : ${seconds}</b>`;
    }	
    
  }

  function setRemainingPathColor(timeLeft) {
    const { alert, warning, info } = COLOR_CODES;
    if (timeLeft <= alert.threshold) {

      if(ID == '.app-timer'){
        $(EL).siblings('.base-timer__svg').find('.base-timer__path-remaining')
        .removeClass(warning.color);
        $(EL).siblings('.base-timer__svg').find('.base-timer__path-remaining')
        .addClass(alert.color);
        $(EL).addClass('red');
      }else{
        $(ID).find('.base-timer__path-remaining')
        .removeClass(warning.color);
        $(ID).find('.base-timer__path-remaining')
        .addClass(alert.color);
        $(ID).find('.timer-overlay').addClass('red');
      }
    
    } 
    else if (timeLeft <= warning.threshold) {

      if(ID == '.app-timer'){
        $(EL).siblings('.base-timer__svg').find('.base-timer__path-remaining')
        .removeClass(info.color);

        $(EL).siblings('.base-timer__svg').find('.base-timer__path-remaining')
        .addClass(warning.color);
        $(EL).addClass('orange');
      }else{
        $(ID).find('.base-timer__path-remaining')
        .removeClass(info.color);
        $(ID).find('.base-timer__path-remaining')
        .addClass(warning.color);
        $(ID).find('.timer-overlay').addClass('orange');
      }

      
    }
  }

  function calculateTimeFraction() {
    const rawTimeFraction = timeLeft / TIME_LIMIT;
    return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
  }

  function setCircleDasharray() {
    const circleDasharray = `${(
      calculateTimeFraction() * FULL_DASH_ARRAY
    ).toFixed(0)} 283`;
    $(ID).find('.base-timer__path-remaining')
      .attr("stroke-dasharray", circleDasharray);
  }

}else{
  var TIME_LIMIT = [];
  var FULL_DASH_ARRAY = [];
  var WARNING_THRESHOLD = [];
  var ALERT_THRESHOLD = [];
  var timePassed = [];
  var EL = [];
  var timeLeft = [];
  var timerInterval = [];
  var remainingPathColor = [];
  var COLOR_CODES = [];
  var TIMEUP = [];
  for(let i=0; i < $('.view-only').length; i++){
    if($('.view-only').eq(i).data('job-progress') == 1){
      
      TIME_LIMIT[i] = $('.view-only').eq(i).data('time-limit');
      
      // var MIN_TIME = init()['min'];
      FULL_DASH_ARRAY[i] = 283;
      WARNING_THRESHOLD[i] = TIME_LIMIT[i] * 0.6;
      ALERT_THRESHOLD[i] = TIME_LIMIT[i] * 0.2;
      // ID = init()['id']; 
      // html_id = '';

      COLOR_CODES[i] = {
        info: {
          color: "green"
        },
        warning: {
          color: "orange",
          threshold: WARNING_THRESHOLD[i]
        },
        alert: {
          color: "red",
          threshold: ALERT_THRESHOLD[i]
        }
      };
      timePassed[i] = $('.view-only').eq(i).data('seek');
      EL[i] = $('.view-only').eq(i);
      timeLeft[i] = TIME_LIMIT[i];
      timerInterval[i] = null;
      remainingPathColor[i] = COLOR_CODES[i].info.color;
      TIMEUP[i] = false;

      startTimer();
      function startTimer() {
        timerInterval[i] = setInterval(() => {
          timePassed[i] = timePassed[i] += 1;
          timeLeft[i] = TIME_LIMIT[i] - timePassed[i];

          
            $(EL[i]).parents('.base-timer').siblings('.base-timer-label').html(formatTime(timeLeft[i]));
          
          
          setCircleDasharray();
          setRemainingPathColor(timeLeft[i]);

          if (timeLeft[i] < 0) {
            TIMEUP[i] = true;
          }
        }, 1000);
      }

      function formatTime(time) {
        let time_var = Math.abs(parseInt(time / 60));
        let minutes = (time_var > 59) ? (time_var - (60 * Math.floor( time_var/60 ))) : time_var;
        let hours = parseInt(time_var/60);
        let seconds = time % 60;
        seconds = Math.abs(seconds);
        minutes = Math.abs(minutes);
        hours = Math.abs(hours);
        
        if (seconds < 10) {
          seconds = '0' + String(seconds);
        }
        if(minutes < 10){
          minutes = '0' + String(minutes);
        }
        if(hours < 10){
          hours = '0' + String(hours);
        }
        if(TIMEUP[i]){
          return `<br>Overdue: <b style="color:red">${hours} : ${minutes} : ${seconds}</b>`;
        }else{
          return `<br>Countdown: <b>${hours} : ${minutes} : ${seconds}</b>`;
        }	
        
      }

      function setRemainingPathColor(timeLeft) {
        const { alert, warning, info } = COLOR_CODES[i];
        if (timeLeft <= alert.threshold) {

          $(EL[i]).siblings('.base-timer__svg').find('.base-timer__path-remaining')
          .removeClass(warning.color);
          $(EL[i]).siblings('.base-timer__svg').find('.base-timer__path-remaining')
          .addClass(alert.color);
          $(EL[i]).addClass('red');
  
        } 
        else if (timeLeft <= warning.threshold) {

         
          $(EL[i]).siblings('.base-timer__svg').find('.base-timer__path-remaining')
          .removeClass(info.color);

          $(EL[i]).siblings('.base-timer__svg').find('.base-timer__path-remaining')
          .addClass(warning.color);
          $(EL[i]).addClass('orange');
          

          
        }
      }

      function calculateTimeFraction() {
        const rawTimeFraction = timeLeft[i] / TIME_LIMIT[i];
        return rawTimeFraction - (1 / TIME_LIMIT[i]) * (1 - rawTimeFraction);
      }

      function setCircleDasharray() {
        const circleDasharray = `${(
          calculateTimeFraction() * FULL_DASH_ARRAY[i]
        ).toFixed(0)} 283`;


        $(EL[i]).siblings('.base-timer__svg').find('.base-timer__path-remaining')
          .attr("stroke-dasharray", circleDasharray);


      }


      }
    }
}
