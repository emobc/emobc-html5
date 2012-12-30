
sas_tmstp=Math.round(Math.random()*10000000000);sas_masterflag=1;

function sasmobile(sas_pageid,sas_formatid,sas_target) {

 if (sas_masterflag==1) {sas_masterflag=0;sas_master='M';} else {sas_master='S';};

 document.write('<scr'+'ipt src="http://www4.smartadserver.com/call2/pubmj/'+sas_pageid+'/'+sas_formatid+'/'+sas_master+'/'+sas_tmstp+'/'+escape(sas_target)+'?"></scr'+'ipt>');

}function sascc(sas_imageid,sas_pageid) {

img=new Image();

img.src='http://www4.smartadserver.com/h/mcp?imgid='+sas_imageid+'&pgid='+sas_pageid+'&uid=[uid]&tmstp='+sas_tmstp+'&tgt=[targeting]';

}