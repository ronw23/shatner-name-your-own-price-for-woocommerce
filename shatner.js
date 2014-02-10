jQuery(function($){
        $('.cart').click(function(){
            if($('.name_price').length && !parseFloat($('input.name_price').val()))
            {
                alert('Enter a price to continue');
                return false;
            }  

            if($('#minimum_price').length && parseFloat($('#minimum_price').val()) >= parseFloat($('input.name_price').val()))
            {
                alert('Price must be higher than the minimum price');
                return false;
            } 

        });
        $('.cart').submit(function(){
          
            $('<input name="price" />').val($('input.name_price').val()).attr('type','hidden').appendTo($('form.cart'));
            return;
        });
});
