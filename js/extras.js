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
    shuffleNodes(list);//shuffle the overlays
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

function bigRandomizer() {   
    if (localStorage.getItem('random-list')) { // Check if menu exists
        return localStorage.getItem('random-list').split(",");
    }
    return [];
}

function randomizerButton() {
    let menuList = bigRandomizer();
    //console.log(menuList)
    const nav = document.getElementById('hist-nav');
    //console.log(menuList.length)
    const pageCount = menuList.length;
    nav.innerHTML = nav.innerHTML + menuList.length;
    if (menuList.length === 0) {
        nav.innerHTML = `<a class="btn no-more" id="btn-random" href="https://digitalhistology.org/_randomizer/">No more items.</a>`;
        return;
    }

    const randomNext = Math.floor(Math.random() * menuList.length);
    const selectedItem = menuList[randomNext];

    // Create the button and populate it with a URL
    nav.innerHTML = `<a class="btn" id="btn-random" href="${selectedItem}">Next (${pageCount} remain)</a>`;

    // Delay the removal to ensure the button is displayed first
    setTimeout(() => {
        document.getElementById('btn-random').addEventListener('click', function (event) {
            // Remove the clicked item from the list
            menuList.splice(randomNext, 1);
            localStorage.setItem('random-list', menuList.join(",")); // Update storage
        });
    }, 0);
}



function randomNavMath(id,value){
   let randomPageCounter = parseInt(localStorage.getItem('random-list-page-number'));
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
        randomizerButton();
      if (mainSlide){
        mainSlide.setAttribute('href', mainSlide.href+'#hidden');
      }
    hideSlideTitles();
  }
});

//UPPER MENU RANDOMIZING**************************
//shuffle overlay elements 
 function shuffleNodes(list) {
        const nodes = list.children;
        shuffleNodes(list);        
    }

//from so https://stackoverflow.com/questions/7070054/javascript-shuffle-html-list-element-order
function shuffleNodes(list) {
        for (var i = list.children.length; i >= 0; i--) {
          list.appendChild(list.children[Math.random() * i | 0]);
      }
    }

//remove hash from url
//from https://stackoverflow.com/a/5298684/3390935
function removeHash () { 
    history.pushState("", document.title, window.location.pathname + window.location.search);
}


function fetchChildren(ancestorId){
  const url = WPURLS.siteurl+`/wp-json/wp/v2/pages?parent=${ancestorId}&order=asc`;
  var linksArray = [];
  //console.log(url)
  fetch(
      url
    )
      .then(function(response) {
      // Convert to JSON
      return response.json();
    })
      .then(function(data) {
      // GOOD!
      data.forEach(async (page)=>{
        linksArray.push(page.link);
      })
      
    });
      return linksArray;
  }


// //SLIDER NAVIGATION
// if (document.getElementById('slide-the-pages')){  
//   let slider = document.getElementById('slide-the-pages');
//   let max = slider.max-1;
//   console.log('max='+max); 
//   const ancestorId = document.getElementById('hist-nav').dataset.ancestor;
//   const links = fetchChildren(ancestorId);
//   console.log(links)

//   var urlArray = window.location.href.split('#')[0].split('/');
//   //console.log(urlArray);
//   if(document.getElementById('quizzer').dataset.quizstate){
//       var state = document.getElementById('quizzer').dataset.quizstate;
//   }
//   var urlState = window.location.href.split('#')[1];
//   var lastSegment = urlArray.pop(); 
//   console.log('state is - ' + state);
//   console.log('urlstate - ' + urlState);
//   const sliderValue = parseInt(slider.value);
//   console.log(sliderValue >= max);
//   slider.oninput = function() {    
//       if (state === 'hidden' || urlState === 'hidden'){
//         console.log('i am hidden')
//         var newPage = lastSegment.replace(/\d/g, '')+getRandomInt(max);//set this to variable and randomize if hash = hidden 
//         window.location.assign(urlArray.join('/')+newPage+'#hidden');
//       } else {
//         const newPage = links[(sliderValue)]; 
//         console.log(newPage);
//         if(max >= sliderValue){
//           console.log(sliderValue-1)
//           console.log(max)
//           window.location.assign(newPage);
//         } else {
//           alert('No more pages in this content area.')
//         }
//       }
//   }
// }



function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

