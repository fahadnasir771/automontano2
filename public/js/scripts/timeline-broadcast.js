// ---- PUSHER ----
Pusher.logToConsole = false;

var pusher = new Pusher('3a5e5ed0226f48310b94', {
  cluster: 'ap2'
});
var channel = pusher.subscribe('my-channel');

// GLOBAL
// -------------- PRoGRESS --------------
const GREEN = '#238f23';
const RED = '#dc3545';
const PURPLE = 'purple';
const GRAY = 'gray';
const SKY = 'skyblue';
const BLUE = 'royalblue';

// -------------- TIME --------------
var SEC_PX = 1.43020833;
var SCROLL_PX = 1.41918981;
var scrollinc = 0;
var current_pos = $('#timeline-column').scrollLeft();
var navigate = false;

// Navigation tikme line
$('.nav-now').on('click', function(){
  if(navigate){
    navigate = false;
    $('.nav-now').removeClass('bg-gradient-success');
    $('.nav-now').addClass('bg-gradient-danger');
    
    move_to_time();
  }else{
    navigate = true;
    $('.nav-now').removeClass('bg-gradient-danger');
    $('.nav-now').addClass('bg-gradient-success');
  }

}); 

function time_passed(init_time){
  let pos = init_time * SEC_PX;
  let sec = SEC_PX;
  $('.timeline').css({
    'left': (parseFloat(pos + sec) ) + 'px'
  });

  if(!navigate){
    // greater than equal to 16hrs
    if(parseInt(pos + sec) >= (sec * 60 * 60 * 16)){
      current_pos += (init_time * SCROLL_PX) + 300;
      $("#timeline-column").animate({
          scrollLeft: (init_time * SCROLL_PX) + 300 //scroll into seconds
      }, 800);
    } // greater than equal to 10hrs
    else if(parseInt(pos + sec) >= (sec * 60 * 60 * 10)){
      current_pos += (init_time * SCROLL_PX) + 100;
      $("#timeline-column").animate({
          scrollLeft: (init_time * SCROLL_PX) + 100 //scroll into seconds
      }, 800);
    } // less than 10hrs
    else if(parseInt(pos + sec) < (sec * 60 * 60 * 10)){
      current_pos += (init_time * SCROLL_PX) -200;
      $("#timeline-column").animate({
          scrollLeft: (init_time * SCROLL_PX) -200 //scroll into seconds
      }, 800);
    }

  }
    
}
//move the line to current time
function move_to_time(){
  $("#timeline-column").animate({
    scrollLeft: current_pos
  }, 800);  
}

var date; /* creating object of Date class */
var hour;
var min;
var sec;
//curren time // Calling function every second to move the line every second
var time_passed_seconds = 0;
function currentTime() {
  date = new Date();
  // date.setHours(23)
  // date.setMinutes(59)
  // console.log(date);
  hour = updateTime(date.getHours());
  min = updateTime(date.getMinutes());
  sec = updateTime(date.getSeconds());

  scrollinc += 1;

  let time_passed_seconds = parseInt(hour * 60 * 60) + parseInt(min * 60) + parseInt(sec);

  
  
  // time_passed_seconds += 1;
  time_passed(time_passed_seconds);
  progress(time_passed_seconds);
  TIME = time_passed_seconds
  
  $('.current-time').text(hour + " : " + min + " : " + sec);
  
  var t = setTimeout(currentTime, 1000); /* setting timer */
}
function updateTime(k) { /* appending 0 before time elements if less than 10 */
  if (k < 10) {
    return "0" + k;
  }
  else {
    return k;
  }
}

currentTime();




