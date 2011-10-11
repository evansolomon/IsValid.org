<h1>IsValid.org API</h1>
<p>Everything is json encoded and accepts GET or POST requests</p>

<h3>Confidence Interval</h3>
<p>Use case: Calculate the estimated lower, middle and upper bound for a population's conversion rate using sample data. This test is used to determine the appropriate margin for error given performance and sample size.</p>
<p>Required Parameters:</p>
<ul>
<li><strong>conversions</strong> (integer greater than or equal to 0)</li>
<li><strong>samples</strong> (integer greater than or equal to conversions)</li>
</ul>
<p>Optional Parameters:</p>
<ul>
<li><strong>confidence</strong> (decimal greater than 0 and less than 1, .999 used by default)</li>
</ul>
<p>Response:</p>
<ul>
<li>results:</li>
	<ul>
	<li>low: lower bound of confidence interval</li>
	<li>average: middle of confidence interval, same as observed conversion rate</li>
	<li>high: upper bound of confidence interval</li>
	</ul>
<li>chart: Google chart API URL</li>
</ul>
<p>Example query: <em><a href="?function=confidence&conversions=100&samples=1000&confidence=.95">?function=confidence&conversions=100&samples=1000&confidence=.95</a></em></p>
<p>Example response: {"results":{"low":0.081405860999113,"average":0.1,"high":0.11859413900089},"chart":"http:\/\/chart.apis.google.com\/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:8.1405860999113|10|11.859413900089"}</p>


<h3>Significance Test</h3>
<p>Use case: Calculate the likelihood that an experimental variant is better than a control variant. This test is used to determine if an experimental variant is likely to be better than the control, but not by how much.</p>
<p>Required Parameters:</p>
<ul>
<li><strong>conversions_control</strong> (integer greater than or equal to 0)</li>
<li><strong>samples_control</strong> (integer greater than or equal to control conversions)</li>
<li><strong>conversions_experiment</strong> (integer greater than or equal to 0)</li>
<li><strong>samples_experiment</strong> (integer greater than or equal to experiment conversions)</li>
</ul>
<p>Optional Parameters:</p>
<ul>
<li><em>None</em></li>
</ul>
<p>Response:</p>
<ul>
<li>results:</li>
	<ul>
	<li>control: likelihood that the control is the better variant</li>
	<li>experiment: likelihood that the experiment is the better variant</li>
	</ul>
<li>chart: Google chart API URL</li>
</ul>
<p>Example query: <em><a href="?function=significance&conversions_control=100&samples_control=1000&conversions_experiment=150&samples_experiment=1000">?function=significance&conversions_control=100&samples_control=1000&conversions_experiment=150&samples_experiment=1000</a></em></p>
<p>Example response: {"results":{"control":0.00034911083604428,"experiment":0.99965088916396},"chart":"http:\/\/chart.apis.google.com\/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:0.99965088916396"}</p>

<h3>Improvement</h3>
<p>Use case: Calculate the estimated lower, middle and upper bound for an experiment's improvement relative to a control for a population using sample data. This test is used to determine how much better (or worse) an experimental variant is, with appropriate margin for error based on performance and sample size.</p>
<p>Required Parameters:</p>
<ul>
<li><strong>conversions_control</strong> (integer greater than or equal to 0)</li>
<li><strong>samples_control</strong> (integer greater than or equal to control conversions)</li>
<li><strong>conversions_experiment</strong> (integer greater than or equal to 0)</li>
<li><strong>samples_experiment</strong> (integer greater than or equal to experiment conversions)</li>
</ul>
<p>Optional Parameters:</p>
<ul>
<li><strong>confidence</strong> (decimal greater than 0 and less than 1, .8 used by default)</li>
</ul>
<p>Response:</p>
<ul>
<li>results:</li>
	<ul>
	<li>low: lower bound of confidence interval</li>
	<li>average: middle of confidence interval, same as observed improvement of experiment</li>
	<li>high: upper bound of confidence interval</li>
	</ul>
<li>chart: Google chart API URL</li>
</ul>
<p>Example query: <em><a href="?function=improvement&conversions_control=100&samples_control=1000&conversions_experiment=150&samples_experiment=1000&confidence=.9">?function=improvement&conversions_control=100&samples_control=1000&conversions_experiment=150&samples_experiment=1000&confidence=.9</a></em></p>
<p>Example response: {"results":{"low":0.25740646488421,"average":0.5,"high":0.74259353511579},"chart":"http:\/\/chart.apis.google.com\/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:62.870323244211|75|87.129676755789"}</p>

<h3>Errors</h3>
<p>Appropriate error messages will be returned for malformed data or functiona arguments.</p>