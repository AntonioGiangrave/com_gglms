var _Debug=false;

var aiccsid;
var aiccurl;
var aiccversion="2.0";

var resultx="";
var error_value="";
var error_text_value="";
var score_value="";
var location_value="";
var status_value="";
var timer_value="00:00:00";
var old_time_value="";
var times="";
var score=0;
var debug=0;
var mastery_Score=""
var statolezione=""
var punteggiolezione=""
var punteggiolezioneMin="0"
var punteggiolezioneMax="0"
var figliScore=""
var eseguireCommit=true
var uscitaregolare=false

var traceAICC=false;
var tracer;
if (traceAICC)
{
	tracer=window.open("about:blank","tracer","top=0,left=0,menubar=no,resizable=yes,scrollbars=yes,width=400,height=300,status=no");
}


function writeDebug(stringa)
{
	tracer.document.writeln("<br>"+unescape(stringa));
}



function inizia()
{
	//alert("tipoRegistrazione-->" + tipoRegistrazione)
	if (tipoRegistrazione=="aicc")
	{
		parseurl()
	}
	else if (tipoRegistrazione=="scorm")
	{
		inizio=API.LMSInitialize("")
		if (inizio=="true")
		{
			start_timer()
			getparam() 
		}
		else
		{
			alert("Errore nella comunicazione con la piattaforma.\n Il corso verrà chiuso.");
			logOut()
		}
	}
	else
	{
		avviaCorso()
	}
}

function parseurl()
{
	var i,s1=new String(parent.parent.window.location),s2,s3;
	i=s1.indexOf("?");
	if(i>-1)
	{
		s1=s1.substring(i+1,s1.length);
		while( s1.length>0)
		{
			i=s1.indexOf("&");
			if(i>-1)
			{
				s2=s1.substring(0,i);
				s1=s1.substring(i+1,s1.length);
			}
			else
			{
				s2=s1;
				s1="";
			}
			i=s2.indexOf("=")
			if(i>-1)
			{
				s3=s2.substring(0,i);
				s3=s3.toLowerCase();
				if(s3=="aicc-sid" || s3=="aicc_sid")
				{
					aiccsid=s2.substring(i+1,s2.length);
					aiccsid=unescape(aiccsid);
				}
				if(s3=="aicc-url" || s3=="aicc_url")
				{
					aiccurl=s2.substring(i+1,s2.length);
					if (aiccurl.substring(0,4).toUpperCase() != "HTTP")
					{
						aiccurl=unescape("http://") + unescape(aiccurl)
					}
					else
					{
						aiccurl=unescape(aiccurl);
					}
				}
			}
		}
	}
	start_timer()
	getparam() 
}

