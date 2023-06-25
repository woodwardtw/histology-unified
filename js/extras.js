(function($) {

  $('.button').click(function(){
    var $this = $(this);
    let theId = $this.data('id');
    $siblings = $this.parent().children();
    position = $siblings.index($this)+1;
    //console.log (position);
    $('.subcontent div').removeClass('active');
    $('.subcontent-'+theId).addClass('active');   
    $siblings.removeClass('active');
    $this.addClass('active');     
  })

})(jQuery);



//main index interactions -- left over and to be deleted
// jQuery('.childbearing').click(function(e) {
//   e.preventDefault(); 
//   jQuery('.active').removeClass('active');
//   jQuery(this).parent().children('ul').toggleClass('active');
//   jQuery(this).parentsUntil('.cell-main-index').addClass('active');

// });



//indent buttons that lead with a hyphen 
var buttons = document.getElementsByClassName('button');

for (var i =0; i < buttons.length; i++){
  var text =buttons[i].innerHTML;
  if(text[0]==='-'){
    buttons[i].style.paddingLeft = '1em';
  }  
}


//KEY BINDING for nav
function leftArrowPressed() {
   var url = document.getElementById('nav-arrow-left').parentElement.href;
   window.location.href = url;
}

function rightArrowPressed() {
   var url = document.getElementById('nav-arrow-right').parentElement.href;
   window.location.href = url;

}

document.onkeydown = function(evt) {
    evt = evt || window.event;
    switch (evt.keyCode) {
        case 37:
            leftArrowPressed();
            break;
        case 39:
            rightArrowPressed();
            break;
    }
};


//HIDE AND SEEK FOR QUIZ YOURSELF STUFF
function hideSlideTitles(){   
    window.location.hash = 'hidden';     
    let list = document.querySelector('.button-wrap');
    //shuffleNodes(list);//shuffle the overlays
    var mainSlide = document.getElementById('slide-button-1'); 
    if (mainSlide){
      var buttons = document.getElementsByClassName('button');
      var subslides = document.getElementsByClassName('sub-deep');     
      for (var i = 0; i < buttons.length; i++){
        var original = buttons[i].innerHTML;
        buttons[i].innerHTML = '<span class="hidden">' + original + '</span>* * *';        
        }
      for (var i = 0; i < subslides.length; i++){
            subslides[i].classList.add('nope')
        }
        document.getElementById('the_slide_title').classList.add('nope')
        document.querySelectorAll('header')[1].classList.add('nope')
        document.getElementById('the_slide_content').classList.add('nope')
        document.getElementById('quizzer').dataset.quizstate = 'hidden'
        document.getElementById('quizzer').innerHTML = 'Show'        
    }    
}

  function bigRandomizer(){   
      if ( localStorage.getItem('random-list')){
    
          let randomLinks = localStorage.getItem('random-list').split(',');

          if (!localStorage.getItem('random-list-page-number')){
            console.log('no page number')
             var randomPageCounter = localStorage.setItem('random-list-page-number', 0)
          } else {
            console.log('page # ' + localStorage.getItem('random-list-page-number'))
            var randomPageCounter = parseInt(localStorage.getItem('random-list-page-number'))
          }
          
          let totalPages = document.querySelector('.total-pages').innerHTML = (randomPageCounter+1) +' of ' + randomLinks.length;

          if (localStorage.getItem('random-list-page-number') == 0){
                // let next = document.getElementById('next-link')
                // next.href = randomLinks[randomPageCounter+1]
                // randomNavMath(next,1)
                let prev = document.getElementById('previous-link')
                prev.innerHTML = '&nbsp;'
          } else {
            console.log(randomLinks[randomPageCounter])
            if (randomLinks[randomPageCounter+1]){
                jQuery( "#nav-arrow-right" ).wrap( '<a href="'+randomLinks[randomPageCounter+1]+'" id="next-link"></a>' );
                let next = document.getElementById('next-link')
                //next.href = randomLinks[randomPageCounter+1]
                randomNavMath(next,1)
            }

            if (document.getElementById('previous-link')){
              let previous = document.getElementById('previous-link')
              previous.href = randomLinks[randomPageCounter-1]
              randomNavMath(previous,-1)
            }
          }
        }
    }


