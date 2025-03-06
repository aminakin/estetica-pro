/*jQuery(".number").keypress (
    function(event)
    {
        var key, keyChar;
        if(!event) var event = window.event;
            
        if (event.keyCode) key = event.keyCode;
        else if(event.which) key = event.which;

        if(key==null || key==0 || key==8 || key==13 || key==37 || key==39 || key==46 || key==9) return true;
        keyChar=String.fromCharCode(key);

        if(!/[0-9.]/.test(keyChar))  return false;

    }
);

$(function(){
    $('form[name="FEEDBACK"]').submit(function(e)
    {
        if($('#name').val() != ''){
            
        }
        else{
            console.log('Введите Ваше имя:');
            e.preventDefault(); 
        }
        

    });

});
*/