## What is this?

IsValid automates statistical tests for A/B experiments.  It will calculate significance and confidence intervials.

In short, you can use it to figure out what, if anything, the result of an A/B test means.

## How can I use it?

I host this exact code at [isvalid.org](http://isvalid.org).  If you want, you can host it yourself.

There is also an API (which you could host yourself!) that you can read about [here](https://github.com/evansolomon/IsValid.org/wiki/API).

## Bookmarklet

You can send data to IsValid with a handy browser bookmarklet using this as the URL:

    javascript:(function(){if(window.jQuery===undefined){var%20done=false,script=document.createElement(%22script%22);script.src=%22//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js%22;script.onload=script.onreadystatechange=function(){if(!%20done%20%26%26(!%20this.readyState%20||%20this.readyState==%22loaded%22%20||%20this.readyState=='complete')){done=true;init();}};document.getElementsByTagName('head')[0].appendChild(script);}else{init();}function%20fancyClickThing(event){var%20text=event.target.innerHTML;var%20number=parseInt(text.replace(',',''),10);window.isvalidClicks.push(number);jQuery(event.target).css('background-color','rgba(50,%20200,%2080,%200.7)').addClass('isvalid-clicked');if(4==window.isvalidClicks.length){jQuery(this).unbind('mouseup',fancyClickThing);window.open('http://isvalid.org/'+'%3Fsc='+window.isvalidClicks[0]+'%26cc='+window.isvalidClicks[1]+'%26se='+window.isvalidClicks[2]+'%26ce='+window.isvalidClicks[3]);reset();}}function%20init(){window.isvalidClicks=[];window.isvalidBookmarklet=(function(){jQuery(document).mouseup(fancyClickThing);})();jQuery('*').hover(function(event){var%20element=jQuery(this);if(element.hasClass('isvalid-clicked'))return;event.stopPropagation();var%20text=event.target.innerHTML;var%20number=parseInt(text.replace(',',''),10);if(!%20isNaN(parseFloat(number))%26%26%20isFinite(number))element.css('background-color','rgba(50,%20200,%2080,%200.25)');},function(event){event.stopPropagation();var%20element=jQuery(this);if(!%20element.hasClass('isvalid-clicked'))element.css('background-color','');});}function%20reset(){jQuery('.isvalid-clicked').removeClass('isvalid-clicked').css('background-color','');window.isvalidClicks=[];}})();
