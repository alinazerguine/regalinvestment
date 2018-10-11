<?php
namespace Application\Model;

use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Mime;

use Zend\Db\Sql\Sql,
    Zend\Db\Sql\Where;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Session\Container;

use Application\Model\AbstractModel;
use Application\Model\Email;

class Email extends AbstractModel
{
	private $AbstractModel;
	public $table = 'email_templates';	
	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
		$this->initialize();
	}
	
	public function sendEmail($type = false, $data = false){
		
		/* Site Configuration */
		$site_config = $this->getConfig();
		global $currencyBox;global $methodBox;
		global $currencyCode;
		/* Site Administrator Information */
		$admin_info = $this->Super_Get('users','user_type="admin"','fetch');
		
		if(!$type){
			return  (object) array("error"=>true , "success"=>false , "message"=>" Please Define Type of Email");
		}
		
		/* Receipient Information Array */
		$recipient_info = array(
			"sender" => array(
				"name"	=>	$site_config['site_title'],
				"email"	=>	$admin_info['user_email']
			),
			"receiver" => array(
				"name"	=>"",
				"email"	=>""
			),
			"subject" => "" ,
			"replace" => array(
				"{website_logo}"	=> '<img src="'.HTTP_IMG_PATH.'/logo.png" style="width:167px; display:block; margin:auto;" width="167" alt="'.$site_config['site_name'].'">',
				"{website_link}"	=> APPLICATION_URL,
				"{site_title}"		=> $site_config['site_title'],
				
				"{social_facebook}"			=> $site_config['social_link_facebook'],
				"{social_twitter}"			=> $site_config['social_link_twitter'],
				"{social_instagram}"		=> $site_config['instagram_link'],
				"{social_google_plus}"		=> $site_config['social_link_gplus'],
				"{social_linkedin}"			=> $site_config['social_linkedin_link'],
				"{social_pinterest}"		=> $site_config['social_pinterest_link'],
				"{contact_mail_address}"	=> $site_config['contact_mail_address'],
				
				"{date}"	=>	date("l d F"),
				
				"{link_privacy}"	=> APPLICATION_URL."/privacy-policy",
				"{year}"			=> date("Y"),
				"{link_about}"		=> APPLICATION_URL."/about",
				"{link_contact}"	=> APPLICATION_URL."/contact",
			),
			"message" => ""
		);
		 $website=APPLICATION_URL;
		$logoLink=HTTP_IMG_PATH.'/logo.png?d='.time();
		 $site_title=$site_config['site_title'];
		 $year=date('Y'); 
		 $SenderName =$site_config['site_title']; $SenderEmail =$site_config['contact_mail_address'];$ReceiverName = ""; $ReceiverEmail = "";
		switch($type){
				
				case 'admin_job_apply':		
				$ReceiverEmail =  trim($site_config['contact_mail_address']);
				$ReceiverName = $admin_info['user_first_name'].' '.$admin_info['user_last_name'];
				
				//$ReceiverEmail =  trim($data['job_app_email']);
				//$ReceiverName = $data['job_app_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$ReceiverName;
				$recipient_info["receiver"]['email']= $ReceiverEmail;
				
				$recipient_info["replace"]["{apply_user_name}"]	= $data['job_app_name'];
			
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				case 'user_prosperity_updated':		
				$ReceiverEmail =  trim($data['user_email']);
				$ReceiverName = $data['user_first_name'].' '.$data['user_last_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$data['user_name'];
				$recipient_info["receiver"]['email']= $data['user_email'];
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				
				
				case 'user_balance_updated':		
				$ReceiverEmail =  trim($data['user_email']);
				$ReceiverName = $data['user_first_name'].' '.$data['user_last_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$data['user_name'];
				$recipient_info["receiver"]['email']= $data['user_email'];
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				$recipient_info["replace"]["{plan_name}"]	= $data['plan_name'];
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				
				case 'withdraw_approve_request':		
				$ReceiverEmail =  trim($data['user_email']);
				$ReceiverName = $data['user_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$data['user_name'];
				$recipient_info["receiver"]['email']= $data['user_email'];
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				
				case 'deposit_approve_request':		
				$ReceiverEmail =  trim($data['user_email']);
				$ReceiverName = $data['user_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$data['user_name'];
				$recipient_info["receiver"]['email']= $data['user_email'];
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				case 'user_resource_approve':		
				$ReceiverEmail =  trim($data['user_email']);
				$ReceiverName = $data['user_first_name'].' '.$data['user_last_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$data['user_name'];
				$recipient_info["receiver"]['email']= $data['user_email'];
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				$recipient_info["replace"]["{resource_name}"]	= $data['resource_name'];
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				
				case 'user_resource_reject':		
				$ReceiverEmail =  trim($data['user_email']);
				$ReceiverName = $data['user_first_name'].' '.$data['user_last_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$data['user_name'];
				$recipient_info["receiver"]['email']= $data['user_email'];
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				$recipient_info["replace"]["{resource_name}"]	= $data['resource_name'];
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				
				case 'user_plan_approve':		
				$ReceiverEmail =  trim($data['user_email']);
				$ReceiverName = $data['user_first_name'].' '.$data['user_last_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$data['user_name'];
				$recipient_info["receiver"]['email']= $data['user_email'];
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				$recipient_info["replace"]["{plan_name}"]	= $data['plan_name'];
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				
				case 'user_plan_reject':		
				$ReceiverEmail =  trim($data['user_email']);
				$ReceiverName = $data['user_first_name'].' '.$data['user_last_name'];
				
				$template = $this->getTemplate($type);
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	=$data['user_name'];
				$recipient_info["receiver"]['email']= $data['user_email'];
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				$recipient_info["replace"]["{plan_name}"]	= $data['plan_name'];
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				case 'admin_withdraw_amount':
				
	
				$ReceiverEmail =  trim($site_config['contact_mail_address']);
				$ReceiverName = $admin_info['user_first_name'].' '.$admin_info['user_last_name'];
				$template = $this->getTemplate('admin_withdraw_amount');
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				$recipient_info["receiver"]['name']	= $ReceiverName;
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["receiver"]['email']= $ReceiverEmail;
				
				$recipient_info["replace"]['{withdraw_currency}']= $currencyCode[$data["withdraw_currency"]];
				$recipient_info["replace"]['{withdraw_amount}']= $data['withdraw_amount'];
				$recipient_info["replace"]['{withdraw_method}']= $methodBox[$data["withdraw_method"]];
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break;
				
				
				case 'admin_deposit_amount':
				
				$ReceiverEmail =  trim($site_config['contact_mail_address']);
				$ReceiverName = $admin_info['user_first_name'].' '.$admin_info['user_last_name'];
				$template = $this->getTemplate('admin_deposit_amount');
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				$recipient_info["receiver"]['name']	= $ReceiverName;
				$recipient_info["replace"]['{deposit_currency}']=$currencyCode[$data["deposit_currency"]];
				$recipient_info["replace"]['{deposit_amount}']= $data['deposit_amount'];
				$recipient_info["replace"]['{deposit_method}']= $methodBox[$data["deposit_method"]];
				
				$recipient_info["receiver"]['email']= $ReceiverEmail;
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break;
			
				case 'admin_plan':		
				$ReceiverEmail =  trim($site_config['contact_mail_address']);
				$ReceiverName = $admin_info['user_first_name'].' '.$admin_info['user_last_name'];
				
				$template = $this->getTemplate('admin_plan');
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	= $ReceiverName;
				$recipient_info["receiver"]['email']= $ReceiverEmail;
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				break ;
				case 'admin_resource':		
				$ReceiverEmail =  trim($site_config['contact_mail_address']);
				$ReceiverName = $admin_info['user_first_name'].' '.$admin_info['user_last_name'];
				
				$template = $this->getTemplate('admin_resource');
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	= $ReceiverName;
				$recipient_info["receiver"]['email']= $ReceiverEmail;
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				break ;
				case 'admin_consulting_request':		
				$ReceiverEmail =  trim($site_config['contact_mail_address']);
				$ReceiverName = $admin_info['user_first_name'].' '.$admin_info['user_last_name'];
				
				$template = $this->getTemplate('admin_consulting_request');
				$subject = $site_config['site_title']." - ".$template['emailtemp_subject'];
				
				$recipient_info["receiver"]['name']	= $ReceiverName;
				$recipient_info["receiver"]['email']= $ReceiverEmail;
				
				$recipient_info["replace"]["{user_name}"]	= $data['user_name'];
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["replace"]["{site_link}"] =APPLICATION_URL;
				$recipient_info["replace"]["{website_link}"] =APPLICATION_URL;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				break ;
			
			case 'admin_copy_for_contact_us' : /* admin contact us email copy {	*/
				/* Mail To Admin  */
				$template = $this->getTemplate('contact_us_admin');
				
				$recipient_info["receiver"]['name'] = $admin_info['user_first_name'].' '.$admin_info['user_last_name'];
				$recipient_info["receiver"]['email'] = trim($site_config['contact_mail_address']);
				
				$recipient_info["sender"]['name'] = htmlentities($data['user_name'], ENT_COMPAT, 'UTF-8');
				$recipient_info["sender"]['email'] = $data['user_email'];
				
				$recipient_info["replace"]["{guest_name}"]	= htmlentities($data['user_name'], ENT_COMPAT, 'UTF-8');
				$recipient_info["replace"]["{guest_email}"]	= $data['user_email'];
				$recipient_info["replace"]["{guest_phone}"]	= $data['user_phone'];
				$recipient_info["replace"]["{guest_message}"]	= $data['user_message'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["message"]= str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
			break ;/* } end admin contact us email copy  */
			
			case 'registration_email':/* begin : Registration Email { */
				$template = $this->getTemplate('registration_email');
				
				$ReceiverEmail	= $data['user_email'];
				$ReceiverName	= $data['user_name'];
				
				$recipient_info["receiver"]['name'] = $data['user_name'];
				$recipient_info["receiver"]['email'] = $data['user_email'];
				
				$recipient_info["replace"]["{verification_link}"] = APPLICATION_URL."/activate/".$data['key'];
				$recipient_info["replace"]["{resend_verification_link}"] = APPLICATION_URL."/sendverification/".$data['user_id'];
				
				$recipient_info["replace"]["{member_name}"] = htmlentities($data['user_name'], ENT_COMPAT, 'UTF-8');
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				//prd($recipient_info);
			break ;/* }  end : Registration Email */
			
			case 'registration_by_admin_email':/* begin : Registration By Admin Email { */
				$template = $this->getTemplate('registration_by_admin_email');
				
				$ReceiverEmail	= $data['user_email'];
				$ReceiverName	= $data['user_name'];
				
				$recipient_info["receiver"]['name'] = $data['user_name'];
				$recipient_info["receiver"]['email'] = $data['user_email'];
				
				$recipient_info["replace"]["{user_password}"] = $data['password'];
				$recipient_info["replace"]["{user_email}"] = $data['user_email'];
				$recipient_info["replace"]["{user_name}"] = htmlentities($data['user_name'], ENT_COMPAT, 'UTF-8');
				
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
			break ;/* }  end : Registration by admin email */
			
			case 'reset_password' : /* Reset password email {	*/
				$template = $this->getTemplate('reset_password');
				
				if($data['user_type']=="admin"){
					$resetLink	= SITE_HTTP_URL."/".BACKEND."/reset-password/".$data['pass_resetkey'];
					$username	= "Administrator";
				}
				else{
					$resetLink = SITE_HTTP_URL."/user-resetpassword/".$data['pass_resetkey'];
					$username	= $data['user_fullname'];
				}
				
				$recipient_info["receiver"]['name']	= $username;
				$recipient_info["receiver"]['email']= trim($data['user_email']);
				
				$recipient_info["replace"]["{reset_link}"]	= $resetLink;
				$recipient_info["replace"]["{member_name}"]	= $username;
				$recipient_info["subject"] = $template['emailtemp_subject'];
				$recipient_info["replace"]["{SITE_NAME}"] =$site_title;
				$recipient_info["message"] = str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				//prd($recipient_info);
				//$MESSAGE= str_ireplace(array_keys($recipient_info["replace"]),array_values($recipient_info["replace"]),$template['emailtemp_content']);
				
				
			
			break ;/* } end reset password email  */
			
			
			
			
			
		}
		
		$filename = $attachment='';
		
		$this->sendMail($recipient_info['message'],$template['emailtemp_subject'],$recipient_info["sender"]['email'],$recipient_info["receiver"]['email'],$recipient_info["sender"]['name'],$recipient_info["receiver"]['name'],$attachment,$filename);
		
		
		return (object)array("error"=>false , "success"=>true , "message"=>" Mail Successfully Sent");
	}
	
	function sendMail($htmlBody,$subject,$from, $to,$FromName,$ToName,$attachment=false,$filename=false)
	{
		
		$htmlPart = new MimePart($htmlBody);
		
		$htmlPart->type = "text/html";
	
		$textPart = new MimePart($htmlBody);
		$textPart->type = "text/plain";
	
	    $attachmentfile='';
		$body = new MimeMessage();
			
		if($attachment!=''){
			$content  = new MimeMessage();
			$content->setParts(array($textPart, $htmlPart));
       		$contentPart = new MimePart($content->generateMessage());
			
			$attachmentfile = new MimePart(file_get_contents($attachment));
			$attachmentfile->type = 'application/pdf';
			$attachmentfile->filename = $filename;
			$attachmentfile->encoding    = Mime::ENCODING_BASE64;
			$attachmentfile->disposition = Mime::DISPOSITION_ATTACHMENT;
			$body->setParts(array($htmlPart,$attachmentfile));
		}
		else{
			$body->setParts(array($textPart, $htmlPart));
		}
	
		$message = new MailMessage();
		$message->setFrom($from,$FromName);
		$message->addTo($to,$FromName);
		$message->setSubject($subject);
		
		$message->setEncoding("UTF-8");
		$message->setBody($body);	
		
		$message->getHeaders()->get('content-type')->setType('multipart/alternative');	
		//prd(gcm($message));
		
		$transport = new SendMailTransport();
		
		/* FOR Sending emails via SMTP */
		$transport = new SmtpTransport();
		$options   = new SmtpOptions(
			array(
				'name'              => 'localhost.localdomain',
				'host'              => 'techdemolink.co.in',
				'connection_class'  => 'login',
				'connection_config' => array(
					'username' =>  'techdemo@techdemolink.co.in',
				'password' => 'test@1234',
				),
			)
		);
		
		$transport->setOptions($options);
		try{
		$reply=$transport->send($message);	
		
		}catch(Exception $ex){
			
		}
	}
	
	public function getTemplate($type)
    {
		try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'email_templates' => $this->table
            ));
            
            if (count($type) > 0) {
               
				$select->where(array('emailtemp_key' =>$type));
            }
          	
            $select->columns(array('emailtemp_title','emailtemp_subject','emailtemp_content'));            
         	
            $statement = $sql->prepareStatementForSqlObject($select);
            $email_data = $this->resultSetPrototype->initialize($statement->execute())->toArray();
		
            return $email_data[0];
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }
	
	public function getConfig()
	{
		$config_qry = " SELECT * FROM  config";      
		$results = $this->adapter->query($config_qry)->execute();
		$configuration=$results->getResource()->fetchAll();		
		
		foreach($configuration as $key=>$config){
			$config_data[$config['config_key']]= $config['config_value'] ;
			$config_groups[$config['config_group']][$config['config_key']]=$config['config_value'];	
		}
		
		return $config_data;
	}
}