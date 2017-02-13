<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once(PATH_THIRD . 'math/addon.setup.php');

$plugin_info = array (
	'pi_author' => MATH_AUTHOR,
	'pi_author_url' => MATH_AUTHOR_URL,
	'pi_description' => MATH_DESC,
	'pi_name' => MATH_NAME,
	'pi_version' => MATH_VER,
	'pi_usage' => Math::usage()
);

class Math
{
	public $return_data = '';

	public function __construct() {
		$formula = ee()->TMPL->fetch_param('formula');

		$error = false;
		$result = '';

		if ($formula !== false) {
			// Convert HTML entities to math characters
			$formula = html_entity_decode($formula);

			// Replace parameters
			$params = ee()->TMPL->fetch_param('params');
			$numericError = ee()->TMPL->fetch_param('numeric_error', 'Invalid input');

			if ($params !== false) {
				$params = explode('|', $params);
				$i = 1;

				foreach ($params as $param) {
					if (! is_numeric($param)) {
						$param = preg_replace('/[^0-9.]*/', '', $param);

						if (! is_numeric($param)) {
							$this->return_data = $numericError;

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
				$decimalPoint = ee()->TMPL->fetch_param('dec_point', '.');
				$thousandsSeparator = ee()->TMPL->fetch_param('thousands_separator', ',');
				$absolute = ee()->TMPL->fetch_param('absolute');
				$trailingZeros = ee()->TMPL->fetch_param('trailing_zeros');

				// Absolute value
				if ($absolute) {
					$result = abs($result);
				}

				// Rounding
				if ($decimals !== false || $round !== false) {
					$dec = $decimals !== false ? $decimals : 0;
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

				$decimalValue = count($parts) > 1 ? $parts[1] : false;
				$decimalDigits = strlen($decimalValue);

				// Format response
				if ($decimals !== false) {
					$result = number_format((int) $parts[0], 0, $decimalPoint, $thousandsSeparator);

					if ($decimals > 0) {
						if ($decimalDigits < $decimals && $trailingZeros) {
							$result .= $decimalPoint . str_pad($decimalValue, $decimals, 0);
						} else {
							$result .= $decimalValue ? ($decimalPoint . $decimalValue) : '';
						}
					}
				} else {
					$decimals = $decimalDigits;

					$result = number_format(
						(float) $result, $decimals, $decimalPoint, $thousandsSeparator
					);
				}
			}
		}

		$this->return_data = $result;
	}

	public static function usage() {
		return 'See docs and examples at https://github.com/caddis/math';
	}
}
