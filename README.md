## Math 1.5.1

Math allows you to execute many PHP math functions in your ExpressionEngine templates without needing to enable PHP parsing.

## Quick Tag Reference (full documentation below)

```
formula="(5 * 2) / [1]" — math formula (required) supports the following operators as well as bitwise + - * / % ++ -- < > <= => != <> ==
params="{var}|{var2}" — pipe delimited list of numeric parameters to be replaced into formula, recommended due to use of PHP eval (default: null)
decimals="2" — sets the number of decimal points (default: "0")
dec_point="." — sets the separator for the decimal point (default: ".")
thousands_seperator="," — sets the thousands separator; (default: ",")
absolute="yes" — return the absolute number of the result (defaults: "no")
round="up|down|ceil" — whether to round the result up or down (defaults: no rounding)
numeric_error="Error" — message returned when non-numeric parameters are provided (default: "Invalid input")
trailing_zeros="yes" — include trailing 0 decimal places (defaults: "no")
```

## Parameters

More detailed reference.

### formula="(5 * 2) / [1]"

This is a required parameter and supports the following standard PHP operators as well as bitwise operators:

	+ - * / % ++ -- < > <= => != <> ==

Example:

	{exp:math formula="10 - 2"}

Output: 8

### params="{var}|{var2}"

This is a pipe-delimited list of numeric parameters to be replaced into the formula, recommendeded when using dynamic parameters (default: null).

Set your parameters and call them by bracketed number reference. For instance [1] would call the first value, [2] would call the second value, and so forth.

Example:

	{exp:math formula="[1] - [2]" params="{var1}|{var2}"}

### decimals="2"

Sets the number of decimal points (default: "0")

Example:

	{exp:math formula="((4 * 3) / 5)" decimals="1"}

Output: 2.4

### dec_point="."

Sets the separator for the decimal point (default: ".")

### thousands_seperator=","

Sets the thousands separator (default: ",")

### absolute="yes"

Return the absolute value of the result (defaults: "no")

Example:

	{exp:math formula="10 - 12" absolute="yes"}

Output: 2

### round="up|down|ceil"

Whether to round the result up or down (defaults: no rounding)

Example:

	{exp:math formula="([1] + 1) / [2]" params="{total_results}|2" round="down"}

Output: 5 (where {total_results} is 10)

	{exp:math formula="2/3" decimals="2" round="up"}

Output: 0.67

### numeric_error="Error"

Message returned when non-numeric parameters are provided (default: "Invalid input")

### trailing_zeros="yes"

Include trailing 0 decimal places (defaults: "no")

## Installation

### EE 2

DevDemon Updater is fully supported, or for manual installs copy "system/expressionengine/third_party/math" to your third_party system directory.

### EE 3

1. Copy "system/expressionengine/third_party/math" to "system/user/addons"
2. Go to your control panel and navigate to the Add-On Manager
3. Locate Math in the Third Party Add-Ons section and click install


## License

Copyright 2016 [Caddis Interactive, LLC](https://www.caddis.co). Licensed under the [Apache License, Version 2.0](https://github.com/caddis/math/blob/master/LICENSE).