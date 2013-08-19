<?php
/************************************************************
  Validation Class
  Author: Jason Ashdown
  Updated: 12:12 24/07/2008
  Version 0.2j

 --------------------------------------------------------
 Change Log:-
 v0.2j - Added 'decode' function to form helpers. To help
		 pass UTF-8 characters back to a form from the DB.
 v0.2h - Removed 'spaces' from the regexp strings
 v0.2g - Email address function is now in compliant
		 with RFC 2822
 v0.2f - Added $masks for allErrors to be more
		 user-friendly, updated loop_clean to use
		 htmlspecialchars and supports UTF-8
 v0.2e - Fixed allErrors() to include wrappers
 v0.2d - Changed "required" to check for empty
		 strings properly
 v0.2c - Added "not_equal" and "selected" functions
 v0.2b - Tidied comments; fixed email mx check
 v0.2a - Added loop_clean (multi-array sanitiser)
 V0.2 - Added new helper functions (label, check)
 - - -
 v0.1 - Initial Release
*************************************************************/

class validateForm
{
	var $input = array();
	var $error = array();
	var $error_wrapper;
	var $pass;

	// You can call the validated inputs directly from this class
	// when you come to inserting them into the db, e.g.
	// $form = new validateForm($_POST);
	// $form->input['fullname']; etc...

	function validateForm($input=array()) // Yes, post the the whole $_POST/$_GET array into the function
	{
		$this->input = $input; // Clone form inputs array into here

		$this->pass = true; // Flag changes if theres an error

		// Config
		$this->error_wrapper['start'] = "<br /><span class=\"error\">";
		$this->error_wrapper['end'] = "</span>";

		// Sanitise our arrays
		$this->loop_clean($this->input);
	}

	//
	// We can even perform some security checks here if we wish
	//
	function loop_clean(&$data)
	{
		foreach ($data as $key => $value)
		{
			if ( !is_array($value) )
			{
				// Well formatted string; PHP4 requires "stripslashes" on all input fields
				$data[$key] = trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8'));
			}
			else
			{
				$this->loop_clean($value);
				$data[$key] = $value;
			}
		}
	}

/************************************/
/* Error Functions                  */
/************************************/

	// Set error
	function error($item, $desc)
	{
		$this->pass = false;
		$this->error[$item] .= $desc." "; // Append multiple error messages
	}

	// Return the error
	function showError($item)
	{
		return $this->error_wrapper['start'].trim($this->error[$item]).$this->error_wrapper['end'];
	}

	function allErrors($masks=array())
	{
		foreach ( $this->error as $key => $value )
		{
			// Mask field names with more appropriate User friendly names
			$key = $masks[$key] != '' ? $masks[$key] : $key;
			echo $this->error_wrapper['start']."<b>".ucfirst($key)."</b>: ".trim($value).$this->error_wrapper['end'];
		}
	}

/************************************/
/* Debugging                        */
/************************************/

	function showInputs()
	{
		print_r($this->input);
	}

/************************************/
/* Validation Functions             */
/************************************/

	function not_equal($string, $field)
	{
		if ( is_string($string) )
		{
			if ($string == $this->input[$field])
			{
				$msg = "You must select a different option other than \"$string\"";
				$this->error($field, $msg);
				return false;
			}
			return true;
		}
		return false;
	}

	function min_length($min=0, $field)
	{
		if( strlen($this->input[$field]) < (int) $min )
		{
			$msg = "This field cannot be shorter than $min characters.";
			$this->error($field, $msg);
			return false;
		}
		return true;
	}

	function max_length($max=0, $field)
	{
		if ( strlen($this->input[$field]) > (int) $max )
		{
			$msg = "This field cannot be longer than $max characters.";
			$this->error($field, $msg);
			return false;
		}
		return true;
	}

	function alpha($field)
	{
		if ( !preg_match("/^([a-z])+$/i", $this->input[$field]) )
		{
			$msg = "This field can only contain letters (A-Z). No foreign characters allowed.";
			$this->error($field, $msg);
			return false;
		}
		return true;
	}

	// Double-barrel names and marital status will require this
	function alpha_dotdash($field)
	{
		if ( !preg_match("/^([a-z\-\.])+$/i", $this->input[$field]) )
		{
			$msg = "This field can only contain characters (A-Z-.). No foreign characters allowed.";
			$this->error($field, $msg);
			return false;
		}
		return true;
	}

