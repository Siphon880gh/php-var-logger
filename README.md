PHP Var Logger
-------
By Weng Fei Fung
A variable logger to external files, PHP Var Logger can handle server sided, session, global, and local variables. There can be descriptions added. By not echoing or var_dumping on the layout and testing multiple variables in an organized, described manner, you cut down the development time. Also great to use in combination with mySQL queries where you may have to input variables from ajax and test if you have the correct rows returned.

****Initialization****<br/>
1. **MUST** include the php file:
`include("varlogger/init.php");`<br/>

2. Start logging with a filename.

    **Overriding or creating new log file**<br/>
    Optional.<br/>
    `varlogger_new("filename_or_relative_path");`

    **Appending to exisiting log file**<br/>
    Optional.<br/>
    `varlogger_append("filename_or_relative_path");`<br/>

    **Lazily default to RESULTS.log**<br/>
    `varlogger_new(); // OR`<br/>
    `varlogger_append(); // OR`<br/>
You can simply do no initializations.

3. Set UTC for timestamps.<br/>
Optional.<br/>
`varlogger_utc(int);`<br/>
Eg. `varlogger_utc(-8);`

****Logging****<br/>
**Where are we logging again?**<br/>
Returns the path of the log file. Pass true to log to console too.<br/>
`varlogger_string_path([true]);`

**Add timestamp with random letters and numbers**<br/>
`varlogger_stamp();`

**Log all possible variables**<br/>
Server variables, session variables, method parameters (eg. $_POST["someVar"]), global variables, and local variables. Pass true to log to console too.<br/>
`varlogger_vars([true]);`

**Log AJAX type variables**<br/>
Session variables and method parameters (eg. $_POST["someVar"]).<br/>
`varlogger_ajax();`

**Log value**<br/>
function varlogger( mixed $var, [ string, string, string ])
`varlogger($var);`

**Log variable name and value**<br/>
Just pass true<br/>
function varlogger( mixed $var, [ string, string, string ], true)
`varlogger($arr, true);`

**Add text before logging on same line**<br/>
`varlogger($var, "^The variable is:");`

**Add text after logging on same line**<br/>
`varlogger($var, "is the variable.$");`

**Add text before and text after logging on same line**<br/>
`varlogger($arr, "^The variable is:", "which should be an array with three elements$", true);`

**Lazily default to add text before logging on same line**<br/>
`varlogger($var, "The variable is:");`

**Start grouping of log lines**<br/>
`varlogger_group("Important Variables");`

**End grouping**<br/>
`varlogger_groupEnd();`

****Best Practices****<br/>
**-Use an editor that automatically reopens files that change if you want to see the logs live.**<br/>
**-You may add newlines and tabs into the string parameters with \n and \t to decorate the log file.**<br/>
**-If you pass true to `varlogger_string_path(..)` or `varlogger_vars(..)`, you will get the information instantly available at Console. Make sure to turn that off before uploading to final production because that's sensitive information. I am not responsible for any damage caused.**<br/>
