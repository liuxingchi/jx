//¶¯Ì¬´´½¨µÇÂ¼¿ò
 var closeLoginBox=function(){
	 var loginBox=document.getElementById('loginBox');
	 	
		 
	 document.body.removeChild(loginBox);
	 }

var openLoginBox=function(url){


 var loginBox=document.createElement('div');
      loginBox.setAttribute('id','loginBox');
     loginBox.style.width='1000px';
	 loginBox.style.height='600px';
     loginBox.style.border='10px solid #007add';
     loginBox.style.position='absolute';
     loginBox.style.left='34.7%';
	 loginBox.style.top='150px';
	 loginBox.style.background='#ffffff'
     loginBox.style.overflow='hidden';
     loginBox.style.marginLeft='-260px';
	 loginBox.style.boxShadow='0 0 19px #666666'
	 loginBox.style.borderRadius='10px';
	 document.body.appendChild(loginBox);
var loginBoxHandle=document.createElement('h1');
     loginBoxHandle.setAttribute('id','loginBoxHandle');
	 loginBoxHandle.style.fontSize='14px';
	 loginBoxHandle.style.color='#ffffff';
	 loginBoxHandle.style.background='#007add';
	 loginBoxHandle.style.textAlign='left';
	 loginBoxHandle.style.padding='15px 15px';
	 loginBoxHandle.style.margin='0';
	 loginBoxHandle.innerHTML='<span onclick="closeLoginBox()" title="close" style="position:absolute; cursor:pointer; font-size:14px;right:8px; top:3px">x</span>';
     loginBox.appendChild(loginBoxHandle);
var iframe=document.createElement('iframe');
    iframe.setAttribute('src',url);
	iframe.setAttribute('frameborder','0');
	iframe.setAttribute('scrolling','yes');
	iframe.setAttribute('width',995);
	iframe.setAttribute('height',560);
	loginBox.appendChild(iframe);
	
          target:document.getElementById('loginBox');
         bridge:document.getElementById('loginBoxHandle');
	
}