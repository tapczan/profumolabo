/**
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   Addons PrestaShop license limitation
 * @see       /License
 *
 * International Registered Trademark & Property of INVERTUS, UAB
 */

$(document).ready(function (){
    var countdownElement = document.getElementsByClassName('countdown');

    //Process counter
    x = setInterval(function(){
        for(var i=0; i < countdownElement.length; i++){
            var countDownDate = countdownElement[i].getAttribute("data-time");
            var second = 1;
            var distance = countDownDate - second;
            countdownElement[i].setAttribute("data-time", countDownDate - second);

            var days = Math.floor(distance / (second * 60 * 60 * 24));
            var daysDisplay = "";
            if (days > 0) {daysDisplay = days + "d ";}

            var hours = Math.floor((distance % (second * 60 * 60 * 24)) / (second * 60 * 60));
            var hoursDisplay = "";
            if (hours > 0) {hoursDisplay = hours + "h ";}

            var minutes = Math.floor((distance % (second * 60 * 60)) / (second * 60));
            var minutesDisplay = "";
            if (minutes > 0) {minutesDisplay = minutes + "m ";}

            var seconds = Math.floor((distance % (second * 60)) / second);

            countdownElement[i].innerHTML = daysDisplay + hoursDisplay
                + minutesDisplay + seconds + "s ";

            if (distance < 0) {
                // clearInterval(x); // If uncomment then when first special offer expires, asecond stops as well
                // countdownElement[i].innerHTML = "EXPIRED";
                countdownElement[i].closest(".so-display-div").innerHTML = "";
            }
        }
    }, 1000);
});
