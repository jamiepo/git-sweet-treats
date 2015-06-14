/**********************************
 called from browserdetect.js
 **********************************/
function select(x) {
	var f=document.formAddOrMove;
	var coords=x.split(",");
	f.xcoor.value = coords[0];
	f.ycoor.value = coords[1];
}

function isValidHexColor(hexcolor) {
	var strPattern = /^#([0-9a-fA-F]){6}$/i;
	return strPattern.test(hexcolor);
}

function isValidInteger(str) {
	var strPattern = /^([0-9])+$/i;
	return strPattern.test(str);
}

function addMoveTable(f, fe, mode) {
	var opt_key = fe.selectedIndex;
	var theTable = fe.options[opt_key].value;

	var msg='';
	if (!isValidInteger(f.xcoor.value)) {
		msg+= "\r\nEnter a valid X co-ordinate.";
	}

	if (!isValidInteger(f.ycoor.value)) {
		msg+= "\r\nEnter a valid Y co-ordinate.";
	}

	if (msg=='')  {
		f.table.value=theTable
		f.action.value=mode
		f.submit();
	}else{
		msg+= "\r\nClick anywhere in the canvas to set the X and Y co-ordinates.";
		alert(msg);
		f.reset();
	}
}

/*******************
 erd.php
**********************/
// form Settings
function vCanvasSettings(f, minWidth, minHeight) {
	var msg='', valid= true;
	var maxWidth=1200, maxHeight=1200;
	var myColours= new Array ('canvas','tBG','tText','tBorders','tConnectors');
	var myNames= new Array ('Canvas','Tables','Text','Borders','Lines');
	var i, o;

	// validate in reverse order to bring focus to the earliest point.

	var tmp= myColours.length-1;
	for (i=myColours.length-1; i>=0; i--) {
		//		alert( f.elements[myColours[i]].value);
		o= f.elements[myColours[i]];
		if (!isValidHexColor(o.value)) {
			msg+= "\r\nMissing/Invalid color (#FFFFFF format required): "+myNames[i];
			valid= false;
			setFocus(o);
		}
	}

	o= f.height;
	if (isValidInteger(o.value) ) {
		if (o.value < minHeight) {
			msg+= "\r\nMinimum height is "+minHeight;
			o.value = minHeight;
			setFocus(o);
			valid= false;
		}else if (o.value > maxHeight) {
			msg+= "\r\nMaximum height is "+maxHeight;
			o.value = maxHeight;
			setFocus(o);
			valid= false;
		}
	}else{
		msg+= "\r\nEnter a valid height";
		setFocus(o);
		valid= false;
	}

	o= f.width;
	if (isValidInteger(o.value) ) {
		if (o.value < minWidth) {
			msg+= "\r\nMinimum width is "+minWidth;
			o.value = minWidth;
			setFocus(o);
			valid= false;
		}else if (o.value > maxWidth) {
			msg+= "\r\nMaximum width is "+maxWidth;
			o.value = maxWidth;
			setFocus(o);
			valid= false;
		}
	}else{
		msg+= "\r\nEnter a valid width";
		setFocus(o);
		valid= false;
	}

	o= f.newID;
	if (o.value=='') {
		msg+= "\r\nEnter an ID";
		setFocus(o);
		valid= false;
	}

	//return false;
	if (valid==false) alert (msg);
	return valid;
}

// delete a diagram
function deleteDiagram(f) {
	if (confirm('Sure?'))
		f.submit();
	else
		f.reset();
}

// form new
function newDiagram(f) {
	var msg, success= false;
	var str= f.newID.value;
	if (str=='') {
		msg= "Enter an ID for the new diagram.";
	}else if (str== document.formSettings.newID.value) {
		msg= "This is the ID of the current diagram. Choose another.";
	}else{
		success= true;
	}

	if (success==false) {
		alert(msg);
		setFocus(f.newID);
	}
	return success;
}
/*******************
 END erd.php
**********************/


/*******************
 schema.php
**********************/

// delete a relationship
function vDeleteRelationship(f, a) {
	f= document.forms[f];
	if (confirm('DELETE this record?')) {
		f.elements['action'].value= a;
		f.submit();
	}
}

function vAddRelationship(f) {
	var str='';
	if (f.elements["parent_1"].value== '') str+='Table 1\r\n';
	if (f.elements["child_1"].value== '') str+='Field 1\r\n';
	if (f.elements["relationship"].value== '') str+='Relationship\r\n';
	if (f.elements["parent_2"].value== '') str+='Table 2\r\n';
	if (f.elements["child_2"].value== '') str+='Field 2\r\n';

	if (str.length>0) {
		alert("Missing data:\r\n"+str);
		return false;
	}else{
		return true;
	}
}


/*******************
 END schema.php
**********************/

/*******************
 settings.php
**********************/
function vValidateProject(f) {
	var opt_key = f.strType.selectedIndex;
	var theType = f.strType.options[opt_key].value;
	var valid= true, msg='';

	if (theType=='') {
		msg+= "\r\nSelect a database type.";
		setFocus(f.strType);
		valid= false;
	}

	if (f.strDB.value=='') {
		msg+= "\r\nEnter a database name.";
		setFocus(f.strDB);
		valid= false;
	}

	if (f.strProject.value=='') {
		msg+= "\r\nEnter a project name.";
		setFocus(f.strProject);
		valid= false;
	}

	if (valid==false) {
		alert(msg);
	}

	return valid;
}

function deleteProject(id, last) {
	 window.location.href="settings.php?p=del&id="+id+"&history="+last;
}

// -----------------------------------------
//                  setfocus
// Delayed focus setting to get around IE bug
// Author: Stephen Poley
// wbsite: http://www.xs4all.nl/~sbpoley/webmatters/
// Update Jun 2005: discovered that reason IE wasn't setting focus was
// due to an IE timing bug. Added 0.1 sec delay to fix.
// -----------------------------------------
var glb_vfld;      // retain vfld for timer thread
function setFocusDelayed(vfld)
{
  glb_vfld.focus()
}
function setFocus(vfld)
{
  glb_vfld = vfld;
  setTimeout( 'setFocusDelayed()', 100 );
}