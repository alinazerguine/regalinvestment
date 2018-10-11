<?php
namespace Application\Model;


class Paypalrecurring
{
	public function init()
	{
		if(session_id()=="") 
		session_start();	
		$site_config = Zend_Registry::get("site_config");
		$this->paypal_user_name = $site_config['paypal_username']; 
		$this->paypal_api_password = $site_config['paypal_password']; 
		$this->paypal_api_signature = $site_config['paypal_signature']; 
	}
	
	public function __construct($config_data)
    {
		$this->paypal_user_name = $config_data['paypal_username']; 
		$this->paypal_api_password = $config_data['paypal_password']; 
		$this->paypal_api_signature = $config_data['paypal_signature'];
		$this->paypal_payment_mode = $config_data['paypal_payment_mode'];
    }
		
	public function cancelSubscription($profile_id)
	{	
		//Cancel //Suspend //Reactivate
		$profileID=urlencode($profile_id);
		$action = urlencode('Cancel') ;
		
		$nvpStr="&PROFILEID=$profileID&ACTION=$action";
		$resArray=$this->hash_call("ManageRecurringPaymentsProfileStatus",$nvpStr);
		$ack = strtoupper($resArray["ACK"]);		
		
		if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			$retunsarrary['success']=1;	
		}
		else
		{
			$retunsarrary['success']=$resArray['L_LONGMESSAGE0'];				
		}
		return $retunsarrary;
	}
		
	public function CreateRecurringPaymentsProfile($posted_data)
	{
			
			@$profileID=urlencode($posted_data['profileID']);
			
			//$token= urlencode($SubData['refId']);
			//$customerID =urlencode($SubData['user_id']);
			//prn($posted_data);
			$firstName =urlencode($posted_data['card_name']);
			$lastName ='';
			$dataMonth=explode('/',$posted_data['card_expiry']);
			$padDateMonth =urlencode($dataMonth[0]);	
			//$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);		
			$expDateYear =urlencode($dataMonth[1]);
			$creditCardType =urlencode($posted_data['card_type']);
			$creditCardNumber = urlencode($posted_data['card_number']);
			$cvvNumber = urlencode($posted_data['card_cvv']);		
			//$email=$posted_data['user_email'];
			//$email= urlencode('johndoe@gmail.com');
			
			$amount = urlencode($posted_data['amount']);
			$currencyCode=PRICE_SYMBOL;		
			$profileDesc = urlencode('Subscription');
			$billingPeriod = urlencode($posted_data['billingPeriod']);
			$billingFrequency = urlencode("1");	//Monthly Recurring
		
			$profileStartDate = date('Y-m-d H:i:s'); 
			$notifyurl='';
			//$notifyurl=urlencode(APPLICATION_URL.'/index/handleresposne');
			
			$nvpstr="";
			
			//$nvpstr="&TOKEN=".$token;
			
			/*---------------- In case of update profile --------------------*/	
			if($profileID!='')
			{
				$nvpstr="&PROFILEID=".$profileID;	
			}
			/*---------------- In case of update profile --------------------*/		
			
			/*---------------- User Information --------------------*/	
			
			$nvpstr.="&FIRSTNAME=".$firstName;
			$nvpstr.="&LASTNAME=".$lastName;
			$nvpstr.="&EMAIL=".$posted_data['card_email'];
			$nvpstr.="&STREET=DFGVD";
			$nvpstr.="&CITY=DFG";
			$nvpstr.="&STATE=DFGDF";
			$nvpstr.="&ZIP=123456";
			
			
			/*---------------- Billing Details --------------------*/	
			$nvpstr.="&DESC=".$profileDesc;
			$nvpstr.="&BILLINGPERIOD=".$billingPeriod; // or "Day","Week","SemiMonth","Year"
			$nvpstr.="&BILLINGFREQUENCY=".$billingFrequency; // combination of this and billingPeriod must be at most a year
			$nvpstr.="&AMT=".$amount;
			$nvpstr.="&CURRENCYCODE=".PRICE_SYMBOL_VALUE;
			$nvpstr.="&COUNTRYCODE=US";
			$nvpstr.="&TOTALBILLINGCYCLES=0";
			$nvpstr.="&IPADDRESS=".$_SERVER['REMOTE_ADDR'];	
			/*---------------- Billing Details --------------------*/
			
			/*---------------- Credit Card --------------------*/	
			//$nvpstr.="&CREDITCARDTYPE=".$creditCardType;
			$nvpstr.="&ACCT=".$creditCardNumber;
			$nvpstr.="&CVV2=".$cvvNumber;	
			$nvpstr.="&EXPDATE=".$padDateMonth.$expDateYear;
			
			if($profileID == '')
			{
				$nvpstr.="&PROFILESTARTDATE=".$profileStartDate;	
			}
			//$nvpstr.="&PROFILESTARTDATE=".$profileEndDate;	
			/*---------------- Billing Details --------------------*/
			
			//$nvpstr.="&NOTIFYURL=".$notifyurl;
			if($profileID!='')
			{
				$resArray=$this->hash_call("UpdateRecurringPaymentsProfile",$nvpstr);
			}
			else
			{
				$resArray=$this->hash_call("CreateRecurringPaymentsProfile",$nvpstr);
			}
			
			$ack = strtoupper($resArray["ACK"]);
			if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")
			{
				return (object)array("success"=>true,"error"=>false,"message"=>"Payment Successfull","profile_id"=>$resArray['PROFILEID'],'txn_id'=>$resArray['CORRELATIONID']) ;
			
			}
			else
			{	
				$retunsarrary['success']=0;	
				$retunsarrary['error_code']=$resArray['L_ERRORCODE0'];	
				$retunsarrary['error']=$resArray['L_LONGMESSAGE0'];	
				return (object)array("success"=>false,"error"=>true,"message"=>$resArray['L_LONGMESSAGE0'],
							"exception"=>true) ;		
			}

		
		
		return $retunsarrary;
	}
	
	public function UpdateRecurringPaymentsProfile($SubData)
	{
		
		
			@$profileID=urlencode($SubData['profileID']);
			
			$amount = urlencode($SubData['user_amount']);
			$currencyCode="USD";		
			$profileDesc = urlencode('Subscription');
			
			$billingPeriod = urlencode($SubData['billingPeriod']);
			$billingFrequency = urlencode('1');	//Monthly Recurring
			$profileStartDate = urlencode(date('Y-m-d H:i:s')); 
			
			
			/*$expDateMonth =urlencode($SubData['user_expire_month']);	
			$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);		
			$expDateYear =urlencode($SubData['user_expire_year']);
			
			//$creditCardType =urlencode($SubData['user_paymentmethod']);
			$creditCardNumber = urlencode($SubData['user_cardno']);
			$cvvNumber = urlencode($SubData['user_security_code']);	*/
			
			
			//$notifyurl=urlencode(APPLICATION_URL.'/index/handleresposne');
			
			$nvpstr="";
			$notifyurl='';
			$nvpstr="&PROFILEID=".$profileID;	
			
			
			/*---------------- Billing Details --------------------*/	
			$nvpstr.="&AMT=".$amount;
			$nvpstr.="&DESC=".$profileDesc;
			$nvpstr.="&BILLINGPERIOD=".$billingPeriod;
			$nvpstr.="&BILLINGFREQUENCY=1".$billingFrequency;
		/*	$nvpstr.="&AMT=".$amount;
			$nvpstr.="&CURRENCYCODE=USD";
			$nvpstr.="&COUNTRYCODE=US";
			$nvpstr.="&TOTALBILLINGCYCLES=0";
			$nvpstr.="&IPADDRESS=".$_SERVER['REMOTE_ADDR'];	*/
			/*---------------- Billing Details --------------------*/
			
			/*---------------- Credit Card --------------------*/	
			//$nvpstr.="&CREDITCARDTYPE=".$creditCardType;
			/*$nvpstr.="&ACCT=".$creditCardNumber;
			$nvpstr.="&CVV2=".$cvvNumber;	
			$nvpstr.="&EXPDATE=".$padDateMonth.$expDateYear;
			$nvpstr.="&CURRENCYCODE=USD";	*/
			//$nvpstr.="&PROFILESTARTDATE=".$profileStartDate;	
			//$nvpstr.="&PROFILESTARTDATE=".$profileEndDate;	
			/*---------------- Billing Details --------------------*/
			
			$nvpstr.="&NOTIFYURL=".$notifyurl;
			$resArray=$this->hash_call("UpdateRecurringPaymentsProfile",$nvpstr);
			
			
			$ack = strtoupper($resArray["ACK"]);
			if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")
			{
				return (object)array("success"=>true,"error"=>false,"message"=>"Record Successfully Inserted","profile_id"=>$resArray['PROFILEID'],'txn_id'=>$resArray['CORRELATIONID']) ;
			
			}
			else
			{	
				$retunsarrary['success']=0;	
				$retunsarrary['error_code']=$resArray['L_ERRORCODE0'];	
				$retunsarrary['error']=$resArray['L_LONGMESSAGE0'];	
				return (object)array("success"=>false,"error"=>true,"message"=>$resArray['L_LONGMESSAGE0'],
							"exception"=>true) ;		
			}

		
		
		return $retunsarrary;
	}
	
	
	public function GetRecurringPaymentsProfileDetails($profile_id)
	{
		if(!empty($profile_id))
		{
			$proid = $profile_id;
		}
		else
		$proid = '';
		$nvpstr="";
		
		$nvpstr="&PROFILEID=".urlencode($proid);
		$resArray=$this->hash_call("GetRecurringPaymentsProfileDetails",$nvpstr);
		
		return $resArray;
	}
	
	public function directPayment($posted_data)
	{
		$firstName =urlencode($posted_data['first_name']);
		$lastName =urlencode($posted_data['last_name']);
		$expDateMonth =urlencode($posted_data['expire_month']);	
		$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);		
		$expDateYear =urlencode($posted_data['expire_year']);
		$creditCardType =urlencode($posted_data['card_type']);
		$creditCardNumber = urlencode($posted_data['card_number']);
		$cvvNumber = urlencode($posted_data['cvv']);		
		
		$amount = $posted_data['amount'];
		$currencyCode="USD";	
		$nvpRecurring = '';
		$profileStartDate = urlencode(date('Y-m-d h:i:s'));	
		$notifyurl='';
		$methodToCall = 'doDirectPayment';
		$paymentAction = urlencode("Sale");
		$nvpstr="";
		
		$nvpstr = '&PAYMENTACTION='.$paymentAction.'&AMT='.$amount.'&CREDITCARDTYPE='.$creditCardType.'&ACCT='.$creditCardNumber.'&EXPDATE='.$padDateMonth.$expDateYear.'&CVV2='.$cvvNumber.'&FIRSTNAME='.$firstName.'&LASTNAME='.$lastName.'&COUNTRYCODE=US&CURRENCYCODE='.$currencyCode.$nvpRecurring;
		//echo $nvpstr;die;
		$resArray = $this->hash_call($methodToCall,$nvpstr);
		$ack = strtoupper($resArray["ACK"]);
		
		if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			return (object)array("success"=>true,"error"=>false,"message"=>"Success","transaction_id"=>$resArray['TRANSACTIONID'],'txn_id'=>$resArray['CORRELATIONID']);
		}
		else
		{	
			$retunsarrary['success']=0;	
			$retunsarrary['error_code']=$resArray['L_ERRORCODE0'];	
			$retunsarrary['error']=$resArray['L_LONGMESSAGE0'];	
			return (object)array("success"=>false,"error"=>true,"message"=>$resArray['L_LONGMESSAGE0'],"exception"=>true) ;		
		}

		return $retunsarrary;
	}
	
	function hash_call($methodName,$nvpStr)
	{		
		$PROXY_HOST = '127.0.0.1';
		$PROXY_PORT = '808';
		$SandboxFlag = $this->paypal_payment_mode;
		$USE_PROXY = false;
		
		$API_UserName=$this->paypal_user_name;
		$API_Password=$this->paypal_api_password;
		$API_Signature=$this->paypal_api_signature;
		
		$version='92.0';
		
		// BN Code 	is only applicable for partners
		//$sBNCode = "PP-ECWizard";
		
		if ($SandboxFlag == 0) 
		{
		$API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
		//$PAYPAL_URL = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=";
		}
		else
		{
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		//$PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
		}
		
		//setting the curl parameters.
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSLVERSION , 1);
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		//curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, "rsa_rc4_128_sha");
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_CAPATH, PATH_TO_CERT_DIR);
		//curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if($USE_PROXY) curl_setopt ($ch, CURLOPT_PROXY, $PROXY_HOST. ":" . $PROXY_PORT); 

		//NVPRequest for submitting to server
		/*	$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($version) . "&PWD=" . urlencode($API_Password) . "&USER=" . urlencode($API_UserName) . "&SIGNATURE=" . urlencode($API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($sBNCode);*/
		
			$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($version) . "&PWD=" . urlencode($API_Password) . "&USER=" . urlencode($API_UserName) . "&SIGNATURE=" . urlencode($API_Signature).$nvpStr;
			//echo $nvpreq;
		//var_dump($nvpreq);
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$response = curl_exec($ch);
		
		//convrting NVPResponse to an Associative Array
		$nvpResArray=$this->deformatNVP($response);
		$nvpReqArray=$this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;

		if (curl_errno($ch)) 
		{
			echo "<br>";
			echo curl_error($ch);die;
			// moving to display page to display curl errors
			  $_SESSION['curl_error_no']=curl_errno($ch) ;
			  $_SESSION['curl_error_msg']=curl_error($ch);

			  //Execute the Error handling module to display errors. 
		} 
		else 
		{
			 //closing the curl
		  	curl_close($ch);
		}

		return $nvpResArray;
	}
	
	public function RedirectToPayPal($token)
	{
		global $PAYPAL_URL;
		// Redirect to paypal.com here
		$payPalURL = $PAYPAL_URL . $token;
		header("Location: ".$payPalURL);
	}
	
	public function deformatNVP($nvpstr)
	{
		$intial=0;
	 	$nvpArray = array();
		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	    }
		return $nvpArray;
	}
	
	
	
}