function randomNavMath(id,value){
   let randomPageCounter = parseInt(localStorage.getItem('random-list-page-number'))
  id.addEventListener("click", function(){
   localStorage.setItem('random-list-page-number', (parseInt(randomPageCounter)+value))
  });
}


function showSlideTitles(){
  //window.location.hash = '';
     window.history.replaceState('', '', window.location.href.split('#')[0]);//wipe out hash and hidden

  var mainSlide = document.getElementById('slide-button-1'); 
    if (mainSlide){
      var buttons = document.getElementsByClassName('button');

      for (var i =0; i < buttons.length; i++){ //REVISIT THIS
        var hidden = buttons[i].firstChild.innerHTML;
          buttons[i].innerHTML = hidden;       
        }
        document.getElementById('the_slide_title').classList.remove('nope')
        document.getElementById('the_slide_content').classList.remove('nope')
        document.getElementById('quizzer').dataset.quizstate = 'visible'
        document.getElementById('quizzer').innerHTML = 'Hide'
        var subslides = document.getElementsByClassName('sub-deep');
        for (var i = 0; i < subslides.length; i++){
            subslides[i].classList.remove('nope')
        }
    }
}


function setQuizState(){
  var state = document.getElementById('quizzer').dataset.quizstate;
  if (state === 'hidden'){
    showSlideTitles();
  } else {
    hideSlideTitles();
  }
}

function retainQuizState(){
  var state = document.getElementById('quizzer').dataset.quizstate
  if (state === 'hidden'){
    hideSlideTitles()
  } else if (state === 'visible'){
    showSlideTitles()
  }
}

//read hash and look for button click
jQuery( document ).ready(function() {
  if ( document.getElementById('quizzer')){
    document.getElementById('quizzer').addEventListener("click", setQuizState);
  } 
  if (window.location.hash.substring(1) === 'hidden'){
      let mainSlide = document.getElementById('slide-button-0');
      bigRandomizer();
      if (mainSlide){
        mainSlide.setAttribute('href', mainSlide.href+'#hidden');
      }

    hideSlideTitles();
  }
});


//shuffle overlay elements from https://stackoverflow.com/questions/7070054/javascript-shuffle-html-list-element-order
 function shuffleNodes(list) {
        var nodes = list.children, i = 0;
        nodes = Array.prototype.sort.call(nodes);
        while(i < nodes.length) {
           list.appendChild(nodes[i]);
           ++i;
        }
    }

//remove hash from url
//from https://stackoverflow.com/a/5298684/3390935
function removeHash () { 
    history.pushState("", document.title, window.location.pathname + window.location.search);
}



//SLIDER NAVIGATION
if (document.getElementById('slide-the-pages')){  
  let slider = document.getElementById('slide-the-pages');
  let max = slider.max;
  slider.oninput = function() {
    //console.log(slider.value);
    var location = window.location.href.split('#')[0];
    var urlArray = location.split('/');
    // console.log(urlArray);
    // var lastSegment = urlArray.pop() || urlArray.pop();  
    //console.log(lastSegment)
  var state = document.getElementById('quizzer').dataset.quizstate;
  var urlState = window.location.hash.substring(1);
  var lastSegment = urlArray.pop() || urlArray.pop();  
  if (state === 'hidden' || urlState === 'hidden'){
    var newPage = lastSegment.replace(/\d/g, '')+getRandomInt(max);//set this to variable and randomize if hash = hidden 
    window.location.assign(urlArray.join('/')+'/'+newPage+'/#hidden');
  } else {
      var newPage = lastSegment.replace(/\d/g, '')+slider.value; 
      window.location.assign(urlArray.join('/')+'/'+newPage);
    }
  }
}



function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

