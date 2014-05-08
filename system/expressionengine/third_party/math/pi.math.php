<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array (
	'pi_name' => 'Math',
	'pi_version' => '1.4.1',
	'pi_author' => 'Caddis',
	'pi_author_url' => 'http://www.caddis.co',
	'pi_description' => 'Use Math to execute PHP supported math formulas.',
	'pi_usage' => Math::usage()
);

class Math {

	public $return_data = '';

	public function __construct()
	{
		// Get formula
		$formula = ee()->TMPL->fetch_param('formula');

		$error = false;
		$result = '';

		if ($formula !== false) {
			// Convert html entities to math characters
			$formula = html_entity_decode($formula);

			// Replace parameters
			$params = ee()->TMPL->fetch_param('params');
			$numeric_error = ee()->TMPL->fetch_param('numeric_error', 'Invalid input');

			if ($params !== false) {
				$params = explode('|', $params);
				$i = 1;

				foreach ($params as $param) {
					if (! is_numeric($param)) {
						$param = preg_replace('/[^0-9.]*/', '', $param);

						if (! is_numeric($param)) {
							$this->return_data = $numeric_error;

							return;
						}
					}

					$formula = str_replace('[' . $i . ']', $param, $formula);

					$i++;
				}
			}

			if ($error !== true) {
				// Evaluate math
				@eval("\$result = $formula;");

				// Get settings
				$round = ee()->TMPL->fetch_param('round');
				$decimals = ee()->TMPL->fetch_param('decimals');
				$decimal_point = ee()->TMPL->fetch_param('dec_point', '.');
				$thousands_seperator = ee()->TMPL->fetch_param('thousands_seperator', ',');
				$absolute = ee()->TMPL->fetch_param('absolute');
				$trailing_zeros = ee()->TMPL->fetch_param('trailing_zeros');

				$decimal_digits = 0;

				// Absolute value
				if ($absolute) {
					$result = abs($result);
				}

				// Rounding
				if ($decimals !== false or $round !== false) {
					$dec = ($decimals !== false) ? $decimals : 0;
					$mult = pow(10, $dec);

					switch ($round) {
						case 'up':
							$result = round($result, $dec, PHP_ROUND_HALF_UP);
							break;
						case 'ceil':
	   						$result = ceil($result * $mult) / $mult;
							break;
	   					default:
	   						$result = intval($result * $mult) / $mult;
					} 
				}

				$parts = explode('.', $result);

				$decimal_value = (count($parts) > 1) ? $parts[1] : false;
				$decimal_digits = strlen($decimal_value);

				// Format response
				if ($decimals !== false) {
					$result = number_format((int) $parts[0], 0, $decimal_point, $thousands_seperator);

					if ($decimals > 0) {
						if ($decimal_digits < $decimals and $trailing_zeros) {
							$result .= '.' . str_pad($decimal_value, $decimals, 0);
						} else {
							$result .= $decimal_value ? ('.' . $decimal_value) : '';
						}
					}
				} else {
					$decimals = $decimal_digits;

					$result = number_format((float) $result, $decimals, $decimal_point, $thousands_seperator);
				}
			}
		}

		$this->return_data = $result;
	}

	public static function usage()
	{
		ob_start();
?>
Parameters:

formula = '(5 * 2) / [1]' // math formula (required) supports the following operators as well as bitwise + - * / % ++ -- < > <= => != <> ==
params = '{var}|{var2}'   // pipe delimited list of numeric parameters to be replaced into formula, recommended due to use of PHP eval (default: null)
decimals = '2'            // sets the number of decimal points (default: "0")
decimal_point = '.'       // sets the separator for the decimal point (default: ".")
thousands_seperator = ',' // sets the thousands separator; (default: ",")
absolute = 'yes'          // return the absolute number of the result (defaults: "no")
round = 'up|down|ceil'    // whether to round the result up or down, where up is standard rounding (defaults: no rounding)
numeric_error = 'Error'   // message returned when non-numeric parameters are provided (default: "Invalid input")
trailing_zeros = 'yes'    // include trailing 0 decimal places (defaults: "no")

Usage:

{exp:math formula="10 - 12" absolute="yes"} outputs 2
{exp:math formula="((4 * 5) / 2)" decimals="2"} outputs 10.00
{exp:math formula="([1] + 1) / [2]" params="{total_results}|2" round="down"} outputs 5 where {total_results} is 10
{exp:math formula="2/3" decimals="2" round="up"} outputs 0.67
<?php
		$buffer = ob_get_contents();

		ob_end_clean();

		return $buffer;
	}
}
?>