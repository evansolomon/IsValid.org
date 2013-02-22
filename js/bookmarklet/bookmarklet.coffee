isvalid =
  init: ->
    unless window.jQuery
      script        = document.createElement "script"
      script.type   = "text/javascript"
      script.src    = "//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"
      script.onload = script.onreadystatechange = ->
        isvalid.ready() if not @readyState or @readyState is "loaded" or @readyState is "complete"

      document.getElementsByTagName( "head" )[0].appendChild script
    else
      isvalid.ready()

  ready: ->
    isvalid.reset()
    isvalid.header.create()
    jQuery( document ).click isvalid.click
    jQuery( "*" ).hover isvalid.mouseEnter, isvalid.mouseLeave

  click: ( event ) ->
    text   = event.target.innerHTML
    number = parseInt text.replace( /,/g, "" ), 10
    return unless isvalid.selectable number

    isvalid.selected.push number
    jQuery(event.target).css("background-color", "rgba(50, 200, 80, 0.7)").addClass "isvalid-clicked"
    jQuery(".isvalid-steps").text isvalid.nextStep()
    if 4 is isvalid.selected.length
      window.open "http://isvalid.org/?sc=#{isvalid.selected[0]}&cc=#{isvalid.selected[1]}&se=#{isvalid.selected[2]}&ce=#{isvalid.selected[3]}"
      isvalid.reset()

    event.target.style.cursor = "default"
    isvalid.header.update()
    false

  mouseLeave: ( event ) ->
    element = jQuery @
    element.css "background-color", "" if not element.hasClass("isvalid-clicked") and not element.hasClass("isvalid-steps")
    event.target.style.cursor = "default"

  mouseEnter: ( event ) ->
    element = jQuery @
    return if element.hasClass "isvalid-clicked"

    text   = event.target.innerHTML
    number = parseInt text.replace( /,/g, "" ), 10
    return unless isvalid.selectable number

    event.stopPropagation()
    element.css "background-color", "rgba(50, 200, 80, 0.25)"
    event.target.style.cursor = "pointer"

  selectable: (string) ->
    not isNaN( parseFloat( string ) ) and isFinite( string )

  nextStep: ->
    direction = undefined
    switch isvalid.selected.length
      when 0 then direction = "Control samples"
      when 1 then direction = "Control conversions"
      when 2 then direction = "Experiment samples"
      when 3 then direction = "Experiment conversions"
      else direction = "This should never happen"

    "Select: #{direction}"

  header:
    create: ->
      header = jQuery "<div>"
      header.css
        top                : "0px"
        left               : "0px"
        "background-color" : "rgba(50, 200, 80, 0.8)"
        "z-index"          : "10000"
        width              : "100%"
        height             : "60px"
        position           : "fixed"
        "text-align"       : "center"
        "padding-top"      : "20px"
        "font-size"        : "30px"

      header.addClass "isvalid-steps"
      header.prependTo "body"
      @update()

    update: ->
      jQuery(".isvalid-steps").text isvalid.nextStep()

  reset: ->
    jQuery(".isvalid-clicked").removeClass("isvalid-clicked").css "background-color", ""
    jQuery(document).unbind "click", isvalid.click
    jQuery("*").unbind("mouseleave", isvalid.mouseLeave).unbind "mouseenter", isvalid.mouseEnter
    jQuery(".isvalid-steps").remove()
    isvalid.selected = []

isvalid.init()
