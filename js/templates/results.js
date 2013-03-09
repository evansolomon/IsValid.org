this["Handlebars"] = this["Handlebars"] || {};
this["Handlebars"]["templates"] = this["Handlebars"]["templates"] || {};

this["Handlebars"]["templates"]["results"] = Handlebars.template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [2,'>= 1.0.0-rc.3'];
helpers = helpers || Handlebars.helpers; data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n    <div class=\"result span6\">\n      <h3>";
  if (stack1 = helpers.title) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.title; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</h3>\n      <div class=\"average\">";
  if (stack1 = helpers.average) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.average; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "%</div>\n      ";
  stack1 = helpers['if'].call(depth0, depth0.high, {hash:{},inverse:self.noop,fn:self.program(2, program2, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n      <img src=\"";
  if (stack1 = helpers.chart) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.chart; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" class=\"chart-image\" alt=\"";
  if (stack1 = helpers.average) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.average; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\n    </div>\n  ";
  return buffer;
  }
function program2(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\n        <div class=\"range\">";
  if (stack1 = helpers.low) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.low; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " – ";
  if (stack1 = helpers.high) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.high; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</div>\n      ";
  return buffer;
  }

  buffer += "<div class=\"row\">\n  ";
  stack1 = helpers.each.call(depth0, depth0.results, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\n</div>\n";
  return buffer;
  });