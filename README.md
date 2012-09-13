## What is this?

IsValid automates statistical tests for A/B experiments.  It will calculate significance and confidence intervials.

In short, you can use it to figure out what, if anything, the result of an A/B test means.

## How can I use it?

I host this exact code at [isvalid.org](http://isvalid.org).  If you want, you can host it yourself.

There is also an API (which you could host yourself!) that you can read about [here](https://github.com/evansolomon/IsValid.org/wiki/API).

## Bookmarklet

You can send data to IsValid with a handy browser bookmarklet using this as the URL:

    javascript:(function(){if(window.jQuery===undefined){var%20b=document.createElement(%22script%22);b.src=%22//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js%22;b.onload=b.onreadystatechange=function(){if(!this.readyState||this.readyState==%22loaded%22||this.readyState==%22complete%22){e();}};document.getElementsByTagName(%22head%22)[0].appendChild(b);}else{e();}function%20f(h){var%20i=h.target.innerHTML,g=parseInt(i.replace(%22,%22,%22%22),10);window.isvalidClicks.push(g);jQuery(h.target).css(%22background-color%22,%22rgba(50,%20200,%2080,%200.7)%22).addClass(%22isvalid-clicked%22);if(4==window.isvalidClicks.length){window.open(%22http://isvalid.org/%3Fsc=%22+window.isvalidClicks[0]+%22%26cc=%22+window.isvalidClicks[1]+%22%26se=%22+window.isvalidClicks[2]+%22%26ce=%22+window.isvalidClicks[3]);c();}}function%20a(i){var%20g=jQuery(this);if(g.hasClass(%22isvalid-clicked%22)){return;}i.stopPropagation();var%20j=i.target.innerHTML,h=parseInt(j.replace(%22,%22,%22%22),10);if(!isNaN(parseFloat(h))%26%26isFinite(h)){g.css(%22background-color%22,%22rgba(50,%20200,%2080,%200.25)%22);i.target.style.cursor=%22pointer%22;}}function%20d(h){var%20g=jQuery(this);if(!g.hasClass(%22isvalid-clicked%22)){g.css(%22background-color%22,%22%22);}}function%20e(){c();window.isvalidClicks=[];jQuery(document).mouseup(f);jQuery(%22*%22).hover(a,d);}function%20c(){jQuery(%22.isvalid-clicked%22).removeClass(%22isvalid-clicked%22).css(%22background-color%22,%22%22);jQuery(document).unbind(%22mouseup%22,f);jQuery(%22*%22).unbind(%22mouseleave%22,d).unbind(%22mouseenter%22,a);window.isvalidClicks=[];}})();