function progress(time) {

  if(time * SEC_PX >= 123565) {
    window.location.reload(false); 
  }

  // configurations
  for(let i=0; i < $('.progressbar2').length; i++){

    // Finding In progress Job -> 1
    let $el_job_progress;
    let has_job_progress = false;
    for(let k=0; k < $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').length; k++){
      if($('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').eq(k).attr('data-status') == '1'){
        $el_job_progress = $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').eq(k);
        has_job_progress = true;
        break;
      }
    }

    // Finding Job FInished -> 2
    let $el_job_finished;
    let $el_job_finished_main;
    let has_job_finished = false;
    for(let k=0; k < $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').length; k++){
      if($('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').eq(k).attr('data-status') == '2'){
        $el_job_finished = $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').eq(k);
        has_job_finished = true;
        for(let j=0; j < $('.progressbar2').eq(i).find('.main-bar').length; j++){
          if($('.progressbar2').eq(i).find('.main-bar').eq(j).attr('data-objects-id') == $el_job_finished.attr('data-worksheet-id')){
            $el_job_finished_main = $('.progressbar2').eq(i).find('.main-bar').eq(j)
            break
          }
        }
        break;
      }
    }

    // Job Finshed
    if(has_job_finished){

      $el_job_finished_main.attr('data-mode', 'work-in-progress');
      $el_job_finished.attr('data-status', 3);
      parseInt($el_job_finished_main.attr('data-jobs-done', parseInt($el_job_finished_main.attr('data-jobs-done')) + 1));

      // if the finishing job has icreasing width attribute
      if(
        $el_job_finished.attr('data-mode2') == 'increase-width' || 
        $el_job_finished.attr('data-mode2') == 'increase-width-wait' ||
        $el_job_finished.attr('data-mode2') == 'increase-width2'
      ){

        $el_job_finished.attr('data-mode2', 'normal');
        // Determining which job index is in progress
        if(parseInt($el_job_finished_main.attr('data-jobs-done')) < parseInt($el_job_finished_main.attr('data-jobs'))){
          let $el_worksheet_id = parseInt($el_job_finished.attr('data-worksheet-id'));
          
          for(let j = 0; j < $el_job_finished.parents('.progressbar3').find('.secondary-bar').length; j++) {
            let $el_iterating = $el_job_finished.parents('.progressbar3').find('.secondary-bar').eq(j);
            if(
              parseInt($el_iterating.attr('data-worksheet-id')) == $el_worksheet_id &&
              parseInt($el_iterating.attr('data-status')) == 0
            ){
              $el_iterating.attr('data-mode2', 'increase-left');
            }
          }
          $el_job_finished.parents('.progressbar3').append(
          `
            <div class="bar secondary-bar" data-left="` + ( parseFloat($el_job_finished.attr('data-left')) + parseFloat($el_job_finished.attr('data-width')) ) + `" data-color="` + GRAY + `" data-width="` + 0 + `" data-mode="delay1"  data-mode2="" style="display: none"></div>
          `
          );

        }
        // Finish of the whole worksheet if he is passes the max time of the job
        else if(parseInt($el_job_finished_main.attr('data-jobs-done')) == parseInt($el_job_finished_main.attr('data-jobs'))){

          // if job late is present then pause where it is
          for(let k=0; k < $('.progressbar2').eq(i).find('.main-bar').length; k++){
            let $el112 = $('.progressbar2').eq(i).find('.main-bar').eq(k);
            
            if($el112.attr('data-mode') == 'job-late'){
              $el112.attr('data-mode', 'normal');
            }
          }

          // Find skyblue bar and slice it or remove it
          for(let k=0; k < $('.progressbar2').eq(i).find('.main-bar').length; k++){
            let $el112 = $('.progressbar2').eq(i).find('.main-bar').eq(k);
            
            if($el112.attr('data-mode') == 'pre-object-early-2'){
              let exp1 = parseFloat($el_job_finished.attr('data-left')) + parseFloat($el_job_finished.attr('data-width'))
              let exp2 = parseFloat($el112.attr('data-left')) + parseFloat($el112.attr('data-width'))
              
              let slice = exp2 - exp1
              let hehe39 = parseFloat($el112.attr('data-width')) - slice;

              $el112.attr('data-width', hehe39)

              break;
            }
          }
        }
      
        
      }else{

        if(parseInt($el_job_finished_main.attr('data-jobs-done')) < parseInt($el_job_finished_main.attr('data-jobs'))){
          // slice the job upto the time and make it green
          $el_job_finished.attr('data-color', GREEN)
          let sliced = ((time * SEC_PX) - parseFloat($el_job_finished.attr('data-left')));
          let remained = ((parseFloat($el_job_finished.attr('data-left')) + parseFloat($el_job_finished.attr('data-width'))) - parseFloat($el_job_finished.attr('data-left')) ) - sliced;
          $el_job_finished.attr('data-width', sliced)

          // changin next elementsto pre object 3
          for(let j=0; j < $el_job_finished_main.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').length; j++){
            let $sec_el_id = $el_job_finished_main.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').eq(j);

            if(
              parseInt($el_job_finished.attr('data-worksheet-id')) == parseInt($sec_el_id.attr('data-worksheet-id')) &&
              parseInt($sec_el_id.attr('data-status')) == 0
            ){
              $sec_el_id.attr('data-mode', 'pre-object3')
            }
          }

          $el_job_finished.parents('.progressbar3').append(
          `
            <div class="bar secondary-bar" data-left="` + ( parseFloat($el_job_finished.attr('data-left')) + parseFloat($el_job_finished.attr('data-width')) ) + `" data-worksheet-id="` + $el_job_finished.attr('data-worksheet-id') + `" data-color="` + SKY + `" data-width="` + remained + `" data-mode="early-pre-object-bonus"  data-mode2="" style="display: none"></div>
          `
          );
        }

        else if(parseInt($el_job_finished_main.attr('data-jobs-done')) == parseInt($el_job_finished_main.attr('data-jobs'))){
          $el_job_finished.attr('data-color', GREEN)
          let sliced = ((time * SEC_PX) - parseFloat($el_job_finished.attr('data-left')));
          $el_job_finished.attr('data-width', sliced);

          let $el7328; // normal job late
          let $el7328_exists = false;
          for(let w=0; w < $el_job_finished.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').length ; w++){
          
            $el7328 = $el_job_finished.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').eq(w)
            if($el7328.attr('data-mode') == 'normal-job-late'){
              $el7328.attr('data-mode', 'normal')
              $el7328 = $el_job_finished.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').eq(w)
              $el7328_exists = true;
              break
            }
          }

          if(
            parseInt(parseFloat($el_job_finished.attr('data-left')) + parseFloat($el_job_finished.attr('data-width'))) <
            parseInt(parseFloat($el7328.attr('data-left')) + parseFloat($el7328.attr('data-width'))) &&
            $el7328_exists == true
          ){
            
            let hehe = (time * SEC_PX) - parseFloat($el7328.attr('data-left'))
            if(hehe < 0){
              $el7328.remove()
            }else{
              $el7328.attr('data-width', hehe);
            }
            
            
          }

          // if even less than the main worksheet time
          let exp45 = parseFloat($el_job_finished_main.attr('data-left')) + parseFloat($el_job_finished_main.attr('data-width'))
          let exp46 = parseFloat($el_job_finished.attr('data-left')) + parseFloat($el_job_finished.attr('data-width'))
          
          if(exp46 < exp45){
            let slice34 = exp45 - exp46;
            let hehe6763 = parseFloat($el_job_finished_main.attr('data-width')) - slice34;
            $el_job_finished_main.attr('data-width', hehe6763);
          }

          // Find skyblue bar and slice it or remove it
          for(let k=0; k < $('.progressbar2').eq(i).find('.main-bar').length; k++){
            let $el112 = $('.progressbar2').eq(i).find('.main-bar').eq(k);
            
            if($el112.attr('data-mode') == 'pre-object-early-2'){
              let exp1 = parseFloat($el_job_finished.attr('data-left')) + parseFloat($el_job_finished.attr('data-width'))
              let exp2 = parseFloat($el112.attr('data-left')) + parseFloat($el112.attr('data-width'))

              if(exp1 < parseFloat($el112.attr('data-left'))){
                $el112.remove();
              }else{
                let slice = exp2 - exp1
                let hehe39 = parseFloat($el112.attr('data-width')) - slice;

                $el112.attr('data-width', hehe39)
              }


              break;
            }
          }

        }

      }
    }

    // Early bonus Job
    for(let j=0; j < $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').length; j++){
      let $el113 = $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').eq(j); // secondary bar
      if(
        parseInt((time * SEC_PX)) == parseInt( parseFloat($el113.attr('data-left')) + parseFloat($el113.attr('data-width')) ) &&
        $el113.attr('data-mode') == 'early-pre-object-bonus'
      ){
        $el113.attr('data-mode', 'normal')
        // Move forward all the next tasks
        for(let k=0; k < $el113.parents('.progressbar3').find('.secondary-bar').length ; k++){
          let $el114 = $el113.parents('.progressbar3').find('.secondary-bar').eq(k) // secondary bar

          if(
            parseInt($el113.attr('data-worksheet-id')) == parseInt($el114.attr('data-worksheet-id')) &&
            parseInt($el114.attr('data-status')) == 0
          ){
            $el114.attr('data-mode2', 'increase-left');
          }

        }

        // append gray delay bar in 3rd line
        $el113.parents('.progressbar3').append(
        `
          <div class="bar secondary-bar" data-left="` + ( parseFloat($el113.attr('data-left')) + parseFloat($el113.attr('data-width')) ) + `" data-color="` + GRAY + `" data-width="0" data-mode="delay1"  data-mode2="" style="display: none"></div>
        `
        );

        
        let $el116 = {}; // work in progress
        let $el118 = {}; // job late
        let $el118_has_job_late = false;
        for(let l=0; l < $el113.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').length ; l++){
          let $el117 = $el113.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').eq(l)
          if(
            $el117.attr('data-mode') == 'work-started-after' ||  
            $el117.attr('data-mode') == 'work-started-early' ||
            $el117.attr('data-mode') == 'work-in-progress' ||
            $el117.attr('data-mode') == 'work-started-in' 

          ){
            $el116 = $el117;
          }
          if($el117.attr('data-mode') == 'normal-job-late'){
            $el118 = $el117;
            $el118_has_job_late = true;
          }
        }
        if($el118_has_job_late){
          $el118.attr('data-mode', 'job-late')
        }else{
          // pre-object-early-2
          let $el645_has_early = false;
          for(let k=0; k < $el113.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').length ; k++) {
            $el4958 = $el113.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').eq(k);

            if($el4958.attr('data-mode') == 'pre-object-early-2'){
              $el645_has_early = true;
              break;
            }
          }

          if(!$el645_has_early){
            $('.progressbar2').eq(i).append(
              `
                <div class="bar main-bar" data-left="` + (parseFloat($el116.attr('data-left')) + parseFloat($el116.attr('data-width'))) + `" data-color="#dc3545"  data-width="0" data-mode="job-late"  data-objects-id="-1"></div>
              `
            )
          }
          
        }
          
        


      }
    }

    // if gray bar prsent so takecare of early bar
    let $el3478_has_bonus = false;
    let $el3478_bonus = '';
    for(let j=0; j < $('.progressbar2').eq(i).find('.main-bar').length; j++){
      $el39748 = $('.progressbar2').eq(i).find('.main-bar').eq(j);

      if($el39748.attr('data-mode') == 'pre-object-early-2'){
        $el3478_has_bonus = true;
        $el3478_bonus = $el39748;
        break;
      }
    }
    
    for(let j=0; j < $('.progressbar2').eq(i).find('.main-bar').length; j++){
      $el438 = $('.progressbar2').eq(i).find('.main-bar').eq(j);

      if(
        $el438.attr('data-mode') == 'work-in-progress' 
      ){
        for(let k=0; k < $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').length; k++){
          let $el8467 = $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').eq(k);

          if(
            $el8467.attr('data-worksheet-id') == $el438.attr('data-objects-id') &&
            $el8467.attr('data-position') == 'last'
          ){
            
            // Main working logic
            if($el3478_has_bonus){
              for(let j=0; j < $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').length; j++){
                let $el3978 = $('.progressbar2').eq(i).siblings('.progressbar3').find('.secondary-bar').eq(j);
                let exp1 = parseFloat($el3478_bonus.attr('data-width')) + parseFloat($el3478_bonus.attr('data-left'))
                // console.log($el29_last_obj.attr('data-left'));
                let exp2 = parseFloat($el8467.attr('data-left')) + parseFloat($el8467.attr('data-width'))
                
                if(
                  $el3978.attr('data-mode') == 'delay1' &&
                  parseInt(exp1) == parseInt(exp2)
                ){
                  $('.progressbar2').eq(i).append(
                    `
                      <div class="bar main-bar" data-left="` + exp1 + `" data-color="` + RED + `"  data-width="0" data-mode="job-late" data-objects-id="-1"></div>
                    `
                  )
                }
              }
            }


            break
          }
        }
        break;
      }
    }
    
    

    // Ensures that the job late red bar is already present
    let has_normal_job_late = false;
    let normal_job_late = {};
    for(let j=0; j < $('.progressbar2').eq(i).find('.main-bar').length; j++){
      if(
        $('.progressbar2').eq(i).find('.main-bar').eq(j).attr('data-mode') == 'normal-job-late' ||
        $('.progressbar2').eq(i).find('.main-bar').eq(j).attr('data-mode') == 'job-late'
      ){
        has_normal_job_late = true;
        normal_job_late = $('.progressbar2').eq(i).find('.main-bar').eq(j)
      }
    }
    
    
    // early_job_counter[i] = 0;
    for(let j=0; j < $('.progressbar2').eq(i).find('.main-bar').length; j++){

      // Element
      let $el = $('.progressbar2').eq(i).find('.main-bar').eq(j);
      let $el_secondary_last = $el.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar:last-child'); // last (Default)

      // Progressbar
      let progressbar2 = $el.parents('.progressbar2');

      // Attributes (Main Bar)
      let left = parseFloat($el.attr('data-left'));
      let width = parseFloat($el.attr('data-width'));
      let mode = $el.attr('data-mode');
      let text = $el.text();
      let dataid = $el.attr('id');
      let datanext = $el.data('next');
      let current_time = time * SEC_PX;
      let $next_el = $('#' + datanext);
      let datajobs = $el.attr('data-jobs')
      let datajobsdone = $el.attr('data-jobs-done')
      let dataobjectsid = $el.attr('data-objects-id')

      // Attributes (Job in Progress - jip)
      if(has_job_progress){
        var jip_left = parseFloat($el_job_progress.attr('data-left'));
        var jip_width = parseFloat($el_job_progress.attr('data-width'));
        var jip_mode = $el_job_progress.attr('data-mode');
        var jip_worksheet = $el_job_progress.attr('data-worksheet-id');
        var jip_object_index = $el_job_progress.attr('data-object-index');
        var jip_mode2 = $el_job_progress.attr('data-mode2');
      }


      // Template
      let pb2_temp = (left, color, width, mode, text='', jobs=datajobs, jobs_done=datajobsdone, data_objects_id='-1' ) => 
      `<div 
        class="bar main-bar" 
        data-left="${left}" 
        data-color="${color}" 
        data-width="${width}" 
        data-mode="${mode}" 
        data-jobs="${jobs}" 
        data-jobs-done="${jobs_done}"
        data-objects-id="${data_objects_id}"
      >${text}</div>`;

      // 1: If work started in and passes the whole time of job 
      if(
        (mode == 'work-started') &&
        has_job_progress == true
      ){
        if(
          (parseInt(parseFloat($el_job_progress.attr('data-left')) + parseFloat($el_job_progress.attr('data-width'))) <= parseInt(current_time)) &&
          (jip_mode == 'pre-object' || jip_mode == 'pre-object2' || jip_mode == 'pre-object3') &&
          jip_mode2 != 'increase-width'
        ){
            
            // if skyblue bar in 2nd row is present pre-object-early-2
            let $el_has_bonus = false;
            let $el423_bonus = '';
            for(let k=0; k < $el.parents('.progressbar2').find('.main-bar').length; k++){
              let $el111 = $el.parents('.progressbar2').find('.main-bar').eq(k);
              if($el111.attr('data-mode') == 'pre-object-early-2') {
                $el_has_bonus = true;
                $el423_bonus = $el111;
              }
            }

            if(has_normal_job_late){
              normal_job_late.attr('data-mode', 'job-late');
            }else{
              let $el_last_obj = '';
              for(let k=0; k < $el.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').length; k++){
                let $el11543 = $el.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').eq(k);
                if($el11543.attr('data-position') == 'last') {
                  $el_last_obj = $el11543;
                }
              }
              let expression1;
              let expression2;
              if($el_has_bonus){
                expression1 = parseFloat($el423_bonus.attr('data-left')) + parseFloat($el423_bonus.attr('data-width'))
                expression2 = parseFloat($el_last_obj.attr('data-left')) + parseFloat($el_last_obj.attr('data-width'))
              }
              

              if(
                $el_has_bonus &&
                expression2 <= expression1
              ){
                // DO NOTHING
              }else{
                
                if($el_has_bonus){
                  progressbar2.append(pb2_temp(expression1, RED, 0, 'job-late'))
                }else{
                  progressbar2.append(pb2_temp((left+width), RED, 0, 'job-late'))
                }
                if($el_has_bonus){
                  $el423_bonus.attr('data-mode', 'normal')
                }
                
              }
              
              
            } 

            if($el_has_bonus){
              $el_job_progress.attr('data-mode2', 'increase-width2')
            }else{
              $el_job_progress.attr('data-mode2', 'increase-width')
            }

            // pre-object left increase
            for(let k=0; k < $el.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').length; k++){
              let $el111 = $el.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').eq(k);
              if(parseInt($el.attr('data-objects-id')) == parseInt($el111.attr('data-worksheet-id'))) {
                if(parseInt( $el111.attr('data-status')) == 0 ){
                  $el111.attr('data-mode2', 'increase-left')
                }
              }
            }
            
            $el_job_progress.attr('data-color', RED)
          
        }
      }

    }
  }

  render_timeline()
}


