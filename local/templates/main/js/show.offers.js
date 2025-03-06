$(document).ready(function () {
    $(document)
        .on("click", ".show-modal-select-props", function (e) {
            e.preventDefault();
            var prod = $(this).closest(".product-item-container"),
                prodName = prod.find(".product-item-title"),
                prodNameID = "prodName_" + time() + "_" + makeid(),
                amountField = prod.find(".product-item-amount-field-container"),
                amountFieldID = "amountField_" + time() + "_" + makeid(),
                priceField = prod.find(".product-item-price-container"),
                priceFieldID = "priceField_" + time() + "_" + makeid(),
                buyField = prod.find("div [id$=basket_actions]"),
                buyFieldID = "buyField_" + time() + "_" + makeid(),
                skuField = prod.find("div[id$=sku_tree]"),
                skuFieldID = "skuField_" + time() + "_" + makeid();
            $(prodName).after(generateDiv(prodNameID));
            $(amountField).after(generateDiv(amountFieldID));
            $(priceField).after(generateDiv(priceFieldID));
            $(buyField).after(generateDiv(buyFieldID));
            $(skuField).after(generateDiv(skuFieldID));

            $("body").append("<div id='modal-select-props' data-prodNameID='" + prodNameID + "' data-amountFieldID='" + amountFieldID + "' data-priceFieldID='" + priceFieldID + "' data-buyFieldID='" + buyFieldID + "' data-skuFieldID='" + skuFieldID + "'><div class='modal-select-props-wrapper'><div class='close' onclick='closeModalProps(" + prodNameID + "," + amountFieldID + "," + priceFieldID + "," + buyFieldID + "," + skuFieldID + ")'><div class='close-button'><div></div><div></div></div></div><div class='prodName bg3'></div><div class='prodBody'><div class='block-price'><div class='title'>Цена</div><div class='block-body'></div></div><div class='block-count'><div class='title'>Количество</div><div class='block-body'></div></div><div class='block-offers'><div class='block-body'></div></div><div class='block-cart' data-entity='buttons-block'><div class='block-body'></div></div></div><div class='more bg3'><a href=''>Подробнее</a></div></div></div>");

            var msp = $("#modal-select-props"), prodNameWrap = $(msp).find(".prodName"),
                prodBodyWrap = $(msp).find(".prodBody");
            prodNameWrap.append(prodName);
            prodBodyWrap.find(".block-count").find(".block-body").append(amountField);
            prodBodyWrap.find(".block-price").find(".block-body").append(priceField);
            prodBodyWrap.find(".block-cart").find(".block-body").append(buyField);
            prodBodyWrap.find(".block-offers").find(".block-body").append(skuField);
            $(msp).find(".more").find("a").prop("href", $(prodName).find("a").attr("href"));
        })
        .on("click", "#modal-select-props .close-button", function () {
            var msp = $(this).closest("#modal-select-props");
            var prodNameID = msp.data("prodnameid"),
                amountFieldID = msp.data("amountfieldid"),
                priceFieldID = msp.data("pricefieldid"),
                buyFieldID = msp.data("buyfieldid"),
                skuFieldID = msp.data("skufieldid");
            closeModalProps(prodNameID, amountFieldID, priceFieldID, buyFieldID, skuFieldID, 1);
        });
});

function closeModalProps(prodNameID, amountFieldID, priceFieldID, buyFieldID, skuFieldID, dom) {
    var mspw = $("#modal-select-props");
    if (dom == 1) {
        prodNameID = "#" + prodNameID;
        priceFieldID = "#" + priceFieldID;
        amountFieldID = "#" + amountFieldID;
        skuFieldID = "#" + skuFieldID;
        buyFieldID = "#" + buyFieldID;
    }
    $(prodNameID).after($(mspw).find(".prodName").find(".product-item-title"));
    $(priceFieldID).after($(mspw).find(".block-price").find(".product-item-price-container"));
    $(amountFieldID).after($(mspw).find(".block-count").find(".product-item-amount-field-container"));
    $(skuFieldID).after($(mspw).find(".block-offers").find("div [id$=sku_tree]"));
    $(buyFieldID).after($(mspw).find(".block-cart").find("div [id$=basket_actions]"));
    $(prodNameID).remove();
    $(amountFieldID).remove();
    $(priceFieldID).remove();
    $(buyFieldID).remove();
    $(skuFieldID).remove();
    $(mspw).remove();

}

function generateDiv(id) {
    return "<div id=" + id + "></div>"
}

function time() {
    return parseInt(new Date().getTime() / 1000)
}

function makeid() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";

    for (var i = 0; i < 20; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}