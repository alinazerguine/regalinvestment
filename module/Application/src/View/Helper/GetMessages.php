<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;

class GetMessages extends AbstractHelper
{
    public function __invoke()
    {
		
	   $Msgsession = new Container(DEFAULT_AUTH_NAMESPACE);	

	   if(isset($Msgsession['infoMsg']) && $Msgsession['infoMsg']!=''){ ?>
	   <script type="text/javascript">
		$(document).ready(function(){
		$.notify({
			icon: 'fa fa-info-circle',
			message: "<?php echo $Msgsession['infoMsg']?>"
		
		},{
			type: 'info',
			timer: 4000
		});
		});
		</script>

	   <?php $Msgsession['infoMsg']=''; }
	   
	   if(isset($Msgsession['successMsg']) && $Msgsession['successMsg']!=''){ ?>
	    <script type="text/javascript">
		$(document).ready(function(){
		$.notify({
			icon: 'fa fa-check-circle',
			message: "<?php echo $Msgsession['successMsg']?>"
		
		},{
			type: 'success',
			timer: 4000
		});
		});
		</script>
	    <?php $Msgsession['successMsg']=''; }
		
	   if(isset($Msgsession['errorMsg']) && $Msgsession['errorMsg']!=''){?>
    	<script type="text/javascript">
		$(document).ready(function(){
		$.notify({
			icon: 'fa fa-info-circle',
			message: "<?php echo $Msgsession['errorMsg']?>"
		
		},{
			type: 'danger',
			timer: 3000
		});
		});
		</script>
	    <?php $Msgsession['errorMsg']=''; }
	   
	   
	   $AdminMsgsession = new Container(ADMIN_AUTH_NAMESPACE);	 
	   if(isset($AdminMsgsession['infoMsg']) && $AdminMsgsession['infoMsg']!=''){ ?>
	   <script type="text/javascript">
		$(document).ready(function(){
		$.notify({
			icon: 'fa fa-info-circle',
			message: "<?php echo $AdminMsgsession['infoMsg']?>"
		
		},{
			type: 'info',
			timer: 4000
		});
		});
		</script>
	   <?  $AdminMsgsession['infoMsg']='';}
	   
	   if(isset($AdminMsgsession['successMsg']) && $AdminMsgsession['successMsg']!=''){ ?>
	    <script type="text/javascript">
		$(document).ready(function(){
		$.notify({
			icon: 'fa fa-check-circle',
			message: "<?php echo $AdminMsgsession['successMsg']?>"
		
		},{
			type: 'success',
			timer: 4000
		});
		});
		</script>
	   <? $AdminMsgsession['successMsg']=''; }
	   
	   if(isset($AdminMsgsession['errorMsg']) && $AdminMsgsession['errorMsg']!=''){ ?>
    	<script type="text/javascript">
		$(document).ready(function(){
		$.notify({
			icon: 'fa fa-info-circle',
			message: "<?php echo $AdminMsgsession['errorMsg']?>"
		
		},{
			type: 'danger',
			timer: 3000
		});
		});
		</script>
	   <?  $AdminMsgsession['errorMsg']='';
	   } 
    }
}
