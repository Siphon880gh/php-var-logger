###Rapid Debug (PHP only)
####*Debug in PHP by logging variables with custom descriptions to a log file so you aren't forced to break the website layout or so you can log the variables that result from AJAX requests. -Weng Fei Fung*
<p/>
**Initialization**<br/>
1. \*MUST\* include the php file:
`include("redebug/init.php");`<br/>
<p/>
2. Start logging with a filename.
<p/>
    *Overriding or creating new log file*<br/>
    Optional.<br/>
    `rdebug_new("filename_or_relative_path");`
<p/>
    *Appending to exisiting log file*<br/>
    Optional.<br/>
    `rdebug_append("filename_or_relative_path");`<br/>
<p/>
    *Lazily default to RESULTS.log*<br/>
    `rdebug_new(); // OR`<br/>
    `rdebug_append(); // OR`<br/>
You can simply do no initializations.
<p/>
3. Set UTC for timestamps.<br/>
Optional.<br/>
`rdebug_utc(int);`<br/>
Eg. `rdebug_utc(-8);`
<p/>
**Logging**<br/>
*Where are we logging again?*<br/>
Returns the path of the log file. Pass true to log to console too.<br/>
`rdebug_string_path([true]);`
<p/>
*Add timestamp with random letters and numbers*<br/>
`rdebug_stamp();`
<p/>
*Log all possible variables*<br/>
Server variables, session variables, method parameters (eg. $_POST["someVar"]), global variables, and local variables. Pass true to log to console too.<br/>
`rdebug_vars([true]);`
<p/>
*Log AJAX type variables*<br/>
Session variables and method parameters (eg. $_POST["someVar"]).<br/>
`rdebug_ajax();`
<p/>
*Log value*<br/>
function rdebug( mixed $var, [ string, string, string ])
`rdebug($var);`
<p/>
*Log variable name and value*<br/>
Just pass true<br/>
function rdebug( mixed $var, [ string, string, string ], true)
`rdebug($arr, true);`
<p/>
*Add text before logging on same line*<br/>
`rdebug($var, "^The variable is:");`
<p/>
*Add text after logging on same line*<br/>
`rdebug($var, "is the variable.$");`
<p/>
*Add text before and text after logging on same line*<br/>
`rdebug($arr, "^The variable is:", "which should be an array with three elements$", true);`
<p/>
*Lazily default to add text before logging on same line*<br/>
`rdebug($var, "The variable is:");`
<p/>
*Start grouping of log lines*<br/>
`rdebug_group("Important Variables");`
<p/>
*End grouping*<br/>
`rdebug_groupEnd();`
<p/>
**Best Practices**<br/>
*-Use an editor that automatically reopens files that change if you want to see the logs live.*<br/>
*-You may add newlines and tabs into the string parameters with \n and \t to decorate the log file.*<br/>
*-If you pass true to `rdebug_string_path(..)` or `rdebug_vars(..)`, you will get the information instantly available at Console. Make sure to turn that off before uploading to final production because that's sensitive information. I am not responsible for any damage caused.*<br/>