function getparam()
{
	var message,result;
	resultx="";
	result2="";
	result="";
	if (tipoRegistrazione=="aicc")
	{
		message= "command=getparam"+"&version="+aiccversion+"&session_id="+aiccsid+"&aicc_data=";      
		if (traceAICC)
		{
			writeDebug("INVIO COMANDO:<br>"+ message)
		}
		result2=Cmi.get_URL(aiccurl,message);
		if (traceAICC)
		{
			writeDebug("RISPOSTA LMS=<BR>" +escape(result2))
		}
		result=result2.toLowerCase();
		error_value=Cmi.get_error(result);
		if (error_value!="0") 
		{
			alert("Errore nella comunicazione con la piattaforma.\n Il corso verrà chiuso.");
			exitAU()
		}

		punteggiolezione=Cmi.get_score(result);
		if (isNaN(parseInt(punteggiolezione,10)))
		{
			punteggiolezione=""
		}
		location_value=Cmi.get_location(result);
		status_value=Cmi.get_status(result); 
		status_value=status_value.substring(0,1);       
		statolezione=status_value;       
		old_time_value=Cmi.get_time(result);  
		stringaintera=result.toLowerCase();
//---------------------------CORE VENDOR -----------------------------------
			miaStringa=escape(stringaintera)
			pos=miaStringa.indexOf('core_vendor');
			if (pos!=-1)
			{
				nuovaStr=miaStringa.substring(pos);
				pos2=nuovaStr.indexOf("%0D%0A");
				if (pos2!=-1)
				{
					if (nuovaStr.indexOf("propedeutico%3Dtrue")!=-1)
					{
						propedeutico=true
					}
					if (nuovaStr.indexOf("menuespanso%3Dfalse")!=-1)
					{
						menuEspanso=false
					}
				}
			}
//---------------------------CORE VENDOR -----------------------------------				
//-----------------------------MASTERY SCORE---------------------------------------			
			miaStringa=escape(stringaintera)
			pos=miaStringa.indexOf('mastery_score%3D');
			if (pos!=-1)
			{
				mastery_Score=miaStringa.substring(pos,miaStringa.length-1);
				pos2=mastery_Score.indexOf("%0D");
				mastery_Score=mastery_Score.substring(16,pos2);
			}
			else
			{
				mastery_Score=""
			}
//-----------------------------MASTERY SCORE---------------------------------------		
		resultx+="error="+error_value+"\n";
		resultx+="score_value="+score_value+"\n";
		resultx+="location_value="+location_value+"\n";
		resultx+="status_value="+status_value+"\n";
		resultx+="timer_value="+timer_value+"\n";
	}
	else
	{
		figliStudent=eseguiGetValueNoMandatory("cmi.student_data._children")
		figliScore=eseguiGetValue("cmi.core.score._children")
		statolezione=eseguiGetValue("cmi.core.lesson_status")
		if (figliStudent.indexOf("mastery_score")!=-1)
		{
			mastery_Score=eseguiGetValue("cmi.student_data.mastery_score")
		}
		if (figliScore.indexOf("raw")!=-1)
		{	
			punteggiolezione=eseguiGetValue("cmi.core.score.raw")
		}
		launch_data=eseguiGetValue("cmi.launch_data")
		if (launch_data!="" && launch_data.indexOf(";")!=-1)
		{
			appo=launch_data.split(";")
			propedeutico=eval(appo[0].replace("propedeutico=",""))
			menuEspanso=eval(appo[1].replace("menuespanso=",""))
		}
		if (isNaN(parseInt(punteggiolezione,10)))
		{
			punteggiolezione=""
		}
	}
	location_value=location_value.replace(" ","")
	pagina=1;
	if (statolezione.charAt(0)=="n" || statolezione.charAt(0)=="u")
	{
		lezione=1
		avviaCorso()
	}
	else
	{
		if (tipoRegistrazione=="scorm")
		{
			location_value=eseguiGetValue("cmi.core.lesson_location")
		}
		locationlezione=location_value	
		appo=location_value.split("-")
		stringavisti=appo[0]
		segnalibro=appo[1]
		appo2=segnalibro.split(",")
		appo3=stringavisti.split(",")
		lezione=parseInt(appo2[0],10)
		pagina=parseInt(appo2[1],10)
		
		for (i=0; i<appo3.length; i++)
		{
			for (j=0; j<appo3[i].length; j++)
			{
				bit=appo3[i].substr(j,1)
				if (bit=="1")
				{
					macro.lezione[i+1].pagina[j+1].vista=true
				}
			}	
		}
		avviaCorso()
		/*
		if (pagina>1)
		{
			var riprendi=window.confirm("Vuoi continuare dall'ultima pagina visitata?")
			if (riprendi)
			{
				modulo=parseInt(appo2[0],10)
				lezione=parseInt(appo2[1],10)
				argomento=parseInt(appo2[2],10)
				lanciaDaSegnaLibro()
			}
			else
			{
				pagina=0
				aprilezione(modulo, lezione)
			}	
		}
		else
		{
			pagina=0
			aprilezione(modulo, lezione)
		}
		*/
	}
}

function putparam(n)
{
	var message,result;
	if (stringavisti.indexOf("0")!=-1)
	{
		if (tipoRegistrazione=="aicc")
		{
			statolezione="I"
		}
		else
		{
			statolezione="incomplete"
		}
	}
	else
	{
		if (tipoRegistrazione=="aicc")
		{
			statolezione="C"
		}
		else
		{
			statolezione="completed"
		}
	}
	segnalibro=lezione+","+pagina
	locationlezione=stringavisti+"-"+segnalibro;
	if (tipoRegistrazione=="aicc")
	{
		aicc_data_value="\r\nScore="+ punteggiolezione +"\r\nTime="+timer_value + "\r\nLesson_Status="+statolezione+"\r\nLesson_Location="+locationlezione
		message= "command=putparam"+"&version="+aiccversion+"&session_id="+aiccsid+"&aicc_data=[core]"+escape(aicc_data_value);
		if (traceAICC)
		{
			writeDebug("INVIO COMANDO:<br>"+ message)
		}
		result=Cmi.get_URL(aiccurl,message);
		error_value=Cmi.get_error(result);
		if (error_value!="0") 
		{
			alert("Errore nella comunicazione con la piattaforma.\n Il corso verrà chiuso.");
			exitAU()
		}
		if (traceAICC)
		{
			writeDebug("RISPOSTA LMS=<BR>" + result)
		}
		reset_timer()
	}
	else
	{
		invioStatus=eseguiSetValue("cmi.core.lesson_status",statolezione)
		//invioScore=eseguiSetValue("cmi.core.score.raw",punteggiolezione.toString())
		invioLocation=eseguiSetValue("cmi.core.lesson_location",locationlezione)
		if (eseguireCommit)
		{
			eseguiCommit()
		}
	}
	if (n!=1)
	{
		exitAU()	
	}
}

function exitAU()
{
	uscitaregolare=true
	if (tipoRegistrazione=="aicc")
	{
		message= "command=exitau"+"&version="+aiccversion+"&session_id="+aiccsid+"&aicc_data=";
		if (traceAICC)
		{
			writeDebug("INVIO COMANDO:<br>"+ message)
		}
		result=Cmi.get_URL(aiccurl,message);
		error_value=Cmi.get_error(result);
		if (error_value!="0") 
		{
			alert("Errore nella comunicazione con la piattaforma.\n Il corso verrà chiuso.");
		}
		if (traceAICC)
		{
			writeDebug("RISPOSTA LMS=<BR>" + result)
		}
		logOut()
	}
	else
	{
		invioTempo=eseguiSetValue("cmi.core.session_time",timer_value)
		if (eseguireCommit)
		{
			eseguiCommit()
		}
		eseguiFinish()
	}
}

