<?php
class CCValidator extends CustomValidator
{
    function DoValidate(&$formars,&$error_hash)
    {
         // Get the first digit
                    $firstnumber = substr($formars['card_num'], 0, 1);
                    // Make sure it is the correct amount of digits. Account for dashes being present.
                    switch ($firstnumber)
                    {
                        case 3:
                            if (!preg_match('/^3\d{3}[ \-]?\d{6}[ \-]?\d{5}$/', $formars['card_num']))
                            {
                                $error_hash['card_num'] = "Please enter a valid credit card number";
                                return FALSE;
                                
                            }
                            break;
                        case 4:
                            if (!preg_match('/^4\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $formars['card_num']))
                            {
                                $error_hash['card_num'] = "Please enter a valid credit card number";
                                return FALSE;
                                 
                            }
                            break;
                        case 5:
                            if (!preg_match('/^5\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $formars['card_num']))
                            {
                                $error_hash['card_num'] = "Please enter a valid credit card number";
                                return FALSE;
          
                            }
                            break;
                        case 6:
                            if (!preg_match('/^6011[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $formars['card_num']))
                            {
                                $error_hash['card_num'] = "Please enter a valid credit card number";
                                return FALSE;

                            }
                            break;
                        default:
                            $error_hash['card_num'] = "Please enter a valid credit card number";
                            return FALSE;

                    }
                    // Here's where we use the Luhn Algorithm
                    $formars['card_num'] = str_replace('-', '', $formars['card_num']);
                    $map = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
                                0, 2, 4, 6, 8, 1, 3, 5, 7, 9);
                    $sum = 0;
                    $last = strlen($formars['card_num']) - 1;
                    for ($i = 0; $i <= $last; $i++)
                    {
                        $sum += $map[$formars['card_num'][$last - $i] + ($i & 1) * 10];
                    }
                    if ($sum % 10 != 0)
                    {
                        $error_hash['card_num'] = "Please enter a valid credit card number";
                        return false;
               
                    }
                    // If we made it this far the credit card number is in a valid format
                    return TRUE;
    }
}


class expirationYear extends CustomValidator
{
    function DoValidate(&$formars,&$error_hash)
    {
		if (!preg_match('/^\d{4}$/', $formars['exp_year']))
							{
								$error_hash['card_num'] = "Please enter a valid expiration year.";
								return false;
							}
							else if ($formars['exp_year'] < date("Y"))
							{
								$error_hash['card_num'] = "Please enter a valid expiration year.";
								return false;
							}
						 
							return true;
	}
}

class phoneLength extends CustomValidator
{
    function DoValidate(&$formars,&$error_hash)
    {
            $cleanPhone = preg_replace("/\D/","",$formars['phone']); 
            if (strlen($cleanPhone) != 10) {
			    $error_hash['card_num'] = "Please enter a valid phone number.";
                return false;
            } else {
                return true;
            }
            
            }
}

class dollarAmount extends CustomValidator
{
       function DoValidate(&$formars,&$error_hash)
       {
           
           
            if(!preg_match('/^[0-9]+(?:\.[0-9]{1,2})?$/im', $formars['amount'])) {
                $error_hash['amount'] = "Please enter a valid dollar amount";
                 return false;
            } else {
                return true;
            }
       }
}

class tooMuch extends CustomValidator
{
         function DoValidate(&$formars,&$error_hash)
       {
             if($formars['amount'] > $formars['currAmount']) {
                 $error_hash['amount'] = "You cannot subtract more credits than the agency has.";
                 return false;
             } else {
                 return true;
             }
         }
}

class productModExists extends CustomValidator
{
    
        function DoValidate(&$formars,&$error_hash)
        {
            $folderName = $formars['directory_name'];
            $query = "SELECT * FROM product_mod WHERE folder_name = '$folderName'";
            $result = mysql_query($query);
            if(mysql_num_rows($result) > 0) {
                $error_hash['amount'] = "There is already a product module with this directory name.";
                return false;
            } else {
                return true;
            }
        }
}

class fullfillModExists extends CustomValidator
{
    
        function DoValidate(&$formars,&$error_hash)
        {
            $folderName = $formars['n_fullfillment_mod'];
            $query = "SELECT * FROM product_mod WHERE folder_name = '$folderName'";
            $result = mysql_query($query);
            if(mysql_num_rows($result) > 0) {
                $error_hash['amount'] = "There is already a product module with this directory name.";
                return false;
            } else {
                return true;
            }
        }
}



?>
