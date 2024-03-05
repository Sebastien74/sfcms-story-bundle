/** Teaser stories */
document.addEventListener("DOMContentLoaded", function (event) {
    setTimeout(function () {
        let teasers = document.getElementsByClassName('teaser-stories');
        for (let i = 0; i < teasers.length; i++) {
            let height = 0;
            let teaser = teasers[i];
            let items = teaser.getElementsByClassName('carousel-item');
            for (let j = 0; j < items.length; j++) {
                let item = items[j];
                item.style.display = 'inline-block';
                let heightToCheck = item.offsetHeight;
                if (heightToCheck > height) {
                    height = heightToCheck;
                }
                item.style.removeProperty('display');
            }
            let cards = teaser.getElementsByClassName('card');
            for (let j = 0; j < cards.length; j++) {
                cards[j].style.height = height + 'px'
            }
        }
    }, 1500);
});