jQuery(function($){
  $('.cart').click(function(){

// Test to see if the price exceeds the minimum price (ie the Suggested Price):

    if($('#minimum_price').length && parseFloat($('#minimum_price').val()) >= parseFloat($('input.name_price').val()))
    {
        alert('Please offer a price higher than the Minimum Price.');
        return false;
    } 
// See if the price input is zero (because we want people to be able to choose a free option: NEEDS LOGIC FOR SET MINIMUM

    else if(parseFloat($('input.name_price').val()) == 0)           
    {
        return;
    }  

// Test to see if the input field has been left blank:

    else if($('.name_price').length && !parseFloat($('input.name_price').val()))
    {
        alert('Please enter a price in the Price field!');
        return false;
    }
// Test to see if input field is non-negative:

    else if(parseFloat($('input.name_price').val()) < 0)
    {
        alert("Look here, old chap, that's just not on. Please enter a positive number, or zero. Tsk tsk.");
        return false;
    }

  });
  $('.cart').submit(function(){
  
    $('<input name="price" />').val($('input.name_price').val()).attr('type','hidden').appendTo($('form.cart'));
    return;
  });
});

