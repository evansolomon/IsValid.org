<?php

function print_footer_scripts() {
?>
	<!-- Scripts -->
	<script src="static/js/scripts.min.js"></script>
<?php
}

function print_footer() {
?>
	<div class="span4 offset4">
		<p><a href="https://github.com/evansolomon/IsValid.org">Source code</a> & <a href="https://github.com/evansolomon/IsValid.org/wiki/API">API documentation</a> on&nbsp;Github</p>
		<p><a href='javascript:(function(){var e;e={init:function(){var t;return window.jQuery?e.ready():(t=document.createElement("script"),t.type="text/javascript",t.src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js",t.onload=t.onreadystatechange=function(){return this.readyState&&"loaded"!==this.readyState&&"complete"!==this.readyState?void 0:e.ready()},document.getElementsByTagName("head")[0].appendChild(t))},ready:function(){return e.reset(),e.header.create(),jQuery(document).click(e.click),jQuery("*").hover(e.mouseEnter,e.mouseLeave)},click:function(t){var n,r;return r=t.target.innerHTML,n=parseInt(r.replace(/,/g,""),10),e.selectable(n)?(e.selected.push(n),jQuery(t.target).css("background-color","rgba(50, 200, 80, 0.7)").addClass("isvalid-clicked"),jQuery(".isvalid-steps").text(e.nextStep()),4===e.selected.length&&(window.open("http://isvalid.org/?sc="+e.selected[0]+"&cc="+e.selected[1]+"&se="+e.selected[2]+"&ce="+e.selected[3]),e.reset()),t.target.style.cursor="default",e.header.update(),!1):void 0},mouseLeave:function(e){var t;return t=jQuery(this),t.hasClass("isvalid-clicked")||t.hasClass("isvalid-steps")||t.css("background-color",""),e.target.style.cursor="default"},mouseEnter:function(t){var n,r,i;return n=jQuery(this),!n.hasClass("isvalid-clicked")&&(i=t.target.innerHTML,r=parseInt(i.replace(/,/g,""),10),e.selectable(r))?(t.stopPropagation(),n.css("background-color","rgba(50, 200, 80, 0.25)"),t.target.style.cursor="pointer"):void 0},selectable:function(e){return!isNaN(parseFloat(e))&&isFinite(e)},nextStep:function(){var t;switch(t=void 0,e.selected.length){case 0:t="Control samples";break;case 1:t="Control conversions";break;case 2:t="Experiment samples";break;case 3:t="Experiment conversions";break;default:t="This should never happen"}return"Select: "+t},header:{create:function(){var e;return e=jQuery("<div>"),e.css({top:"0px",left:"0px","background-color":"rgba(50, 200, 80, 0.8)","z-index":"10000",width:"100%",height:"60px",position:"fixed","text-align":"center","padding-top":"20px","font-size":"30px"}),e.addClass("isvalid-steps"),e.prependTo("body"),this.update()},update:function(){return jQuery(".isvalid-steps").text(e.nextStep())}},reset:function(){return jQuery(".isvalid-clicked").removeClass("isvalid-clicked").css("background-color",""),jQuery(document).unbind("click",e.click),jQuery("*").unbind("mouseleave",e.mouseLeave).unbind("mouseenter",e.mouseEnter),jQuery(".isvalid-steps").remove(),e.selected=[]}},e.init()}).call(this);'>Isvalid-ify</a> bookmarklet here & <a href="https://github.com/evansolomon/IsValid.org/wiki/Bookmarklet">instructions</a> on&nbsp;Github</p>
	</div>

<?php
}