function eseguiGetValue(nome)
{
	if (traceAICC)
	{
		writeDebug("INVIO COMANDO GetValue:<br>"+ nome)
	}
	var value = API.LMSGetValue(nome);
	if (traceAICC)
	{
		writeDebug("RISPOSTA LMS=<BR>" + value.toString())
	}
	var errCode = API.LMSGetLastError().toString();
	if (errCode != "0")
	{
		var errDescription = API.LMSGetErrorString(errCode);
		alert("LMSGetValue("+nome+") fallito. \n"+errDescription);
		return "";
	}
	else
	{
		return value.toString();
	}
}

function eseguiGetValueNoMandatory(nome)
{
	if (traceAICC)
	{
		writeDebug("INVIO COMANDO GetValue:<br>"+ nome)
	}
	var value = API.LMSGetValue(nome);
	if (traceAICC)
	{
		writeDebug("RISPOSTA LMS=<BR>" + value.toString())
	}
	var errCode = API.LMSGetLastError().toString();
	if (errCode != "0")
	{
		var errDescription = API.LMSGetErrorString(errCode);
		return "";
	}
	else
	{
		return value.toString();
	}
}

function eseguiSetValue(nome,valore)
{
	if (traceAICC)
	{
		writeDebug("INVIO COMANDO SetValue:<br>"+ nome+" valore= "+valore)
	}
	var result = API.LMSSetValue(nome,valore);
	if (traceAICC)
	{
		writeDebug("RISPOSTA LMS=<BR>" + result.toString())
	}
	if (result.toString() != "true")
	{
		alert("errore: "+nome+"\n"+valore);
	}
}


function eseguiCommit()
{
	var risultato
	if (traceAICC)
	{
		writeDebug("INVIO COMANDO: LMSCommit")
	}
	risultato=API.LMSCommit("")
	if (risultato != "true")
	{
		alert("Errore: LMSCommit non riuscito. \n" + risultato)
	}
	if (traceAICC)
	{
		writeDebug("RISPOSTA LMS= " + risultato)
	}
}
function eseguiFinish()
{
	//alert("finish")
	var risultato
	if (traceAICC)
	{
		writeDebug("INVIO COMANDO: LMSFinish")
	}
	risultato=API.LMSFinish("")
	if (risultato != "true")
	{
		alert("Errore: LMSFinish non riuscito. \n" + risultato)
	}
	if (traceAICC)
	{
		writeDebug("RISPOSTA LMS= " + risultato)
	}
	logOut()
}

function start_timer() 
{
var tot,ore, minuti, secondi;

tot=timer_value;
ore=tot.substring(0,tot.indexOf(':'));
tot=tot.substring(tot.indexOf(':')+1,tot.length);
minuti=tot.substring(0,tot.indexOf(':'));
secondi=Number(tot.substring(tot.indexOf(':')+1,tot.length))+1;
if (secondi<10)
 secondi='0'+secondi;
  
if(secondi==60)
{
 secondi='00';
 minuti=(Number(minuti)+1)+"";
}

if(minuti.length<2)
 minuti='0'+minuti;
 
if(minuti==60)
{
 minuti='00';
 ore=(Number(ore)+1)+"";
}

if(ore.length<2)
 ore='0'+ore;

 timer_value=ore+":"+minuti+":"+secondi;
 setTimeout("start_timer()",1000);
}

function reset_timer() {
 timer_value='00:00:00';
}

function getAPI()
{ 
	var miaWin = trovaTop(window)
	var theAPI = findAPI(miaWin);
	if ((theAPI == null) && (miaWin.opener != null) && (typeof(miaWin.opener) != "undefined") && (!miaWin.opener.closed))
	{ 
		var miaWin2 = trovaTop(miaWin.opener)
		theAPI = findAPI(miaWin2); 
	} 
	alert("tonixx");

	return theAPI
}

function findAPI(win)
{  
	if (win.API != null)
	{
		return win.API; 
	}
	for (var i=0;i<win.length;i++) 
	{ 
		var theAPI = findAPI(win.frames[i]); 
		if (theAPI != null) 
		{ 
			return theAPI; 
		}
	}
}

function trovaTop(win2)
{
	if (win2.location==win2.parent.location)
	{
		return win2
	}
	else
	{
		var win2 = trovaTop(win2.parent)
		return win2
	}
}

function gestioneUscitaAnomala(ev)
{
	if (tipoRegistrazione!="")
	{
		if (!ev)
		{
			ev = event
		}
		if (!uscitaregolare)
		{ 
			exitAU()
		}
	}
}

window.onbeforeunload=gestioneUscitaAnomala

function logOut()
{
	miaTop=trovaTop(window)
	if (miaTop.location==self.location)
	{
		self.top.close()
	}
	else
	{ 
		self.location="logout.htm"
	} 
}