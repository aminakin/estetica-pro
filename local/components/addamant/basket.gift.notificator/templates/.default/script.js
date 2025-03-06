class basketTriggerNotificator{

    static ajaxPath = '/local/ajax/basketTriggerNotificator.php';

    static checkBasket(){
        var that = this;
        $.ajax({
           type: 'GET',
           url: this.ajaxPath,
           data: {action: 'NOTIFICATE_CLIENT'},
           success: function (data){
               var parsed_data = JSON.parse(data);
               // console.log(parsed_data);
               if(parsed_data['SUCCESS'] == true && parsed_data['BASKKET_TRIGGER'] != false){
                   that.openPopup(parsed_data['BASKKET_TRIGGER']);
               }
           }
        });
    }

    static openPopup(saleData){
        console.log(saleData['ID']);
        $('#sale-popup_content').html(saleData['PREVIEW_TEXT'])
        $('.sale-popup-overlay, .sale-popup').fadeIn();
    }

    static closePopup(){
        $('.sale-popup-overlay, .sale-popup').fadeOut();
    }
}

$(document).ready(function () {
    //обновление статистики бонусов
    setInterval(function () {
        basketTriggerNotificator.checkBasket()
    }, 10000);

    // $(document).on('click', '', function (){
    //     setTimeout(function (){
    //         basketTriggerNotificator.checkBasket()
    //     }, 3000)
    // })
});