	// Useful for Addresses or fields that may contain unusual but still valid chars
	function alpha_special($field)
	{
		if ( !preg_match("/^([a-z0-9\-+\.,_='\"@#])+$/i", $this->input[$field]) )
		{
			$msg = "This field has illegal characters. You can use letters, numbers and (._-+='\"@#).";
			$this->error($field, $msg);
			return false;
		}
		return true;
	}

	function numeric($field)
	{
		if ( !preg_match("/^[\-+]?[0-9]*\.?[0-9]+$/", $this->input[$field]) )
		{
			$msg = "This field must contain only numbers.";
			$this->error($field, $msg);
			return false;
		}
		return true;
		//return is_numeric($this->input[$field]);
	}

	function alpha_numeric($field)
	{
		if( !preg_match("/^([a-z0-9])+$/i", $this->input[$field]) )
		{
			$msg = "This field can only contain letters and numbers.";
			$this->error($field, $msg);
			return false;
		}
		return true;
	}

	function required($field)
	{
		if ( !isset($this->input[$field]) OR $this->input[$field] == '' )
		{
			$this->error($field, 'This field is required.');
			return false;
		}
		elseif ( is_array($this->input[$field]) )
		{
			$this->error($field, 'This is an array and won\'t be passed.');
			return false;
		}
		return true;
	}

/************************************/
/* Alias Functions                  */
/************************************/

	function fullname($field, $req=true)
	{
		if ( $req == true AND !$this->required($field) )
			return false;

		return $this->alpha_dotdash($field);
	}

	function address($field, $req=true)
	{
		if ( $req == true AND !$this->required($field) )
			return false;

		return $this->alpha_special($field);
	}

	function telephone($field, $req=true)
	{
		if ( $req == true AND !$this->required($field) )
			return false;

		if ( $this->numeric($field) AND $this->min_length(11, $field) AND $this->max_length(14, $field) )
		{
			return true;
		}

		return false;
	}

	function mobile($field, $req=true)
	{
		if ( $req == true AND !$this->required($field) )
			return false;

		return $this->telephone($field);
	}

	function postcode($field, $req=true)
	{
		if ( $req == true AND !$this->required($field) )
			return false;

		if ( !preg_match("/^[a-zA-Z]{1,3}[0-9]{1,3} [0-9]{1}[a-zA-Z]{2}$/i", $this->input[$field]) )
		{
			$msg = "Postcode must follow the format of \"XX1 1XX\".";
			$this->error($field, $msg);
			return false;
		}
		return true;
	}

	function email($field, $req=true, $mx_records=false)
	{
		if ( $req == true AND !$this->required($field) )
			return false;

		// Function from: http://www.ilovejackdaniels.com/php/email-address-validation/
		// Complies with the email address specification guidelines: RFC 2822

		// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $this->input[$field]))
		{
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			$msg = "Your email address is the wrong length.";
			$this->error($field, $msg);
			return false;
		}

		// Split it into sections to make life easier
		$email_array = explode("@", $this->input[$field]);
		$local_array = explode(".", $email_array[0]);

		for ($i = 0; $i < sizeof($local_array); $i++)
		{
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i]))
			{
				$msg = "The first part of your email is malformed.";
				$this->error($field, $msg);
				return false;
			}
		}

		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) // Check if domain is IP. If not, it should be valid domain name
		{
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2)
			{
				$msg = "Your email doesn't have a valid domain.";
				$this->error($field, $msg);
				return false; // Not enough parts to domain
			}

			for ($i = 0; $i < sizeof($domain_array); $i++)
			{
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i]))
				{
					$msg = "Your email doesn't have a valid domain.";
					$this->error($field, $msg);
					return false;
				}
			}
		}

		// Check online to see if this is a real email host!
		if ( $mx_records != false )
		{
			$host = $email_array[1]; //The whooole domain

			getmxrr($host, $mxhosts);
			if ( count($mxhosts) < 1 )
			{
				$msg = "There is no email host associated with your email. This probably means its fake.";
				$this->error($field, $msg);
				return false;
			}
		}

		return true;
	}

/************************************/
/* Helper Functions                 */
/************************************/

	function decode($field)
	{
		return html_entity_decode($this->input[$field]);
	}

	function label($text, $id)
	{
		return "<label for=\"$id\">$text</label>";
	}

	function check($field, $value, $default=false)
	{
		if ( $default == true AND empty($this->input[$field]) )
			return 'checked="checked"';

		return $this->input[$field] == $value ? 'checked="checked"' : '';
	}

	function selected($field, $value, $default=false)
	{
		if ( $default == true AND empty($this->input[$field]) )
			return 'selected="selected"';

		return $this->input[$field] == $value ? 'selected="selected"' : '';
	}
}