// Start work
// Pusher worksheet job
channel.bind('worksheetJob', function(data) {
  if(data.started == 1){
    
    bootstrap(data.operator, data.main, data.secondary, data.append_html);
    OPERATOR_ID = data.operator;
  }
  if(data.stopped == 1){
    halt(data.operator);
  }
});
var TIME;

function bootstrap(id, main, sec, html_on){

  if(html_on){
    $('#' + id).siblings('.progressbar2').append(main);
    $('#' + id).append(sec); 
  }

  for(let i =0; i < $('#' + id).siblings('.progressbar2').find('.main-bar').length; i++ ){
    let $el_start = $('#' + id).siblings('.progressbar2').find('.main-bar').eq(i);

    if($el_start.attr('data-mode') == 'work-not-started' ||
       $el_start.attr('data-mode') == 'work-in-progress'
    ){

      for(let j=0; j < $el_start.parents('.progressbar2').find('.main-bar').length; j++){
        if($el_start.parents('.progressbar2').find('.main-bar').eq(j).attr('data-mode') == 'job-late'){
          $el_start.parents('.progressbar2').find('.main-bar').eq(j).attr('data-mode', 'normal-job-late');
        }
      }

      $el_start.attr('data-mode', 'work-started');

      let main_bar_objects_id = parseInt($el_start.attr('data-objects-id'));
      
      for(let j=0; j < $el_start.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').length; j++){
        let $sec_el_id = $el_start.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').eq(j);
        if($sec_el_id.attr('data-mode') == 'delay1'){
          $sec_el_id.attr('data-mode', 'normal-delay1');

        }
        if($sec_el_id.attr('data-mode2') == 'increase-left' || $sec_el_id.attr('data-mode2') == 'increase-width'){
          $sec_el_id.attr('data-mode2', 'normal');
          $sec_el_id.attr('data-mode', 'pre-object2');

        }
      }

      for(let j=0; j < $el_start.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').length; j++){
        let $sec_el_id = $el_start.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').eq(j);
        if(
          parseInt($sec_el_id.attr('data-worksheet-id')) == main_bar_objects_id &&
          parseInt($sec_el_id.attr('data-status')) == 0
        ){
          $sec_el_id.attr('data-status', 1);
          $sec_el_id.attr('data-color', PURPLE);
          break;
        }
      }

      


      for(let j=0; j < $el_start.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').length; j++){
        $el_early_pre_object_bonus = $el_start.parents('.progressbar2').siblings('.progressbar3').find('.secondary-bar').eq(j);
        if($el_early_pre_object_bonus.attr('data-mode') == 'early-pre-object-bonus'){

          let hehe = parseFloat(TIME * SEC_PX) - parseFloat($el_early_pre_object_bonus.attr('data-left'))
          let remained = 
          (parseFloat($el_early_pre_object_bonus.attr('data-width'))) - hehe;

          if(parseInt($el_start.attr('data-jobs-done')) < parseInt($el_start.attr('data-jobs'))){
            $el534_last_3 = '';
            // Finding yhe elemtn which just recently completed
            for(let z=0; z < $el_early_pre_object_bonus.parents('.progressbar3').find('.secondary-bar').length; z++){
              $el4i87236589 = $el_early_pre_object_bonus.parents('.progressbar3').find('.secondary-bar').eq(z);
              if(
                parseInt($el4i87236589.attr('data-worksheet-id')) == parseInt($el_start.attr('data-objects-id')) &&
                parseInt($el4i87236589.attr('data-status')) == 3
              ){
                $el534_last_3 = $el4i87236589;
              }
            }
            
            // Making preceding jobs at the posotion where next job start early
            for(let z=0; z < $el_early_pre_object_bonus.parents('.progressbar3').find('.secondary-bar').length; z++){
              $el34970 = $el_early_pre_object_bonus.parents('.progressbar3').find('.secondary-bar').eq(z);
  
              if(
                parseInt($el34970.attr('data-worksheet-id')) == parseInt($el_start.attr('data-objects-id')) &&
                parseInt($el534_last_3.attr('data-object-index')) < parseInt($el34970.attr('data-object-index')) 
              ){
                let hehe90 = (parseFloat($el34970.attr('data-left'))) - remained;
                $el34970.attr('data-left', hehe90);

              }
              
            }

            // Appropriately altering 2nd row to the effects
            let $el3827_has_job_late = false; 
            for(let z=0; z < $el_early_pre_object_bonus.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').length; z++){
              
              $el34970 = $el_early_pre_object_bonus.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').eq(z);
              if($el34970.attr('data-mode') == 'job-late' || $el34970.attr('data-mode') == 'normal-job-late'){
                $el3827_has_job_late = true;
                if((parseFloat($el34970.attr('data-width')) - remained) < 0 ){
                  $el34970.remove();
                  $el3827_has_job_late = false;
                  break;
                }else{
                  let hehe94893 = parseFloat($el34970.attr('data-width')) - remained;
                  $el34970.attr('data-width', hehe94893);

                }
              }

            }

            // if there is no job late
            if(!$el3827_has_job_late){
              // Checking if bonus is already there
              let $el3872_has_bonus = false;
              let $el3872_bonus = '';
              for(let z=0; z < $el_early_pre_object_bonus.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').length; z++){
                $el372816 = $el_early_pre_object_bonus.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').eq(z);

                if($el372816.attr('data-mode') == 'pre-object-early-2'){
                  $el3872_has_bonus = true
                  $el3872_bonus = $el372816
                  break
                }
              }

              for(let z=0; z < $el_early_pre_object_bonus.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').length; z++){
                $elr8738 = $el_early_pre_object_bonus.parents('.progressbar3').siblings('.progressbar2').find('.main-bar').eq(z);

                if($elr8738.attr('data-objects-id') == $el534_last_3.attr('data-worksheet-id')){
                  let hehe39893 = parseFloat($elr8738.attr('data-width')) - remained;
                  $elr8738.attr('data-width', hehe39893);

                  let hehe349 = parseFloat($elr8738.attr('data-left')) + parseFloat($elr8738.attr('data-width'))

                  if($el3872_has_bonus){
                    $el3872_bonus.attr('data-width', parseFloat($el3872_bonus.attr('data-width')) + remained);
                    $el3872_bonus.attr('data-left', parseFloat($el3872_bonus.attr('data-left')) - remained);


                  }else{
                    $elr8738.parents('.progressbar2').append(
                      `
                        <div class="bar main-bar" data-left="` + hehe349 + `" data-color="` + SKY + `"  data-width="` + remained + `" data-mode="pre-object-early-2" data-objects-id="-1"></div>
                      `
                    );

                  }
                  


                  break;
                }
              }
            }



          }

          $el_early_pre_object_bonus.attr('data-width', hehe);
          $el_early_pre_object_bonus.attr('data-mode', 'move-early-pre-object-bonus');

        }
      }
      break;
    }

  }
  
  
};

