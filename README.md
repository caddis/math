ExpressionEngine Math Plugin 1.3.1
====

Use Math to execute PHP supported math formulas in ExpressionEngine.

Parameters:

	formula = '(5 * 2) / [1]' // math formula (required) supports the following operators as well as bitwise + - * / % ++ -- < > <= => != <> ==

	params = '{var}|{var2}' // pipe delimited list of numeric parameters to be replaced into formula, recommended due to use of PHP eval (default: null)

	decimals = '2' // sets the number of decimal points (default: "0")

	decimal_point = '.' // sets the separator for the decimal point (default: ".")

	thousands_seperator = ',' // sets the thousands separator; (default: ",")

	absolute = 'yes' // return the absolute number of the result (defaults: "no")

	round = 'up|down|ceil' // whether to round the result up or down (defaults: no rounding)

	numeric_error = 'Error' // message returned when non-numeric parameters are provided (default: "Invalid input")

	trailing_zeros = 'yes'	// include trailing 0 decimal places (defaults: "no")

Usage:

	{exp:math formula="10 - 12" absolute="yes"} outputs 2

	{exp:math formula="((4 * 5) / 2)" decimals="2"} outputs 10.00

	{exp:math formula="([1] + 1) / [2]" params="{total_results}|2" round="down"} outputs 5 where {total_results} is 10

	{exp:math formula="2/3" decimals="2" round="up"} outputs 0.67