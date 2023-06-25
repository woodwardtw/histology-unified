//console.log('sorter loaded')

jQuery( function() {
    jQuery( "#sorter" ).sortable({
    stop: function(event, ui ) {
      //console.log(ui.item.attr('id'))
      //console.log(ui.item.data('id'))
      histSetSort();
    }
  });
} );


function histSetSort(){
  const theList = document.querySelector('#sorter');
  const listItems = theList.querySelectorAll('li');
  const startDate = new Date(listItems[0].dataset.date);
  const day = 60*60*24*1000;
  listItems.forEach((item, i) => {
    // if(i>0){
      let newDate = new Date(startDate.getTime()+(i*day));
      let newYear = 2000;
      let newMonth = String(newDate.getMonth() + 1).padStart(2, '0');
      let newDay = String(newDate.getDate()).padStart(2, '0');
      let newHrs = newDate.getHours();
      let newMins = newDate.getMinutes();
      let newMs = '00';
      item.dataset.date = `${newYear}-${newMonth}-${newDay} ${newHrs}:${newMins}:${newMs}`;
    //}
  });
}

jQuery(document).ready(function() { 
  //modal show
      jQuery('#myModal').on('shown.bs.modal', function () {
      jQuery('#myInput').trigger('focus')
  })
      //create new post ajax stuff
   jQuery("#new-post-submit").click(function () {   
    jQuery.ajax({
        type: "POST",
        url: sorterObj.ajaxUrl,
        data: {
            action: 'hist_sorter_new_item',
            // add your parameters here
            parent_id: document.querySelector("#new-post").dataset.parentid,
            post_name: document.querySelector("#new-post-name").value,
            nonce: sorterObj.nonce
        },
        method: 'POST',
        success : function( response ){ 
          window.location.replace(response)
          console.log('response = ' + response)
        },
        error : function(error){ console.log(error) }
        });
     });
   //change sort ajax
    jQuery("#update-sort").click(function () {   
    jQuery.ajax({
        type: "POST",
        url: sorterObj.ajaxUrl,
        data: {
            action: 'hist_sorter_update_sort',
            // add your parameters here
            post_ids: hist_get_all_ids(),
            post_dates: hist_get_all_dates(),
            nonce: sorterObj.nonce
        },
        method: 'POST',
        success : function( response ){ 
          console.log('response = ' + response)
        },
        error : function(error){ console.log(error) }
        });
     });

});


function hist_get_all_ids(){
  const listItems = document.querySelectorAll("#sorter li");
  let allIds = [];
  let allDates = [];
  listItems.forEach((item, i) => {
    allIds.push(item.dataset.id);
    allDates.push(item.dataset.date);
  })
  return allIds;
}

function hist_get_all_dates(){
  const listItems = document.querySelectorAll("#sorter li");
  let allDates = [];
  listItems.forEach((item, i) => {
    allDates.push(item.dataset.date);
  })
  return allDates;
}