/* data status
0 -> Not started job
1 -> Job in progress
2 -> Should be checked and things applied
3 -> IGNORE!
*/

function halt(id){
  for(let i =0; i < $('#' + id).siblings('.progressbar2').siblings('.progressbar3').find('.secondary-bar').length; i++ ){
    let $el_finish = $('#' + id).siblings('.progressbar2').siblings('.progressbar3').find('.secondary-bar').eq(i);
    if(parseInt($el_finish.attr('data-status')) == 1){
      $el_finish.attr('data-status', 2);

    }
  }
};


// Rendering whole Timeline
function render_timeline(){
  render_main_bar()
  render_secondary_bar()
  render_late_bar()
}

// Rendering Secondary bar
function render_secondary_bar(){
  //rendering secondary bar
  for (let i = 0; i < $('.progressbar3').length; i++) {
    const $sec_el = $('.progressbar3').eq(i).find('.secondary-bar:last-child');

    

    let $el_all_sec = $('.progressbar3').eq(i).find('.secondary-bar');
    let $el_all_main = $('.progressbar3').eq(i).siblings('.progressbar2').find('.main-bar')
    
    
    for(let j=0; j < $el_all_sec.length; j++){
      let rendered = false;
      for(let k=0; k < $el_all_main.length; k++){
      
        if($el_all_sec.eq(j).attr('data-mode') == 'pre-object'){
          if($el_all_main.eq(k).attr('data-objects-id') == $el_all_sec.eq(j).attr('data-worksheet-id')){
            // Attributes
            let $el_main = $el_all_main.eq(k);
            let $el_sec = $el_all_sec.eq(j);
            let $el_main_id = parseInt($el_all_main.eq(k).attr('data-objects-id'));
            let $el_sec_id = parseInt($el_all_sec.eq(j).attr('data-worksheet-id'));
            let $el_sec_obj_index = parseInt($el_all_sec.eq(j).attr('data-object-index'));
            var $el_temp_prev = $el_sec.prev();

            // Increase Width
            if($el_sec.attr('data-mode2') == 'increase-width' || $el_sec.attr('data-mode2') == 'increase-width-wait'){
              $el_sec.attr('data-width', parseFloat($el_sec.attr('data-width')) + SEC_PX)
            }

            if($el_sec_obj_index == 1){
              $el_sec.attr('data-left', $el_main.attr('data-left'));
              $el_sec.css({
                'left': parseFloat($el_sec.attr('data-left')),
                'background': $el_sec.attr('data-color'),
                'width': parseFloat($el_sec.attr('data-width')),
                'display': 'block'
              });
              rendered = true
            }else{

              if($el_sec.attr('data-mode2') == 'increase-left'){
                $el_sec.attr('data-left', parseFloat($el_sec.attr('data-left')) + SEC_PX)
                $el_sec.css({
                  'left': parseFloat($el_sec.attr('data-left')),
                  'background': $el_sec.attr('data-color'),
                  'width': parseFloat($el_sec.attr('data-width')),
                  'display': 'block'
                });
                rendered = true
              }else{
                $el_sec.attr('data-left', parseFloat($el_temp_prev.attr('data-left')) + parseFloat($el_temp_prev.attr('data-width')))
                $el_sec.css({
                  'left': parseFloat($el_temp_prev.attr('data-left')) + parseFloat($el_temp_prev.attr('data-width')),
                  'background': $el_sec.attr('data-color'),
                  'width': parseFloat($el_sec.attr('data-width')),
                  'display': 'block'
                });
                rendered = true
              }
              
            }

            
          }
        }

      }

      if($el_all_sec.eq(j).attr('data-mode') == 'delay1'){
        
        let $el_sec = $el_all_sec.eq(j);
        $el_sec.attr('data-width', parseFloat($el_sec.attr('data-width')) + SEC_PX)
        $el_sec.css({
          'left': parseFloat($el_sec.attr('data-left')),
          'background': $el_sec.attr('data-color'),
          'width': parseFloat($el_sec.attr('data-width')),
          'display': 'block'
        });
        rendered = true
      }

      if($el_all_sec.eq(j).attr('data-mode') == 'move-early-pre-object-bonus'){
        let laststatus3 = '';
        for(let e=0; e < $el_all_sec.eq(j).parents('.progressbar3').find('.secondary-bar').length; e++){
          if(parseInt($el_all_sec.eq(j).parents('.progressbar3').find('.secondary-bar').eq(e).attr('data-status')) == 3){
            laststatus3 = $el_all_sec.eq(j).parents('.progressbar3').find('.secondary-bar').eq(e);
          }
        }

        $el_all_sec.eq(j).attr('data-mode', 'normal');
        
        let $el_sec = $el_all_sec.eq(j);
        let hehe = parseFloat(laststatus3.attr('data-left')) + parseFloat(laststatus3.attr('data-width'))
        $el_all_sec.eq(j).attr('data-left', hehe);
       
      }

      // Pre object increasen width
      if(
        !rendered && 
        ($el_all_sec.eq(j).attr('data-mode') == 'pre-object2' || $el_all_sec.eq(j).attr('data-mode') == 'pre-object3') && 
        ($el_all_sec.eq(j).attr('data-mode2') == 'increase-width' || $el_all_sec.eq(j).attr('data-mode2') == 'increase-width2')
      ){
        let $el_sec = $el_all_sec.eq(j);
        $el_sec.attr('data-width', parseFloat($el_sec.attr('data-width')) + SEC_PX)
        $el_sec.css({
          'left': parseFloat($el_sec.attr('data-left')),
          'background': $el_sec.attr('data-color'),
          'width': parseFloat($el_sec.attr('data-width')),
          'display': 'block'
        });
        rendered = true;
      }

      // Pre object move forward
      if(
        !rendered && 
        (
          $el_all_sec.eq(j).attr('data-mode') == 'pre-object2' && $el_all_sec.eq(j).attr('data-mode2') == 'increase-left' ||
          $el_all_sec.eq(j).attr('data-mode') == 'pre-object3' && $el_all_sec.eq(j).attr('data-mode2') == 'increase-left'
        )
      ){
        let $el_sec = $el_all_sec.eq(j);
        $el_sec.attr('data-left', parseFloat($el_sec.attr('data-left')) + SEC_PX)
        $el_sec.css({
          'left': parseFloat($el_sec.attr('data-left')),
          'background': $el_sec.attr('data-color'),
          'width': parseFloat($el_sec.attr('data-width')),
          'display': 'block'
        });
        rendered = true;
      }

      if(!rendered && $el_all_sec.eq(j).attr('data-mode') != 'normal-delay1'){
        let $el_sec = $el_all_sec.eq(j);
        $el_sec.css({
          'left': parseFloat($el_sec.attr('data-left')),
          'background': $el_sec.attr('data-color'),
          'width': parseFloat($el_sec.attr('data-width')),
          'display': 'block'
        });
      }
      

    }
  }
}

