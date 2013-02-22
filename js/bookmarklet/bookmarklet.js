(function() {
  var isvalid;

  isvalid = {
    init: function() {
      var script;
      if (!window.jQuery) {
        script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js";
        script.onload = script.onreadystatechange = function() {
          if (!this.readyState || this.readyState === "loaded" || this.readyState === "complete") {
            return isvalid.ready();
          }
        };
        return document.getElementsByTagName("head")[0].appendChild(script);
      } else {
        return isvalid.ready();
      }
    },
    ready: function() {
      isvalid.reset();
      isvalid.header.create();
      jQuery(document).click(isvalid.click);
      return jQuery("*").hover(isvalid.mouseEnter, isvalid.mouseLeave);
    },
    click: function(event) {
      var number, text;
      text = event.target.innerHTML;
      number = parseInt(text.replace(/,/g, ""), 10);
      if (!isvalid.selectable(number)) {
        return;
      }
      isvalid.selected.push(number);
      jQuery(event.target).css("background-color", "rgba(50, 200, 80, 0.7)").addClass("isvalid-clicked");
      jQuery(".isvalid-steps").text(isvalid.nextStep());
      if (4 === isvalid.selected.length) {
        window.open("http://isvalid.org/?sc=" + isvalid.selected[0] + "&cc=" + isvalid.selected[1] + "&se=" + isvalid.selected[2] + "&ce=" + isvalid.selected[3]);
        isvalid.reset();
      }
      event.target.style.cursor = "default";
      isvalid.header.update();
      return false;
    },
    mouseLeave: function(event) {
      var element;
      element = jQuery(this);
      if (!element.hasClass("isvalid-clicked") && !element.hasClass("isvalid-steps")) {
        element.css("background-color", "");
      }
      return event.target.style.cursor = "default";
    },
    mouseEnter: function( event) {
      var element, number, text;
      element = jQuery(this);
      if (element.hasClass("isvalid-clicked")) {
        return;
      }
      text = event.target.innerHTML;
      number = parseInt(text.replace(/,/g, ""), 10);
      if (!isvalid.selectable(number)) {
        return;
      }
      event.stopPropagation();
      element.css("background-color", "rgba(50, 200, 80, 0.25)");
      return event.target.style.cursor = "pointer";
    },
    selectable: function(string) {
      return !isNaN(parseFloat(string)) && isFinite(string);
    },
    nextStep: function() {
      var direction;
      direction = void 0;
      switch (isvalid.selected.length) {
        case 0:
          direction = "Control samples";
          break;
        case 1:
          direction = "Control conversions";
          break;
        case 2:
          direction = "Experiment samples";
          break;
        case 3:
          direction = "Experiment conversions";
          break;
        default:
          direction = "This should never happen";
      }
      return "Select: " + direction;
    },
    header: {
      create: function() {
        var header;
        header = jQuery("<div>");
        header.css({
          top: "0px",
          left: "0px",
          "background-color": "rgba(50, 200, 80, 0.8)",
          "z-index": "10000",
          width: "100%",
          height: "60px",
          position: "fixed",
          "text-align": "center",
          "padding-top": "20px",
          "font-size": "30px"
        });
        header.addClass("isvalid-steps");
        header.prependTo("body");
        return this.update();
      },
      update: function() {
        return jQuery(".isvalid-steps").text(isvalid.nextStep());
      }
    },
    reset: function() {
      jQuery(".isvalid-clicked").removeClass("isvalid-clicked").css("background-color", "");
      jQuery(document).unbind("click", isvalid.click);
      jQuery("*").unbind("mouseleave", isvalid.mouseLeave).unbind("mouseenter", isvalid.mouseEnter);
      jQuery(".isvalid-steps").remove();
      return isvalid.selected = [];
    }
  };

  isvalid.init();

}).call(this);
