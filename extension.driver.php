<?php

	Class Extension_pingomatic extends Extension{
	    
		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/publish/',
					'delegate' => 'EntryPreDelete',
					'callback' => 'notify'
				),
				array(
					'page' => '/publish/new/',
					'delegate' => 'EntryPostCreate',
					'callback' => 'notify'
				),
				array(
					'page' => '/publish/edit/',
					'delegate' => 'EntryPostEdit',
					'callback' => 'notify'
				)
			);
		}
		
		public function notify($context){
			
			include_once(TOOLKIT . '/class.gateway.php');
           	$ch = new Gateway;
            
            $ch->init();
            $ch->setopt('URL', 'http://rpc.pingomatic.com/');
            $ch->setopt('POST', 1);
            $ch->setopt('CONTENTTYPE', 'text/xml');
            $ch->setopt('HTTPVERSION', CURL_HTTP_VERSION_1_0);
            
            $xml = new XMLElement('methodCall');
            $xml->appendChild(new XMLElement('methodName', 'weblogUpdates.ping'));
            
            $params = new XMLElement('params');
            
            $param = new XMLElement('param');       
            $param->appendChild(new XMLElement('value', Symphony::Configuration()->get('sitename', 'general')));
            $params->appendChild($param);            

            $param = new XMLElement('param');
            $param->appendChild(new XMLElement('value', URL));
            $params->appendChild($param);    
            
            $xml->appendChild($params);        
			
            $ch->setopt('POSTFIELDS', $xml->generate(true, 0));

            $ch->exec(GATEWAY_FORCE_SOCKET);
         
		}
		
	}