// Rendering Main Bar
function render_main_bar(){
  // mainbar rendering
  for (let i = 0; i < $('.main-bar').length; i++) {
    let $el = $('.main-bar').eq(i);
    let mode = $el.attr('data-mode');

    let leftArr = [ 'pending-forward']
    let widthArr = ['job-late']

    // Rendering main bar
    if(widthArr.includes(mode)){ // Increasing WIDTH 
      $el = $el.attr('data-width', parseFloat($el.attr('data-width')) + SEC_PX);
      $el.css({
        'left': parseFloat($el.attr('data-left')),
        'background': $el.data('color'),
        'width': parseFloat($el.attr('data-width')) + SEC_PX
      })
    }else if(leftArr.includes(mode)){ //Increasing LEFT
      $el.attr('data-left', parseFloat($el.attr('data-left')) + SEC_PX)
      $el.css({
        'left': parseFloat($el.attr('data-left'))  + SEC_PX,
        'background': $el.data('color'),
        'width': parseFloat($el.attr('data-width'))
      })
    }else{
      $el.css({
        'left': parseFloat($el.attr('data-left')),
        'background': $el.data('color'),
        'width': parseFloat($el.attr('data-width'))
      })
    }
  }
}

// Rendering Late Bar
function render_late_bar(){
  // Rendering late bars
  for (let i = 0; i < $('.late-bar').length; i++) {
    const $el = $('.late-bar').eq(i);
    $el.css({
      'left': $el.data('left'),
      'width': $el.data('width'),
      'background': $el.data('color'),
    })
    
  }
}