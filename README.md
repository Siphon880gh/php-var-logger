Rapid Debug (PHP only)
-------
By Weng Fei Fung
A variable logger to external files, Rapid PHP Debug can handle server sided, session, global, and local variables. There can be descriptions added. By not echoing or var_dumping on the layout and testing multiple variables in an organized, described manner, you cut down the development time. Also great to use in combination with mySQL queries where you may have to input variables from ajax and test if you have the correct rows returned.

****Initialization****<br/>
1. **MUST** include the php file:
`include("redebug/init.php");`<br/>

2. Start logging with a filename.

    **Overriding or creating new log file**<br/>
    Optional.<br/>
    `rdebug_new("filename_or_relative_path");`

    **Appending to exisiting log file**<br/>
    Optional.<br/>
    `rdebug_append("filename_or_relative_path");`<br/>

    **Lazily default to RESULTS.log**<br/>
    `rdebug_new(); // OR`<br/>
    `rdebug_append(); // OR`<br/>
You can simply do no initializations.

3. Set UTC for timestamps.<br/>
Optional.<br/>
`rdebug_utc(int);`<br/>
Eg. `rdebug_utc(-8);`

****Logging****<br/>
**Where are we logging again?**<br/>
Returns the path of the log file. Pass true to log to console too.<br/>
`rdebug_string_path([true]);`

**Add timestamp with random letters and numbers**<br/>
`rdebug_stamp();`

**Log all possible variables**<br/>
Server variables, session variables, method parameters (eg. $_POST["someVar"]), global variables, and local variables. Pass true to log to console too.<br/>
`rdebug_vars([true]);`

**Log AJAX type variables**<br/>
Session variables and method parameters (eg. $_POST["someVar"]).<br/>
`rdebug_ajax();`

**Log value**<br/>
function rdebug( mixed $var, [ string, string, string ])
`rdebug($var);`

**Log variable name and value**<br/>
Just pass true<br/>
function rdebug( mixed $var, [ string, string, string ], true)
`rdebug($arr, true);`

**Add text before logging on same line**<br/>
`rdebug($var, "^The variable is:");`

**Add text after logging on same line**<br/>
`rdebug($var, "is the variable.$");`

**Add text before and text after logging on same line**<br/>
`rdebug($arr, "^The variable is:", "which should be an array with three elements$", true);`

**Lazily default to add text before logging on same line**<br/>
`rdebug($var, "The variable is:");`

**Start grouping of log lines**<br/>
`rdebug_group("Important Variables");`

**End grouping**<br/>
`rdebug_groupEnd();`

****Best Practices****<br/>
**-Use an editor that automatically reopens files that change if you want to see the logs live.**<br/>
**-You may add newlines and tabs into the string parameters with \n and \t to decorate the log file.**<br/>
**-If you pass true to `rdebug_string_path(..)` or `rdebug_vars(..)`, you will get the information instantly available at Console. Make sure to turn that off before uploading to final production because that's sensitive information. I am not responsible for any damage caused.**<br/>
